<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileSelectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile selection page, or role-select for first-time users.
     * Clears any existing active_role so the user consciously picks again.
     */
    public function index()
    {
        $user = Auth::user();
        $profiles = $user->profiles;

        if ($profiles->isEmpty()) {
            return view('user.role-select');
        }

        session()->forget('active_role');

        return view('user.profile-select', compact('profiles'));
    }

    /**
     * Store the chosen role in session and redirect to the dashboard.
     */
    public function activate(Request $request)
    {
        $type = $request->input('type');

        if (!in_array($type, ['employer', 'specialist'])) {
            return redirect()->route('profile.select');
        }

        $user = Auth::user();
        $hasProfile = $user->profiles->contains('type', $type);

        if (!$hasProfile) {
            return redirect()->route('profile.select');
        }

        session(['active_role' => $type]);

        return redirect()->route('root');
    }
}
