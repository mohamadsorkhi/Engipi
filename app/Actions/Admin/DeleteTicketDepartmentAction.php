<?php

namespace App\Actions\Admin;

use App\Models\TicketDepartment;

class DeleteTicketDepartmentAction
{
    public function execute(TicketDepartment $department): void
    {
        $department->delete();
    }
}
