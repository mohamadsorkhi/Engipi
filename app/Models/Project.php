<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class Project extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'employer_id',
        'employer_profile_id',
        'short_id',
        'title',
        'description',
        'work_type',
        'view_count',
        'duration_days',
        'deadline_date',
        'budget_min',
        'budget_max',
        'seo_title',
        'seo_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'view_count' => 'integer',
        'duration_days' => 'integer',
        'deadline_date' => 'date',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employer that owns the project.
     */
    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function employerProfile()
    {
        return $this->belongsTo(
            UserProfile::class,
            'employer_profile_id',
        );
    }

    public function domains()
    {
        return $this->belongsToMany(
            SkillDomain::class,
            'project_domains',
            'project_id',
            'skill_domain_id',
        )->withTimestamps();
    }

    /**
     * The skills required for the project.
     */
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'project_skills',
        )
            ->withPivot([
                'level',
                'years_of_experience',
            ])
            ->withTimestamps();
    }

    public function processes()
    {
        return $this->belongsToMany(
            Process::class,
            'project_processes',
            'project_id',
            'process_id',
        )
            ->withPivot([
                'desired_levels',
            ])
            ->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Get the requests for the project.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * Get created_at in Jalali format.
     *
     * @param  string  $value
     * @return Jalalian
     */
    public function getCreatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Get updated_at in Jalali format.
     *
     * @param  string  $value
     * @return Jalalian
     */
    public function getUpdatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Scope projects matched to a specialist through canonical identifiers.
     *
     * Matching paths:
     * 1. Exact user skill ID equals the project skill ID.
     * 2. Different skill IDs share the same non-null canonical process ID.
     * 3. A user skill process ID equals a project process ID.
     * 4. A legacy profile process ID equals a project process ID.
     */
    public function scopeForWorkerMatches(
        Builder $query,
        User $worker,
    ): Builder {
        $profile = $worker->profiles()
            ->where('type', 'specialist')
            ->first();

        if (! $profile) {
            return $query->whereRaw('1 = 0');
        }

        $directSkillCount = DB::table(
            'project_skills as count_project_skills',
        )
            ->join(
                'skills as count_project_skill_records',
                'count_project_skill_records.id',
                '=',
                'count_project_skills.skill_id',
            )
            ->selectRaw(
                'COUNT(DISTINCT count_project_skills.skill_id)',
            )
            ->whereColumn(
                'count_project_skills.project_id',
                'projects.id',
            )
            ->where(function ($skillMatches) use ($worker): void {
                $skillMatches
                    ->whereExists(
                        function ($exactWorkerSkills) use ($worker): void {
                            $exactWorkerSkills
                                ->selectRaw('1')
                                ->from(
                                    'user_skills as count_exact_user_skills',
                                )
                                ->whereColumn(
                                    'count_exact_user_skills.skill_id',
                                    'count_project_skills.skill_id',
                                )
                                ->where(
                                    'count_exact_user_skills.user_id',
                                    $worker->id,
                                );
                        },
                    )
                    ->orWhere(
                        function ($processMatches) use ($worker): void {
                            $processMatches
                                ->whereNotNull(
                                    'count_project_skill_records.process_id',
                                )
                                ->whereExists(
                                    function ($equivalentWorkerSkills) use ($worker): void {
                                        $equivalentWorkerSkills
                                            ->selectRaw('1')
                                            ->from(
                                                'user_skills as count_equivalent_user_skills',
                                            )
                                            ->join(
                                                'skills as count_equivalent_skill_records',
                                                'count_equivalent_skill_records.id',
                                                '=',
                                                'count_equivalent_user_skills.skill_id',
                                            )
                                            ->whereColumn(
                                                'count_equivalent_skill_records.process_id',
                                                'count_project_skill_records.process_id',
                                            )
                                            ->whereNotNull(
                                                'count_equivalent_skill_records.process_id',
                                            )
                                            ->where(
                                                'count_equivalent_user_skills.user_id',
                                                $worker->id,
                                            );
                                    },
                                );
                        },
                    );
            });

        $processCount = DB::table(
            'project_processes as count_project_processes',
        )
            ->selectRaw(
                'COUNT(DISTINCT count_project_processes.process_id)',
            )
            ->whereColumn(
                'count_project_processes.project_id',
                'projects.id',
            )
            ->where(function ($processMatches) use (
                $worker,
                $profile,
            ): void {
                $processMatches
                    ->whereExists(
                        function ($workerSkillProcesses) use ($worker): void {
                            $workerSkillProcesses
                                ->selectRaw('1')
                                ->from(
                                    'user_skills as process_user_skills',
                                )
                                ->join(
                                    'skills as process_skills',
                                    'process_skills.id',
                                    '=',
                                    'process_user_skills.skill_id',
                                )
                                ->whereColumn(
                                    'process_skills.process_id',
                                    'count_project_processes.process_id',
                                )
                                ->whereNotNull(
                                    'process_skills.process_id',
                                )
                                ->where(
                                    'process_user_skills.user_id',
                                    $worker->id,
                                );
                        },
                    )
                    ->orWhereExists(
                        function ($profileProcesses) use ($profile): void {
                            $profileProcesses
                                ->selectRaw('1')
                                ->from(
                                    'profile_processes as count_profile_processes',
                                )
                                ->whereColumn(
                                    'count_profile_processes.process_id',
                                    'count_project_processes.process_id',
                                )
                                ->where(
                                    'count_profile_processes.profile_id',
                                    $profile->id,
                                );
                        },
                    );
            });

        $matchingCountSql = sprintf(
            '(%s) + (%s) as matching_skills_count',
            $directSkillCount->toSql(),
            $processCount->toSql(),
        );

        return $query
            ->select('projects.*')
            ->selectRaw(
                $matchingCountSql,
                array_merge(
                    $directSkillCount->getBindings(),
                    $processCount->getBindings(),
                ),
            )
            ->where(function ($matches) use ($worker, $profile): void {
                $matches
                    ->whereExists(
                        function ($directSkills) use ($worker): void {
                            $directSkills
                                ->selectRaw('1')
                                ->from(
                                    'project_skills as matching_project_skills',
                                )
                                ->join(
                                    'skills as matching_project_skill_records',
                                    'matching_project_skill_records.id',
                                    '=',
                                    'matching_project_skills.skill_id',
                                )
                                ->whereColumn(
                                    'matching_project_skills.project_id',
                                    'projects.id',
                                )
                                ->where(
                                    function ($skillMatches) use ($worker): void {
                                        $skillMatches
                                            ->whereExists(
                                                function ($exactWorkerSkills) use ($worker): void {
                                                    $exactWorkerSkills
                                                        ->selectRaw('1')
                                                        ->from(
                                                            'user_skills as matching_exact_user_skills',
                                                        )
                                                        ->whereColumn(
                                                            'matching_exact_user_skills.skill_id',
                                                            'matching_project_skills.skill_id',
                                                        )
                                                        ->where(
                                                            'matching_exact_user_skills.user_id',
                                                            $worker->id,
                                                        );
                                                },
                                            )
                                            ->orWhere(
                                                function ($processMatches) use ($worker): void {
                                                    $processMatches
                                                        ->whereNotNull(
                                                            'matching_project_skill_records.process_id',
                                                        )
                                                        ->whereExists(
                                                            function ($equivalentWorkerSkills) use ($worker): void {
                                                                $equivalentWorkerSkills
                                                                    ->selectRaw('1')
                                                                    ->from(
                                                                        'user_skills as matching_equivalent_user_skills',
                                                                    )
                                                                    ->join(
                                                                        'skills as matching_equivalent_skill_records',
                                                                        'matching_equivalent_skill_records.id',
                                                                        '=',
                                                                        'matching_equivalent_user_skills.skill_id',
                                                                    )
                                                                    ->whereColumn(
                                                                        'matching_equivalent_skill_records.process_id',
                                                                        'matching_project_skill_records.process_id',
                                                                    )
                                                                    ->whereNotNull(
                                                                        'matching_equivalent_skill_records.process_id',
                                                                    )
                                                                    ->where(
                                                                        'matching_equivalent_user_skills.user_id',
                                                                        $worker->id,
                                                                    );
                                                            },
                                                        );
                                                },
                                            );
                                    },
                                );
                        },
                    )
                    ->orWhereExists(
                        function ($skillProcesses) use ($worker): void {
                            $skillProcesses
                                ->selectRaw('1')
                                ->from(
                                    'project_processes as matching_project_processes',
                                )
                                ->join(
                                    'skills as matching_process_skills',
                                    'matching_process_skills.process_id',
                                    '=',
                                    'matching_project_processes.process_id',
                                )
                                ->join(
                                    'user_skills as matching_process_user_skills',
                                    'matching_process_user_skills.skill_id',
                                    '=',
                                    'matching_process_skills.id',
                                )
                                ->whereColumn(
                                    'matching_project_processes.project_id',
                                    'projects.id',
                                )
                                ->whereNotNull(
                                    'matching_process_skills.process_id',
                                )
                                ->where(
                                    'matching_process_user_skills.user_id',
                                    $worker->id,
                                );
                        },
                    )
                    ->orWhereExists(
                        function ($profileProcesses) use ($profile): void {
                            $profileProcesses
                                ->selectRaw('1')
                                ->from(
                                    'project_processes as matching_project_processes',
                                )
                                ->join(
                                    'profile_processes as matching_profile_processes',
                                    'matching_profile_processes.process_id',
                                    '=',
                                    'matching_project_processes.process_id',
                                )
                                ->whereColumn(
                                    'matching_project_processes.project_id',
                                    'projects.id',
                                )
                                ->where(
                                    'matching_profile_processes.profile_id',
                                    $profile->id,
                                );
                        },
                    );
            })
            ->whereNotExists(
                function ($rejectedRequests) use ($worker): void {
                    $rejectedRequests
                        ->selectRaw('1')
                        ->from('requests as rejected_requests')
                        ->whereColumn(
                            'rejected_requests.project_id',
                            'projects.id',
                        )
                        ->where(
                            'rejected_requests.user_id',
                            $worker->id,
                        )
                        ->where(
                            'rejected_requests.status',
                            'rejected',
                        );
                },
            )
            ->where(
                'projects.employer_id',
                '!=',
                $worker->id,
            )
            ->with([
                'employer',
                'skills',
                'processes',
                'domains',
            ]);
    }
}
