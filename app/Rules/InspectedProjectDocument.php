<?php

namespace App\Rules;

use App\Contracts\ProjectDocumentInspector;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Throwable;

final class InspectedProjectDocument implements ValidationRule
{
    public function __construct(private readonly ProjectDocumentInspector $inspector) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('فایل آپلود شده قابل بررسی نیست.');

            return;
        }

        try {
            $result = $this->inspector->inspect($value);
        } catch (Throwable) {
            $fail('فایل آپلود شده قابل بررسی نیست.');

            return;
        }

        if (! $result->isClean()) {
            $fail('فایل آپلود شده قابل بررسی نیست.');
        }
    }
}
