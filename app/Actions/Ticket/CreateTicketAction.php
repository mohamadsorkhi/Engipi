<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\User;

class CreateTicketAction
{
    public function execute(User $user, array $data): Ticket
    {
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'department_id' => $data['department_id'] ?? null,
            'subject' => $data['subject'],
            'status' => 'open',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $data['message'],
        ]);

        return $ticket;
    }
}
