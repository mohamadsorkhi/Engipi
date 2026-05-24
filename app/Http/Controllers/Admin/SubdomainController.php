<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\CreateSubdomainAction;
use App\Actions\Admin\DeleteSubdomainAction;
use App\Actions\Admin\UpdateSubdomainAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubdomainRequest;
use App\Http\Requests\Admin\UpdateSubdomainRequest;
use App\Models\SkillDomain;
use App\Models\Subdomain;
use Illuminate\Http\Request;

class SubdomainController extends Controller
{
    public function index(Request $request)
    {
        $query = Subdomain::with('domain')->withCount('skills')->latest();

        if ($request->filled('domain_id')) {
            $query->where('skill_domain_id', $request->domain_id);
        }

        $subdomains    = $query->paginate(30);
        $domains       = SkillDomain::orderBy('name')->get();
        $selectedDomain = $request->filled('domain_id')
            ? $domains->firstWhere('id', $request->domain_id)
            : null;

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.subdomains.components.table_and_pagination',
                    compact('subdomains', 'domains', 'selectedDomain'))->render(),
            ]);
        }

        return view('admin.subdomains.index', compact('subdomains', 'domains', 'selectedDomain'));
    }

    public function store(StoreSubdomainRequest $request, CreateSubdomainAction $action)
    {
        $action->execute($request->validated());

        $subdomains    = $this->query($request)->paginate(30);
        $domains       = SkillDomain::orderBy('name')->get();
        $selectedDomain = null;

        return response()->json([
            'success' => true,
            'message' => 'زیرحوزه با موفقیت افزوده شد.',
            'table'   => view('admin.subdomains.components.table_and_pagination',
                compact('subdomains', 'domains', 'selectedDomain'))->render(),
            'close'   => true,
        ]);
    }

    public function update(UpdateSubdomainRequest $request, Subdomain $subdomain, UpdateSubdomainAction $action)
    {
        $action->execute($subdomain, $request->validated());

        $subdomains    = $this->query($request)->paginate(30);
        $domains       = SkillDomain::orderBy('name')->get();
        $selectedDomain = null;

        return response()->json([
            'success' => true,
            'message' => 'زیرحوزه با موفقیت ویرایش شد.',
            'table'   => view('admin.subdomains.components.table_and_pagination',
                compact('subdomains', 'domains', 'selectedDomain'))->render(),
            'close'   => true,
        ]);
    }

    public function destroy(Subdomain $subdomain, DeleteSubdomainAction $action, Request $request)
    {
        $action->execute($subdomain);

        $subdomains    = $this->query($request)->paginate(30);
        $domains       = SkillDomain::orderBy('name')->get();
        $selectedDomain = null;

        return response()->json([
            'success' => true,
            'message' => 'زیرحوزه با موفقیت حذف شد.',
            'table'   => view('admin.subdomains.components.table_and_pagination',
                compact('subdomains', 'domains', 'selectedDomain'))->render(),
        ]);
    }

    private function query(Request $request)
    {
        $q = Subdomain::with('domain')->withCount('skills')->latest();

        if ($request->filled('domain_id')) {
            $q->where('skill_domain_id', $request->domain_id);
        }

        return $q;
    }
}
