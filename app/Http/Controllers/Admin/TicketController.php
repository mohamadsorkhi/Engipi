<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Ticket\CloseTicketAction;
use App\Actions\Ticket\PostTicketMessageAction;
use App\Actions\Ticket\ReopenTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTicketMessageRequest;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query()->with(['user', 'department']);

        $q = trim((string)$request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('subject', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQ) use ($q) {
                        $userQ->where('first_name', 'like', "%{$q}%")
                            ->orWhere('last_name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('mobile', 'like', "%{$q}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $tickets = $query->latest()->paginate(20);
        $tickets->appends($request->query());

        $departments = TicketDepartment::query()->where('active', true)->orderBy('name')->get();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.tickets.components.table_and_pagination', compact('tickets'))->render(),
            ]);
        }

        return view('admin.tickets.index', compact('tickets', 'departments'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load([
            'user',
            'department',
            'messages.user',
            'messages.admin',
        ]);

        return view('admin.tickets.show', compact('ticket'));
    }

    public function message(StoreTicketMessageRequest $request, Ticket $ticket, PostTicketMessageAction $action)
    {
        if ($ticket->status !== 'open') {
            return response()->json([
                'status' => 'error',
                'message' => 'این تیکت بسته شده است.',
            ], 422);
        }

        $action->executeAsAdmin($ticket, Auth::user(), $request->validated()['message']);

        return response()->json([
            'status' => 'success',
            'message' => 'پاسخ ارسال شد.',
            'redirect' => route('admin.tickets.show', $ticket),
        ]);
    }

    public function close(Ticket $ticket, CloseTicketAction $action)
    {
        if ($ticket->status === 'closed') {
            return response()->json([
                'status' => 'success',
                'message' => 'این تیکت قبلا بسته شده است.',
                'redirect' => route('admin.tickets.show', $ticket),
            ]);
        }

        $action->execute($ticket, 'admin');

        return response()->json([
            'status' => 'success',
            'message' => 'تیکت بسته شد.',
            'redirect' => route('admin.tickets.show', $ticket),
        ]);
    }

    public function reopen(Ticket $ticket, ReopenTicketAction $action)
    {
        if ($ticket->status === 'open') {
            return response()->json([
                'status' => 'success',
                'message' => 'این تیکت باز است.',
                'redirect' => route('admin.tickets.show', $ticket),
            ]);
        }

        $action->execute($ticket);

        return response()->json([
            'status' => 'success',
            'message' => 'تیکت باز شد.',
            'redirect' => route('admin.tickets.show', $ticket),
        ]);
    }
}
