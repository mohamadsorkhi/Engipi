<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the specialist dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profiles()->where('type', 'specialist')->first();
        
        $hasProcesses = $profile ? $profile->processes()->exists() : false;

        // Build the base query for matched projects
        $matchedProjectsQuery = Project::forWorkerMatches($user);

        $stats = [
            'matched_projects' => $hasProcesses ? (clone $matchedProjectsQuery)->get()->count() : 0,
            'sent_requests' => $user->requests()->count(),
            'accepted_requests' => $user->requests()->where('status', 'accepted')->count(),
        ];

        // Only apply the complex ordering if the user has skills/processes
        if ($hasProcesses) {
            $recentMatchedProjects = $matchedProjectsQuery
                ->orderBy('matching_skills_count', 'desc')
                ->orderBy('projects.created_at', 'desc')
                ->take(5)
                ->get();
        } else {
            $recentMatchedProjects = collect();
        }

        return view('specialist.dashboard', compact('stats', 'recentMatchedProjects', 'profile'));
    }
}
