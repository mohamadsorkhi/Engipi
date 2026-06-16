<?php

namespace App\Actions\Admin;

use App\Models\Subdomain;
use Illuminate\Support\Facades\DB;

class DeleteSubdomainAction
{
    public function execute(Subdomain $subdomain): bool
    {
        return DB::transaction(function () use ($subdomain) {
            // Disassociate skills rather than cascade-deleting them
            $subdomain->skills()->update(['subdomain_id' => null]);
            return $subdomain->delete();
        });
    }
}
