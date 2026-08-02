@props(['project'])
@php
    $skills = $project->skills->take(3);
    $budget = null;
    if ($project->budget_min !== null || $project->budget_max !== null) {
        $min = $project->budget_min !== null ? number_format((float) $project->budget_min) : null;
        $max = $project->budget_max !== null ? number_format((float) $project->budget_max) : null;
        $budget = $min && $max ? $min.' تا '.$max.' تومان' : ($min ?: $max).' تومان';
    }
@endphp
<article class="sp-project">
    <div class="sp-project__thumb" aria-hidden="true"><i class="ri-building-2-line"></i></div>
    <div class="sp-project__content">
        <div class="sp-project__title-row">
            <div><h3>{{ $project->title }}</h3><p>{{ $project->employer->name ?? 'کارفرمای EngiPi' }}</p></div>
            <span>{{ $project->matching_skills_count }} تطبیق</span>
        </div>
        <div class="sp-project__facts">
            @if($budget)<span><i class="ri-wallet-3-line"></i>{{ $budget }}</span>@endif
            @if($project->duration_days)<span><i class="ri-time-line"></i>{{ $project->duration_days }} روز</span>@endif
            @if($project->work_type)<span><i class="ri-map-pin-line"></i>{{ __('project.work_type.'.$project->work_type) }}</span>@endif
        </div>
        @if($skills->isNotEmpty())<div class="sp-project__skills">@foreach($skills as $skill)<span>{{ $skill->name }}</span>@endforeach</div>@endif
    </div>
    <a href="{{ route('user.matched-projects.show', $project) }}" class="sp-project__button" aria-label="مشاهده پروژه {{ $project->title }}">مشاهده <i class="ri-arrow-left-line"></i></a>
</article>