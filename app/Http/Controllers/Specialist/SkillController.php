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
     * Shows domain selection, subdomains, process selection and skill levels.
     */
    public function index()
    {
        $user = Auth::user();

        $profile = $user->profiles()
            ->where('type', 'specialist')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Load domains with:
        | - subdomains
        | - skills of each subdomain
        | - processes
        |--------------------------------------------------------------------------
        */

        $domains = SkillDomain::with([
            'subdomains.skills',
            'processes'
        ])
        ->orderBy('name')
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Current selected processes with levels
        |--------------------------------------------------------------------------
        */

        $selectedProcesses = $profile
            ? $profile->processes()->withPivot('level')->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | Current selected domains
        |--------------------------------------------------------------------------
        */

        $selectedDomains = $profile
            ? $profile->domains
            : collect();

        return view('user.skills.index', [
            'domains' => $domains,
            'selectedProcesses' => $selectedProcesses,
            'selectedDomains' => $selectedDomains,
            'profile' => $profile,
        ]);
    }

    /**
     * Store/update specialist skills.
     */
    public function store(StoreSkillsRequest $request)
    {
        $user = Auth::user();

        $profile = $user->profiles()
            ->where('type', 'specialist')
            ->first();

        if (!$profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'پروفایل متخصص یافت نشد.',
            ], 403);
        }

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Sync selected domains
        |--------------------------------------------------------------------------
        */

        $profile->domains()->sync($validated['domains']);

        /*
        |--------------------------------------------------------------------------
        | Sync selected processes with levels
        |--------------------------------------------------------------------------
        */

        $syncData = [];

        foreach ($validated['processes'] as $processData) {

            $syncData[$processData['id']] = [
                'level' => $processData['level'],
            ];

        }

        $profile->processes()->sync($syncData);

        return response()->json([
            'status' => 'success',
            'message' => 'مهارت‌ها با موفقیت بروزرسانی شدند.',
        ]);
    }
}