<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SkillDomain;

class SkillSelectController extends Controller
{
    public function index()
    {
        $domains = SkillDomain::all();

        return view('test', compact('domains'));
    }

    public function saveSkills(Request $request)
    {
        $validated = $request->validate([
            'skills'            => ['required', 'array', 'min:1', 'max:5'],
            'skills.*.skill_id' => ['required', 'uuid', 'exists:skills,id', 'distinct'],
            'skills.*.level'    => ['required', 'string', 'max:50'],
            'skills.*.years'    => ['required', 'integer', 'min:0', 'max:50'],
            'domains'           => ['nullable', 'array', 'max:2'],
            'domains.*'         => ['uuid', 'exists:skill_domains,id'],
        ], [
            'skills.max'               => 'حداکثر ۵ مهارت مجاز است.',
            'skills.*.skill_id.distinct'=> 'مهارت تکراری در لیست وجود دارد.',
        ]);

        // Verify every submitted skill belongs to a subdomain of a submitted domain
        $domainIds = $validated['domains'] ?? [];
        if (!empty($domainIds)) {
            $skillIds      = collect($validated['skills'])->pluck('skill_id')->toArray();
            $validCount    = DB::table('skills')
                ->join('subdomains', 'skills.subdomain_id', '=', 'subdomains.id')
                ->whereIn('skills.id', $skillIds)
                ->whereIn('subdomains.skill_domain_id', $domainIds)
                ->count();

            if ($validCount !== count($skillIds)) {
                return response()->json([
                    'errors' => ['skills' => ['یک یا چند مهارت با حوزه‌های انتخابی تطابق ندارند.']],
                ], 422);
            }
        }

        $user = auth()->user();

        $syncData = collect($validated['skills'])
            ->mapWithKeys(fn($item) => [
                $item['skill_id'] => [
                    'level'               => $item['level'],
                    'years_of_experience' => $item['years'],
                ],
            ])
            ->toArray();

        $user->skills()->sync($syncData);

        if (!empty($validated['domains'])) {
            $profile = $user->profiles()->where('type', 'specialist')->first();
            if ($profile) {
                $profile->domains()->sync($validated['domains']);
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => 'مهارت‌ها با موفقیت ذخیره شدند',
            'redirect' => route('root'),
        ]);
    }
}
