<?php

namespace App\Actions\Admin;

use App\Models\Subdomain;

class UpdateSubdomainAction
{
    public function execute(Subdomain $subdomain, array $data): Subdomain
    {
        $subdomain->update($data);
        return $subdomain->fresh();
    }
}
