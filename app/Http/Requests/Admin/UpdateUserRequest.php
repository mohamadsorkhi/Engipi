<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام کاربر الزامی است.',
            'name.string' => 'نام کاربر باید متن باشد.',
            'name.max' => 'نام کاربر نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'email.required' => 'ایمیل الزامی است.',
            'email.string' => 'ایمیل باید متن باشد.',
            'email.email' => 'فرمت ایمیل صحیح نیست.',
            'email.max' => 'ایمیل نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'email.unique' => 'این ایمیل قبلا ثبت شده است.',
            'password.string' => 'رمز عبور باید متن باشد.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
        ];
    }
}
