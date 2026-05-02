<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specialist\StoreSkillsRequest;
use App\Models\Process;
use App\Models\SkillDomain;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    /**
     * Display the specialist's skills management page.
     * Shows domain selection, process selection (1-3), and skill levels.
     */
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profiles()->where('type', 'specialist')->first();

        // Get all domains for selection
        $domains = SkillDomain::with('processes')->orderBy('name')->get();

        // Get current profile's selected processes with levels
        $selectedProcesses = $profile ? $profile->processes()->withPivot('level')->get() : collect();

        // Get selected domains for the profile
        $selectedDomains = $profile ? $profile->domains : collect();

        return view('user.skills.index', [
            'domains' => $domains,
            'selectedProcesses' => $selectedProcesses,
            'selectedDomains' => $selectedDomains,
            'profile' => $profile,
        ]);
    }

    /**
     * Store/update the specialist's skill selections.
     */
    public function store(StoreSkillsRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profiles()->where('type', 'specialist')->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'پروفایل متخصص یافت نشد.',
            ], 403);
        }

        $validated = $request->validated();

        // Sync domains
        $profile->domains()->sync($validated['domains']);

        // Build the sync array with levels
        $syncData = [];
        foreach ($validated['processes'] as $processData) {
            $syncData[$processData['id']] = [
                'level' => $processData['level'],
            ];
        }

        // Sync processes with levels
        $profile->processes()->sync($syncData);

        return response()->json([
            'status' => 'success',
            'message' => 'مهارت‌ها با موفقیت بروزرسانی شدند.',
        ]);
    }
}
