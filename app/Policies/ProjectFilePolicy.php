<?php

namespace App\Policies;

use App\Models\ProjectFile;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class ProjectFilePolicy
{
    public function download(User $user, ProjectFile $projectFile): Response
    {
        $project = $projectFile->project;

        if ($user->is_admin || $project->employer_id === $user->getKey()) {
            return Response::allow();
        }

        return Gate::forUser($user)->allows('viewMatchedProject', $project)
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
