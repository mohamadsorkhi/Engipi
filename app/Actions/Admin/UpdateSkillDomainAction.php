<?php

namespace App\Actions\Admin;

use App\Models\SkillDomain;

class UpdateSkillDomainAction
{
    public function execute(SkillDomain $domain, array $data): SkillDomain
    {
        $domain->update($data);
        return $domain;
    }
}
