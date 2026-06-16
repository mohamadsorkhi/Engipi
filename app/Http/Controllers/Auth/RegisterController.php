<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\UserProfile;
use App\Rules\Persian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255', new Persian],
            'last_name' => ['required', 'string', 'max:255', new Persian],
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست.',
            'mobile.unique' => 'این شماره موبایل قبلا ثبت شده است.',
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'active' => true,
            'is_admin' => false,
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? response()->json([
                        'message' => 'ثبت نام شما با موفقیت انجام شد.',
                        'redirect' => $this->redirectPath()
                      ])
                    : redirect($this->redirectPath());
    }

    /**
     * After registration, create pending project (employer pre-reg flow) or go to role select.
     */
    protected function registered(Request $request, $user)
    {
        $pending = session()->pull('pending_project');

        if ($pending) {
            DB::transaction(function () use ($user, $pending) {
                $profile = UserProfile::create([
                    'user_id' => $user->id,
                    'type'    => 'employer',
                ]);

                do {
                    $shortId = strtoupper(Str::random(8));
                } while (Project::where('short_id', $shortId)->exists());

                Project::create([
                    'employer_id'         => $user->id,
                    'employer_profile_id' => $profile->id,
                    'short_id'            => $shortId,
                    'title'               => $pending['title'],
                    'description'         => $pending['description'],
                    'work_type'           => $pending['work_type'],
                    'budget_min'          => $pending['budget_min'] ?? null,
                    'budget_max'          => $pending['budget_max'] ?? null,
                    'view_count'          => 0,
                ]);
            });

            if ($request->wantsJson()) {
                return response()->json([
                    'message'  => 'ثبت نام شما با موفقیت انجام شد. پروژه‌تان ثبت شد.',
                    'redirect' => route('user.projects.index'),
                ]);
            }

            return redirect()->route('user.projects.index');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message'  => 'ثبت نام شما با موفقیت انجام شد.',
                'redirect' => route('profile.select'),
            ]);
        }

        return redirect()->route('profile.select');
    }
}
