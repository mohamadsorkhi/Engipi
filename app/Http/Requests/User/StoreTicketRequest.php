<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'department_id' => ['nullable', 'uuid', 'exists:ticket_departments,id'],
            'subject' => ['required', 'string', 'min:5', 'max:255'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'department_id.uuid' => 'دپارتمان انتخاب شده معتبر نیست.',
            'department_id.exists' => 'دپارتمان انتخاب شده یافت نشد.',
            'subject.required' => 'عنوان تیکت الزامی است.',
            'subject.min' => 'عنوان تیکت باید حداقل ۵ کاراکتر باشد.',
            'subject.max' => 'عنوان تیکت نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            'message.required' => 'متن پیام الزامی است.',
            'message.min' => 'متن پیام باید حداقل ۱۰ کاراکتر باشد.',
        ];
    }
}
