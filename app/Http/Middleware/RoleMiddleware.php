<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
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

        // The user mentioned the role is in the 'role' column of the 'users' table.
        // We will check if the user's role is in the list of roles provided to the middleware.
        if (!$user || !in_array($user->role, $roles)) {
            abort(403, 'You do not have the required role to access this page.');
        }

        return $next($request);
    }
}
