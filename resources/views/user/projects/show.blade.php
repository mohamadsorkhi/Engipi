@extends('layouts.master')

@section('title', $project->title)

@section('content')
    @php
        $workTypes = [
            'remote' => ['name' => 'دورکاری', 'icon' => 'ri-home-wifi-line', 'badge' => 'background:var(--bp-teal);color:#fff;', 'tile' => 'background:var(--bp-tint-teal);color:var(--bp-teal);'],
            'onsite' => ['name' => 'حضوری', 'icon' => 'ri-building-line', 'badge' => 'background:var(--bp-blue);color:#fff;', 'tile' => 'background:var(--bp-tint-blue);color:var(--bp-blue);'],
            'hybrid' => ['name' => 'ترکیبی', 'icon' => 'ri-git-merge-line', 'badge' => 'background:var(--bp-tint-blue);color:var(--bp-blue);', 'tile' => 'background:var(--bp-tint-amber);color:var(--bp-c-amber);'],
        ];
        $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'icon' => 'ri-question-line', 'badge' => 'background:var(--bp-surface);color:var(--bp-muted);', 'tile' => 'background:var(--bp-surface);color:var(--bp-muted);'];
    @endphp

    <div class="bp-det-grid">
        <div>
            <!-- Hero -->
            <div class="bp-hero-card mb-4">
                <div class="bp-hero-band">
                    <div class="grid-bg bp-grid"></div>
                    <div class="bp-hero-tags">
                        <span class="badge" style="{{ $wt['badge'] }}"><i class="{{ $wt['icon'] }} me-1"></i>{{ $wt['name'] }}</span>
                    </div>
                    <h1>{{ $project->title }}</h1>
                    <div class="bp-hero-meta">
                        @if($project->domains->isNotEmpty())
                            <span><i class="ri-stack-line"></i>{{ $project->domains->pluck('name')->join('، ') }}</span>
                        @endif
                        <span><i class="ri-calendar-line"></i>ثبت: {{ $project->created_at->format('Y/m/d') }}</span>
                        @if($project->duration_days)
                            <span><i class="ri-time-line"></i>مهلت: {{ $project->duration_days }} روز</span>
                        @endif
                        <span><i class="ri-group-line"></i>{{ $project->requests->count() }} درخواست</span>
                    </div>
                </div>
                <div class="bp-hero-body">
                    <h5><i class="ri-file-text-line"></i>شرح پروژه</h5>
                    <p>{!! nl2br(e($project->description)) !!}</p>

                    <div class="bp-hr"></div>
                    <h5 class="mb-3"><i class="ri-price-tag-3-line"></i>مشخصات</h5>
                    <div class="bp-kv">
                        <div class="bp-kv-item">
                            <div class="bp-kv-ic" style="background:var(--bp-tint-teal);color:var(--bp-teal);"><i class="ri-wallet-3-line"></i></div>
                            <div>
                                <div class="bp-kv-l">بودجه</div>
                                <div class="bp-kv-v">
                                    @if($project->budget_min && $project->budget_max)
                                        {{ number_format($project->budget_min) }} - {{ number_format($project->budget_max) }} تومان
                                    @elseif($project->budget_min)
                                        از {{ number_format($project->budget_min) }} تومان
                                    @elseif($project->budget_max)
                                        تا {{ number_format($project->budget_max) }} تومان
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bp-kv-item">
                            <div class="bp-kv-ic" style="{{ $wt['tile'] }}"><i class="{{ $wt['icon'] }}"></i></div>
                            <div>
                                <div class="bp-kv-l">نوع همکاری</div>
                                <div class="bp-kv-v">{{ $wt['name'] }}</div>
                            </div>
                        </div>
                        <div class="bp-kv-item">
                            <div class="bp-kv-ic" style="background:var(--bp-tint-amber);color:var(--bp-c-amber);"><i class="ri-time-line"></i></div>
                            <div>
                                <div class="bp-kv-l">مدت زمان</div>
                                <div class="bp-kv-v">{{ $project->duration_days ? $project->duration_days.' روز' : '-' }}</div>
                            </div>
                        </div>
                        <div class="bp-kv-item">
                            <div class="bp-kv-ic" style="background:var(--bp-tint-sky);color:var(--bp-c-sky);"><i class="ri-eye-line"></i></div>
                            <div>
                                <div class="bp-kv-l">بازدید</div>
                                <div class="bp-kv-v">{{ $project->view_count }}</div>
                            </div>
                        </div>
                    </div>

                    @if($project->processes->isNotEmpty())
                        @php
                            $levelLabels = [
                                'practical' => 'عملی',
                                'proficient' => 'مسلط',
                                'advanced' => 'پیشرفته',
                            ];
                        @endphp
                        <div class="bp-hr"></div>
                        <h5 class="mb-3"><i class="ri-flow-chart"></i>پردازش‌های مورد نیاز</h5>
                        <div class="row g-2">
                            @foreach($project->processes as $process)
                                @php
                                    $levelsRaw = $process->pivot?->desired_levels;
                                    $levels = [];
                                    if (is_string($levelsRaw)) {
                                        $decoded = json_decode($levelsRaw, true);
                                        $levels = is_array($decoded) ? $decoded : [];
                                    } elseif (is_array($levelsRaw)) {
                                        $levels = $levelsRaw;
                                    }
                                @endphp
                                <div class="col-md-6 col-lg-4">
                                    <div class="bp-mini-box">
                                        <div class="fw-medium" style="color:var(--bp-blue);">{{ $process->name }}</div>
                                        <div class="small text-muted mt-1">
                                            سطح(ها):
                                            @if(!empty($levels))
                                                {{ collect($levels)->map(fn($l) => $levelLabels[$l] ?? $l)->join('، ') }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($project->skills->isNotEmpty())
                        <div class="bp-hr"></div>
                        <h5 class="mb-3"><i class="ri-tools-line"></i>مهارت‌های مورد نیاز</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($project->skills as $skill)
                                <span class="badge" style="background:var(--bp-tint-teal);color:var(--bp-teal-d, var(--bp-teal));">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($project->files->isNotEmpty())
                        <div class="bp-hr"></div>
                        <h5 class="mb-3"><i class="ri-attachment-2"></i>فایل‌های پیوست</h5>
                        <div class="d-flex flex-column gap-2">
                            @foreach($project->files as $file)
                                <a href="{{ Storage::url($file->path) }}" class="bp-file-row" target="_blank">
                                    <i class="ri-file-line"></i>
                                    <span class="flex-grow-1">{{ $file->original_name }}</span>
                                    <span class="badge" style="background:var(--bp-surface);color:var(--bp-muted);">{{ number_format($file->size / 1024, 1) }} KB</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    <div class="bp-hr"></div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.projects.edit', $project) }}" class="btn btn-soft-info btn-sm">
                            <i class="ri-pencil-line me-1"></i> ویرایش
                        </a>
                        <form action="{{ route('user.projects.destroy', $project) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                data-confirm="آیا از حذف این پروژه مطمئن هستید؟">
                                <i class="ri-delete-bin-line me-1"></i> حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Requests -->
            <div class="bp-panel2">
                <div class="bp-ph">
                    <h5 class="mb-0"><i class="ri-inbox-line text-primary me-2"></i>درخواست‌های همکاری</h5>
                    <span class="badge" style="background:var(--bp-tint-blue);color:var(--bp-blue);">{{ $project->requests->count() }} نفر</span>
                </div>
                <div class="bp-pb-table">
                    @if($project->requests->isEmpty())
                        <div class="alert alert-info text-center mb-0 mt-3">
                            هنوز درخواستی برای این پروژه ارسال نشده است.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless align-middle bp-table mb-0">
                                <thead>
                                    <tr>
                                        <th>متخصص</th>
                                        <th>پیام</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->requests as $request)
                                        <tr>
                                            <td class="fw-medium">{{ $request->user->name }}</td>
                                            <td>{{ Str::limit($request->message, 50) ?: '-' }}</td>
                                            <td>
                                                <x-request-status-badge :status="$request->status" />
                                            </td>
                                            <td class="text-muted">{{ $request->created_at }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <div class="d-flex gap-1">
                                                        <form action="{{ route('user.requests.accept', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-soft-success btn-sm ajax-submit" title="پذیرش">
                                                                <i class="ri-check-line"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('user.requests.reject', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" title="رد">
                                                                <i class="ri-close-line"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <form action="{{ route('user.requests.revert', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-warning btn-sm ajax-submit" title="بازگردانی">
                                                            <i class="ri-refresh-line"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <div class="bp-aside-card">
                <h6>اطلاعات پروژه</h6>
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">حوزه‌های تخصصی</span>
                    <span class="bp-mini-v">
                        @if($project->domains->isNotEmpty())
                            {{ $project->domains->pluck('name')->join('، ') }}
                        @else
                            -
                        @endif
                    </span>
                </div>
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">نام شرکت</span>
                    <span class="bp-mini-v">{{ $project->employerProfile?->company_name ?? '-' }}</span>
                </div>
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">نوع اجرا</span>
                    <span class="badge" style="{{ $wt['badge'] }}"><i class="{{ $wt['icon'] }} me-1"></i>{{ $wt['name'] }}</span>
                </div>
                @if($project->deadline_date)
                    <div class="bp-mini-stat">
                        <span class="bp-mini-k">ددلاین</span>
                        <span class="bp-mini-v">{{ $project->deadline_date }}</span>
                    </div>
                @endif
                @if($project->duration_days)
                    <div class="bp-mini-stat">
                        <span class="bp-mini-k">مدت زمان</span>
                        <span class="bp-mini-v">{{ $project->duration_days }} روز</span>
                    </div>
                @endif
                @if($project->budget_min || $project->budget_max)
                    <div class="bp-mini-stat">
                        <span class="bp-mini-k">بودجه</span>
                        <span class="bp-mini-v">
                            @if($project->budget_min && $project->budget_max)
                                {{ number_format($project->budget_min) }} - {{ number_format($project->budget_max) }} تومان
                            @elseif($project->budget_min)
                                از {{ number_format($project->budget_min) }} تومان
                            @else
                                تا {{ number_format($project->budget_max) }} تومان
                            @endif
                        </span>
                    </div>
                @endif
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">بازدید</span>
                    <span class="bp-mini-v">{{ $project->view_count }}</span>
                </div>
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">تاریخ ثبت</span>
                    <span class="bp-mini-v">{{ $project->created_at }}</span>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bp-det-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
    @media (max-width: 980px) { .bp-det-grid { grid-template-columns: 1fr; } }

    .bp-hero-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-hero-band { background: var(--bp-navy); position: relative; overflow: hidden; padding: 24px; }
    .bp-hero-band .grid-bg { position: absolute; inset: 0; opacity: .55; }
    .bp-hero-tags { position: relative; display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
    .bp-hero-band h1 { position: relative; color: #fff; font-size: 1.45rem; font-weight: 900; line-height: 1.4; margin: 0; }
    .bp-hero-meta { position: relative; display: flex; flex-wrap: wrap; gap: 18px; margin-top: 14px; color: rgba(255,255,255,.7); font-size: .85rem; }
    .bp-hero-meta span { display: flex; align-items: center; gap: 6px; }
    .bp-hero-body { padding: 24px; }
    .bp-hero-body h5 { font-size: 1rem; display: flex; align-items: center; gap: 8px; margin: 0 0 10px; }
    .bp-hero-body h5 i { color: var(--bp-blue); }
    .bp-hero-body p { color: var(--bp-text); line-height: 1.9; font-size: .95rem; margin: 0; }
    .bp-hr { height: 1px; background: var(--bp-hair); margin: 22px 0; }

    .bp-kv { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; }
    @media (max-width: 560px) { .bp-kv { grid-template-columns: 1fr; } }
    .bp-kv-item { display: flex; align-items: center; gap: 12px; padding: 14px; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); }
    .bp-kv-ic { width: 40px; height: 40px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex: none; }
    .bp-kv-l { font-size: .76rem; color: var(--bp-muted); }
    .bp-kv-v { font-weight: 800; color: var(--bp-ink); font-size: .95rem; }

    .bp-mini-box { border: 1px solid var(--bp-border); border-radius: var(--bp-r); padding: 10px 12px; height: 100%; }
    .bp-file-row { display: flex; align-items: center; gap: 8px; border: 1px solid var(--bp-border); border-radius: var(--bp-r); padding: 10px 14px; color: var(--bp-text); text-decoration: none; transition: border-color .2s, background .2s; }
    .bp-file-row:hover { border-color: var(--bp-blue); background: var(--bp-tint-blue); color: var(--bp-blue); }

    .bp-panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
    .bp-ph h5 { font-size: 1rem; font-weight: 700; }
    .bp-pb-table { padding: 8px 20px 20px; }
    .table.bp-table thead th { color: var(--bp-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; border-bottom: 1px solid var(--bp-hair); }
    .table.bp-table tbody tr:hover { background: var(--bp-tint-blue); }
    .table.bp-table tbody td { border-bottom: 1px solid var(--bp-hair); }

    .bp-aside-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 20px; }
    .bp-aside-card h6 { font-size: .92rem; margin-bottom: 14px; font-weight: 700; }
    .bp-mini-stat { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--bp-hair); font-size: .85rem; }
    .bp-mini-stat:last-child { border-bottom: 0; }
    .bp-mini-k { color: var(--bp-muted); }
    .bp-mini-v { font-weight: 700; color: var(--bp-ink); text-align: left; }
    </style>
@endsection
