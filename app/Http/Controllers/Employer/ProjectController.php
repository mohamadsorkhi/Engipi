<?php

namespace App\Http\Controllers\Employer;

use App\Actions\Employer\CreateProjectAction;
use App\Actions\Employer\DeleteProjectAction;
use App\Actions\Employer\UpdateProjectAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\StoreProjectRequest;
use App\Http\Requests\Employer\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SkillDomain;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = Auth::user()->projects()->with(['skills', 'domains'])->latest()->paginate(10);
        return view('user.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $domains = SkillDomain::with('processes.skills')->orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        
        return view('user.projects.create', compact('domains', 'skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request, CreateProjectAction $action)
    {
        $validated = $request->validated();
        $files = $request->file('files', []);

        $project = $action->execute(Auth::user(), $validated, $files);

        return response()->json([
            'status' => 'success',
            'message' => 'پروژه با موفقیت ثبت شد.',
            'redirect' => route('user.projects.index'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Authorize that the current user owns the project
        if (Auth::id() !== $project->employer_id) {
            abort(403);
        }

        $project->load(['skills', 'domains', 'processes', 'files', 'requests.user', 'employerProfile']);
        return view('user.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        // Authorize that the current user owns the project
        if (Auth::id() !== $project->employer_id) {
            abort(403);
        }

        $domains = SkillDomain::with('processes')->orderBy('name')->get();
        $processes = \App\Models\Process::orderBy('name')->get();
        $skills = Skill::orderBy('name')->get();
        $project->load('skills', 'processes', 'domains');
        return view('user.projects.edit', compact('project', 'skills', 'domains', 'processes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $action)
    {
        $action->execute($project, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'پروژه با موفقیت ویرایش شد.',
            'redirect' => route('user.projects.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, DeleteProjectAction $action)
    {
        if (Auth::id() !== $project->employer_id) {
            return response()->json(['message' => 'شما اجازه حذف این پروژه را ندارید.'], 403);
        }

        $action->execute($project);

        return response()->json([
            'status' => 'success',
            'message' => 'پروژه با موفقیت حذف شد.',
            'redirect' => route('user.projects.index'),
        ]);
    }
}
