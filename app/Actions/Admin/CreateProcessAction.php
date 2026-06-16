<?php

namespace App\Actions\Admin;

use App\Models\Process;
use Illuminate\Validation\ValidationException;

class CreateProcessAction
{
    public function execute(array $data): Process
    {
        $exists = Process::where('skill_domain_id', $data['skill_domain_id'])
            ->where('name', $data['name'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['این پردازش در این حوزه قبلا ثبت شده است.'],
            ]);
        }

        return Process::create($data);
    }
}
