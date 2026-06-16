<?php

namespace App\Actions\Admin;

use App\Models\SkillDomain;

class CreateSkillDomainAction
{
    public function execute(array $data): SkillDomain
    {
        return SkillDomain::create($data);
    }
}
