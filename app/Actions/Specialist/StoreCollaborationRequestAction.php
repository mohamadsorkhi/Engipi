<?php

namespace App\Actions\Specialist;

use App\Models\Request as CollaborationRequest;
use App\Models\User;

class StoreCollaborationRequestAction
{
    public function execute(User $user, string $projectId, ?string $message = null): CollaborationRequest
    {
        return $user->requests()->create([
            'project_id' => $projectId,
            'message' => $message,
            'status' => 'pending',
        ]);
    }
}
