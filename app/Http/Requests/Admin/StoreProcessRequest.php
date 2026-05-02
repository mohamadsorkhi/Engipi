<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'skill_domain_id' => ['required', 'uuid', 'exists:skill_domains,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام پردازش الزامی است.',
            'skill_domain_id.required' => 'انتخاب حوزه الزامی است.',
            'skill_domain_id.exists' => 'حوزه انتخاب شده یافت نشد.',
        ];
    }
}
