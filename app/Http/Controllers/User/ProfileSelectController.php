<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Support\Auth\ProfileContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSelectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile selection page, preserving a valid current selection.
     */
    public function index(ProfileContext $context)
    {
        $user = Auth::user();
        $profiles = $context->availableProfiles($user);

        if ($profiles->isEmpty()) {
            return view('user.role-select');
        }

        $activeProfile = $context->activeProfile($user);

        return view('user.profile-select', compact('profiles', 'activeProfile'));
    }

    /**
     * Activate an owned profile identity and redirect to the dashboard.
     */
    public function activate(Request $request, ProfileContext $context)
    {
        $user = Auth::user();
        $request->validate(['profile_id' => ['required', 'string', 'max:36']]);
        $profile = $context->activate($user, $request->string('profile_id')->toString());

        if (! $profile) {
            return redirect()->route('profile.select')->with('error', 'پروفایل انتخاب‌شده معتبر نیست.');
        }

        return redirect()->route('root');
    }
}
