<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Request as ProjectRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = [
            'total_employers' => UserProfile::where('type', 'employer')->count(),
            'total_workers' => UserProfile::where('type', 'specialist')->count(),
            'total_projects' => Project::count(),
            'total_requests' => ProjectRequest::count(),
        ];

        $recentProjects = Project::with('employer')->latest()->take(10)->get();
        $recentUsers = User::where('is_admin', false)->latest()->take(10)->get();


        return view('admin.dashboard', compact('stats', 'recentProjects', 'recentUsers'));
    }
}
