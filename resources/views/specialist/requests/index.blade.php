@extends('layouts.master')

@section('title', 'درخواست‌های من')

@section('content')
    <x-admin.breadcrumb title="درخواست‌های من" parent="داشبورد" parentUrl="{{ route('specialist.dashboard') }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">درخواست‌های همکاری ارسال شده</h5>
                </div>
                <div class="card-body">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-mail-line me-2"></i>
                            شما هنوز درخواست همکاری ارسال نکرده‌اید.
                            <a href="{{ route('specialist.matched-projects.index') }}" class="alert-link">مشاهده پروژه‌های منطبق</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>پروژه</th>
                                        <th>کارفرما</th>
                                        <th>پیام</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ ارسال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <a href="{{ route('specialist.matched-projects.show', $request->project) }}" class="fw-medium link-primary">
                                                    {{ Str::limit($request->project->title, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $request->project->employer->name ?? '-' }}</td>
                                            <td>{{ Str::limit($request->message, 50) ?: '-' }}</td>
                                            <td>
                                                @switch($request->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning-subtle text-warning">در انتظار بررسی</span>
                                                        @break
                                                    @case('accepted')
                                                        <span class="badge bg-success-subtle text-success">پذیرفته شده</span>
                                                        @break
                                                    @case('rejected')
                                                        <span class="badge bg-danger-subtle text-danger">رد شده</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ $request->jalali_created_at }}</td>
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
