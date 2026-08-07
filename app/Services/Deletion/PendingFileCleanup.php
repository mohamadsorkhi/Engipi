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
            PendingFileDeletion::query()->orderBy('created_at')->limit($limit)
        );
    }

    /** @return array{processed: int, failed: int} */
    private function processQuery(Builder $query): array
    {
        $processed = 0;
        $failed = 0;

        foreach ($query->get() as $pending) {
            try {
                $disk = Storage::disk($pending->disk);

                if ($disk->exists($pending->path) && ! $disk->delete($pending->path)) {
                    throw new RuntimeException('The storage adapter did not delete the file.');
                }

                // Another invocation may already have completed this record.
                PendingFileDeletion::query()->whereKey($pending->id)->delete();
                $processed++;
            } catch (Throwable $exception) {
                PendingFileDeletion::query()->whereKey($pending->id)->update([
                    'attempts' => $pending->attempts + 1,
                    'last_attempt_at' => now(),
                    'last_error' => $exception->getMessage(),
                    'updated_at' => now(),
                ]);
                $failed++;

                Log::error('Pending project file cleanup failed.', [
                    'pending_file_deletion_id' => $pending->id,
                    'disk' => $pending->disk,
                    'path' => $pending->path,
                    'exception' => $exception,
                ]);
            }
        }

        return compact('processed', 'failed');
    }
}
