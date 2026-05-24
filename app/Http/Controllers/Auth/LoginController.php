<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     * Supports both email and mobile.
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the user login request.
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
     * Attempt to log the user into the application.
     */
    protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        // Determine if login is email or mobile
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        return Auth::attempt(
            [$field => $login, 'password' => $password],
            $request->boolean('remember')
        );
    }

    /**
     * Get the needed authorization credentials from the request.
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    /**
     * After login, redirect specialist users who have no skills to the skill selection page.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->is_admin) {
            return null;
        }

        $profiles = $user->profiles;

        if ($profiles->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'redirect' => route('profile.select'),
                    'message'  => 'شما با موفقیت وارد شدید.',
                ]);
            }

            return redirect()->route('profile.select');
        }

        $specialistProfile = $profiles->firstWhere('type', 'specialist');

        if ($specialistProfile && $user->skills()->doesntExist()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'redirect' => route('skill.select'),
                    'message'  => 'شما با موفقیت وارد شدید.',
                ]);
            }

            return redirect()->route('skill.select');
        }

        return null;
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        if ($request->wantsJson()) {
            return new JsonResponse([
                'redirect' => $this->redirectPath(),
                'message' => 'شما با موفقیت وارد شدید.'
            ], 200);
        }

        return redirect()->intended($this->redirectPath());
    }
}
