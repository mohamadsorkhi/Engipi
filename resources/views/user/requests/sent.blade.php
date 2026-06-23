@extends('layouts.master')

@section('title', 'درخواست‌های ارسالی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="bp-panel2">
                <div class="bp-ph">
                    <h5 class="mb-0"><i class="ri-send-plane-2-line text-primary me-2"></i>درخواست‌های ارسالی</h5>
                    <a href="{{ route('user.matched-projects.index') }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-search-line me-1"></i> جستجوی پروژه
                    </a>
                </div>
                <div class="bp-pb-grid">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز درخواستی ارسال نکرده‌اید.
                            <a href="{{ route('user.matched-projects.index') }}" class="alert-link">پروژه‌های پیشنهادی را ببینید</a>
                        </div>
                    @else
                        <div class="bp-proj-grid">
                            @foreach($requests as $request)
                                <div class="bp-proj">
                                    <div class="bp-proj-top">
                                        <div>
                                            <a href="{{ route('user.matched-projects.show', $request->project) }}" class="bp-pname">
                                                {{ $request->project->title }}
                                            </a>
                                            <div class="bp-pemp">
                                                <i class="ri-user-line"></i>{{ $request->project->employer->name ?? '-' }}
                                            </div>
                                        </div>
                                        <x-request-status-badge :status="$request->status" />
                                    </div>

                                    @if($request->message)
                                        <p class="bp-pdesc">
                                            <strong>پیام شما:</strong> {{ Str::limit($request->message, 100) }}
                                        </p>
                                    @endif

                                    <div class="bp-proj-foot">
                                        <span class="bp-pdate">
                                            <i class="ri-calendar-line"></i>{{ $request->created_at }}
                                        </span>
                                        <span class="badge bg-primary-subtle text-primary">{{ $request->project->domains->pluck('name')->join('، ') ?: '-' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $requests->links() }}
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
    .bp-pdesc { color: var(--bp-muted); font-size: .9rem; line-height: 1.7; margin-bottom: 0; }
    .bp-proj-foot { display: flex; align-items: center; justify-content: space-between; margin-top: 14px; }
    .bp-pdate { color: var(--bp-muted); font-size: .8rem; display: flex; align-items: center; gap: 5px; }
    </style>
@endsection
