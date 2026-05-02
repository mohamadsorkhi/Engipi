<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileSelectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile selection page.
     */
    public function index()
    {
        $user = Auth::user();
        $profiles = $user->profiles;

        return view('user.profile-select', compact('profiles'));
    }
}
