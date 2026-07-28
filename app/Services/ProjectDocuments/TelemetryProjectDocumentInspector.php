<?php

namespace App\Services\ProjectDocuments;

use App\Contracts\OperationalTelemetry;
use App\Contracts\ProjectDocumentInspector;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;
use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Http\UploadedFile;
use Throwable;

final class TelemetryProjectDocumentInspector implements ProjectDocumentInspector
{
    public function __construct(
        private readonly ProjectDocumentInspector $inspector,
        private readonly OperationalTelemetry $telemetry,
    ) {}

    public function inspect(UploadedFile $file): ProjectDocumentInspectionResult
    {
        $startedAt = microtime(true);

        try {
            $result = $this->inspector->inspect($file);
            $this->emit($result->status->value, $startedAt);

            return $result;
        } catch (Throwable $exception) {
            $this->emit('exception', $startedAt);

            throw $exception;
        }
    }

    private function emit(string $outcome, float $startedAt): void
    {
        $severity = match ($outcome) {
            'clean' => OperationalSeverity::Info,
            'malware_detected' => OperationalSeverity::Warning,
            default => OperationalSeverity::Error,
        };

        try {
            $this->telemetry->emit(OperationalEvent::ProjectDocumentInspectionCompleted, $severity, [
                'event_version' => 1,
                'outcome' => $outcome,
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            ]);
        } catch (Throwable) {
            // Custom telemetry implementations are also isolated from inspection behavior.
        }
    }
}
