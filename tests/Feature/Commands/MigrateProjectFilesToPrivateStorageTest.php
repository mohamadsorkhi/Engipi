<?php

namespace Tests\Feature\Commands;

use App\Contracts\OperationalTelemetry;
use App\Models\Project;
use App\Models\ProjectFile;
use App\ValueObjects\OperationalEvent;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\Fakes\FakeOperationalTelemetry;
use Tests\Feature\Authorization\AuthorizationTestCase;

class MigrateProjectFilesToPrivateStorageTest extends AuthorizationTestCase
{
    private FakeOperationalTelemetry $telemetry;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::fake('local');
        $this->telemetry = new FakeOperationalTelemetry;
        $this->app->instance(OperationalTelemetry::class, $this->telemetry);
    }

    public function test_default_dry_run_reports_without_mutating_files_or_metadata(): void
    {
        [$file] = $this->createLegacyFile('sensitive/customer-a/brief.pdf', 'legacy content');

        $exitCode = Artisan::call('project-files:migrate-private');
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('DRY RUN MODE', $output);
        $this->assertStringNotContainsString($file->path, $output);
        $this->assertStringNotContainsString($file->original_name, $output);
        $this->assertNull($file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_execute_copies_verifies_updates_and_removes_legacy_public_file(): void
    {
        [$file] = $this->createLegacyFile('legacy/brief.pdf', 'legacy content');

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        Storage::disk('local')->assertExists($file->path);
        Storage::disk('public')->assertMissing($file->path);
        $this->assertSame('legacy content', Storage::disk('local')->get($file->path));
    }

    public function test_execute_resumes_verified_legacy_record_when_both_copies_exist(): void
    {
        [$file] = $this->createLegacyFile('legacy/resume.pdf', 'matching content');
        Storage::disk('local')->put($file->path, 'matching content');

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        Storage::disk('local')->assertExists($file->path);
        Storage::disk('public')->assertMissing($file->path);
    }

    public function test_execute_cleans_verified_public_copy_after_metadata_was_already_updated(): void
    {
        [$file] = $this->createLegacyFile('legacy/cleanup.pdf', 'matching content');
        Storage::disk('local')->put($file->path, 'matching content');
        $file->update(['storage_disk' => 'local']);

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        Storage::disk('local')->assertExists($file->path);
        Storage::disk('public')->assertMissing($file->path);
    }

    public function test_content_mismatch_fails_without_changing_or_deleting_either_copy(): void
    {
        [$file] = $this->createLegacyFile('legacy/mismatch.pdf', 'public content');
        Storage::disk('local')->put($file->path, 'different private content');

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertNull($file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_missing_files_fail_without_metadata_change(): void
    {
        [$file] = $this->createLegacyFile('legacy/missing.pdf', null);

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertNull($file->fresh()->storage_disk);
    }

    public function test_private_metadata_with_only_public_file_fails_without_automatic_repair(): void
    {
        [$file] = $this->createLegacyFile('legacy/metadata-mismatch.pdf', 'public content');
        $file->update(['storage_disk' => 'local']);

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_batch_size_is_bounded_and_multiple_batches_are_processed(): void
    {
        [$first] = $this->createLegacyFile('legacy/first.pdf', 'first content');
        [$second] = $this->createLegacyFile('legacy/second.pdf', 'second content');

        $exitCode = Artisan::call('project-files:migrate-private', [
            '--execute' => true,
            '--batch' => 1,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('local', $first->fresh()->storage_disk);
        $this->assertSame('local', $second->fresh()->storage_disk);

        foreach ([-1, 0, 'not-an-integer', 1001] as $invalidBatch) {
            $invalidExitCode = Artisan::call('project-files:migrate-private', ['--batch' => $invalidBatch]);
            $this->assertSame(1, $invalidExitCode);
        }
    }

    public function test_explicit_public_metadata_migrates_like_legacy_null_metadata(): void
    {
        [$file] = $this->createLegacyFile('legacy/explicit-public.pdf', 'public content');
        $file->update(['storage_disk' => 'public']);

        $exitCode = Artisan::call('project-files:migrate-private', ['--execute' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        Storage::disk('public')->assertMissing($file->path);
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_remaining_state_matrix_is_reported_without_automatic_repair(): void
    {
        [$legacyPrivateOnly] = $this->createLegacyFile('legacy/private-only.pdf', null);
        Storage::disk('local')->put($legacyPrivateOnly->path, 'private only');

        [$alreadyPrivate] = $this->createLegacyFile('private/complete.pdf', null);
        Storage::disk('local')->put($alreadyPrivate->path, 'private content');
        $alreadyPrivate->update(['storage_disk' => 'local']);

        [$unknownDisk] = $this->createLegacyFile('legacy/unknown.pdf', 'unknown disk content');
        $unknownDisk->update(['storage_disk' => 'remote']);

        $exitCode = Artisan::call('project-files:migrate-private');
        $output = Artisan::output();

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('legacy_private_only', $output);
        $this->assertStringContainsString('already_private', $output);
        $this->assertStringContainsString('metadata_mismatch', $output);
        $this->assertNull($legacyPrivateOnly->fresh()->storage_disk);
        $this->assertSame('local', $alreadyPrivate->fresh()->storage_disk);
        $this->assertSame('remote', $unknownDisk->fresh()->storage_disk);
    }

    public function test_operational_report_contains_batches_bytes_and_elapsed_time_only_as_aggregates(): void
    {
        [$file] = $this->createLegacyFile('sensitive/customer/aggregate.pdf', '12345');

        $exitCode = Artisan::call('project-files:migrate-private', ['--batch' => 1]);
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('batches_processed', $output);
        $this->assertStringContainsString('public_bytes_detected', $output);
        $this->assertStringContainsString('elapsed_milliseconds', $output);
        $this->assertStringNotContainsString($file->path, $output);
        $this->assertStringNotContainsString($file->id, $output);
        $this->assertStringNotContainsString($file->project_id, $output);
        $this->assertStringNotContainsString($file->original_name, $output);
    }

    public function test_orphan_inventory_is_read_only_and_reports_each_disk_aggregately(): void
    {
        [$referenced] = $this->createLegacyFile('project-files/referenced.pdf', 'referenced');
        $publicOrphan = 'project-files/orphans/public-secret.pdf';
        $privateOrphan = 'project-files/orphans/private-secret.pdf';
        Storage::disk('public')->put($publicOrphan, 'public orphan');
        Storage::disk('local')->put($privateOrphan, 'private orphan');

        $exitCode = Artisan::call('project-files:migrate-private', ['--orphans' => true]);
        $output = Artisan::output();

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('ORPHAN INVENTORY MODE', $output);
        $this->assertStringContainsString('public_orphan_files', $output);
        $this->assertStringContainsString('local_orphan_files', $output);
        $this->assertStringNotContainsString($publicOrphan, $output);
        $this->assertStringNotContainsString($privateOrphan, $output);
        Storage::disk('public')->assertExists($referenced->path);
        Storage::disk('public')->assertExists($publicOrphan);
        Storage::disk('local')->assertExists($privateOrphan);
        $this->assertNull($referenced->fresh()->storage_disk);
    }

    public function test_rollback_copies_verifies_updates_metadata_and_retains_private_source(): void
    {
        [$file] = $this->createLegacyFile('project-files/rollback/private.pdf', null);
        Storage::disk('local')->put($file->path, 'private content');
        $file->update(['storage_disk' => 'local']);

        $exitCode = Artisan::call('project-files:migrate-private', ['--rollback' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('public', $file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertExists($file->path);
        $this->assertSame('private content', Storage::disk('public')->get($file->path));
    }

    public function test_rollback_resumes_matching_public_copy_and_is_idempotent(): void
    {
        [$file] = $this->createLegacyFile('project-files/rollback/resume.pdf', 'matching content');
        Storage::disk('local')->put($file->path, 'matching content');
        $file->update(['storage_disk' => 'local']);

        $firstExitCode = Artisan::call('project-files:migrate-private', ['--rollback' => true]);
        $secondExitCode = Artisan::call('project-files:migrate-private', ['--rollback' => true]);

        $this->assertSame(0, $firstExitCode);
        $this->assertSame(0, $secondExitCode);
        $this->assertSame('public', $file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_rollback_restores_public_copy_when_interrupted_state_has_legacy_metadata(): void
    {
        [$file] = $this->createLegacyFile('project-files/rollback/legacy-private-only.pdf', null);
        Storage::disk('local')->put($file->path, 'verified private content');

        $exitCode = Artisan::call('project-files:migrate-private', ['--rollback' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertSame('public', $file->fresh()->storage_disk);
        $this->assertSame('verified private content', Storage::disk('public')->get($file->path));
        Storage::disk('local')->assertExists($file->path);
    }

    public function test_rollback_never_overwrites_mismatched_public_file(): void
    {
        [$file] = $this->createLegacyFile('project-files/rollback/mismatch.pdf', 'public content');
        Storage::disk('local')->put($file->path, 'private content');
        $file->update(['storage_disk' => 'local']);

        $exitCode = Artisan::call('project-files:migrate-private', ['--rollback' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertSame('local', $file->fresh()->storage_disk);
        $this->assertSame('public content', Storage::disk('public')->get($file->path));
        $this->assertSame('private content', Storage::disk('local')->get($file->path));
    }

    public function test_modes_are_mutually_exclusive_before_any_mutation(): void
    {
        [$file] = $this->createLegacyFile('legacy/exclusive.pdf', 'legacy content');

        $exitCode = Artisan::call('project-files:migrate-private', [
            '--execute' => true,
            '--rollback' => true,
        ]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('mutually exclusive', Artisan::output());
        $this->assertNull($file->fresh()->storage_disk);
        Storage::disk('public')->assertExists($file->path);
        Storage::disk('local')->assertMissing($file->path);
    }

    public function test_dry_run_emits_start_and_bounded_completion_summary(): void
    {
        $this->createLegacyFile('sensitive/customer/report.pdf', 'content');

        $this->assertSame(0, Artisan::call('project-files:migrate-private'));
        $this->assertSame([
            OperationalEvent::ProjectFilesOperationStarted,
            OperationalEvent::ProjectFilesOperationCompleted,
        ], array_column($this->telemetry->events, 'event'));
        $context = $this->telemetry->events[1]['context'];
        $this->assertSame('dry_run', $context['mode']);
        $this->assertSame('success', $context['result']);
        $this->assertSame(1, $context['total']);
        $this->assertStringNotContainsString('customer', json_encode($context));
    }

    public function test_rollback_and_orphan_modes_emit_their_safe_summaries(): void
    {
        $this->assertSame(0, Artisan::call('project-files:migrate-private', ['--rollback' => true]));
        $this->assertSame('rollback', $this->telemetry->events[1]['context']['mode']);

        $this->telemetry->events = [];
        $this->assertSame(0, Artisan::call('project-files:migrate-private', ['--orphans' => true]));
        $this->assertSame('orphans', $this->telemetry->events[1]['context']['mode']);
    }

    public function test_invalid_options_emit_only_safe_rejection_event(): void
    {
        $this->assertSame(1, Artisan::call('project-files:migrate-private', ['--batch' => 'secret/path']));
        $this->assertCount(1, $this->telemetry->events);
        $this->assertSame(OperationalEvent::ProjectFilesOptionsRejected, $this->telemetry->events[0]['event']);
        $this->assertSame('invalid_batch', $this->telemetry->events[0]['context']['reason']);
        $this->assertStringNotContainsString('secret/path', json_encode($this->telemetry->events));
    }

    public function test_unresolved_and_failed_runs_are_classified(): void
    {
        $this->createLegacyFile('legacy/missing.pdf', null);
        $this->assertSame(1, Artisan::call('project-files:migrate-private'));
        $this->assertSame('unresolved', $this->telemetry->events[1]['context']['result']);

        $this->telemetry->events = [];
        [$directoryRecord] = $this->createLegacyFile('legacy/unreadable-directory', null);
        Storage::disk('public')->makeDirectory($directoryRecord->path);
        $this->assertSame(1, Artisan::call('project-files:migrate-private'));
        $this->assertSame('failed', $this->telemetry->events[1]['context']['result']);
    }

    private function createLegacyFile(string $path, ?string $content): array
    {
        $employer = $this->createUser();
        $project = $this->createProject($employer);
        $file = $this->createProjectFile($project, $path);

        if ($content !== null) {
            Storage::disk('public')->put($path, $content);
        }

        return [$file, $project];
    }

    private function createProjectFile(Project $project, string $path): ProjectFile
    {
        return ProjectFile::query()->create([
            'project_id' => $project->id,
            'path' => $path,
            'original_name' => 'confidential-project-brief.pdf',
            'mime_type' => 'application/pdf',
            'size' => 100,
        ]);
    }
}
