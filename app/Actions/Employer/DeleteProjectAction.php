<?php

namespace App\Actions\Employer;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteProjectAction
{
    public function execute(Project $project): bool
    {
        return DB::transaction(function () use ($project) {
            // Delete associated files from storage
            foreach ($project->files as $file) {
                Storage::disk('public')->delete($file->path);
            }

            // Delete the project (cascades to related records via foreign keys)
            return $project->delete();
        });
    }
}
