<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'min:1', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'متن پیام الزامی است.',
            'message.max' => 'متن پیام نباید بیشتر از ۵۰۰۰ کاراکتر باشد.',
        ];
    }
}
