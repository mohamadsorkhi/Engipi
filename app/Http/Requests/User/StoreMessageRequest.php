<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'uuid', 'exists:users,id'],
            'body' => ['required', 'string', 'min:1', 'max:5000'],
            'project_id' => ['nullable', 'uuid', 'exists:projects,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.required' => 'گیرنده الزامی است.',
            'receiver_id.exists' => 'کاربر گیرنده یافت نشد.',
            'body.required' => 'متن پیام الزامی است.',
            'body.max' => 'پیام نمی‌تواند بیش از ۵۰۰۰ کاراکتر باشد.',
        ];
    }
}
