<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\AddUserSkillAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserSkillRequest;

class UserSkillController extends Controller
{
    public function store(StoreUserSkillRequest $request, AddUserSkillAction $action)
    {
        if (! $action->execute($request->user(), $request->validated('skill_id'))) {

            return response()->json([
                'message' => 'این مهارت قبلا ثبت شده',
            ], 409);
        }

        return response()->json([
            'message' => 'مهارت ذخیره شد',
        ]);
    }
}
