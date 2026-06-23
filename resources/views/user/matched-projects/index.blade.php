@extends('layouts.master')

@section('title', 'پروژه‌های پیشنهادی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="bp-panel2">
                <div class="bp-ph">
                    <h5 class="mb-0"><i class="ri-lightbulb-flash-line text-primary me-2"></i>پروژه‌های پیشنهادی</h5>
                    <a href="{{ route('user.skills.index') }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-settings-3-line me-1"></i> مدیریت مهارت‌ها
                    </a>
                </div>
                <div class="bp-pb-grid">
                    @if($projects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشد.
                            <br>
                            <a href="{{ route('user.skills.index') }}" class="alert-link">مهارت‌های خود را به‌روز کنید</a>
                        </div>
                    @else
                        @php
                            $workTypes = [
                                'remote' => ['name' => 'دورکاری', 'style' => 'background:var(--bp-teal);color:#fff;'],
                                'onsite' => ['name' => 'حضوری', 'style' => 'background:var(--bp-blue);color:#fff;'],
                                'hybrid' => ['name' => 'ترکیبی', 'style' => 'background:var(--bp-tint-blue);color:var(--bp-blue);'],
                            ];
                        @endphp

                        <div class="bp-proj-grid">
                            @foreach($projects as $project)
                                <div class="bp-proj">
                                    <div class="bp-proj-top">
                                        <div>
                                            <a href="{{ route('user.matched-projects.show', $project) }}" class="bp-pname">
                                                {{ $project->title }}
                                            </a>
                                            <div class="bp-pemp">
                                                <i class="ri-user-line"></i>{{ $project->employer->name ?? '-' }}
                                            </div>
                                        </div>
                                        @php $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'style' => 'background:var(--bp-surface);color:var(--bp-muted);']; @endphp
                                        <span class="badge" style="{{ $wt['style'] }}">{{ $wt['name'] }}</span>
                                    </div>

                                    <p class="bp-pdesc">{{ Str::limit($project->description, 120) }}</p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="badge bg-primary-subtle text-primary">{{ $project->domains->first()?->name ?? '-' }}</span>
                                        @foreach($project->processes->take(3) as $process)
                                            <span class="badge bg-info-subtle text-info">{{ $process->name }}</span>
                                        @endforeach
                                    </div>

                                    <div class="bp-proj-foot">
                                        <span class="bp-pdate">
                                            <i class="ri-calendar-line"></i>{{ $project->created_at }}
                                        </span>
                                        <a href="{{ route('user.matched-projects.show', $project) }}" class="btn btn-soft-primary btn-sm">
                                            مشاهده و ارسال درخواست
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
    .bp-panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
    .bp-ph h5 { font-size: 1rem; font-weight: 700; }
    .bp-pb-grid { padding: 20px; }

    .bp-proj-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    @media (max-width: 900px) { .bp-proj-grid { grid-template-columns: 1fr; } }
    .bp-proj {
        background: #fff;
        border: 1px solid var(--bp-border);
        border-radius: var(--bp-r-lg);
        padding: 20px;
        transition: transform .25s var(--bp-ease), box-shadow .25s, border-color .25s;
    }
    .bp-proj:hover { transform: translateY(-3px); box-shadow: var(--bp-sh-md); border-color: var(--bp-blue); }
    .bp-proj-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
    .bp-pname { font-size: 1.05rem; font-weight: 800; color: var(--bp-ink); text-decoration: none; }
    .bp-pname:hover { color: var(--bp-blue); }
    .bp-pemp { color: var(--bp-muted); font-size: .82rem; display: flex; align-items: center; gap: 5px; margin-top: 3px; }
    .bp-pdesc { color: var(--bp-muted); font-size: .9rem; line-height: 1.7; margin-bottom: 14px; }
    .bp-proj-foot { display: flex; align-items: center; justify-content: space-between; }
    .bp-pdate { color: var(--bp-muted); font-size: .8rem; display: flex; align-items: center; gap: 5px; }
    </style>
@endsection
