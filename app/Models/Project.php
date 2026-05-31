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
        return $this->belongsTo(UserProfile::class, 'employer_profile_id');
    }

    public function domains()
    {
        return $this->belongsToMany(SkillDomain::class, 'project_domains', 'project_id', 'skill_domain_id')
            ->withTimestamps();
    }

    /**
     * The skills required for the project.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'project_skills')->withTimestamps();
    }

    public function processes()
    {
        return $this->belongsToMany(Process::class, 'project_processes', 'project_id', 'process_id')
            ->withPivot(['desired_levels'])
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
     * @return \Morilog\Jalali\Jalalian
     */
    public function getCreatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Get updated_at in Jalali format.
     *
     * @param  string  $value
     * @return \Morilog\Jalali\Jalalian
     */
    public function getUpdatedAtAttribute($value)
    {
        return Jalalian::fromDateTime($value);
    }

    /**
     * Scope matched projects for a specialist worker.
     * Three matching paths are tried in union:
     *   1. Direct skill UUID match  — user_skills.skill_id ∈ project_skills.skill_id
     *   2. Skill-name → process     — skill name matches process name in project_processes
     *   3. Legacy profile_processes — for data saved via older admin/API flows
     */
    public function scopeForWorkerMatches(Builder $query, User $worker)
    {
        $profile = $worker->profiles()->where('type', 'specialist')->first();

        if (!$profile) {
            return $query->whereRaw('1 = 0');
        }

        $rejectedProjectIds = $worker->requests()
            ->where('status', 'rejected')
            ->pluck('project_id');

        // ── Path 1: direct skill UUID match ─────────────────────────────
        $workerSkillIds = DB::table('user_skills')
            ->where('user_id', $worker->id)
            ->pluck('skill_id');

        $matchedBySkill = $workerSkillIds->isNotEmpty()
            ? DB::table('project_skills')
                ->whereIn('skill_id', $workerSkillIds)
                ->pluck('project_id')
            : collect();

        // ── Path 2: skill name → process name in project_processes ───────
        $matchedByProcessName = collect();
        if ($workerSkillIds->isNotEmpty()) {
            $workerSkillNames = DB::table('skills')
                ->whereIn('id', $workerSkillIds)
                ->pluck('name');

            if ($workerSkillNames->isNotEmpty()) {
                $matchedByProcessName = DB::table('project_processes as pp')
                    ->join('processes as p', 'pp.process_id', '=', 'p.id')
                    ->whereIn('p.name', $workerSkillNames)
                    ->pluck('pp.project_id');
            }
        }

        // ── Path 3: legacy profile_processes ────────────────────────────
        $workerProcessIds = DB::table('profile_processes')
            ->where('profile_id', $profile->id)
            ->pluck('process_id');

        $matchedByProcess = $workerProcessIds->isNotEmpty()
            ? DB::table('project_processes')
                ->whereIn('process_id', $workerProcessIds)
                ->pluck('project_id')
            : collect();

        // ── Path 4: skill-name bridge across project_skills ─────────────
        // Handles the case where employer and specialist pick the same tool
        // (e.g. Revit) from different subdomains, yielding different UUIDs.
        $matchedBySkillName = collect();
        if ($workerSkillIds->isNotEmpty()) {
            $workerSkillNames = $workerSkillNames ?? DB::table('skills')
                ->whereIn('id', $workerSkillIds)
                ->pluck('name');

            if ($workerSkillNames->isNotEmpty()) {
                $matchedBySkillName = DB::table('project_skills as ps')
                    ->join('skills as s', 'ps.skill_id', '=', 's.id')
                    ->whereIn('s.name', $workerSkillNames)
                    ->pluck('ps.project_id');
            }
        }

        $allMatchingIds = $matchedBySkill
            ->merge($matchedByProcessName)
            ->merge($matchedByProcess)
            ->merge($matchedBySkillName)
            ->unique()
            ->values();

        if ($allMatchingIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query
            ->whereIn('projects.id', $allMatchingIds)
            ->whereNotIn('projects.id', $rejectedProjectIds)
            ->where('projects.employer_id', '!=', $worker->id)
            ->with(['employer', 'skills', 'processes', 'domains'])
            ->distinct();
    }
}
