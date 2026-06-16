<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetUsersAction
{
    public function execute(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = User::query()->where('is_admin', false);

        if (!empty($filters['has_profile'])) {
            $query->whereHas('profiles');
        }

        $q = trim((string)($filters['q'] ?? ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%");
            });
        }

        $profileType = $filters['profile_type'] ?? null;
        if (in_array($profileType, ['employer', 'specialist'], true)) {
            $query->whereHas('profiles', function ($sub) use ($profileType) {
                $sub->where('type', $profileType);
            });
        }

        if (!empty($filters['has_projects'])) {
            $query->whereHas('projects');
        }

        if (!empty($filters['has_skills'])) {
            $query->whereHas('profiles', function ($sub) {
                $sub->where('type', 'specialist')->whereHas('processes');
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
