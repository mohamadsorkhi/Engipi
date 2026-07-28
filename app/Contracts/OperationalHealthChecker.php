<?php

namespace App\Contracts;

use App\ValueObjects\OperationalHealthResult;

interface OperationalHealthChecker
{
    public function check(): OperationalHealthResult;
}
