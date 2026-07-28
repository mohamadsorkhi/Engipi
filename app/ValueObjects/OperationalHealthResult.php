<?php

namespace App\ValueObjects;

final readonly class OperationalHealthResult
{
    public const COMPONENTS = ['database', 'private_storage', 'public_storage', 'scanner', 'cache'];

    public function __construct(public array $components, public array $durations = []) {}

    public function overallStatus(): string
    {
        foreach (['database', 'private_storage', 'scanner'] as $component) {
            if (($this->components[$component] ?? 'unhealthy') !== 'healthy') {
                return 'unhealthy';
            }
        }

        foreach (['public_storage', 'cache'] as $component) {
            if (($this->components[$component] ?? 'unhealthy') !== 'healthy') {
                return 'degraded';
            }
        }

        return 'healthy';
    }

    public function telemetryContext(): array
    {
        $context = ['event_version' => 1, 'status' => $this->overallStatus()];

        foreach (self::COMPONENTS as $component) {
            $context[$component.'_status'] = $this->components[$component] ?? 'unhealthy';
            $context[$component.'_duration_ms'] = $this->durations[$component] ?? 0;
        }

        return $context;
    }
}
