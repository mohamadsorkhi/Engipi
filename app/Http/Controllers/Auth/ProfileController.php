<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\AddProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AddProfileRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get current user's profiles.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $profiles = $user->profiles;

        return response()->json([
            'profiles' => $profiles,
        ]);
    }

    /**
     * Add a new profile to the current user.
     */
    public function store(AddProfileRequest $request, AddProfileAction $action): JsonResponse
    {
        $user = Auth::user();
        $profile = $action->execute($user, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'پروفایل جدید با موفقیت ایجاد شد.',
            'profile' => $profile,
            'redirect' => route('root'),
        ]);
    }

    /**
     * Update an existing profile of the current user.
     */
    public function update(UpdateProfileRequest $request, UserProfile $profile): JsonResponse
    {
        $profile->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'اطلاعات پروفایل با موفقیت بروزرسانی شد.',
            'profile' => $profile,
        ]);
    }

}
