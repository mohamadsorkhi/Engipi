<?php

namespace App\Http\Controllers\User;

use App\Actions\Ticket\CloseTicketAction;
use App\Actions\Ticket\CreateTicketAction;
use App\Actions\Ticket\PostTicketMessageAction;
use App\Actions\Ticket\ReopenTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreTicketMessageRequest;
use App\Http\Requests\User\StoreTicketRequest;
use App\Models\Ticket;
use App\Models\TicketDepartment;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Auth::user()->tickets()
            ->with(['department'])
            ->latest()
            ->paginate(10);

        return view('user.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $departments = TicketDepartment::query()->where('active', true)->orderBy('name')->get();
        return view('user.tickets.create', compact('departments'));
    }

    public function store(StoreTicketRequest $request, CreateTicketAction $action)
    {
        $ticket = $action->execute(Auth::user(), $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'تیکت با موفقیت ایجاد شد.',
            'redirect' => route('user.tickets.show', $ticket),
        ]);
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        $ticket->load([
            'department',
            'messages.user',
            'messages.admin',
        ]);

        return view('user.tickets.show', compact('ticket'));
    }

    public function message(StoreTicketMessageRequest $request, Ticket $ticket, PostTicketMessageAction $action)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status !== 'open') {
            return response()->json([
                'status' => 'error',
                'message' => 'این تیکت بسته شده است.',
            ], 422);
        }

        $action->executeAsUser($ticket, Auth::user(), $request->validated()['message']);

        return response()->json([
            'status' => 'success',
            'message' => 'پیام شما ارسال شد.',
            'redirect' => route('user.tickets.show', $ticket),
        ]);
    }

    public function close(Ticket $ticket, CloseTicketAction $action)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status === 'closed') {
            return response()->json([
                'status' => 'success',
                'message' => 'این تیکت قبلا بسته شده است.',
                'redirect' => route('user.tickets.show', $ticket),
            ]);
        }

        $action->execute($ticket, 'user');

        return response()->json([
            'status' => 'success',
            'message' => 'تیکت بسته شد.',
            'redirect' => route('user.tickets.show', $ticket),
        ]);
    }

    public function reopen(Ticket $ticket, ReopenTicketAction $action)
    {
        if ($ticket->user_id !== Auth::id()) {
            abort(403);
        }

        if ($ticket->status === 'open') {
            return response()->json([
                'status' => 'success',
                'message' => 'این تیکت باز است.',
                'redirect' => route('user.tickets.show', $ticket),
            ]);
        }

        $action->execute($ticket);

        return response()->json([
            'status' => 'success',
            'message' => 'تیکت باز شد.',
            'redirect' => route('user.tickets.show', $ticket),
        ]);
    }
}
