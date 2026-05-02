<?php

namespace App\Http\Requests\Specialist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreSkillsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'domains' => ['required', 'array', 'min:1'],
            'domains.*' => ['required', 'uuid', 'exists:skill_domains,id'],
            'processes' => ['required', 'array', 'min:1'],
            'processes.*.id' => [
                'required',
                'uuid',
                'exists:processes,id',
            ],
            'processes.*.level' => [
                'required',
                Rule::in(['practical', 'proficient', 'advanced']),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'domains.required' => 'انتخاب حداقل یک حوزه الزامی است.',
            'domains.array' => 'فرمت حوزه‌ها صحیح نیست.',
            'domains.min' => 'حداقل یک حوزه باید انتخاب شود.',
            'domains.*.required' => 'شناسه حوزه الزامی است.',
            'domains.*.uuid' => 'شناسه حوزه معتبر نیست.',
            'domains.*.exists' => 'حوزه انتخاب شده یافت نشد.',
            'processes.required' => 'انتخاب حداقل یک پردازش الزامی است.',
            'processes.array' => 'فرمت پردازش‌ها صحیح نیست.',
            'processes.min' => 'حداقل یک پردازش باید انتخاب شود.',
            'processes.*.id.required' => 'شناسه پردازش الزامی است.',
            'processes.*.id.uuid' => 'شناسه پردازش معتبر نیست.',
            'processes.*.id.exists' => 'پردازش انتخاب شده یافت نشد.',
            'processes.*.level.required' => 'سطح مهارت برای هر پردازش الزامی است.',
            'processes.*.level.in' => 'سطح مهارت انتخاب شده معتبر نیست. (عملی، مسلط، پیشرفته)',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $domainIds = $this->input('domains', []);
            $processes = $this->input('processes', []);

            if (!empty($domainIds) && !empty($processes)) {
                // Verify all processes belong to one of the selected domains
                $processIds = collect($processes)->pluck('id')->filter();
                
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
