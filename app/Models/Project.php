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
     * Scope a query to only include projects that match a worker's processes and levels.
     * It also excludes projects for which the worker has a rejected request.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User  $worker
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForWorkerMatches(Builder $query, User $worker)
    {
        // Get worker's specialist profile
        $profile = $worker->profiles()->where('type', 'specialist')->first();
        
        if (!$profile) {
            return $query->whereRaw('1 = 0'); // No specialist profile
        }

        // Get worker's processes with their levels
        $workerProcesses = $profile->processes()->withPivot('level')->get();

        if ($workerProcesses->isEmpty()) {
            return $query->whereRaw('1 = 0'); // No processes selected
        }

        // Get project IDs where the worker's request was rejected
        $rejectedProjectIds = $worker->requests()
            ->where('status', 'rejected')
            ->pluck('project_id');

        // Build matching logic: worker's process+level must match project's process+desired_levels
        $matchingProjectIds = DB::table('project_processes as pp')
            ->join('profile_processes as upp', 'pp.process_id', '=', 'upp.process_id')
            ->where('upp.profile_id', $profile->id)
            ->where(function ($q) {
                // Match if worker's level is in the project's desired_levels JSON array
                $q->whereRaw("JSON_CONTAINS(pp.desired_levels, JSON_QUOTE(upp.level))")
                  ->orWhereNull('pp.desired_levels'); // Or if project has no level requirement
            })
            ->select('pp.project_id', DB::raw('COUNT(DISTINCT pp.process_id) as matching_processes_count'))
            ->groupBy('pp.project_id');

        // Get user's selected domain IDs
        $userDomainIds = DB::table('user_profile_domains')
            ->where('profile_id', $profile->id)
            ->pluck('skill_domain_id');

        if ($userDomainIds->isEmpty()) {
            return $query->whereRaw('1 = 0'); // No domains selected
        }

        return $query
            ->joinSub($matchingProjectIds, 'process_matches', function ($join) {
                $join->on('projects.id', '=', 'process_matches.project_id');
            })
            ->join('project_domains', 'projects.id', '=', 'project_domains.project_id')
            ->select('projects.*', 'process_matches.matching_processes_count as matching_skills_count')
            ->whereNotIn('projects.id', $rejectedProjectIds)
            ->whereIn('project_domains.skill_domain_id', $userDomainIds) // Match any of user's domains
            ->with(['employer', 'skills', 'processes', 'domains'])
            ->distinct();
    }
}
