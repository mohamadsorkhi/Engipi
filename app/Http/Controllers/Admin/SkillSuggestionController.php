<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\ReviewSkillSuggestionAction;
use App\Http\Controllers\Controller;
use App\Models\SkillSuggestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillSuggestionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'skill_type' => ['nullable', Rule::in(SkillSuggestion::types())],
            'status' => ['nullable', Rule::in([
                SkillSuggestion::STATUS_PENDING,
                SkillSuggestion::STATUS_APPROVED,
                SkillSuggestion::STATUS_REJECTED,
            ])],
        ]);
        $status = $validated['status'] ?? SkillSuggestion::STATUS_PENDING;
        $skillType = $validated['skill_type'] ?? null;
        $suggestions = SkillSuggestion::query()
            ->with(['user', 'subdomain.domain', 'reviewer'])
            ->where('status', $status)
            ->when($skillType, fn ($query) => $query->where('skill_type', $skillType))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.skill-suggestions.index', compact('suggestions', 'status', 'skillType'));
    }

    public function approve(SkillSuggestion $skillSuggestion, Request $request, ReviewSkillSuggestionAction $action)
    {
        $action->approve($skillSuggestion, $request->user());

        return back()->with('success', 'پیشنهاد تأیید و مهارت به لیست اصلی اضافه شد.');
    }

    public function reject(SkillSuggestion $skillSuggestion, Request $request, ReviewSkillSuggestionAction $action)
    {
        $action->reject($skillSuggestion, $request->user());

        return back()->with('success', 'پیشنهاد مهارت رد شد.');
    }
}
