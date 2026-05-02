<?php

namespace App\Actions\Admin;

use App\Models\SkillDomain;
use Illuminate\Validation\ValidationException;

class DeleteSkillDomainAction
{
    public function execute(SkillDomain $domain): void
    {
        if ($domain->processes()->count() > 0) {
            throw ValidationException::withMessages([
                'domain' => ['این حوزه دارای پردازش است و قابل حذف نیست.'],
            ]);
        }

        $domain->delete();
    }
}
