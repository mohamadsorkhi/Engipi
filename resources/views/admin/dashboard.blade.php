@extends('layouts.master')

@section('title', 'داشبورد ادمین')

@section('content')
    <x-admin.breadcrumb title="داشبورد" parent="پنل ادمین" />

    <!-- Stat Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">تعداد کارفرماها</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $stats['total_employers'] }}">{{ $stats['total_employers'] }}</span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                                <i class="ri-user-add-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">تعداد متخصص‌ها</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $stats['total_workers'] }}">{{ $stats['total_workers'] }}</span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-info-subtle text-info rounded-circle fs-4">
                                <i class="ri-user-star-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">تعداد پروژه‌ها</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $stats['total_projects'] }}">{{ $stats['total_projects'] }}</span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-4">
                                <i class="ri-briefcase-2-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">تعداد درخواست‌ها</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $stats['total_requests'] }}">{{ $stats['total_requests'] }}</span></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-4">
                                <i class="ri-inbox-archive-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects & Users -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">آخرین پروژه‌ها</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.projects.index') }}" class="btn btn-soft-primary btn-sm">مشاهده همه <i class="ri-arrow-left-s-line align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-nowrap table-hover align-middle mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th>عنوان پروژه</th>
                                    <th>کارفرما</th>
                                    <th>نوع همکاری</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProjects as $project)
                                    <tr>
                                        <td>{{ $project->title }}</td>
                                        <td>{{ $project->employer->full_name }}</td>
                                        <td>{{ __('project.work_type.' . $project->work_type) }}</td>
                                        <td>{{ $project->created_at->format('Y/m/d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-soft-primary btn-sm"><i class="ri-eye-line align-bottom"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">پروژه‌ای یافت نشد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">آخرین کاربران</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-soft-primary btn-sm">مشاهده همه <i class="ri-arrow-left-s-line align-middle"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table table-nowrap table-hover align-middle mb-0">
                            <thead class="text-muted table-light">
                                <tr>
                                    <th>#</th>
                                    <th>نام</th>
                                    <th>ایمیل</th>
                                    <th>نقش</th>
                                    <th>تاریخ عضویت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $user) }}" class="fw-medium text-primary">
                                                {{ $user->full_name }}
                                            </a>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->display_role }}</td>
                                        <td>{{ $user->created_at->format('Y/m/d') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">کاربری یافت نشد.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
