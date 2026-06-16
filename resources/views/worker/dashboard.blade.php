@extends('layouts.master')

@section('title', 'داشبورد متخصص')

@section('content')
    <x-admin.breadcrumb title="داشبورد" parent="پنل متخصص" />

    <!-- Welcome Message -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">روز بخیر، {{ Auth::user()->name }}!</h4>
                    <p class="text-muted mb-0">خلاصه وضعیت کاربری شما</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <a href="{{ route('specialist.skills.index') }}" class="btn btn-primary">
                        <i class="ri-star-line align-bottom me-1"></i> مدیریت مهارت‌ها
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">پروژه‌های پیشنهادی</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $stats['matched_projects'] }}">{{ $stats['matched_projects'] }}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                    <i class="ri-lightbulb-flash-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">کل درخواست‌های ارسالی</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $stats['sent_requests'] }}">{{ $stats['sent_requests'] }}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-4">
                                    <i class="ri-send-plane-2-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">درخواست‌های پذیرفته شده</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ $stats['accepted_requests'] }}">{{ $stats['accepted_requests'] }}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-4">
                                    <i class="ri-check-double-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Matched Projects -->
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                 <h4 class="card-title mb-0">آخرین پروژه‌های پیشنهادی</h4>
                 <a href="{{ route('specialist.matched-projects.index') }}" class="btn btn-soft-primary btn-sm">مشاهده همه</a>
            </div>

            @if($recentMatchedProjects->isEmpty())
                 <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info text-center mb-0">
                            در حال حاضر پروژه‌ای متناسب با مهارت‌های شما یافت نشده است.
                        </div>
                    </div>
                </div>
            @else
                @foreach($recentMatchedProjects as $project)
                    <x-project-card :project="$project" :is-owner="false" />
                @endforeach
            @endif
        </div>
    </div>
@endsection
