<?php

namespace App\Actions\Admin;

use App\Models\Process;
use Illuminate\Validation\ValidationException;

class DeleteProcessAction
{
    public function execute(Process $process): void
    {
        if ($process->skills()->count() > 0) {
            throw ValidationException::withMessages([
                'process' => ['این پردازش دارای مهارت است و قابل حذف نیست.'],
            ]);
        }

        $process->delete();
    }
}
