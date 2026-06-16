<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreSubdomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255',
                Rule::unique('subdomains', 'name')->where('skill_domain_id', $this->input('skill_domain_id'))],
            'skill_domain_id' => ['required', 'uuid', 'exists:skill_domains,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'نام زیرحوزه الزامی است.',
            'name.unique'              => 'این زیرحوزه قبلاً در این حوزه ثبت شده است.',
            'skill_domain_id.required' => 'انتخاب حوزه الزامی است.',
            'skill_domain_id.exists'   => 'حوزه انتخاب‌شده یافت نشد.',
        ];
    }
}
