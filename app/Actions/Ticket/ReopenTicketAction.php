<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;

class ReopenTicketAction
{
    public function execute(Ticket $ticket): void
    {
        $ticket->reopen();
    }
}
