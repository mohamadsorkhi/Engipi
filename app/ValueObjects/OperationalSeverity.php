<?php

namespace App\ValueObjects;

enum OperationalSeverity: string
{
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
}
