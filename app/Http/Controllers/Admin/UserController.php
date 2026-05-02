<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\DeleteUserAction;
use App\Actions\Admin\GetUsersAction;
use App\Actions\Admin\UpdateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetUsersAction $action)
    {
        $filters = $request->only(['q', 'profile_type', 'has_profile', 'has_skills', 'has_projects']);
        $users = $action->execute($filters);
        $users->appends($request->query());

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.users.components.table_and_pagination', compact('users'))->render(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user with full details.
     */
    public function show(User $user)
    {
        $user->load([
            'profiles.domains',
            'profiles.processes',
        ]);

        $projects = $user->projects()
            ->with(['domains'])
            ->latest()
            ->paginate(10);

        $requests = $user->requests()
            ->with(['project'])
            ->latest()
            ->paginate(10);

        return view('admin.users.show', compact('user', 'projects', 'requests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action, GetUsersAction $getUsersAction)
    {
        $action->execute($user, $request->validated());

        $filters = request()->only(['q', 'profile_type', 'has_profile', 'has_skills', 'has_projects']);
        $users = $getUsersAction->execute($filters);
        $users->appends(request()->query());
        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ویرایش شد.',
            'table' => view('admin.users.components.table_and_pagination', compact('users'))->render(),
            'close' => true,
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DeleteUserAction $action, GetUsersAction $getUsersAction)
    {
        // Prevent deleting admin users
        if ($user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'امکان حذف ادمین وجود ندارد.',
            ], 403);
        }

        $action->execute($user);

        $filters = request()->only(['q', 'profile_type', 'has_profile', 'has_skills', 'has_projects']);
        $users = $getUsersAction->execute($filters);
        $users->appends(request()->query());

        return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت حذف شد.',
            'table' => view('admin.users.components.table_and_pagination', compact('users'))->render(),
        ]);
    }
}
