<?php

namespace App\Actions\Admin;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteProjectAction
{
    public function execute(Project $project): bool
    {
        return DB::transaction(function () use ($project) {
            // Delete project files from storage
            foreach ($project->files as $file) {
                Storage::disk('public')->delete($file->path);
                $file->delete();
            }

            $project->skills()->detach();
            $project->processes()->detach();
            $project->requests()->delete();
            
            return $project->delete();
        });
    }
}
