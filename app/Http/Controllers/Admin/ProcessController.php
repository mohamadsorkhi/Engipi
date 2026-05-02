<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateProcessAction;
use App\Actions\Admin\DeleteProcessAction;
use App\Actions\Admin\GetProcessesAction;
use App\Actions\Admin\UpdateProcessAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProcessRequest;
use App\Http\Requests\Admin\UpdateProcessRequest;
use App\Models\Process;
use App\Models\SkillDomain;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function index(Request $request, GetProcessesAction $getProcessesAction)
    {
        $processes = $getProcessesAction->execute(20);
        $domains = SkillDomain::orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.processes.components.table_and_pagination', compact('processes'))->render(),
            ]);
        }

        return view('admin.processes.index', compact('processes', 'domains'));
    }

    public function store(StoreProcessRequest $request, CreateProcessAction $action, GetProcessesAction $getProcessesAction)
    {
        $action->execute($request->validated());

        $processes = $getProcessesAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'پردازش با موفقیت افزوده شد.',
            'table' => view('admin.processes.components.table_and_pagination', compact('processes'))->render(),
            'close' => true,
        ]);
    }

    public function update(UpdateProcessRequest $request, Process $process, UpdateProcessAction $action, GetProcessesAction $getProcessesAction)
    {
        $action->execute($process, $request->validated());

        $processes = $getProcessesAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'پردازش با موفقیت ویرایش شد.',
            'table' => view('admin.processes.components.table_and_pagination', compact('processes'))->render(),
            'close' => true,
        ]);
    }

    public function destroy(Process $process, DeleteProcessAction $action, GetProcessesAction $getProcessesAction)
    {
        $action->execute($process);

        $processes = $getProcessesAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'پردازش با موفقیت حذف شد.',
            'table' => view('admin.processes.components.table_and_pagination', compact('processes'))->render(),
        ]);
    }
}
