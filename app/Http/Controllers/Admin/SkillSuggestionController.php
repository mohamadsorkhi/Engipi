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
            'status' => ['nullable', Rule::in([
                SkillSuggestion::STATUS_PENDING,
                SkillSuggestion::STATUS_APPROVED,
                SkillSuggestion::STATUS_REJECTED,
            ])],
        ]);
        $status = $validated['status'] ?? SkillSuggestion::STATUS_PENDING;
        $suggestions = SkillSuggestion::query()
            ->with(['user', 'subdomain.domain', 'reviewer'])
            ->where('status', $status)
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.skill-suggestions.index', compact('suggestions', 'status'));
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