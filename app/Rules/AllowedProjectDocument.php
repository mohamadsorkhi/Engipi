<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class AllowedProjectDocument implements ValidationRule
{
    public const ALLOWED_MIME_TYPES = [
        'pdf' => ['application/pdf'],
        'txt' => ['text/plain'],
        'csv' => ['text/csv', 'text/plain', 'application/csv'],
        'doc' => ['application/msword', 'application/x-ole-storage', 'application/cdfv2'],
        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
        'xls' => ['application/vnd.ms-excel', 'application/x-ole-storage', 'application/cdfv2'],
        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip'],
        'zip' => ['application/zip', 'application/x-zip-compressed'],
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            $fail('فایل آپلود شده معتبر نیست.');

            return;
        }

        $extension = strtolower($value->getClientOriginalExtension());
        $mimeType = strtolower((string) $value->getMimeType());
        $allowedMimeTypes = self::ALLOWED_MIME_TYPES[$extension] ?? [];

        if (! in_array($mimeType, $allowedMimeTypes, true)) {
            $fail('نوع فایل آپلود شده مجاز نیست.');
        }
    }
}
