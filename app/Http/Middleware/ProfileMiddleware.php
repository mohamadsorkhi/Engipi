<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     * Check if user has the required profile type.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$profileTypes
     */
    public function handle(Request $request, Closure $next, ...$profileTypes): Response
    {
        if (!Auth::check()) {
            abort(403, 'Access Denied. User not authenticated.');
        }

        $user = Auth::user();
        
        // Check if user has ANY profile of the required types
        $hasRequiredProfile = $user->profiles()->whereIn('type', $profileTypes)->exists();

        if (!$hasRequiredProfile) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'شما دسترسی به این بخش را ندارید. لطفا پروفایل مناسب را ایجاد کنید.',
                ], 403);
            }

            return redirect()->route('profile.select')
                ->with('error', 'لطفا ابتدا پروفایل مناسب را ایجاد کنید.');
        }

        return $next($request);
    }
}
