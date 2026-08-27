<?php

namespace App\Http\Controllers;

use App\Models\SkillDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkillSelectController extends Controller
{
    public function index()
    {
        $domains = SkillDomain::with('subdomains')
            ->orderBy('name')
            ->get();

        return view('test', compact('domains'));
    }

    public function saveSkills(Request $request)
    {
        $validated = $request->validate([
            'skills' => ['required', 'array', 'min:1', 'max:5'],
            'skills.*.skill_id' => [
                'required',
                'uuid',
                'exists:skills,id',
                'distinct',
            ],
            'skills.*.level' => [
                'required',
                'string',
                'max:50',
            ],
            'skills.*.years' => [
                'required',
                'integer',
                'min:0',
                'max:50',
            ],
            'domains' => [
                'nullable',
                'array',
                'max:2',
            ],
            'domains.*' => [
                'uuid',
                'exists:skill_domains,id',
            ],
        ], [
            'skills.max' => 'حداکثر ۵ مهارت مجاز است.',
            'skills.*.skill_id.distinct' => 'مهارت تکراری در لیست وجود دارد.',
        ]);

        /*
         * Verify every submitted skill belongs to at least one canonical
         * subdomain within the submitted domains.
         */
        $domainIds = $validated['domains'] ?? [];

        if (! empty($domainIds)) {
            $skillIds = collect($validated['skills'])
                ->pluck('skill_id')
                ->all();

            $validCount = DB::table('skills as skills')
                ->join(
                    'skill_subdomain as relation',
                    'relation.skill_id',
                    '=',
                    'skills.id',
                )
                ->join(
                    'subdomains as subdomains',
                    'subdomains.id',
                    '=',
                    'relation.subdomain_id',
                )
                ->whereIn('skills.id', $skillIds)
                ->whereIn(
                    'subdomains.skill_domain_id',
                    $domainIds,
                )
                ->distinct()
                ->count('skills.id');

            if ($validCount !== count($skillIds)) {
                return response()->json([
                    'errors' => [
                        'skills' => [
                            'یک یا چند مهارت با حوزه‌های انتخابی تطابق ندارند.',
                        ],
                    ],
                ], 422);
            }
        }

        $user = auth()->user();

        $syncData = collect($validated['skills'])
            ->mapWithKeys(
                fn (array $item): array => [
                    $item['skill_id'] => [
                        'level' => $item['level'],
                        'years_of_experience' => $item['years'],
                    ],
                ],
            )
            ->all();

        $user->skills()->sync($syncData);

        $profile = $user->profiles()
            ->where('type', 'specialist')
            ->first();

        if ($profile && ! empty($domainIds)) {
            $profile->domains()->sync($domainIds);

            /*
             * Derive profile_processes from skill names so the legacy
             * matching path continues to work during migration.
             */
            $levelMap = [
                'مبتدی' => 'practical',
                'متوسط' => 'proficient',
                'حرفه ای' => 'advanced',
            ];

            $skillRecords = DB::table('skills')
                ->whereIn(
                    'id',
                    collect($validated['skills'])
                        ->pluck('skill_id'),
                )
                ->get()
                ->keyBy('id');

            $processSyncData = [];

            foreach ($validated['skills'] as $skillItem) {
                $skill = $skillRecords->get(
                    $skillItem['skill_id'],
                );

                if (! $skill) {
                    continue;
                }

                $matchingProcessIds = DB::table('processes')
                    ->whereIn('skill_domain_id', $domainIds)
                    ->where('name', $skill->name)
                    ->pluck('id');

                $level = $levelMap[$skillItem['level']]
                    ?? 'practical';

                foreach ($matchingProcessIds as $processId) {
                    $processSyncData[$processId] = [
                        'level' => $level,
                    ];
                }
            }

            if (! empty($processSyncData)) {
                $profile->processes()->sync(
                    $processSyncData,
                );
            }
        } elseif (
            $profile
            && ! empty($validated['domains'])
        ) {
            $profile->domains()->sync(
                $validated['domains'],
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'مهارت‌ها با موفقیت ذخیره شدند',
            'redirect' => route('root'),
        ]);
    }
}
