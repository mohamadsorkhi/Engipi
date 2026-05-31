<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class MatchedProjectController extends Controller
{
    /**
     * Display a listing of matched projects.
     */
    public function index()
    {
        $user = Auth::user();

        $projects = Project::forWorkerMatches($user)
            ->orderBy('projects.created_at', 'desc')
            ->paginate(10);

        return view('user.matched-projects.index', compact('projects'));
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $user = Auth::user();
        $project->load(['employer', 'skills', 'domains', 'processes', 'files', 'employerProfile']);
        
        // Check if user already sent a request
        $sentRequest = $project->requests()->where('user_id', $user->id)->first();
        
        // Increment view count
        $project->increment('view_count');
        
        return view('user.matched-projects.show', compact('project', 'sentRequest'));
    }
}
