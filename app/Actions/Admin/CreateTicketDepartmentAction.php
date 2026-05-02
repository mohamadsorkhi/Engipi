<?php

namespace App\Actions\Admin;

use App\Models\TicketDepartment;

class CreateTicketDepartmentAction
{
    public function execute(array $data): TicketDepartment
    {
        return TicketDepartment::create([
            'name' => $data['name'],
            'active' => $data['active'] ?? true,
        ]);
    }
}
