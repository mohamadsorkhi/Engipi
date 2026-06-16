<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserProfile;

class AddProfileAction
{
    public function execute(User $user, array $data): UserProfile
    {
        return UserProfile::create([
            'user_id' => $user->id,
            'type' => $data['profile_type'],
            'company_name' => $data['company_name'] ?? null,
            'headline' => $data['headline'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);
    }
}
