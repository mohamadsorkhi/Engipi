<?php

namespace Tests\Feature\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Tests\TestCase;

class PendingFileCleanupScheduleTest extends TestCase
{
    public function test_pending_file_cleanup_is_scheduled_with_overlap_protection(): void
    {
        $event = collect(app(Schedule::class)->events())
            ->first(
                static fn ($event): bool => str_contains(
                    $event->command ?? '',
                    'project-files:cleanup',
                ),
            );

        $this->assertNotNull(
            $event,
            'The project-files:cleanup command is not scheduled.',
        );

        $this->assertSame(
            '*/5 * * * *',
            $event->expression,
        );

        $this->assertTrue(
            $event->withoutOverlapping,
            'The cleanup schedule must prevent overlapping executions.',
        );
    }
}
