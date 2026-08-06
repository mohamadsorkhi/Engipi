<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Support\Auth\ProfileContext;

class RoleMiddleware
{
    public function __construct(private ProfileContext $context)
    {
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            // This part may be redundant if you always use the 'auth' middleware before 'role'
            abort(403, 'Access Denied. User not authenticated.');
        }

        $user = Auth::user();

        // Legacy alias only. users.role is deprecated and never grants authority.
        $allowed = (in_array('admin', $roles, true) && $user->is_admin)
            || in_array($this->context->activeType($user), $roles, true);

        if (! $allowed) {
            abort(403, 'You do not have the required role to access this page.');
        }

        return $next($request);
    }
}
