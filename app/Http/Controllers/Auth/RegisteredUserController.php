<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterRequest $request, RegisterUserAction $action)
    {
        $user = $action->execute($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'message' => 'ثبت نام شما با موفقیت انجام شد.',
            'redirect' => route('root'),
        ]);
    }
}
