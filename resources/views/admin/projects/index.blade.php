@extends('layouts.master')

@section('title', 'مدیریت پروژه‌ها')

@section('content')
    <x-admin.breadcrumb title="مدیریت پروژه‌ها" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}"/>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست پروژه‌ها</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.projects.index') }}" method="GET" id="project-filter-form">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="project-q" class="form-label">جستجو</label>
                                <input type="text" name="q" id="project-q" class="form-control" value="{{ request('q') }}" placeholder="عنوان، شناسه یا کارفرما">
                            </div>
                            <div class="col-md-3">
                                <label for="work-type" class="form-label">نوع همکاری</label>
                                <select name="work_type" id="work-type" class="form-select" onchange="$('#project-filter-form button[type=submit]').click()">
                                    <option value="">همه</option>
                                    <option value="remote" @selected(request('work_type') == 'remote')>{{ __('project.work_type.remote') }}</option>
                                    <option value="onsite" @selected(request('work_type') == 'onsite')>{{ __('project.work_type.onsite') }}</option>
                                    <option value="hybrid" @selected(request('work_type') == 'hybrid')>{{ __('project.work_type.hybrid') }}</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_domains" id="project-has-domains" value="1" @checked(request('has_domains')) onchange="$('#project-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="project-has-domains">دارای حوزه</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="has_processes" id="project-has-processes" value="1" @checked(request('has_processes')) onchange="$('#project-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="project-has-processes">دارای فرآیند</label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary ajax-submit">اعمال</button>
                                <a href="{{ route('admin.projects.index') }}" class="btn btn-light">پاک کردن</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="ajax-table">
                    @include('admin.projects.components.table_and_pagination', ['projects' => $projects])
                </div>
            </div>
        </div>
    </div>

@endsection
