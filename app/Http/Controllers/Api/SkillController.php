<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index($subdomain)
    {
        return DB::table('skills as skills')
            ->join(
                'skill_subdomain as relation',
                'relation.skill_id',
                '=',
                'skills.id',
            )
            ->where('relation.subdomain_id', $subdomain)
            ->select(
                'skills.id',
                'skills.name',
                'skills.skill_type',
            )
            ->orderBy('skills.name')
            ->get();
    }
}
