<?php

namespace App\Actions\Employer;

use App\Models\Request as CollaborationRequest;

class RevertRequestAction
{
    public function execute(CollaborationRequest $request): CollaborationRequest
    {
        $request->update(['status' => 'pending']);
        return $request->fresh();
    }
}
