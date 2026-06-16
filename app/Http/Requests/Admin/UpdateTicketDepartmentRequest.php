<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTicketDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'نام دپارتمان الزامی است.',
            'name.min' => 'نام دپارتمان باید حداقل ۲ کاراکتر باشد.',
            'name.max' => 'نام دپارتمان نباید بیشتر از ۲۵۵ کاراکتر باشد.',
        ];
    }
}
