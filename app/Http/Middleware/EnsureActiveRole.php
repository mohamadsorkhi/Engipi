<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveRole
{
    /**
     * Enforce that an active_role is stored in session before granting access.
     *
     * Usage:
     *   'active_role'            — any valid role required
     *   'active_role:employer'   — employer role required
     *   'active_role:specialist' — specialist role required
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if ($user->is_admin) {
            return $next($request);
        }

        $profiles = $user->profiles;

        // No profiles at all → go create one
        if ($profiles->isEmpty()) {
            return redirect()->route('profile.select');
        }

        $activeRole = session('active_role');

        if (!$activeRole) {
            $hasEmployer   = $profiles->contains('type', 'employer');
            $hasSpecialist = $profiles->contains('type', 'specialist');

            if ($hasEmployer && $hasSpecialist) {
                // Dual-profile user must choose explicitly
                return redirect()->route('profile.select');
            }

            // Single-profile user: auto-set transparently so they skip the picker
            $activeRole = $hasEmployer ? 'employer' : 'specialist';
            session(['active_role' => $activeRole]);
        }

        // If a specific role is required, enforce it
        if (!empty($roles) && !in_array($activeRole, $roles)) {
            return redirect()->route('profile.select')
                ->with('error', 'برای دسترسی به این بخش، نقش مناسب را انتخاب کنید.');
        }

        return $next($request);
    }
}
