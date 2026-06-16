<?php

namespace App\Actions\Employer;

use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProjectAction
{
    public function execute(User $user, array $data, array $files = []): Project
    {
        return DB::transaction(function () use ($user, $data, $files) {
            $profile = $user->profiles()->where('type', 'employer')->first();

            $project = Project::create([
                'employer_id' => $user->id,
                'employer_profile_id' => $profile?->id,
                'short_id' => $this->generateShortId(),
                'title' => $data['title'],
                'description' => $data['description'],
                'work_type' => $data['work_type'],
                'duration_days' => $data['duration_days'] ?? null,
                'deadline_date' => $data['deadline_date'] ?? null,
                'budget_min' => $data['budget_min'] ?? null,
                'budget_max' => $data['budget_max'] ?? null,
                'view_count' => 0,
            ]);

            // Attach domains if provided
            if (!empty($data['domains'])) {
                $project->domains()->attach($data['domains']);
            }

            // Attach processes with levels if provided
            if (!empty($data['processes'])) {
                // Group levels by process ID
                $processesData = [];
                foreach ($data['processes'] as $process) {
                    $processId = $process['id'];
                    if (!isset($processesData[$processId])) {
                        $processesData[$processId] = [];
                    }
                    $processesData[$processId][] = $process['level'];
                }
                
                // Attach with JSON array of levels
                $attachData = [];
                foreach ($processesData as $processId => $levels) {
                    $attachData[$processId] = ['desired_levels' => json_encode(array_unique($levels))];
                }
                $project->processes()->attach($attachData);
            }

            // Attach skills if provided
            if (!empty($data['skills'])) {
                $project->skills()->attach($data['skills']);
            }

            // Handle file uploads
            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeProjectFile($project, $file);
                }
            }

            return $project;
        });
    }

    protected function generateShortId(): string
    {
        do {
            $shortId = strtoupper(Str::random(8));
        } while (Project::where('short_id', $shortId)->exists());

        return $shortId;
    }

    protected function storeProjectFile(Project $project, UploadedFile $file): ProjectFile
    {
        $path = $file->store('project-files/' . $project->id, 'public');

        return ProjectFile::create([
            'project_id' => $project->id,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);
    }
}
