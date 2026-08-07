<?php

namespace App\Actions\Admin;

use App\Models\User;
use App\Services\Deletion\DeletionLifecycle;

class DeleteUserAction
{
    public function __construct(private DeletionLifecycle $deletionLifecycle)
    {
    }

    public function execute(User $user): bool
    {
        return $this->deletionLifecycle->deleteUser($user);
    }
}
