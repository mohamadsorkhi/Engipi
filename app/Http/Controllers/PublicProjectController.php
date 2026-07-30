<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PublicProjectController extends Controller
{
    public function show(string $project)
    {
        $project = Project::query()
            ->whereKey($project)
            ->orWhere('short_id', $project)
            ->firstOrFail();

        $project->load(['skills', 'files']);

        $rawDescription = $project->seo_description ?: $project->description;
        $description = filled($rawDescription)
            ? Str::limit(Str::squish(strip_tags($rawDescription)), 200)
            : null;

        $image = 'https://www.engipi.com/images/engipi-og.jpg';
        $imageType = 'image/jpeg';
        $imageFile = $project->files->first(
            fn ($file) => Str::startsWith((string) $file->mime_type, 'image/')
        );

        if ($imageFile) {
            try {
                $image = Storage::disk($imageFile->storageDisk())->url($imageFile->path);
                $image = Str::startsWith($image, 'https://')
                    ? $image
                    : (Str::startsWith($image, 'http://')
                        ? 'https://'.Str::after($image, 'http://')
                        : 'https://www.engipi.com/'.ltrim($image, '/'));
                $imageType = $imageFile->mime_type;
            } catch (Throwable) {
                // Keep the production fallback when a disk cannot expose public URLs.
            }
        }

        return view('projects.public-show', compact('project', 'description', 'image', 'imageType'));
    }
}
