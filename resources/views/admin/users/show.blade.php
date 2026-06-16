@extends('layouts.master')

@section('title', 'جزئیات کاربر')

@section('content')
    <x-admin.breadcrumb title="جزئیات کاربر" parent="مدیریت کاربران" parentUrl="{{ route('admin.users.index') }}"/>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">اطلاعات کاربر</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">نام</span>
                            <span class="fw-medium">{{ $user->full_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">ایمیل</span>
                            <span class="fw-medium">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">موبایل</span>
                            <span class="fw-medium">{{ $user->mobile ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">وضعیت</span>
                            <span class="fw-medium">{{ $user->active ? 'فعال' : 'غیرفعال' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">تاریخ عضویت</span>
                            <span class="fw-medium">{{ $user->created_at }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">پروفایل‌ها</h5>
                </div>
                <div class="card-body">
                    @if($user->profiles->isEmpty())
                        <div class="alert alert-info text-center mb-0">پروفایلی ثبت نشده است.</div>
                    @else
                        <div class="d-flex flex-column gap-2">
                            @foreach($user->profiles as $profile)
                                <div class="border rounded p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-medium">{{ $profile->type === 'employer' ? 'کارفرما' : 'متخصص' }}</div>
                                        <div class="text-muted small">{{ $profile->id }}</div>
                                    </div>
                                    @if($profile->type === 'employer')
                                        <div class="mt-2 text-muted">نام شرکت: {{ $profile->company_name ?? '-' }}</div>
                                    @else
                                        <div class="mt-2 text-muted">عنوان: {{ $profile->headline ?? '-' }}</div>
                                        <div class="mt-2 text-muted">حوزه تخصصی: {{ $profile->domain?->name ?? '-' }}</div>
                                        @if($profile->processes->isNotEmpty())
                                            <div class="mt-2">
                                                <div class="text-muted mb-1">پردازش‌ها:</div>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($profile->processes as $process)
                                                        <span class="badge bg-primary-subtle text-primary">{{ $process->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌ها</h5>
                    <a href="{{ route('admin.projects.index') }}" class="btn btn-soft-primary btn-sm">لیست پروژه‌ها</a>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <div class="alert alert-info text-center mb-0">پروژه‌ای ثبت نشده است.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>عنوان</th>
                                    <th>حوزه</th>
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($projects as $project)
                                    <tr>
                                        <td class="fw-medium">{{ $project->title }}</td>
                                        <td>{{ $project->domain?->name ?? '-' }}</td>
                                        <td class="text-muted">{{ $project->created_at }}</td>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-soft-primary btn-sm">
                                                <i class="ri-eye-line align-bottom"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">درخواست‌ها</h5>
                </div>
                <div class="card-body">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">درخواستی ثبت نشده است.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="table-light">
                                <tr>
                                    <th>پروژه</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($requests as $req)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.projects.show', $req->project) }}" class="fw-medium text-primary">
                                                {{ $req->project?->title ?? '-' }}
                                            </a>
                                        </td>
                                        <td><x-request-status-badge :status="$req->status" /></td>
                                        <td class="text-muted">{{ $req->created_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
