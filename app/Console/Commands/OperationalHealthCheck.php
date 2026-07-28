<?php

namespace App\Console\Commands;

use App\Contracts\OperationalHealthChecker;
use App\Contracts\OperationalTelemetry;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalHealthResult;
use App\ValueObjects\OperationalSeverity;
use Illuminate\Console\Command;
use Throwable;

final class OperationalHealthCheck extends Command
{
    protected $signature = 'operations:health';

    protected $description = 'Run privacy-safe, read-only operational health checks';

    public function handle(OperationalHealthChecker $checker, OperationalTelemetry $telemetry): int
    {
        $result = $checker->check();

        foreach (OperationalHealthResult::COMPONENTS as $component) {
            $this->line($component.': '.($result->components[$component] ?? 'unhealthy'));
        }

        $status = $result->overallStatus();
        $this->line('overall: '.$status);

        try {
            $telemetry->emit(
                OperationalEvent::OperationsHealthCompleted,
                $status === 'healthy' ? OperationalSeverity::Info : OperationalSeverity::Error,
                $result->telemetryContext(),
            );
        } catch (Throwable) {
            // Health exit status is independent of telemetry availability.
        }

        return $status === 'unhealthy' ? self::FAILURE : self::SUCCESS;
    }
}
