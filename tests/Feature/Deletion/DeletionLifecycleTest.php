<?php

namespace Tests\Feature\Deletion;

use App\Models\PendingFileDeletion;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\SkillDomain;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\Deletion\DeletionLifecycle;
use App\Services\Deletion\PendingFileCleanup;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Tests\TestCase;

class DeletionLifecycleTest extends TestCase
{
    use DatabaseMigrations;

    public function test_sync_runtime_commits_then_cleans_file_without_leaving_pending_record(): void
    {
        config(['queue.default' => 'sync']);
        Storage::fake('local');
        [$project, $file] = $this->createProjectWithFile();
        Storage::disk('local')->put($file->path, 'content');

        $this->assertTrue(app(DeletionLifecycle::class)->deleteProject($project));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseCount('pending_file_deletions', 0);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_failed_after_commit_cleanup_is_persisted_and_does_not_fail_deletion(): void
    {
        Storage::fake('local');
        [$project, $file] = $this->createProjectWithFile();
        $disk = \Mockery::mock(FilesystemAdapter::class);
        $disk->shouldReceive('exists')->once()->with($file->path)->andReturnTrue();
        $disk->shouldReceive('delete')->once()->with($file->path)->andReturnFalse();
        Storage::shouldReceive('disk')->once()->with('local')->andReturn($disk);
        Log::shouldReceive('error')->once();

        $this->assertTrue(app(DeletionLifecycle::class)->deleteProject($project));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('pending_file_deletions', [
            'disk' => 'local',
            'path' => $file->path,
            'attempts' => 1,
            'last_error' => 'The storage adapter did not delete the file.',
        ]);
    }

    public function test_pending_cleanup_can_be_retried_successfully_and_duplicate_execution_is_safe(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('projects/retry.pdf', 'content');
        $pending = PendingFileDeletion::query()->create([
            'disk' => 'local',
            'path' => 'projects/retry.pdf',
            'attempts' => 1,
            'last_error' => 'Earlier failure',
        ]);

        $first = app(PendingFileCleanup::class)->processIds([$pending->id]);
        $duplicate = app(PendingFileCleanup::class)->processIds([$pending->id]);

        $this->assertSame(['processed' => 1, 'failed' => 0], $first);
        $this->assertSame(['processed' => 0, 'failed' => 0], $duplicate);
        Storage::disk('local')->assertMissing('projects/retry.pdf');
        $this->assertDatabaseMissing('pending_file_deletions', ['id' => $pending->id]);
    }

    public function test_retry_command_processes_pending_cleanup_without_a_queue_worker(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('projects/command.pdf', 'content');
        PendingFileDeletion::query()->create([
            'disk' => 'local',
            'path' => 'projects/command.pdf',
        ]);

        $this->artisan('project-files:cleanup')->assertSuccessful();

        Storage::disk('local')->assertMissing('projects/command.pdf');
        $this->assertDatabaseCount('pending_file_deletions', 0);
    }

    public function test_sql_failure_rolls_back_rows_and_pending_cleanup_records(): void
    {
        Storage::fake('local');
        [$project, $file] = $this->createProjectWithFile();
        Storage::disk('local')->put($file->path, 'content');
        Project::deleting(fn () => DB::statement('INSERT INTO injected_missing_table DEFAULT VALUES'));

        try {
            app(DeletionLifecycle::class)->deleteProject($project);
            $this->fail('The injected SQL failure was not thrown.');
        } catch (\Illuminate\Database\QueryException $exception) {
            $this->assertStringContainsString('injected_missing_table', $exception->getMessage());
        }

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('project_files', ['id' => $file->id]);
        $this->assertDatabaseCount('pending_file_deletions', 0);
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_user_deletion_removes_projects_before_employer_profiles(): void
    {
        Storage::fake('local');
        [$project] = $this->createProjectWithFile();
        $user = $project->employer;
        $profile = $project->employerProfile;
        $projectWasGoneWhenProfileDeleted = false;
        UserProfile::deleting(function () use ($project, &$projectWasGoneWhenProfileDeleted) {
            $projectWasGoneWhenProfileDeleted = ! Project::query()->whereKey($project->id)->exists();
        });

        $this->assertTrue(app(DeletionLifecycle::class)->deleteUser($user));

        $this->assertTrue($projectWasGoneWhenProfileDeleted);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('user_profiles', ['id' => $profile->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_project_delete_returning_false_rolls_back_state_and_cleanup_intent(): void
    {
        Storage::fake('local');
        [$project, $file] = $this->createProjectWithFile();
        $domain = SkillDomain::query()->create(['name' => 'Rollback project domain']);
        $project->domains()->attach($domain->id);
        Storage::disk('local')->put($file->path, 'content');
        Project::deleting(fn () => false);

        try {
            app(DeletionLifecycle::class)->deleteProject($project);
            $this->fail('A cancelled project deletion must throw.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Project deletion was cancelled.', $exception->getMessage());
        }

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('project_files', [
            'id' => $file->id,
            'project_id' => $project->id,
        ]);
        $this->assertDatabaseHas('project_domains', [
            'project_id' => $project->id,
            'skill_domain_id' => $domain->id,
        ]);
        $this->assertDatabaseCount('pending_file_deletions', 0);
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_user_delete_returning_false_rolls_back_projects_profiles_and_cleanup_intents(): void
    {
        Storage::fake('local');
        [$project, $file] = $this->createProjectWithFile();
        $user = $project->employer;
        $profile = $project->employerProfile;
        $domain = SkillDomain::query()->create(['name' => 'Rollback user domain']);
        $project->domains()->attach($domain->id);
        $profile->domains()->attach($domain->id);
        Storage::disk('local')->put($file->path, 'content');
        User::deleting(fn () => false);

        try {
            app(DeletionLifecycle::class)->deleteUser($user);
            $this->fail('A cancelled user deletion must throw.');
        } catch (RuntimeException $exception) {
            $this->assertSame('User deletion was cancelled.', $exception->getMessage());
        }

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('user_profiles', [
            'id' => $profile->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'employer_id' => $user->id,
            'employer_profile_id' => $profile->id,
        ]);
        $this->assertDatabaseHas('project_files', [
            'id' => $file->id,
            'project_id' => $project->id,
        ]);
        $this->assertDatabaseHas('project_domains', [
            'project_id' => $project->id,
            'skill_domain_id' => $domain->id,
        ]);
        $this->assertDatabaseHas('user_profile_domains', [
            'profile_id' => $profile->id,
            'skill_domain_id' => $domain->id,
        ]);
        $this->assertDatabaseCount('pending_file_deletions', 0);
        Storage::disk('local')->assertExists($file->path);
    }

    /** @return array{Project, ProjectFile} */
    private function createProjectWithFile(): array
    {
        $user = User::factory()->create();
        $profile = UserProfile::query()->create(['user_id' => $user->id, 'type' => 'employer']);
        $project = Project::query()->create([
            'employer_id' => $user->id,
            'employer_profile_id' => $profile->id,
            'short_id' => Str::upper(Str::random(12)),
            'title' => 'Deletion lifecycle project',
            'description' => 'Test project',
            'work_type' => 'remote',
        ]);
        $file = ProjectFile::query()->create([
            'project_id' => $project->id,
            'path' => 'projects/'.Str::uuid().'.pdf',
            'storage_disk' => 'local',
        ]);

        return [$project, $file];
    }
}
