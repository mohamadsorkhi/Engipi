<?php

namespace Tests\Feature\Authorization;

use App\Actions\Admin\DeleteProjectAction as AdminDeleteProjectAction;
use App\Actions\Admin\DeleteUserAction;
use App\Actions\Employer\DeleteProjectAction as EmployerDeleteProjectAction;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectFileDownloadAuthorizationTest extends AuthorizationTestCase
{
    public function test_guest_cannot_download_project_file(): void
    {
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project);

        $this->get(route('user.project-files.download', $file))
            ->assertRedirect(route('login'));
    }

    public function test_project_employer_can_download_legacy_public_file_with_security_headers(): void
    {
        Storage::fake('public');
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, null, 'legacy/project-brief.pdf');
        Storage::disk('public')->put($file->path, 'legacy project content');

        $response = $this->actingAs($employer)
            ->get(route('user.project-files.download', $file));

        $response->assertOk()
            ->assertHeader('content-type', 'application/pdf')
            ->assertHeader('x-content-type-options', 'nosniff')
            ->assertHeader('pragma', 'no-cache');

        $this->assertStringContainsString('private', $response->headers->get('cache-control'));
        $this->assertStringContainsString('no-store', $response->headers->get('cache-control'));
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));
        $this->assertStringContainsString('project-brief.pdf', $response->headers->get('content-disposition'));
    }

    public function test_matched_specialist_can_download_private_file(): void
    {
        Storage::fake('local');
        $employer = $this->createUser();
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $project = $this->createProject($employer);
        $this->matchProjectToSpecialist($project, $specialist);
        $file = $this->createProjectFile($project, 'local');
        Storage::disk('local')->put($file->path, 'private project content');

        $this->actingAs($specialist)
            ->get(route('user.project-files.download', $file))
            ->assertOk()
            ->assertHeader('x-content-type-options', 'nosniff');
    }

    public function test_administrator_can_download_project_file(): void
    {
        Storage::fake('local');
        $employer = $this->createUser();
        $admin = $this->createUser(['is_admin' => true]);
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, 'local');
        Storage::disk('local')->put($file->path, 'private project content');

        $this->actingAs($admin)
            ->get(route('user.project-files.download', $file))
            ->assertOk();
    }

    public function test_unmatched_specialist_receives_not_found(): void
    {
        Storage::fake('local');
        $employer = $this->createUser();
        $specialist = $this->createUser();
        $this->createProfile($specialist, 'specialist');
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, 'local');
        Storage::disk('local')->put($file->path, 'private project content');

        $this->actingAs($specialist)
            ->get(route('user.project-files.download', $file))
            ->assertNotFound();
    }

    public function test_unrelated_employer_receives_not_found(): void
    {
        Storage::fake('public');
        $employer = $this->createUser();
        $unrelated = $this->createUser();
        $this->createProfile($unrelated, 'employer');
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project);
        Storage::disk('public')->put($file->path, 'legacy project content');

        $this->actingAs($unrelated)
            ->get(route('user.project-files.download', $file))
            ->assertNotFound();
    }

    public function test_authorized_user_receives_not_found_when_physical_file_is_missing(): void
    {
        Storage::fake('local');
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, 'local');

        $this->actingAs($employer)
            ->get(route('user.project-files.download', $file))
            ->assertNotFound();
    }

    public function test_nonexistent_project_file_record_returns_not_found(): void
    {
        $user = $this->createUser();

        $this->actingAs($user)
            ->get(route('user.project-files.download', (string) Str::uuid()))
            ->assertNotFound();
    }

    public function test_download_filename_removes_path_and_header_control_characters(): void
    {
        Storage::fake('local');
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, 'local');
        $file->update([
            'original_name' => "../unsafe\r\ninjected.pdf",
            'mime_type' => "text/plain\r\nx-injected: yes",
        ]);
        Storage::disk('local')->put($file->path, 'private project content');

        $response = $this->actingAs($employer)
            ->get(route('user.project-files.download', $file));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/octet-stream');

        $disposition = $response->headers->get('content-disposition');
        $this->assertStringNotContainsString('..', $disposition);
        $this->assertStringNotContainsString("\r", $disposition);
        $this->assertStringNotContainsString("\n", $disposition);
    }

    public function test_employer_project_deletion_removes_legacy_and_private_files(): void
    {
        [$project, $legacyFile, $privateFile] = $this->createMixedStorageProject();

        app(EmployerDeleteProjectAction::class)->execute($project);

        $this->assertMixedStorageFilesDeleted($legacyFile, $privateFile);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_project_deletion_removes_legacy_and_private_files(): void
    {
        [$project, $legacyFile, $privateFile] = $this->createMixedStorageProject();

        app(AdminDeleteProjectAction::class)->execute($project);

        $this->assertMixedStorageFilesDeleted($legacyFile, $privateFile);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_user_deletion_removes_legacy_and_private_project_files(): void
    {
        [$project, $legacyFile, $privateFile] = $this->createMixedStorageProject();
        $employer = $project->employer;

        app(DeleteUserAction::class)->execute($employer);

        $this->assertMixedStorageFilesDeleted($legacyFile, $privateFile);
        $this->assertDatabaseMissing('users', ['id' => $employer->id]);
    }

    private function createProjectFile(
        Project $project,
        ?string $storageDisk = null,
        string $path = 'project-files/test/project-brief.pdf'
    ): ProjectFile {
        return ProjectFile::query()->create([
            'project_id' => $project->id,
            'path' => $path,
            'storage_disk' => $storageDisk,
            'original_name' => 'project-brief.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
        ]);
    }

    private function createMixedStorageProject(): array
    {
        Storage::fake('public');
        Storage::fake('local');
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $legacyFile = $this->createProjectFile($project, null, 'legacy/project-brief.pdf');
        $privateFile = $this->createProjectFile($project, 'local', 'private/project-brief.pdf');
        Storage::disk('public')->put($legacyFile->path, 'legacy project content');
        Storage::disk('local')->put($privateFile->path, 'private project content');

        return [$project, $legacyFile, $privateFile];
    }

    private function assertMixedStorageFilesDeleted(ProjectFile $legacyFile, ProjectFile $privateFile): void
    {
        Storage::disk('public')->assertMissing($legacyFile->path);
        Storage::disk('local')->assertMissing($privateFile->path);
    }
}
