<?php

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserProfile;
use App\Support\Auth\ProfileContext;

class SwitchProfileAction
{
    /** @deprecated Use ProfileContext directly. */
    public const SESSION_KEY = ProfileContext::SESSION_KEY;

    public function __construct(private ProfileContext $context)
    {
    }

    public function execute(User $user, string $profileId): ?UserProfile
    {
        return $this->context->activate($user, $profileId);
    }

    public static function getActiveProfile(User $user): ?UserProfile
    {
        return app(ProfileContext::class)->activeProfile($user);
    }

    public static function getActiveProfileType(User $user): ?string
    {
        $profile = self::getActiveProfile($user);
        return $profile?->type;
    }
}
