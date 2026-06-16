@extends('layouts.master')

@section('title', 'پروژه‌های منطبق')

@section('content')
    <x-admin.breadcrumb title="پروژه‌های منطبق" parent="داشبورد" parentUrl="{{ route('specialist.dashboard') }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">پروژه‌های منطبق با مهارت‌های شما</h5>
                </div>
                <div class="card-body">
                    @if($projects->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-folder-info-line me-2"></i>
                            در حال حاضر پروژه‌ای منطبق با مهارت‌های شما یافت نشد.
                            <a href="{{ route('specialist.skills.index') }}" class="alert-link">مدیریت مهارت‌ها</a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th>عنوان پروژه</th>
                                        <th>کارفرما</th>
                                        <th>حوزه تخصصی</th>
                                        <th>نوع همکاری</th>
                                        <th>تطابق مهارت</th>
                                        <th>تاریخ ثبت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>
                                                <a href="{{ route('specialist.matched-projects.show', $project) }}" class="fw-medium link-primary">
                                                    {{ Str::limit($project->title, 40) }}
                                                </a>
                                            </td>
                                            <td>{{ $project->employer->name ?? '-' }}</td>
                                            <td>{{ $project->domain->name ?? '-' }}</td>
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

                        <div class="mt-4">
                            {{ $projects->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
