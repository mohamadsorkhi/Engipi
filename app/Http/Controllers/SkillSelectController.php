<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return response()->json([
            'success' => true,
            'message' => 'مهارت‌ها ذخیره شدند',
            'skills' => $request->skills
        ]);
    }
}