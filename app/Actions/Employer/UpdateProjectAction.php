<?php

namespace App\Actions\Employer;

use App\Models\Project;
use Illuminate\Support\Facades\DB;

class UpdateProjectAction
{
    public function execute(Project $project, array $data): Project
    {
        return DB::transaction(function () use ($project, $data) {
            $project->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'work_type' => $data['work_type'],
                'duration_days' => $data['duration_days'] ?? null,
                'deadline_date' => $data['deadline_date'] ?? null,
                'budget_min' => $data['budget_min'] ?? null,
                'budget_max' => $data['budget_max'] ?? null,
            ]);

            // Sync domains if provided
            if (isset($data['domains'])) {
                $project->domains()->sync($data['domains']);
            }

            // Sync processes if provided
            if (isset($data['processes'])) {
                $processesData = [];
                foreach ($data['processes'] as $process) {
                    $processId = $process['id'];
                    if (!isset($processesData[$processId])) {
                        $processesData[$processId] = [];
                    }
                    $processesData[$processId][] = $process['level'];
                }

                $syncData = [];
                foreach ($processesData as $processId => $levels) {
                    $syncData[$processId] = [
                        'desired_levels' => json_encode(array_values(array_unique($levels))),
                    ];
                }

                $project->processes()->sync($syncData);
            }

            // Sync skills if provided
            if (isset($data['skills'])) {
                $project->skills()->sync($data['skills']);
            }

            return $project->fresh();
        });
    }
}
