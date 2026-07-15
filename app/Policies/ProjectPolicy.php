<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    public function viewMatchedProject(User $user, Project $project): Response
    {
        if ($user->is_admin) {
            return Response::allow();
        }

        return $this->isMatchedProject($user, $project)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function requestCollaboration(User $user, Project $project): Response
    {
        return $this->isMatchedProject($user, $project)
            ? Response::allow()
            : Response::deny('This project is not eligible for a collaboration request.');
    }

    private function isMatchedProject(User $user, Project $project): bool
    {
        return Project::query()
            ->forWorkerMatches($user)
            ->whereKey($project->getKey())
            ->exists();
    }
}
