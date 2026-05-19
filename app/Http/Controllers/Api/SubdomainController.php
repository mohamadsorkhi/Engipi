<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SubdomainController extends Controller
{
    public function index($domainId)
    {
        $data = DB::table('subdomains')
            ->where('skill_domain_id', $domainId)
            ->select('id','name')
            ->get();

        return response()->json($data);
    }
}