<?php

namespace Tests\Feature\Commands;

use App\Contracts\OperationalHealthChecker;
use App\Contracts\OperationalTelemetry;
use App\ValueObjects\OperationalHealthResult;
use Illuminate\Support\Facades\Artisan;
use Tests\Fakes\FakeOperationalTelemetry;
use Tests\TestCase;

class OperationalHealthCheckTest extends TestCase
{
    public function test_healthy_state_returns_success_and_safe_output(): void
    {
        [$exitCode, $output, $telemetry] = $this->runHealth([
            'database' => 'healthy', 'private_storage' => 'healthy',
            'public_storage' => 'healthy', 'scanner' => 'healthy', 'cache' => 'healthy',
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('overall: healthy', $output);
        $this->assertSame('healthy', $telemetry->events[0]['context']['status']);
    }

    public function test_cache_or_public_storage_failure_is_degraded(): void
    {
        foreach (['cache', 'public_storage'] as $failedComponent) {
            $components = array_fill_keys(OperationalHealthResult::COMPONENTS, 'healthy');
            $components[$failedComponent] = 'unhealthy';
            [$exitCode, $output] = $this->runHealth($components);

            $this->assertSame(0, $exitCode);
            $this->assertStringContainsString($failedComponent.': unhealthy', $output);
            $this->assertStringContainsString('overall: degraded', $output);
        }
    }

    public function test_required_component_failure_is_unhealthy_and_checks_remain_visible(): void
    {
        foreach (['database', 'private_storage', 'scanner'] as $failedComponent) {
            $components = array_fill_keys(OperationalHealthResult::COMPONENTS, 'healthy');
            $components[$failedComponent] = 'unhealthy';
            [$exitCode, $output, $telemetry] = $this->runHealth($components);

            $this->assertSame(1, $exitCode);
            $this->assertStringContainsString('overall: unhealthy', $output);
            foreach (OperationalHealthResult::COMPONENTS as $component) {
                $this->assertStringContainsString($component.':', $output);
            }
            $this->assertSame('unhealthy', $telemetry->events[0]['context']['status']);
            $this->assertStringNotContainsString('credential', $output);
            $this->assertStringNotContainsString('C:\\', $output);
        }
    }

    private function runHealth(array $components): array
    {
        $checker = new class($components) implements OperationalHealthChecker
        {
            public function __construct(private readonly array $components) {}

            public function check(): OperationalHealthResult
            {
                return new OperationalHealthResult($this->components, array_fill_keys(
                    OperationalHealthResult::COMPONENTS,
                    3,
                ));
            }
        };
        $telemetry = new FakeOperationalTelemetry;
        $this->app->instance(OperationalHealthChecker::class, $checker);
        $this->app->instance(OperationalTelemetry::class, $telemetry);

        $exitCode = Artisan::call('operations:health');

        return [$exitCode, Artisan::output(), $telemetry];
    }
}
