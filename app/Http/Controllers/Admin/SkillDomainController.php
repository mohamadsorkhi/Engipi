<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateSkillDomainAction;
use App\Actions\Admin\DeleteSkillDomainAction;
use App\Actions\Admin\GetSkillDomainsAction;
use App\Actions\Admin\UpdateSkillDomainAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillDomainRequest;
use App\Http\Requests\Admin\UpdateSkillDomainRequest;
use App\Models\SkillDomain;
use Illuminate\Http\Request;

class SkillDomainController extends Controller
{
    public function index(Request $request, GetSkillDomainsAction $getSkillDomainsAction)
    {
        $domains = $getSkillDomainsAction->execute(20);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.domains.components.table_and_pagination', compact('domains'))->render(),
            ]);
        }

        return view('admin.domains.index', compact('domains'));
    }

    public function store(StoreSkillDomainRequest $request, CreateSkillDomainAction $action, GetSkillDomainsAction $getSkillDomainsAction)
    {
        $action->execute($request->validated());

        $domains = $getSkillDomainsAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'حوزه تخصصی با موفقیت افزوده شد.',
            'table' => view('admin.domains.components.table_and_pagination', compact('domains'))->render(),
            'close' => true,
        ]);
    }

    public function update(UpdateSkillDomainRequest $request, SkillDomain $domain, UpdateSkillDomainAction $action, GetSkillDomainsAction $getSkillDomainsAction)
    {
        $action->execute($domain, $request->validated());

        $domains = $getSkillDomainsAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'حوزه تخصصی با موفقیت ویرایش شد.',
            'table' => view('admin.domains.components.table_and_pagination', compact('domains'))->render(),
            'close' => true,
        ]);
    }

    public function destroy(SkillDomain $domain, DeleteSkillDomainAction $action, GetSkillDomainsAction $getSkillDomainsAction)
    {
        $action->execute($domain);

        $domains = $getSkillDomainsAction->execute(20);
        return response()->json([
            'success' => true,
            'message' => 'حوزه تخصصی با موفقیت حذف شد.',
            'table' => view('admin.domains.components.table_and_pagination', compact('domains'))->render(),
        ]);
    }
}
