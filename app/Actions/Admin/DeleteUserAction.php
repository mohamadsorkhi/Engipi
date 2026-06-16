<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DeleteUserAction
{
    public function execute(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            // Detach skills
            $user->skills()->detach();

            // Delete requests
            $user->requests()->delete();

            // Delete user profiles
            foreach ($user->profiles as $profile) {
                $profile->processes()->detach();
                $profile->delete();
            }

            // Delete projects (if the user is an employer)
            foreach ($user->projects as $project) {
                // Delete project files from storage
                foreach ($project->files as $file) {
                    Storage::disk('public')->delete($file->path);
                    $file->delete();
                }
                $project->skills()->detach();
                $project->processes()->detach();
                $project->requests()->delete();
                $project->delete();
            }

            // Finally, delete the user
            return $user->delete();
        });
    }
}
