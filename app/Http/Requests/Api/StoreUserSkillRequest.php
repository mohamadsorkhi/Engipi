<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->profiles()
            ->where('type', 'specialist')
            ->exists() ?? false;
    }

    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'uuid', 'exists:skills,id'],
        ];
    }
}
