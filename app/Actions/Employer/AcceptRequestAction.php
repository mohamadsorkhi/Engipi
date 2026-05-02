<?php

namespace App\Actions\Employer;

use App\Models\Request as CollaborationRequest;

class AcceptRequestAction
{
    public function execute(CollaborationRequest $request): CollaborationRequest
    {
        $request->update(['status' => 'accepted']);
        return $request->fresh();
    }
}
