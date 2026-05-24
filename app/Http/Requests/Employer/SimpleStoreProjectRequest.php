<?php

namespace App\Http\Requests\Employer;

use App\Models\Skill;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SimpleStoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['work_type' => 'remote']);
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:191'],
            'description'   => ['required', 'string', 'min:20'],
            'work_type'     => ['required', Rule::in(['remote', 'onsite', 'hybrid'])],
            'domains'       => ['required', 'array', 'size:1'],
            'domains.*'     => ['required', 'uuid', 'exists:skill_domains,id'],
            'skills'        => ['nullable', 'array'],
            'skills.*'      => ['uuid', 'exists:skills,id'],
            'deadline_date' => ['nullable', 'date', 'after:today'],
            'budget_min'    => ['nullable', 'numeric', 'min:0'],
            'budget_max'    => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'عنوان پروژه الزامی است.',
            'title.max'            => 'عنوان پروژه نباید بیشتر از ۱۹۱ کاراکتر باشد.',
            'description.required' => 'توضیحات پروژه الزامی است.',
            'description.min'      => 'توضیحات باید حداقل ۲۰ کاراکتر باشد.',
            'domains.required'     => 'انتخاب حوزه تخصصی الزامی است.',
            'domains.size'         => 'باید دقیقاً یک حوزه تخصصی انتخاب کنید.',
            'domains.*.exists'     => 'حوزه انتخاب شده معتبر نیست.',
            'skills.*.exists'      => 'مهارت انتخاب شده معتبر نیست.',
            'deadline_date.date'   => 'تاریخ مهلت معتبر نیست.',
            'deadline_date.after'  => 'تاریخ مهلت باید بعد از امروز باشد.',
            'budget_min.numeric'   => 'حداقل بودجه باید عدد باشد.',
            'budget_min.min'       => 'حداقل بودجه نمی‌تواند منفی باشد.',
            'budget_max.numeric'   => 'حداکثر بودجه باید عدد باشد.',
            'budget_max.min'       => 'حداکثر بودجه نمی‌تواند منفی باشد.',
            'budget_max.gte'       => 'حداکثر بودجه باید بزرگتر یا مساوی حداقل بودجه باشد.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $domainId = $this->input('domains.0');
            $skillIds = array_filter((array) $this->input('skills', []));

            if ($domainId && !empty($skillIds)) {
                $validCount = Skill::whereIn('id', $skillIds)
                    ->whereHas('subdomain', fn ($q) => $q->where('skill_domain_id', $domainId))
                    ->count();

                if ($validCount !== count($skillIds)) {
                    $validator->errors()->add('skills', 'مهارت‌های انتخاب شده باید متعلق به حوزه انتخابی باشند.');
                }
            }
        });
    }
}
