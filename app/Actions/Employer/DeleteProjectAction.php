<?php

namespace App\Actions\Employer;

use App\Models\Project;
use App\Services\Deletion\DeletionLifecycle;

class DeleteProjectAction
{
    public function __construct(private DeletionLifecycle $deletionLifecycle)
    {
    }

    public function execute(Project $project): bool
    {
        return $this->deletionLifecycle->deleteProject($project);
    }
}
