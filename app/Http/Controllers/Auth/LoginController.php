<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Support\Auth\ProfileContext;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login field used by authentication.
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the login request.
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'وارد کردن ایمیل یا شماره موبایل الزامی است.',
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
        ]);
    }

    /**
     * Attempt to authenticate only active users.
     */
    protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'mobile';

        return Auth::attempt(
            [
                $field => $login,
                'password' => $password,
                'active' => true,
            ],
            $request->boolean('remember'),
        );
    }

    /**
     * Get the authentication credentials.
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'mobile';

        return [
            $field => $login,
            'password' => $request->input('password'),
            'active' => true,
        ];
    }

    /**
     * Handle a successfully authenticated user.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_admin) {
            return null;
        }

        $context = app(ProfileContext::class);
        $profiles = $context->availableProfiles($user);

        if ($profiles->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'redirect' => route('profile.select'),
                    'message' => 'شما با موفقیت وارد شدید.',
                ]);
            }

            return redirect()->route('profile.select');
        }

        return null;
    }

    /**
     * Send the response after authentication.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if (
            $response = $this->authenticated(
                $request,
                $this->guard()->user(),
            )
        ) {
            return $response;
        }

        if ($request->wantsJson()) {
            return new JsonResponse([
                'redirect' => $this->redirectPath(),
                'message' => 'شما با موفقیت وارد شدید.',
            ], 200);
        }

        return redirect()->intended($this->redirectPath());
    }
}
