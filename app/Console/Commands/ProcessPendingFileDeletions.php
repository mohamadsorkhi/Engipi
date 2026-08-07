<?php

namespace App\Console\Commands;

use App\Services\Deletion\PendingFileCleanup;
use Illuminate\Console\Command;

class ProcessPendingFileDeletions extends Command
{
    protected $signature = 'project-files:cleanup {--limit=100 : Maximum pending records to process}';

    protected $description = 'Retry durable pending project file deletions';

    public function handle(PendingFileCleanup $cleanup): int
    {
        $limit = filter_var($this->option('limit'), FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1],
        ]);

        if ($limit === false) {
            $this->error('The --limit option must be a positive integer.');

            return self::INVALID;
        }

        $result = $cleanup->processPending($limit);
        $this->info("Processed {$result['processed']} pending file deletion(s); {$result['failed']} failed.");

        return $result['failed'] === 0 ? self::SUCCESS : self::FAILURE;
    }
}
