@extends('layouts.master')

@section('title', 'مدیریت پروفایل‌ها')

@section('content')
    <x-admin.breadcrumb title="پروفایل‌های کاربران" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست پروفایل‌ها</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profiles.index') }}" method="GET" id="profile-filter-form" class="mb-3">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="profile-q" class="form-label">جستجو</label>
                                <input type="text" name="q" id="profile-q" class="form-control" value="{{ request('q') }}" placeholder="کاربر، ایمیل، موبایل، عنوان یا شرکت">
                            </div>
                            <div class="col-md-3">
                                <label for="profile-type" class="form-label">نوع پروفایل</label>
                                <select name="type" id="profile-type" class="form-select" onchange="$('#profile-filter-form button[type=submit]').click()">
                                    <option value="">همه</option>
                                    <option value="employer" @selected(request('type') == 'employer')>کارفرما</option>
                                    <option value="specialist" @selected(request('type') == 'specialist')>متخصص</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_processes" id="has-processes" value="1" @checked(request('has_processes')) onchange="$('#profile-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="has-processes">دارای مهارت/فرآیند</label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary ajax-submit">اعمال</button>
                                <a href="{{ route('admin.profiles.index') }}" class="btn btn-light">پاک کردن</a>
                            </div>
                        </div>
                    </form>

                    <div id="table-container" class="ajax-table">
                        @include('admin.profiles.components.table_and_pagination', ['profiles' => $profiles])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
