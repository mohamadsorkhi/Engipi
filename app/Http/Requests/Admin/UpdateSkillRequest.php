<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->is_admin;
    }

    public function rules(): array
    {
        $skillId = $this->route('skill')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('skills')->ignore($skillId),
            ],
            'process_id' => ['nullable', 'uuid', 'exists:processes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام مهارت الزامی است.',
            'name.string' => 'نام مهارت باید متن باشد.',
            'name.max' => 'نام مهارت نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'name.unique' => 'این مهارت قبلا ثبت شده است.',
            'process_id.uuid' => 'شناسه پردازش معتبر نیست.',
            'process_id.exists' => 'پردازش انتخاب شده یافت نشد.',
        ];
    }
}
