<?php

namespace App\Services\Deletion;

use App\Models\PendingFileDeletion;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeletionLifecycle
{
    public function deleteProject(Project $project): bool
    {
        return DB::transaction(fn () => $this->deleteProjectDatabaseState($project));
    }

    public function deleteUser(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            $user->skills()->detach();
            $user->requests()->delete();

            // Projects must retain their profile ownership until their complete
            // database lifecycle has been removed.
            foreach ($user->projects()->get() as $project) {
                $this->deleteProjectDatabaseState($project);
            }

            foreach ($user->profiles()->get() as $profile) {
                $profile->processes()->detach();
                $profile->domains()->detach();
                $profile->delete();
            }

            return $user->delete();
        });
    }

    private function deleteProjectDatabaseState(Project $project): bool
    {
        $cleanupIds = $project->files()
            ->get(['path', 'storage_disk'])
            ->map(fn ($file) => PendingFileDeletion::query()->create([
                'disk' => $file->storageDisk(),
                'path' => $file->path,
            ])->id)
            ->all();

        $project->domains()->detach();
        $project->skills()->detach();
        $project->processes()->detach();
        $project->requests()->delete();
        $deleted = $project->delete();

        if ($deleted && $cleanupIds !== []) {
            // This intentionally does not dispatch to the queue: the current
            // runtime uses the sync driver. Failures remain durable for the
            // project-files:cleanup command and never escape after commit.
            DB::afterCommit(fn () => app(PendingFileCleanup::class)->processIds($cleanupIds));
        }

        return $deleted;
    }
}
