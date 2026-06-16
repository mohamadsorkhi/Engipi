<?php

namespace App\Http\Requests\Specialist;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreCollaborationRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'uuid', 'exists:projects,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'شناسه پروژه الزامی است.',
            'project_id.uuid' => 'شناسه پروژه معتبر نیست.',
            'project_id.exists' => 'پروژه مورد نظر یافت نشد.',
            'message.string' => 'پیام باید متن باشد.',
            'message.max' => 'پیام نباید بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ];
    }
}
