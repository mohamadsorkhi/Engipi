<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSkillController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id'
        ]);

        $exists =
            UserSkill::where('user_id', Auth::id())
                ->where('skill_id', $request->skill_id)
                ->exists();

        if ($exists) {

            return response()->json([
                'message' => 'این مهارت قبلا ثبت شده'
            ], 409);
        }

        UserSkill::create([
            'user_id' => Auth::id(),
            'skill_id' => $request->skill_id
        ]);

        return response()->json([
            'message' => 'مهارت ذخیره شد'
        ]);
    }
}