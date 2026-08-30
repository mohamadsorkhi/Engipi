<?php

namespace App\Actions\Employer;

use App\Models\Request as CollaborationRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetEmployerRequestsAction
{
    public function execute(
        User $employer,
        int $perPage = 20,
    ): LengthAwarePaginator {
        return CollaborationRequest::query()
            ->whereHas('project', function ($query) use ($employer): void {
                $query->where('employer_id', $employer->id);
            })
            ->with(['project', 'user'])
            ->orderByRaw(
                "CASE status
                    WHEN 'pending' THEN 1
                    WHEN 'accepted' THEN 2
                    WHEN 'rejected' THEN 3
                    ELSE 4
                END",
            )
            ->latest('created_at')
            ->paginate($perPage);
    }
}
