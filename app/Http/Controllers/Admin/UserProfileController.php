<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    private function buildProfilesQuery(Request $request)
    {
        $profilesQuery = UserProfile::query()->with('user');

        $q = trim((string)$request->input('q', ''));
        if ($q !== '') {
            $profilesQuery->where(function ($sub) use ($q) {
                $sub->where('headline', 'like', "%{$q}%")
                    ->orWhere('company_name', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($userQ) use ($q) {
                        $userQ->where('first_name', 'like', "%{$q}%")
                            ->orWhere('last_name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%")
                            ->orWhere('mobile', 'like', "%{$q}%");
                    });
            });
        }

        $type = $request->input('type');
        if (in_array($type, ['employer', 'specialist'], true)) {
            $profilesQuery->where('type', $type);
        }

        if ($request->boolean('has_processes')) {
            $profilesQuery->whereHas('processes');
        }

        return $profilesQuery;
    }

    public function index(Request $request)
    {
        $profiles = $this->buildProfilesQuery($request)->latest()->paginate(20);
        $profiles->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.profiles.components.table_and_pagination', compact('profiles'))->render(),
            ]);
        }

        return view('admin.profiles.index', compact('profiles'));
    }

    public function destroy(UserProfile $profile)
    {
        // Detach processes
        $profile->processes()->detach();
        
        $profile->delete();

        $profiles = $this->buildProfilesQuery(request())->latest()->paginate(20);
        $profiles->appends(request()->query());
        return response()->json([
            'success' => true,
            'message' => 'پروفایل با موفقیت حذف شد.',
            'table' => view('admin.profiles.components.table_and_pagination', compact('profiles'))->render(),
        ]);
    }
}
