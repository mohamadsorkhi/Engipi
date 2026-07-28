<?php

namespace App\Console\Commands;

use App\Contracts\OperationalTelemetry;
use App\Models\ProjectFile;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;
use Illuminate\Console\Command;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigrateProjectFilesToPrivateStorage extends Command
{
    protected $signature = 'project-files:migrate-private
        {--execute : Copy verified legacy files, update metadata, and remove verified public copies}
        {--rollback : Copy verified private files back to public storage without deleting private copies}
        {--orphans : Inventory unreferenced files on both disks without modifying them}
        {--batch=100 : Number of database rows processed per batch (1-1000)}';

    protected $description = 'Inventory or migrate legacy public project files to private storage';

    private array $counts = [];

    private float $startedAt;

    private OperationalTelemetry $telemetry;

    private string $mode;

    public function handle(OperationalTelemetry $telemetry): int
    {
        $this->telemetry = $telemetry;
        $this->startedAt = microtime(true);
        $this->mode = $this->operationMode();
        $batchSize = filter_var($this->option('batch'), FILTER_VALIDATE_INT);

        if ($batchSize === false || $batchSize < 1 || $batchSize > 1000) {
            $this->error('The batch option must be an integer between 1 and 1000.');
            $this->emit(OperationalEvent::ProjectFilesOptionsRejected, OperationalSeverity::Warning, [
                'event_version' => 1,
                'reason' => 'invalid_batch',
            ]);

            return self::FAILURE;
        }

        $execute = (bool) $this->option('execute');
        $rollback = (bool) $this->option('rollback');
        $orphans = (bool) $this->option('orphans');

        if (count(array_filter([$execute, $rollback, $orphans])) > 1) {
            $this->error('The execute, rollback, and orphans options are mutually exclusive.');
            $this->emit(OperationalEvent::ProjectFilesOptionsRejected, OperationalSeverity::Warning, [
                'event_version' => 1,
                'reason' => 'mutually_exclusive_modes',
            ]);

            return self::FAILURE;
        }

        $this->counts = $this->initialCounts();
        $this->emit(OperationalEvent::ProjectFilesOperationStarted, OperationalSeverity::Info, [
            'event_version' => 1,
            'mode' => $this->mode,
        ]);
        $this->info(match (true) {
            $execute => 'EXECUTE MODE',
            $rollback => 'ROLLBACK MODE',
            $orphans => 'ORPHAN INVENTORY MODE',
            default => 'DRY RUN MODE',
        });

        if ($orphans) {
            $this->inventoryOrphans();
            $this->report();

            $exitCode = $this->counts['failed'] > 0 ? self::FAILURE : self::SUCCESS;
            $this->emitCompletion($exitCode === self::SUCCESS ? 'success' : 'failed');

            return $exitCode;
        }

        ProjectFile::query()
            ->orderBy('id')
            ->chunkById($batchSize, function ($files) use ($execute, $rollback): void {
                $this->counts['batches_processed']++;

                foreach ($files as $file) {
                    $this->counts['total']++;

                    try {
                        if ($rollback) {
                            $this->processRollbackFile($file);
                        } else {
                            $this->processFile($file, $execute);
                        }
                    } catch (Throwable) {
                        $this->counts['failed']++;
                    }
                }
            }, 'id');

        $this->report();

        if ($this->problemCount() > 0) {
            $this->error('Migration inventory contains unresolved records. No automatic repair was attempted.');
            $this->emitCompletion($this->counts['failed'] > 0 ? 'failed' : 'unresolved');

            return self::FAILURE;
        }

        $this->info(match (true) {
            $execute => 'Migration pass completed without unresolved records.',
            $rollback => 'Rollback pass completed without unresolved records. Private files were retained.',
            default => 'Dry run completed without unresolved records. No files or database rows were changed.',
        });
        $this->emitCompletion('success');

        return self::SUCCESS;
    }

    private function operationMode(): string
    {
        return match (true) {
            (bool) $this->option('execute') => 'execute',
            (bool) $this->option('rollback') => 'rollback',
            (bool) $this->option('orphans') => 'orphans',
            default => 'dry_run',
        };
    }

    private function emitCompletion(string $result): void
    {
        $this->emit(OperationalEvent::ProjectFilesOperationCompleted, match ($result) {
            'success' => OperationalSeverity::Info,
            'unresolved' => OperationalSeverity::Warning,
            default => OperationalSeverity::Error,
        }, array_merge($this->counts, [
            'event_version' => 1,
            'mode' => $this->mode,
            'result' => $result,
            'duration_ms' => $this->counts['elapsed_milliseconds'],
        ]));
    }

    private function emit(OperationalEvent $event, OperationalSeverity $severity, array $context): void
    {
        try {
            $this->telemetry->emit($event, $severity, $context);
        } catch (Throwable) {
            // Telemetry availability never changes migration safety or exit behavior.
        }
    }

    private function processRollbackFile(ProjectFile $file): void
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $publicExists = $public->exists($file->path);
        $privateExists = $private->exists($file->path);

        if ($file->storage_disk !== null && ! in_array($file->storage_disk, ['public', 'local'], true)) {
            $this->counts['metadata_mismatch']++;

            return;
        }

        if ($publicExists && ($file->storage_disk === null || $file->storage_disk === 'public')) {
            $this->counts['rollback_already_public']++;

            return;
        }

        if (! $privateExists) {
            $this->counts[$publicExists ? 'private_metadata_public_only' : 'missing_both']++;

            return;
        }

        if ($publicExists && ! $this->filesMatch($public, $private, $file->path)) {
            $this->counts['content_mismatch']++;

            return;
        }

        if (! $publicExists && ! $this->copyAndVerify($private, $public, $file->path)) {
            $this->counts['failed']++;

            return;
        }

        $file->forceFill(['storage_disk' => 'public'])->save();
        $this->counts['rolled_back']++;
    }

    private function inventoryOrphans(): void
    {
        foreach (['public', 'local'] as $diskName) {
            $disk = Storage::disk($diskName);

            try {
                foreach ($disk->getDriver()->listContents('project-files', true) as $attributes) {
                    if (! $attributes->isFile()) {
                        continue;
                    }

                    $this->counts['orphan_files_scanned']++;
                    $path = $attributes->path();

                    if (ProjectFile::query()->where('path', $path)->exists()) {
                        continue;
                    }

                    $this->counts[$diskName.'_orphan_files']++;
                    $this->counts[$diskName.'_orphan_bytes'] += $disk->size($path);
                }
            } catch (Throwable) {
                $this->counts['failed']++;
            }
        }
    }

    private function report(): void
    {
        $this->counts['elapsed_milliseconds'] = (int) round((microtime(true) - $this->startedAt) * 1000);

        $this->table(['State', 'Count'], collect($this->counts)
            ->map(fn (int $count, string $state): array => [$state, $count])
            ->values()
            ->all());
    }

    private function processFile(ProjectFile $file, bool $execute): void
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $publicExists = $public->exists($file->path);
        $privateExists = $private->exists($file->path);

        if ($publicExists) {
            $this->counts['public_bytes_detected'] += $public->size($file->path);
        }

        if ($privateExists) {
            $this->counts['private_bytes_detected'] += $private->size($file->path);
        }

        if ($file->storage_disk === null || $file->storage_disk === 'public') {
            $this->processLegacyFile($file, $public, $private, $publicExists, $privateExists, $execute);

            return;
        }

        if ($file->storage_disk !== 'local') {
            $this->counts['metadata_mismatch']++;

            return;
        }

        $this->processPrivateFile($file, $public, $private, $publicExists, $privateExists, $execute);
    }

    private function processLegacyFile(
        ProjectFile $file,
        FilesystemAdapter $public,
        FilesystemAdapter $private,
        bool $publicExists,
        bool $privateExists,
        bool $execute
    ): void {
        if (! $publicExists && ! $privateExists) {
            $this->counts['missing_both']++;

            return;
        }

        if (! $publicExists) {
            $this->counts['legacy_private_only']++;

            return;
        }

        if ($privateExists && ! $this->filesMatch($public, $private, $file->path)) {
            $this->counts['content_mismatch']++;

            return;
        }

        $this->counts[$privateExists ? 'legacy_both' : 'legacy_public_only']++;

        if (! $execute) {
            return;
        }

        if (! $privateExists && ! $this->copyAndVerify($public, $private, $file->path)) {
            $this->counts['failed']++;

            return;
        }

        $file->forceFill(['storage_disk' => 'local'])->save();

        if (! $public->delete($file->path)) {
            $this->counts['failed']++;

            return;
        }

        $this->counts['migrated']++;
    }

    private function processPrivateFile(
        ProjectFile $file,
        FilesystemAdapter $public,
        FilesystemAdapter $private,
        bool $publicExists,
        bool $privateExists,
        bool $execute
    ): void {
        if (! $privateExists) {
            $this->counts[$publicExists ? 'private_metadata_public_only' : 'missing_both']++;

            return;
        }

        if (! $publicExists) {
            $this->counts['already_private']++;

            return;
        }

        if (! $this->filesMatch($public, $private, $file->path)) {
            $this->counts['content_mismatch']++;

            return;
        }

        $this->counts['private_with_public_copy']++;

        if (! $execute) {
            return;
        }

        if (! $public->delete($file->path)) {
            $this->counts['failed']++;

            return;
        }

        $this->counts['public_copies_removed']++;
    }

    private function copyAndVerify(
        FilesystemAdapter $source,
        FilesystemAdapter $destination,
        string $path
    ): bool {
        $stream = $source->readStream($path);

        if (! is_resource($stream)) {
            return false;
        }

        try {
            if (! $destination->writeStream($path, $stream)) {
                return false;
            }
        } finally {
            fclose($stream);
        }

        return $destination->exists($path) && $this->filesMatch($source, $destination, $path);
    }

    private function filesMatch(
        FilesystemAdapter $first,
        FilesystemAdapter $second,
        string $path
    ): bool {
        return $first->size($path) === $second->size($path)
            && hash_equals($this->checksum($first, $path), $this->checksum($second, $path));
    }

    private function checksum(FilesystemAdapter $disk, string $path): string
    {
        $stream = $disk->readStream($path);

        if (! is_resource($stream)) {
            throw new \RuntimeException('Unable to read a project file stream.');
        }

        try {
            $context = hash_init('sha256');
            hash_update_stream($context, $stream);

            return hash_final($context);
        } finally {
            fclose($stream);
        }
    }

    private function initialCounts(): array
    {
        return [
            'total' => 0,
            'batches_processed' => 0,
            'public_bytes_detected' => 0,
            'private_bytes_detected' => 0,
            'legacy_public_only' => 0,
            'legacy_both' => 0,
            'legacy_private_only' => 0,
            'already_private' => 0,
            'private_with_public_copy' => 0,
            'migrated' => 0,
            'public_copies_removed' => 0,
            'rollback_already_public' => 0,
            'rolled_back' => 0,
            'orphan_files_scanned' => 0,
            'public_orphan_files' => 0,
            'public_orphan_bytes' => 0,
            'local_orphan_files' => 0,
            'local_orphan_bytes' => 0,
            'private_metadata_public_only' => 0,
            'missing_both' => 0,
            'content_mismatch' => 0,
            'metadata_mismatch' => 0,
            'failed' => 0,
            'elapsed_milliseconds' => 0,
        ];
    }

    private function problemCount(): int
    {
        return $this->counts['legacy_private_only']
            + $this->counts['private_metadata_public_only']
            + $this->counts['missing_both']
            + $this->counts['content_mismatch']
            + $this->counts['metadata_mismatch']
            + $this->counts['failed'];
    }
}
