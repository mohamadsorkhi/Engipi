<?php

namespace App\Actions\Ticket;

use App\Models\Ticket;

class CloseTicketAction
{
    public function execute(Ticket $ticket, string $by): void
    {
        $ticket->close($by);
    }
}
