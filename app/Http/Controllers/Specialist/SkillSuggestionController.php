<?php

namespace App\Http\Controllers\Specialist;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specialist\StoreSkillSuggestionRequest;
use App\Models\SkillSuggestion;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class SkillSuggestionController extends Controller
{
    public function store(StoreSkillSuggestionRequest $request)
    {
        try {
            $suggestion = SkillSuggestion::query()->create([
                'user_id' => $request->user()->id,
                'skill_name' => $request->validated('skill_name'),
                'normalized_name' => SkillSuggestion::normalizeName($request->validated('skill_name')),
                'pending_name' => SkillSuggestion::normalizeName($request->validated('skill_name')),
                'subdomain_id' => $request->validated('subdomain_id'),
                'description' => $request->validated('description'),
                'status' => SkillSuggestion::STATUS_PENDING,
            ]);
        } catch (QueryException $exception) {
            if (! in_array((string) $exception->getCode(), ['23000', '23505'], true)) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'skill_name' => 'شما قبلاً این مهارت را پیشنهاد داده‌اید و در انتظار بررسی است.',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'پیشنهاد شما ثبت شد و پس از بررسی به لیست مهارت‌ها اضافه خواهد شد.',
            'suggestion_id' => $suggestion->id,
        ], 201);
    }
}