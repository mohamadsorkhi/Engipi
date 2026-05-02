<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\DeleteProjectAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $projects = $this->getProjects($request);
        $projects->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.projects.components.table_and_pagination', compact('projects'))->render(),
            ]);
        }

        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        $project->load([
            'employer',
            'employerProfile',
            'domains',
            'processes',
            'skills',
            'files',
            'requests.user',
        ]);

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, DeleteProjectAction $action)
    {
        $action->execute($project);

        $projects = $this->getProjects(request());
        $projects->appends(request()->query());
        return response()->json([
            'success' => true,
            'message' => 'پروژه با موفقیت حذف شد.',
            'table' => view('admin.projects.components.table_and_pagination', compact('projects'))->render(),
        ]);
    }

    /**
     * Helper function to get paginated projects.
     */
    private function getProjects(Request $request)
    {
        $query = Project::query()->with(['employer', 'domains']);

        $q = trim((string)$request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('short_id', 'like', "%{$q}%")
                    ->orWhereHas('employer', function ($userQ) use ($q) {
                        $userQ->where('first_name', 'like', "%{$q}%")
                            ->orWhere('last_name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('mobile', 'like', "%{$q}%");
                    });
            });
        }

        $workType = $request->input('work_type');
        if (in_array($workType, ['remote', 'onsite', 'hybrid'], true)) {
            $query->where('work_type', $workType);
        }

        if ($request->boolean('has_domains')) {
            $query->whereHas('domains');
        }

        if ($request->boolean('has_processes')) {
            $query->whereHas('processes');
        }

        return $query->latest()->paginate(20);
    }
}
