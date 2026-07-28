<?php

namespace Tests\Unit;

use App\Services\OperationalTelemetry\LaravelOperationalTelemetry;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;
use Illuminate\Log\LogManager;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class OperationalTelemetryTest extends TestCase
{
    public function test_allowlisted_context_is_emitted_with_numeric_types_preserved(): void
    {
        $logger = Mockery::mock();
        $logger->shouldReceive('log')->once()->with('info', 'project_document.inspection.completed', [
            'event_version' => 1,
            'outcome' => 'clean',
            'duration_ms' => 12,
        ]);
        $manager = Mockery::mock(LogManager::class);
        $manager->shouldReceive('channel')->once()->with('operations')->andReturn($logger);

        (new LaravelOperationalTelemetry($manager))->emit(
            OperationalEvent::ProjectDocumentInspectionCompleted,
            OperationalSeverity::Info,
            ['event_version' => 1, 'outcome' => 'clean', 'duration_ms' => 12],
        );
    }

    public function test_unknown_sensitive_fields_are_rejected_before_logging(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $manager = Mockery::mock(LogManager::class);
        $manager->shouldNotReceive('channel');

        (new LaravelOperationalTelemetry($manager))->emit(
            OperationalEvent::ProjectDocumentInspectionCompleted,
            OperationalSeverity::Error,
            ['path' => 'C:\\private\\secret.pdf'],
        );
    }

    public function test_nested_context_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $manager = Mockery::mock(LogManager::class);

        (new LaravelOperationalTelemetry($manager))->emit(
            OperationalEvent::ProjectDocumentInspectionCompleted,
            OperationalSeverity::Info,
            ['outcome' => ['clean']],
        );
    }

    public function test_sensitive_free_text_in_a_bounded_field_is_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $manager = Mockery::mock(LogManager::class);

        (new LaravelOperationalTelemetry($manager))->emit(
            OperationalEvent::ProjectDocumentInspectionCompleted,
            OperationalSeverity::Error,
            ['outcome' => 'Eicar at C:\\private\\secret.pdf'],
        );
    }

    public function test_logging_failure_is_swallowed(): void
    {
        $manager = Mockery::mock(LogManager::class);
        $manager->shouldReceive('channel')->once()->andThrow(new \RuntimeException('credential host path'));

        (new LaravelOperationalTelemetry($manager))->emit(
            OperationalEvent::ProjectDocumentInspectionCompleted,
            OperationalSeverity::Info,
            ['event_version' => 1, 'outcome' => 'clean', 'duration_ms' => 0],
        );

        $this->addToAssertionCount(1);
    }
}
