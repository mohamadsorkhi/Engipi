<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Session;

class CaptchaValidationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function passes($attribute, $value)
    {
        // Check if the entered value matches the stored captcha text in the session
        return strtoupper($value) === strtoupper(Session::get('captcha_text'));
    }

    public function message()
    {
        return 'کد وارد شده اشتباه است.';
    }
}
