<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectListController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = Project::forWorkerMatches($user)
            ->orderBy('matching_skills_count', 'desc')
            ->orderBy('projects.created_at', 'desc')
            ->paginate(12);

        $requestedProjects = $user->requests()
            ->whereIn('project_id', $projects->pluck('id'))
            ->pluck('status', 'project_id');

        return view('specialist.projects.index', compact('projects', 'requestedProjects'));
    }
}
