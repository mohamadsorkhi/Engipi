<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Request as ProjectRequest;

class DashboardController extends Controller
{
    /**
     * Display the employer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Get all project IDs owned by the employer
        $projectIds = $user->projects()->pluck('id');

        // 2. Build the base query for requests related to those projects
        $requestsQuery = ProjectRequest::whereIn('project_id', $projectIds);

        $stats = [
            'total_projects' => $projectIds->count(),
            'pending_requests' => (clone $requestsQuery)->where('status', 'pending')->count(),
            'accepted_requests' => (clone $requestsQuery)->where('status', 'accepted')->count(),
        ];

        $recentProjects = $user->projects()->with('skills')->latest()->take(5)->get();

        return view('employer.dashboard', compact('stats', 'recentProjects'));
    }
}
