<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateSkillAction;
use App\Actions\Admin\DeleteSkillAction;
use App\Actions\Admin\UpdateSkillAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Http\Requests\Admin\UpdateSkillRequest;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $skills = $this->getSkills();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.skills.components.table_and_pagination', compact('skills'))->render(),
            ]);
        }

        return view('admin.skills.index', compact('skills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSkillRequest $request, CreateSkillAction $action)
    {
        $action->execute($request->validated());

        $skills = $this->getSkills();
        return response()->json([
            'success' => true,
            'message' => 'مهارت با موفقیت افزوده شد.',
            'table' => view('admin.skills.components.table_and_pagination', compact('skills'))->render(),
            'close' => true,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSkillRequest $request, Skill $skill, UpdateSkillAction $action)
    {
        $action->execute($skill, $request->validated());

        $skills = $this->getSkills();
        return response()->json([
            'success' => true,
            'message' => 'مهارت با موفقیت ویرایش شد.',
            'table' => view('admin.skills.components.table_and_pagination', compact('skills'))->render(),
            'close' => true,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Skill $skill, DeleteSkillAction $action)
    {
        $action->execute($skill);

        $skills = $this->getSkills();
        return response()->json([
            'success' => true,
            'message' => 'مهارت با موفقیت حذف شد.',
            'table' => view('admin.skills.components.table_and_pagination', compact('skills'))->render(),
            'close' => true,
        ]);
    }

    /**
     * Helper function to get paginated skills.
     */
    private function getSkills()
    {
        return Skill::latest()->paginate(50);
    }
}
