<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Persian implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^[\x{0600}-\x{06FF}\s\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]+$/u', $value)) {
            $fail('برای فیلد :attribute فقط استفاده از حروف فارسی مجاز میباشد.');
        }
    }
}
