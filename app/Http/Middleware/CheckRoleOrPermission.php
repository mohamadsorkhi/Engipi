<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $type  Must be 'role' or 'perm'.
     * @param  string  ...$params  The list of roles or permissions to check.
     */
    public function handle(Request $request, Closure $next, string $type, ...$params): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Access Denied.');
        }

        if ($type === 'role') {
            if (!$user->hasAnyRole($params)) {
                abort(403, 'You do not have the required role to access this page.');
            }
        } elseif ($type === 'perm') {
            // Eager load permissions for efficiency
            $user->loadMissing('role.permissions');

            foreach ($params as $permission) {
                if ($user->hasPermission($permission)) {
                    return $next($request); // Allow access if any permission matches
                }
            }
            // Abort if no permissions matched after checking all of them
            abort(403, 'You do not have the required permission to perform this action.');
        } else {
            abort(500, 'Invalid middleware configuration.');
        }

        return $next($request);
    }
}