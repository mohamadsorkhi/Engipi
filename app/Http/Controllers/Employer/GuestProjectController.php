<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GuestProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        return view('employer.guest-project');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => ['required', 'string', 'max:191'],
            'description'=> ['required', 'string'],
            'work_type'  => ['required', Rule::in(['remote', 'onsite', 'hybrid'])],
            'budget_min' => ['nullable', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
        ], [
            'title.required'       => 'عنوان پروژه الزامی است.',
            'title.max'            => 'عنوان پروژه نباید بیشتر از ۱۹۱ کاراکتر باشد.',
            'description.required' => 'توضیحات پروژه الزامی است.',
            'work_type.required'   => 'نوع همکاری الزامی است.',
            'work_type.in'         => 'نوع همکاری معتبر نیست.',
            'budget_min.numeric'   => 'حداقل بودجه باید عدد باشد.',
            'budget_min.min'       => 'حداقل بودجه نمی‌تواند منفی باشد.',
            'budget_max.numeric'   => 'حداکثر بودجه باید عدد باشد.',
            'budget_max.min'       => 'حداکثر بودجه نمی‌تواند منفی باشد.',
            'budget_max.gte'       => 'حداکثر بودجه باید بزرگتر یا مساوی حداقل بودجه باشد.',
        ]);

        session(['pending_project' => $validated]);

        return redirect()
            ->route('register')
            ->with('project_saved', true);
    }
}
