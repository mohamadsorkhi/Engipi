<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PublicProjectController extends Controller
{
    public function show(Project $project)
    {
        $project->load(['skills', 'files']);

        $description = Str::limit(
            Str::squish(strip_tags($project->seo_description ?: $project->description ?: 'پروژه مهندسی در EngiPi')),
            200
        );

        $image = 'https://www.engipi.com/images/engipi-og.jpg';
        $imageType = 'image/jpeg';
        $imageFile = $project->files->first(
            fn ($file) => Str::startsWith((string) $file->mime_type, 'image/')
        );

        if ($imageFile) {
            try {
                $image = Storage::disk($imageFile->storageDisk())->url($imageFile->path);
                $image = Str::startsWith($image, ['http://', 'https://'])
                    ? $image
                    : url($image);
                $imageType = $imageFile->mime_type;
            } catch (Throwable) {
                // Keep the production fallback when a disk cannot expose public URLs.
            }
        }

        return view('projects.public-show', compact('project', 'description', 'image', 'imageType'));
    }
}
