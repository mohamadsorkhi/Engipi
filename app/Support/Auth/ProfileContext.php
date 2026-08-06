<?php

namespace App\Support\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProfileContext
{
    public const SESSION_KEY = 'active_profile_id';

    /** @deprecated Compatibility mirror for rolling deployments and legacy sessions. */
    public const LEGACY_SESSION_KEY = 'active_role';

    public const SUPPORTED_TYPES = ['employer', 'specialist'];

    public function availableProfiles(User $user): Collection
    {
        return $user->profiles()
            ->whereIn('type', self::SUPPORTED_TYPES)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();
    }

    public function activeProfile(User $user): ?UserProfile
    {
        $profileId = Session::get(self::SESSION_KEY);

        if (is_string($profileId) && $profileId !== '') {
            $profile = $user->profiles()
                ->whereIn('type', self::SUPPORTED_TYPES)
                ->whereKey($profileId)
                ->first();

            if ($profile) {
                $this->mirrorLegacyType($profile);

                return $profile;
            }
        }

        // An ID is disposable browser state. Never resolve it globally: a stale or
        // foreign ID is cleared before attempting the strictly validated legacy path.
        Session::forget(self::SESSION_KEY);

        $legacyType = Session::get(self::LEGACY_SESSION_KEY);
        if (! is_string($legacyType) || ! in_array($legacyType, self::SUPPORTED_TYPES, true)) {
            $this->clear();

            return null;
        }

        $matches = $user->profiles()
            ->where('type', $legacyType)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        if ($matches->isEmpty()) {
            $this->clear();

            return null;
        }

        if ($matches->count() > 1) {
            Log::warning('Duplicate profile types found while translating a legacy session.', [
                'user_id' => $user->getKey(),
                'profile_type' => $legacyType,
                'profile_ids' => $matches->modelKeys(),
            ]);
        }

        return $this->activate($user, $matches->first());
    }

    public function activeType(User $user): ?string
    {
        return $this->activeProfile($user)?->type;
    }

    public function activate(User $user, UserProfile|string $profile): ?UserProfile
    {
        $profileId = $profile instanceof UserProfile ? $profile->getKey() : $profile;
        $ownedProfile = $user->profiles()
            ->whereIn('type', self::SUPPORTED_TYPES)
            ->whereKey($profileId)
            ->first();

        if (! $ownedProfile) {
            return null;
        }

        Session::put(self::SESSION_KEY, $ownedProfile->getKey());
        $this->mirrorLegacyType($ownedProfile);

        return $ownedProfile;
    }

    public function clear(): void
    {
        Session::forget([self::SESSION_KEY, self::LEGACY_SESSION_KEY]);
    }

    private function mirrorLegacyType(UserProfile $profile): void
    {
        // Derived compatibility value only; active_profile_id remains authoritative.
        Session::put(self::LEGACY_SESSION_KEY, $profile->type);
    }
}
