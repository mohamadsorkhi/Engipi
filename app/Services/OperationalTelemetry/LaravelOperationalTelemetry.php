<?php

namespace App\Services\OperationalTelemetry;

use App\Contracts\OperationalTelemetry;
use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;
use Illuminate\Log\LogManager;
use InvalidArgumentException;
use Throwable;

final class LaravelOperationalTelemetry implements OperationalTelemetry
{
    public function __construct(private readonly LogManager $logs) {}

    public function emit(OperationalEvent $event, OperationalSeverity $severity, array $context = []): void
    {
        $normalized = $this->normalize($event, $context);

        try {
            $this->logs->channel('operations')->log($severity->value, $event->value, $normalized);
        } catch (Throwable) {
            // Operational visibility must never alter business behavior.
        }
    }

    private function normalize(OperationalEvent $event, array $context): array
    {
        $unknown = array_diff(array_keys($context), $event->allowedContext());

        if ($unknown !== []) {
            throw new InvalidArgumentException('Operational telemetry context contains non-allowlisted fields.');
        }

        foreach ($context as $key => $value) {
            if (! is_string($value) && ! is_int($value) && ! is_float($value) && ! is_bool($value)) {
                throw new InvalidArgumentException('Operational telemetry context values must be scalar.');
            }

            $allowedValues = $event->allowedStringValues($key);

            if ($allowedValues !== null && (! is_string($value) || ! in_array($value, $allowedValues, true))) {
                throw new InvalidArgumentException('Operational telemetry context contains an unbounded value.');
            }
        }

        return $context;
    }
}
