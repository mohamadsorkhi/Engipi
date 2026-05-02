<?php

namespace App\Actions\Employer;

use App\Models\Request as CollaborationRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetEmployerRequestsAction
{
    public function execute(User $employer, int $perPage = 20): LengthAwarePaginator
    {
        return CollaborationRequest::whereHas('project', function ($q) use ($employer) {
            $q->where('employer_id', $employer->id);
        })
        ->with('project', 'user')
        ->orderByRaw("FIELD(status, 'pending', 'accepted', 'rejected')")
        ->latest('created_at')
        ->paginate($perPage);
    }
}
