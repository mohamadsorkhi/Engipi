<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Request as ProjectRequest;
use Illuminate\Support\Facades\Auth;

class MatchedProjectController extends Controller
{
    /**
     * Display a listing of projects that match the worker's skills.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $worker = Auth::user();
        $profile = $worker->profiles()->where('type', 'specialist')->first();
        $hasProcesses = $profile && $profile->processes()->exists();

        if ($hasProcesses) {
            $projects = Project::forWorkerMatches($worker)
                ->orderBy('matching_skills_count', 'desc')
                ->orderBy('projects.created_at', 'desc')
                ->get();
        } else {
            $projects = collect(); // Return an empty collection
        }

        return view('worker.matched-projects.index', compact('projects'));
    }

    /**
     * Display the specified matched project.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        $worker = Auth::user();

        // Ensure the worker should see this project
        $isMatched = Project::forWorkerMatches($worker)->where('projects.id', $project->id)->exists();

        if (!$isMatched) {
            abort(403, 'شما اجازه دسترسی به این پروژه را ندارید.');
        }

        // Find the request the worker has sent for this project
        $sentRequest = ProjectRequest::where('user_id', $worker->id)
                                     ->where('project_id', $project->id)
                                     ->first();

        $project->load(['skills', 'domains', 'processes', 'employer', 'employerProfile']);
        return view('projects.show', compact('project', 'sentRequest'));
    }
}
