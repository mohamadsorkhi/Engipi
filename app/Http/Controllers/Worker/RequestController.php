<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Request as CollaborationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RequestController extends Controller
{
    /**
     * Display a listing of the worker's collaboration requests.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $requests = Auth::user()->requests()->with('project.employer')->latest()->paginate(10);
        return view('worker.requests.index', compact('requests'));
    }

    /**
     * Store a new collaboration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $worker = Auth::user();

        $validated = $request->validate([
            'project_id' => [
                'required',
                'exists:projects,id',
                Rule::unique('requests')->where(function ($query) use ($worker) {
                    return $query->where('user_id', $worker->id);
                }),
            ],
            'message' => 'required|string|min:10|max:2000',
        ]);

        $worker->requests()->create([
            'project_id' => $validated['project_id'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'درخواست همکاری شما با موفقیت ارسال شد.',
            'redirect' => route('worker.requests.index'),
        ]);
    }
}
