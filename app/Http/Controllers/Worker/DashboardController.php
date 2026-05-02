<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Request as ProjectRequest;

class DashboardController extends Controller
{
    /**
     * Display the worker dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $hasSkills = $user->skills()->exists();

        // Build the base query for matched projects
        $matchedProjectsQuery = Project::forWorkerMatches($user);

        $stats = [
            'matched_projects' => $hasSkills ? (clone $matchedProjectsQuery)->get()->count() : 0,
            'sent_requests' => $user->requests()->count(),
            'accepted_requests' => $user->requests()->where('status', 'accepted')->count(),
        ];

        // Only apply the complex ordering if the user has skills
        if ($hasSkills) {
            $recentMatchedProjects = $matchedProjectsQuery
                ->orderBy('matching_skills_count', 'desc')
                ->orderBy('projects.created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $recentMatchedProjects = collect(); // Return an empty collection
        }

        return view('worker.dashboard', compact('stats', 'recentMatchedProjects'));
    }
}
