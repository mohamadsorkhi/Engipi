<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProjectFileController extends Controller
{
    public function download(ProjectFile $projectFile): StreamedResponse
    {
        $this->authorize('download', $projectFile);

        $disk = Storage::disk($projectFile->storageDisk());

        abort_unless($disk->exists($projectFile->path), 404);

        return $disk->download(
            $projectFile->path,
            $this->safeDownloadName($projectFile),
            [
                'Content-Type' => $this->safeContentType($projectFile),
                'X-Content-Type-Options' => 'nosniff',
                'Cache-Control' => 'private, no-store',
                'Pragma' => 'no-cache',
            ]
        );
    }

    private function safeDownloadName(ProjectFile $projectFile): string
    {
        $name = basename(str_replace('\\', '/', (string) $projectFile->original_name));
        $name = str_replace(["\r", "\n"], '', $name);

        return $name !== '' ? $name : 'attachment';
    }

    private function safeContentType(ProjectFile $projectFile): string
    {
        $mimeType = (string) $projectFile->mime_type;

        return preg_match('/\A[a-z0-9][a-z0-9!#$&^_.+-]*\/[a-z0-9][a-z0-9!#$&^_.+-]*\z/i', $mimeType)
            ? $mimeType
            : 'application/octet-stream';
    }
}
