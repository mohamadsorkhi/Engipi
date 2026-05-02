<?php

namespace App\Http\Requests\Auth;

use App\Models\UserProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();
        $profile = $this->route('profile');

        if (!$user || !$profile instanceof UserProfile) {
            return false;
        }

        return $profile->user_id === $user->id;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'company_name.string' => 'نام شرکت معتبر نیست.',
            'company_name.max' => 'نام شرکت نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'headline.required' => 'عنوان تخصصی الزامی است.',
            'headline.string' => 'عنوان تخصصی معتبر نیست.',
            'headline.max' => 'عنوان تخصصی نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'bio.string' => 'بیوگرافی معتبر نیست.',
        ];
    }

    public function withValidator($validator)
    {
        $profile = $this->route('profile');

        $validator->sometimes('headline', ['required'], function () use ($profile) {
            return $profile && $profile->type === 'specialist';
        });
    }
}
