<?php

namespace App\Actions\Employer;

use App\Models\Request as CollaborationRequest;

class RejectRequestAction
{
    public function execute(CollaborationRequest $request): CollaborationRequest
    {
        $request->update(['status' => 'rejected']);
        return $request->fresh();
    }
}
