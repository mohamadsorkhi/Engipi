<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Check if user is an admin.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Access Denied. User not authenticated.');
        }

        $user = Auth::user();

        if (!$user->is_admin) {
            abort(403, 'شما دسترسی ادمین ندارید.');
        }

        return $next($request);
    }
}
