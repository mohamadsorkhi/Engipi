<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255', 'unique:skills,name'],
            'subdomain_id' => ['required', 'uuid', 'exists:subdomains,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'نام مهارت الزامی است.',
            'name.max'              => 'نام مهارت نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'name.unique'           => 'این مهارت قبلاً ثبت شده است.',
            'subdomain_id.required' => 'انتخاب زیرحوزه الزامی است.',
            'subdomain_id.exists'   => 'زیرحوزه انتخاب‌شده یافت نشد.',
        ];
    }
}
