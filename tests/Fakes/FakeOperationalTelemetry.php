<?php

namespace Tests\Fakes;

use App\Contracts\OperationalTelemetry;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;
use RuntimeException;

final class FakeOperationalTelemetry implements OperationalTelemetry
{
    public array $events = [];

    public function __construct(private readonly bool $fail = false) {}

    public function emit(OperationalEvent $event, OperationalSeverity $severity, array $context = []): void
    {
        if ($this->fail) {
            throw new RuntimeException('synthetic telemetry sink failure');
        }

        $this->events[] = compact('event', 'severity', 'context');
    }
}
