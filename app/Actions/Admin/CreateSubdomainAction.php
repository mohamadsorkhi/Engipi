<?php

namespace App\Actions\Admin;

use App\Models\Subdomain;

class CreateSubdomainAction
{
    public function execute(array $data): Subdomain
    {
        return Subdomain::create($data);
    }
}
