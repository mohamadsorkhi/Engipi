<?php

namespace App\Http\Requests\Auth;

use App\Rules\Persian;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', new Persian],
            'last_name' => ['required', 'string', 'max:255', new Persian],
            'mobile' => ['required', 'string', 'regex:/^09[0-9]{9}$/', 'unique:users,mobile'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام باید متن باشد.',
            'first_name.max' => 'نام نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی باید متن باشد.',
            'last_name.max' => 'نام خانوادگی نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'mobile.required' => 'وارد کردن شماره موبایل الزامی است.',
            'mobile.regex' => 'فرمت شماره موبایل صحیح نیست. (مثال: 09123456789)',
            'mobile.unique' => 'این شماره موبایل قبلا ثبت شده است.',
            'email.required' => 'وارد کردن ایمیل الزامی است.',
            'email.string' => 'ایمیل باید متن باشد.',
            'email.email' => 'فرمت ایمیل وارد شده صحیح نیست.',
            'email.max' => 'ایمیل نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'email.unique' => 'این ایمیل قبلا در سیستم ثبت شده است.',
            'password.required' => 'وارد کردن رمز عبور الزامی است.',
            'password.string' => 'رمز عبور باید متن باشد.',
            'password.min' => 'رمز عبور باید حداقل ۸ کاراکتر باشد.',
            'password.confirmed' => 'تکرار رمز عبور مطابقت ندارد.',
        ];
    }
}
