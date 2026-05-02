<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Session;

class SwitchProfileAction
{
    public const SESSION_KEY = 'active_profile_id';

    public function execute(User $user, string $profileId): ?UserProfile
    {
        $profile = $user->profiles()->where('id', $profileId)->first();

        if ($profile) {
            Session::put(self::SESSION_KEY, $profile->id);
            return $profile;
        }

        return null;
    }

    public static function getActiveProfile(User $user): ?UserProfile
    {
        $profileId = Session::get(self::SESSION_KEY);

        if ($profileId) {
            $profile = $user->profiles()->where('id', $profileId)->first();
            if ($profile) {
                return $profile;
            }
        }

        return $user->profiles()->first();
    }

    public static function getActiveProfileType(User $user): ?string
    {
        $profile = self::getActiveProfile($user);
        return $profile?->type;
    }
}
