<?php

namespace App\Services\Deletion;

use App\Models\PendingFileDeletion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class PendingFileCleanup
{
    /**
     * @param  array<int, string>  $ids
     * @return array{processed: int, failed: int}
     */
    public function processIds(array $ids): array
    {
        return $this->processQuery(PendingFileDeletion::query()->whereKey($ids));
    }

    /** @return array{processed: int, failed: int} */
    public function processPending(int $limit = 100): array
    {
        return $this->processQuery(
            PendingFileDeletion::query()
                // Always drain new work first. Failed work then rotates by its
                // oldest attempt so a permanently failing row cannot monopolize
                // every limited batch.
                ->orderByRaw('CASE WHEN last_attempt_at IS NULL THEN 0 ELSE 1 END')
                ->orderBy('last_attempt_at')
                ->orderBy('created_at')
                ->orderBy('id')
                ->limit($limit)
        );
    }

    /** @return array{processed: int, failed: int} */
    private function processQuery(Builder $query): array
    {
        $processed = 0;
        $failed = 0;

        foreach ($query->get() as $pending) {
            $failureCategory = null;

            try {
                try {
                    $disk = Storage::disk($pending->disk);

                    if ($disk->exists($pending->path) && ! $disk->delete($pending->path)) {
                        $failureCategory = 'delete_returned_false';
                        throw new RuntimeException($failureCategory);
                    }
                } catch (Throwable $exception) {
                    $failureCategory ??= 'storage_access_failure';

                    throw $exception;
                }

                // Another invocation may already have completed this record.
                PendingFileDeletion::query()->whereKey($pending->id)->delete();
                $processed++;
            } catch (Throwable) {
                $failureCategory ??= 'unexpected_cleanup_failure';
                $attemptNumber = $pending->attempts + 1;
                PendingFileDeletion::query()->whereKey($pending->id)->update([
                    'attempts' => $attemptNumber,
                    'last_attempt_at' => now(),
                    'last_error' => $failureCategory,
                    'updated_at' => now(),
                ]);
                $failed++;

                Log::error('Pending project file cleanup failed.', [
                    'pending_file_deletion_id' => $pending->id,
                    'failure_category' => $failureCategory,
                    'attempt_number' => $attemptNumber,
                ]);
            }
        }

        return compact('processed', 'failed');
    }
}
