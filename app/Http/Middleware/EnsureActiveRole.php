<?php

namespace App\Http\Middleware;

use App\Support\Auth\ProfileContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveRole
{
    public function __construct(private ProfileContext $context)
    {
    }
    /**
     * Enforce an ownership-validated active profile context.
     *
     * Usage:
     * The legacy middleware alias is retained to avoid route churn; its values
     * are matched only against the type derived from active_profile_id.
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return $next($request);
        }

        $profiles = $this->context->availableProfiles($user);

        // No profiles at all → go create one
        if ($profiles->isEmpty()) {
            return redirect()->route('profile.select');
        }

        $activeProfile = $this->context->activeProfile($user);

        if (! $activeProfile) {
            if ($profiles->count() > 1) {
                // Dual-profile user must choose explicitly
                return redirect()->route('profile.select');
            }

            // Single-profile user: auto-set transparently so they skip the picker
            $activeProfile = $this->context->activate($user, $profiles->first());
        }

        $activeType = $activeProfile?->type;
        if (!empty($roles) && !in_array($activeType, $roles, true)) {
            return redirect()->route('profile.select')
                ->with('error', 'برای دسترسی به این بخش، نقش مناسب را انتخاب کنید.');
        }

        return $next($request);
    }
}
