<?php

namespace App\Actions\Admin;

use App\Models\TicketDepartment;

class UpdateTicketDepartmentAction
{
    public function execute(TicketDepartment $department, array $data): TicketDepartment
    {
        $department->update([
            'name' => $data['name'],
            'active' => $data['active'] ?? $department->active,
        ]);

        return $department;
    }
}
