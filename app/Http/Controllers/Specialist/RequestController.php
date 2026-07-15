<?php

namespace App\Http\Controllers\Specialist;

use App\Actions\Specialist\StoreCollaborationRequestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialist\StoreCollaborationRequestRequest;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RequestController extends Controller
{
    /**
     * Display a listing of the specialist's requests.
     */
    public function index()
    {
        $requests = Auth::user()->requests()
            ->with(['project.employer', 'project.skills'])
            ->latest()
            ->paginate(10);

        return view('user.requests.sent', compact('requests'));
    }

    /**
     * Store a new collaboration request.
     */
    public function store(StoreCollaborationRequestRequest $request, StoreCollaborationRequestAction $action)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $project = Project::query()->findOrFail($validated['project_id']);

        $authorization = Gate::inspect('requestCollaboration', $project);

        if ($authorization->denied()) {
            return response()->json([
                'status' => 'error',
                'message' => 'این پروژه برای ارسال درخواست همکاری در دسترس شما نیست.',
            ], 422);
        }

        // Check if already requested
        $existingRequest = $user->requests()
            ->where('project_id', $validated['project_id'])
            ->first();

        if ($existingRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'شما قبلا برای این پروژه درخواست ارسال کرده‌اید.',
            ], 422);
        }

        $action->execute($user, $validated['project_id'], $validated['message'] ?? null);

        return response()->json([
            'status' => 'success',
            'message' => 'درخواست همکاری شما با موفقیت ارسال شد.',
        ]);
    }
}
