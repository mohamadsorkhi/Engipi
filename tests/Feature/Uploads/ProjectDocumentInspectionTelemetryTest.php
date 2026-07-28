<?php

namespace Tests\Feature\Uploads;

use App\Services\ProjectDocuments\TelemetryProjectDocumentInspector;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Http\UploadedFile;
use RuntimeException;
use Tests\Fakes\FakeOperationalTelemetry;
use Tests\Fakes\FakeProjectDocumentInspector;
use Tests\TestCase;

class ProjectDocumentInspectionTelemetryTest extends TestCase
{
    /** @dataProvider outcomes */
    public function test_each_result_emits_exactly_one_safe_terminal_event($result, string $outcome): void
    {
        $telemetry = new FakeOperationalTelemetry;
        $decorator = new TelemetryProjectDocumentInspector(new FakeProjectDocumentInspector($result), $telemetry);
        $decorator->inspect(UploadedFile::fake()->create('customer-secret.pdf', 1));

        $this->assertCount(1, $telemetry->events);
        $event = $telemetry->events[0];
        $this->assertSame(OperationalEvent::ProjectDocumentInspectionCompleted, $event['event']);
        $this->assertSame($outcome, $event['context']['outcome']);
        $this->assertIsInt($event['context']['duration_ms']);
        $this->assertSame(['event_version', 'outcome', 'duration_ms'], array_keys($event['context']));
    }

    public static function outcomes(): array
    {
        return [
            'clean' => [ProjectDocumentInspectionResult::clean(), 'clean'],
            'malware' => [ProjectDocumentInspectionResult::malwareDetected(), 'malware_detected'],
            'unavailable' => [ProjectDocumentInspectionResult::scannerUnavailable(), 'scanner_unavailable'],
            'failed' => [ProjectDocumentInspectionResult::inspectionFailed(), 'inspection_failed'],
        ];
    }

    public function test_exception_emits_redacted_event_and_is_rethrown(): void
    {
        $telemetry = new FakeOperationalTelemetry;
        $exception = new RuntimeException('Eicar at C:\\private\\customer-secret.pdf');
        $decorator = new TelemetryProjectDocumentInspector(new FakeProjectDocumentInspector($exception), $telemetry);

        try {
            $decorator->inspect(UploadedFile::fake()->create('customer-secret.pdf', 1));
            $this->fail('Expected exception was not rethrown.');
        } catch (RuntimeException $caught) {
            $this->assertSame($exception, $caught);
        }

        $this->assertCount(1, $telemetry->events);
        $this->assertSame('exception', $telemetry->events[0]['context']['outcome']);
        $this->assertStringNotContainsString('Eicar', json_encode($telemetry->events));
        $this->assertStringNotContainsString('customer-secret.pdf', json_encode($telemetry->events));
    }

    public function test_telemetry_failure_does_not_change_clean_or_unsafe_results(): void
    {
        foreach ([ProjectDocumentInspectionResult::clean(), ProjectDocumentInspectionResult::malwareDetected()] as $result) {
            $decorator = new TelemetryProjectDocumentInspector(
                new FakeProjectDocumentInspector($result),
                new FakeOperationalTelemetry(fail: true),
            );

            $this->assertSame($result, $decorator->inspect(UploadedFile::fake()->create('brief.pdf', 1)));
        }
    }
}
