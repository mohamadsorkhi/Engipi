<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Authenticate the request and reject deactivated accounts.
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        $user = $request->user();

        if ($user && ! $user->active) {
            $guardNames = empty($guards) ? [null] : $guards;

            foreach ($guardNames as $guardName) {
                $guard = Auth::guard($guardName);

                if (method_exists($guard, 'logout')) {
                    $guard->logout();
                }
            }

            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            throw new AuthenticationException(
                'Unauthenticated.',
                $guards,
                $this->redirectTo($request),
            );
        }

        return $next($request);
    }

    /**
     * Get the path used when redirecting unauthenticated users.
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }

        return null;
    }
}
