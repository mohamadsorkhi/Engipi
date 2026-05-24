<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $profile = $user->profiles()->where('type', 'specialist')->first();

        $skills = $user->skills()
            ->withPivot(['level', 'years_of_experience'])
            ->get();

        $selectedDomains = $profile ? $profile->domains : collect();

        return view('user.skills.index', compact('skills', 'selectedDomains', 'profile'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'skills'            => ['required', 'array', 'min:1'],
            'skills.*.skill_id' => ['required', 'uuid', 'exists:skills,id'],
            'skills.*.level'    => ['required', 'string', 'max:50'],
            'skills.*.years'    => ['required', 'integer', 'min:0', 'max:50'],
        ], [
            'skills.required'            => 'حداقل یک مهارت الزامی است.',
            'skills.min'                 => 'حداقل یک مهارت الزامی است.',
            'skills.*.skill_id.required' => 'شناسه مهارت الزامی است.',
            'skills.*.level.required'    => 'سطح مهارت الزامی است.',
            'skills.*.years.required'    => 'سال‌های تجربه الزامی است.',
            'skills.*.years.integer'     => 'سال‌های تجربه باید عدد صحیح باشد.',
        ]);

        $syncData = collect($validated['skills'])
            ->mapWithKeys(fn($s) => [
                $s['skill_id'] => [
                    'level'               => $s['level'],
                    'years_of_experience' => (int) $s['years'],
                ],
            ])
            ->toArray();

        $user->skills()->sync($syncData);

        return response()->json([
            'status'  => 'success',
            'message' => 'مهارت‌ها با موفقیت بروزرسانی شدند.',
        ]);
    }
}
