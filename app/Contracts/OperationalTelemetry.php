<?php

namespace App\Contracts;

use App\ValueObjects\OperationalEvent;
use App\ValueObjects\OperationalSeverity;

interface OperationalTelemetry
{
    public function emit(OperationalEvent $event, OperationalSeverity $severity, array $context = []): void;
}
