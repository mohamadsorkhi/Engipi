<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateTicketDepartmentAction;
use App\Actions\Admin\DeleteTicketDepartmentAction;
use App\Actions\Admin\UpdateTicketDepartmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTicketDepartmentRequest;
use App\Http\Requests\Admin\UpdateTicketDepartmentRequest;
use App\Models\TicketDepartment;
use Illuminate\Http\Request;

class TicketDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $departments = TicketDepartment::query()->latest()->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.ticket-departments.components.table_and_pagination', compact('departments'))->render(),
            ]);
        }

        return view('admin.ticket-departments.index', compact('departments'));
    }

    public function store(StoreTicketDepartmentRequest $request, CreateTicketDepartmentAction $action)
    {
        $action->execute($request->validated());

        $departments = TicketDepartment::query()->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'دپارتمان با موفقیت ایجاد شد.',
            'close' => true,
            'table' => view('admin.ticket-departments.components.table_and_pagination', compact('departments'))->render(),
        ]);
    }

    public function update(UpdateTicketDepartmentRequest $request, TicketDepartment $ticket_department, UpdateTicketDepartmentAction $action)
    {
        $action->execute($ticket_department, $request->validated());

        $departments = TicketDepartment::query()->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'دپارتمان با موفقیت بروزرسانی شد.',
            'close' => true,
            'table' => view('admin.ticket-departments.components.table_and_pagination', compact('departments'))->render(),
        ]);
    }

    public function destroy(TicketDepartment $ticket_department, DeleteTicketDepartmentAction $action)
    {
        $action->execute($ticket_department);

        $departments = TicketDepartment::query()->latest()->paginate(20);

        return response()->json([
            'status' => 'success',
            'message' => 'دپارتمان با موفقیت حذف شد.',
            'table' => view('admin.ticket-departments.components.table_and_pagination', compact('departments'))->render(),
        ]);
    }
}
