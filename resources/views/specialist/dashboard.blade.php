@extends('layouts.master')

@section('title', 'داشبورد متخصص')

@section('content')
    <x-admin.breadcrumb title="داشبورد" />

    <div class="row">
        <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-4 row-cols-md-2 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">پروژه‌های منطبق</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-briefcase-line display-6 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $stats['matched_projects'] }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">درخواست‌های ارسالی</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-mail-send-line display-6 text-info"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $stats['sent_requests'] }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">درخواست‌های پذیرفته شده</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ri-checkbox-circle-line display-6 text-success"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="{{ $stats['accepted_requests'] }}">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">پروژه‌های منطبق اخیر</h5>
                    <a href="{{ route('specialist.matched-projects.index') }}" class="btn btn-soft-primary btn-sm">
                        مشاهده همه <i class="ri-arrow-left-line align-middle"></i>
                    </a>
                </div>
                <div class="card-body">
                    @if($recentMatchedProjects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            @if(!$profile || !$profile->processes()->exists())
                                <i class="ri-information-line me-2"></i>
                                برای مشاهده پروژه‌های منطبق، ابتدا مهارت‌های خود را تکمیل کنید.
                                <a href="{{ route('specialist.skills.index') }}" class="alert-link">تکمیل مهارت‌ها</a>
                            @else
                                <i class="ri-folder-info-line me-2"></i>
                                در حال حاضر پروژه‌ای منطبق با مهارت‌های شما یافت نشد.
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>عنوان پروژه</th>
                                        <th>کارفرما</th>
                                        <th>نوع همکاری</th>
                                        <th>تطابق مهارت</th>
                                        <th>تاریخ ثبت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMatchedProjects as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('specialist.matched-projects.show', $project) }}" class="fw-medium link-primary">
                                                    {{ Str::limit($project->title, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $project->employer->name ?? '-' }}</td>
                                            <td>
                                                @switch($project->work_type)
                                                    @case('remote')
                                                        <span class="badge bg-info-subtle text-info">دورکاری</span>
                                                        @break
                                                    @case('onsite')
                                                        <span class="badge bg-warning-subtle text-warning">حضوری</span>
                                                        @break
                                                    @case('hybrid')
                                                        <span class="badge bg-primary-subtle text-primary">ترکیبی</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $project->matching_skills_count }} مهارت</span>
                                            </td>
                                            <td>{{ $project->jalali_created_at }}</td>
                                            <td>
                                                <a href="{{ route('specialist.matched-projects.show', $project) }}" class="btn btn-sm btn-soft-primary">
                                                    مشاهده
                                                </a>
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
    </div>
@endsection
