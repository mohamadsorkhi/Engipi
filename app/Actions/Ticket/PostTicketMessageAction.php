<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;

class PostTicketMessageAction
{
    public function executeAsUser(Ticket $ticket, User $user, string $message): TicketMessage
    {
        return TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $message,
        ]);
    }

    public function executeAsAdmin(Ticket $ticket, User $admin, string $message): TicketMessage
    {
        return TicketMessage::create([
            'ticket_id' => $ticket->id,
            'admin_id' => $admin->id,
            'message' => $message,
        ]);
    }
}
