<?php

namespace App\Actions\Admin;

use App\Models\Process;
use Illuminate\Validation\ValidationException;

class UpdateProcessAction
{
    public function execute(Process $process, array $data): Process
    {
        $exists = Process::where('skill_domain_id', $data['skill_domain_id'])
            ->where('name', $data['name'])
            ->where('id', '!=', $process->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['این پردازش در این حوزه قبلا ثبت شده است.'],
            ]);
        }

        $process->update($data);

        return $process;
    }
}
