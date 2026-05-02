<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AddProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'profile_type' => [
                'required',
                'in:employer,specialist',
                Rule::unique('user_profiles', 'type')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            'company_name' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_type.required' => 'انتخاب نوع پروفایل الزامی است.',
            'profile_type.in' => 'نوع پروفایل انتخاب شده معتبر نیست.',
            'profile_type.unique' => 'شما قبلا این نوع پروفایل را ایجاد کرده‌اید.',
            'company_name.string' => 'نام شرکت معتبر نیست.',
            'company_name.max' => 'نام شرکت نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'headline.string' => 'عنوان تخصصی معتبر نیست.',
            'headline.max' => 'عنوان تخصصی نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'bio.string' => 'بیوگرافی معتبر نیست.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes('headline', ['required'], function ($input) {
            return ($input->profile_type ?? null) === 'specialist';
        });
    }
}
