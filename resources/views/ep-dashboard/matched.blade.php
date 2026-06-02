@extends('layouts.ep-dashboard')

@section('title', 'پروژه‌های پیشنهادی | EngPis')

@php
    $activePage = 'matched';
    $pageTitle  = 'پروژه‌های پیشنهادی';
@endphp

@section('content')
<div class="content">
    <div class="card">
        <div class="card-head">
            <h5>پروژه‌های پیشنهادی</h5>
            @php try { $skillsUrl = route('user.skills.index'); } catch (\Exception $e) { $skillsUrl = '#'; } @endphp
            <a href="{{ $skillsUrl }}" class="btn btn-soft-primary">
                <i class="ri-settings-3-line"></i>مدیریت مهارت‌ها
            </a>
        </div>
        <div class="card-body">
            @if(isset($projects) && $projects->count() > 0)
                <div class="proj-grid">
                    @foreach($projects as $project)
                        @php
                            $domain  = $project->domains->first();
                            $tools   = $project->processes ?? collect();
                            try { $showUrl = route('user.matched-projects.show', $project->id); } catch (\Exception $e) { $showUrl = '#'; }
                        @endphp
                        <div class="proj">
                            <div class="top">
                                <div>
                                    <div class="title">{{ $project->title }}</div>
                                    <div class="emp">
                                        <i class="ri-user-line"></i>
                                        {{ $project->employer?->name ?? 'کارفرما' }}
                                    </div>
                                </div>
                                <span class="badge b-soft-primary">دورکاری</span>
                            </div>
                            <p class="desc">{{ Str::limit($project->description, 140) }}</p>
                            <div class="tags">
                                @if($domain)
                                    <span class="badge b-soft-primary">{{ $domain->name }}</span>
                                @endif
                                @foreach($tools->take(3) as $tool)
                                    <span class="badge b-soft-info">{{ $tool->name }}</span>
                                @endforeach
                            </div>
                            <div class="foot">
                                <span class="date">
                                    <i class="ri-calendar-line"></i>
                                    {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}
                                </span>
                                <a href="{{ $showUrl }}" class="btn btn-soft-primary">مشاهده و ارسال درخواست</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($projects instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div style="margin-top:24px;">
                        {{ $projects->links() }}
                    </div>
                @endif

            @else
                <div style="text-align:center; padding:48px 20px; color:var(--ep-muted);">
                    <i class="ri-lightbulb-flash-line" style="font-size:3rem; opacity:.3; display:block; margin-bottom:12px;"></i>
                    <p style="margin:0; font-size:.95rem;">در حال حاضر پروژه‌ای متناسب با مهارت‌های شما وجود ندارد.</p>
                    @php try { $skillsUrl = route('user.skills.index'); } catch (\Exception $e) { $skillsUrl = '#'; } @endphp
                    <a href="{{ $skillsUrl }}" class="btn btn-soft-primary" style="margin-top:16px;">
                        <i class="ri-settings-3-line"></i>مدیریت مهارت‌ها
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
