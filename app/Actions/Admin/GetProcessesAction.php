<?php

namespace App\Actions\Admin;

use App\Models\Process;

class GetProcessesAction
{
    public function execute(int $perPage = 20)
    {
        return Process::with('domain')->withCount('skills')->latest()->paginate($perPage);
    }
}
