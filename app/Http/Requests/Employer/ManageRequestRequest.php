<?php

namespace App\Http\Requests\Employer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ManageRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        $collaborationRequest = $this->route('request');
        
        if (!$collaborationRequest || !$collaborationRequest->project) {
            return false;
        }

        return Auth::check() && $collaborationRequest->project->employer_id === Auth::id();
    }

    public function rules(): array
    {
        return [];
    }

    protected function failedAuthorization()
    {
        if (request()->expectsJson()) {
            abort(response()->json(['message' => 'شما اجازه انجام این کار را ندارید.'], 403));
        }
        abort(403, 'شما اجازه انجام این کار را ندارید.');
    }
}
