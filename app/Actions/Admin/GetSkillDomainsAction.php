<?php

namespace App\Actions\Admin;

use App\Models\SkillDomain;

class GetSkillDomainsAction
{
    public function execute(int $perPage = 20)
    {
        return SkillDomain::withCount('subdomains')->latest()->paginate($perPage);
    }
}
