<?php

namespace App\Http\Requests\Specialist;

use App\Models\Skill;
use App\Models\SkillSuggestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreSkillSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null
            && ! $this->user()->is_admin
            && $this->user()->profiles()->where('type', 'specialist')->exists();
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->skill_name)) {
            $this->merge(['skill_name' => preg_replace('/\s+/u', ' ', trim($this->skill_name))]);
        }
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['required', 'string', 'max:255'],
            'skill_type' => ['required', Rule::in(SkillSuggestion::types())],
            'subdomain_id' => ['required', 'uuid', 'exists:subdomains,id'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'skill_name.required' => 'نام مهارت پیشنهادی الزامی است.',
            'skill_name.max' => 'نام مهارت پیشنهادی نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'skill_type.required' => 'نوع مهارت مشخص نشده است.',
            'skill_type.in' => 'نوع مهارت معتبر نیست.',
            'subdomain_id.required' => 'انتخاب حوزه مرتبط الزامی است.',
            'subdomain_id.exists' => 'حوزه انتخاب‌شده معتبر نیست.',
            'description.max' => 'توضیح کوتاه نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($validator->errors()->has('skill_name')) {
                return;
            }

            if ($validator->errors()->has('skill_type') || $validator->errors()->has('subdomain_id')) {
                return;
            }

            $normalized = SkillSuggestion::normalizeName((string) $this->skill_name);

            $skillExists = Skill::query()
                ->where('skill_type', $this->skill_type)
                ->where('subdomain_id', $this->subdomain_id)
                ->get(['name'])
                ->contains(
                    fn (Skill $skill): bool => SkillSuggestion::normalizeName($skill->name) === $normalized
                );

            if ($skillExists) {
                $validator->errors()->add('skill_name', 'این مهارت قبلاً در لیست مهارت‌ها وجود دارد.');
                return;
            }

            $pendingExists = SkillSuggestion::query()
                ->where('user_id', $this->user()->id)
                ->where('skill_type', $this->skill_type)
                ->where('subdomain_id', $this->subdomain_id)
                ->where('normalized_name', $normalized)
                ->where('status', SkillSuggestion::STATUS_PENDING)
                ->exists();

            if ($pendingExists) {
                $validator->errors()->add('skill_name', 'شما قبلاً این مهارت را پیشنهاد داده‌اید و در انتظار بررسی است.');
            }
        }];
    }
}
