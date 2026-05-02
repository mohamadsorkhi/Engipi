<?php

namespace App\Http\Controllers\Employer;

use App\Actions\Employer\AcceptRequestAction;
use App\Actions\Employer\GetEmployerRequestsAction;
use App\Actions\Employer\RejectRequestAction;
use App\Actions\Employer\RevertRequestAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Employer\ManageRequestRequest;
use App\Models\Request as CollaborationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    /**
     * Display a listing of the incoming requests for the employer's projects.
     */
    public function index(Request $request, GetEmployerRequestsAction $action)
    {
        $requests = $action->execute(Auth::user());

        if ($request->ajax()) {
            return response()->json([
                'table' => view('employer.requests._list', compact('requests'))->render()
            ]);
        }

        return view('user.requests.received', compact('requests'));
    }

    /**
     * Accept a collaboration request.
     */
    public function accept(
        ManageRequestRequest $formRequest,
        CollaborationRequest $request,
        AcceptRequestAction $action,
        GetEmployerRequestsAction $getRequestsAction
    ) {
        if ($request->status === 'accepted') {
            return response()->json(['message' => 'این درخواست قبلا پذیرفته شده است.'], 422);
        }

        $action->execute($request);

        $requests = $getRequestsAction->execute(Auth::user());
        return response()->json([
            'status' => 'success',
            'message' => 'درخواست با موفقیت پذیرفته شد.',
            'table' => view('employer.requests._list', compact('requests'))->render(),
        ]);
    }

    /**
     * Reject a collaboration request.
     */
    public function reject(
        ManageRequestRequest $formRequest,
        CollaborationRequest $request,
        RejectRequestAction $action,
        GetEmployerRequestsAction $getRequestsAction
    ) {
        if ($request->status === 'rejected') {
            return response()->json(['message' => 'این درخواست قبلا رد شده است.'], 422);
        }

        $action->execute($request);

        $requests = $getRequestsAction->execute(Auth::user());
        return response()->json([
            'status' => 'success',
            'message' => 'درخواست با موفقیت رد شد.',
            'table' => view('employer.requests._list', compact('requests'))->render(),
        ]);
    }

    /**
     * Revert a request back to pending.
     */
    public function revertReject(
        ManageRequestRequest $formRequest,
        CollaborationRequest $request,
        RevertRequestAction $action,
        GetEmployerRequestsAction $getRequestsAction
    ) {
        if (!in_array($request->status, ['rejected', 'accepted'])) {
            return response()->json(['message' => 'فقط درخواست‌های پذیرفته یا رد شده قابل بازبینی هستند.'], 422);
        }

        $action->execute($request);

        $requests = $getRequestsAction->execute(Auth::user());
        return response()->json([
            'status' => 'success',
            'message' => 'درخواست برای بازبینی مجدد بازگردانده شد.',
            'table' => view('employer.requests._list', compact('requests'))->render(),
        ]);
    }
}
