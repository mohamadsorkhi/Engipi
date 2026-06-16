<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreSkillDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('skill_domains', 'name')],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام حوزه الزامی است.',
            'name.unique' => 'این حوزه قبلا ثبت شده است.',
        ];
    }
}
