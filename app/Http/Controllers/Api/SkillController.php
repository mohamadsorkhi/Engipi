<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SkillController extends Controller
{
    public function index($subdomain)
    {
        return DB::table('skills')
            ->where('subdomain_id', $subdomain)
            ->select('id', 'name')
            ->get();
    }
}