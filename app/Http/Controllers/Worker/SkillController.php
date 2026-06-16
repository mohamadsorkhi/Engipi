<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Display the worker's skills management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $worker = Auth::user();

        // Fetch all available skills for the dropdown
        $allSkills = Skill::orderBy('name')->get();

        // Get IDs of skills the worker currently has
        $workerSkillIds = $worker->skills()->pluck('skills.id')->toArray();

        return view('worker.skills.index', [
            'allSkills' => $allSkills,
            'workerSkillIds' => $workerSkillIds,
        ]);
    }

    /**
     * Sync the worker's skills.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'skills' => 'array',
            'skills.*' => 'exists:skills,id',
        ]);

        $worker = Auth::user();

        // Sync skills: this adds new ones and removes unselected ones
        $worker->skills()->sync($request->input('skills', []));

        return response()->json([
            'status' => 'success',
            'message' => 'مهارت‌ها با موفقیت بروزرسانی شدند.',
        ]);
    }
}
