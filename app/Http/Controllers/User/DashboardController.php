<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Request as CollaborationRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show unified user dashboard or redirect admin to admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Admin users go to admin dashboard
        if ($user->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        $profiles = $user->profiles;
        $employerProfile = $profiles->firstWhere('type', 'employer');
        $specialistProfile = $profiles->firstWhere('type', 'specialist');

        // Employer stats
        $myProjects = collect();
        $myProjectsCount = 0;
        $receivedRequestsCount = 0;
        $pendingRequestsCount = 0;

        if ($employerProfile) {
            $myProjects = $user->projects()->latest()->take(5)->get();
            $myProjectsCount = $user->projects()->count();

            $receivedRequestsCount = CollaborationRequest::whereHas('project', function ($q) use ($user) {
                $q->where('employer_id', $user->id);
            })->count();

            $pendingRequestsCount = CollaborationRequest::whereHas('project', function ($q) use ($user) {
                $q->where('employer_id', $user->id);
            })->where('status', 'pending')->count();
        }

        // Specialist stats
        $sentRequestsCount = $specialistProfile ? $user->requests()->count() : 0;
        
        // Get matched projects
        $matchedProjectsCount = 0;
        $recentMatchedProjects = collect();
        
        if ($specialistProfile) {
            $userProcessIds = $specialistProfile->processes()->pluck('processes.id')->toArray();
            
            if (!empty($userProcessIds)) {
                $matchedProjectsCount = Project::whereHas('processes', function ($query) use ($userProcessIds) {
                    $query->whereIn('processes.id', $userProcessIds);
                })->where('employer_id', '!=', $user->id)->count();
                
                $recentMatchedProjects = Project::whereHas('processes', function ($query) use ($userProcessIds) {
                    $query->whereIn('processes.id', $userProcessIds);
                })
                ->where('employer_id', '!=', $user->id)
                ->with(['employer', 'domains'])
                ->latest()
                ->take(5)
                ->get();
            }
        }

        return view('user.dashboard', compact(
            'profiles',
            'employerProfile',
            'myProjects',
            'myProjectsCount',
            'receivedRequestsCount',
            'pendingRequestsCount',
            'sentRequestsCount',
            'matchedProjectsCount',
            'recentMatchedProjects',
            'specialistProfile'
        ));
    }
}
