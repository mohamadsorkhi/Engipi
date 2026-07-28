<?php

namespace App\Contracts;

use App\ValueObjects\ProjectDocumentInspectionResult;
use Illuminate\Http\UploadedFile;

interface ProjectDocumentInspector
{
    public function inspect(UploadedFile $file): ProjectDocumentInspectionResult;
}
