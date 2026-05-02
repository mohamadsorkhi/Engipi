<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => ['required', 'string'],
            'work_type' => ['required', Rule::in(['remote', 'onsite', 'hybrid'])],
            'domains' => ['required', 'array', 'min:1', 'max:3'],
            'domains.*' => ['required', 'uuid', 'exists:skill_domains,id'],
            'processes' => ['required', 'array', 'min:1'],
            'processes.*.id' => ['required', 'uuid', 'exists:processes,id'],
            'processes.*.level' => ['required', Rule::in(['practical', 'proficient', 'advanced'])],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['uuid', 'exists:skills,id'],
            'duration_days' => ['nullable', 'integer', 'min:1'],
            'deadline_date' => ['nullable', 'date', 'after:today'],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:10240'], // 10MB max per file
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان پروژه الزامی است.',
            'title.string' => 'عنوان پروژه باید متن باشد.',
            'title.max' => 'عنوان پروژه نباید بیشتر از ۱۹۱ کاراکتر باشد.',
            'description.required' => 'توضیحات پروژه الزامی است.',
            'description.string' => 'توضیحات پروژه باید متن باشد.',
            'work_type.required' => 'نوع همکاری الزامی است.',
            'work_type.in' => 'نوع همکاری انتخاب شده معتبر نیست.',
            'domains.required' => 'انتخاب حداقل یک حوزه الزامی است.',
            'domains.array' => 'فرمت حوزه‌ها صحیح نیست.',
            'domains.min' => 'حداقل یک حوزه باید انتخاب شود.',
            'domains.max' => 'حداکثر سه حوزه می‌توانید انتخاب کنید.',
            'domains.*.required' => 'شناسه حوزه الزامی است.',
            'domains.*.uuid' => 'شناسه حوزه معتبر نیست.',
            'domains.*.exists' => 'حوزه انتخاب شده یافت نشد.',
            'processes.required' => 'انتخاب حداقل یک پردازش الزامی است.',
            'processes.array' => 'فرمت پردازش‌ها صحیح نیست.',
            'processes.min' => 'حداقل یک پردازش باید انتخاب شود.',
            'processes.*.id.required' => 'شناسه پردازش الزامی است.',
            'processes.*.id.uuid' => 'شناسه پردازش معتبر نیست.',
            'processes.*.id.exists' => 'پردازش انتخاب شده یافت نشد.',
            'processes.*.level.required' => 'سطح مهارت برای پردازش الزامی است.',
            'processes.*.level.in' => 'سطح مهارت انتخاب شده معتبر نیست.',
            'skills.array' => 'فرمت مهارت‌ها صحیح نیست.',
            'skills.*.uuid' => 'شناسه مهارت معتبر نیست.',
            'skills.*.exists' => 'مهارت انتخاب شده یافت نشد.',
            'duration_days.integer' => 'مدت زمان باید عدد صحیح باشد.',
            'duration_days.min' => 'مدت زمان باید حداقل ۱ روز باشد.',
            'deadline_date.date' => 'تاریخ مهلت باید یک تاریخ معتبر باشد.',
            'deadline_date.after' => 'تاریخ مهلت باید بعد از امروز باشد.',
            'budget_min.numeric' => 'حداقل بودجه باید عدد باشد.',
            'budget_min.min' => 'حداقل بودجه نمی‌تواند منفی باشد.',
            'budget_max.numeric' => 'حداکثر بودجه باید عدد باشد.',
            'budget_max.min' => 'حداکثر بودجه نمی‌تواند منفی باشد.',
            'budget_max.gte' => 'حداکثر بودجه باید بزرگتر یا مساوی حداقل بودجه باشد.',
            'files.array' => 'فرمت فایل‌ها صحیح نیست.',
            'files.*.file' => 'فایل آپلود شده معتبر نیست.',
            'files.*.max' => 'حجم هر فایل نباید بیشتر از ۱۰ مگابایت باشد.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $domainIds = $this->input('domains', []);
            $processes = $this->input('processes', []);

            if (!empty($domainIds) && !empty($processes)) {
                // Verify all processes belong to one of the selected domains
                $processIds = collect($processes)->pluck('id')->filter()->unique();
                
                $validCount = \App\Models\Process::whereIn('skill_domain_id', $domainIds)
                    ->whereIn('id', $processIds)
                    ->count();

                if ($validCount !== $processIds->count()) {
                    $validator->errors()->add('processes', 'تمام پردازش‌های انتخاب شده باید متعلق به حوزه‌های انتخابی باشند.');
                }
            }
        });
    }
}
