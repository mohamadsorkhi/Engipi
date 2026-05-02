@extends('layouts.master')

@section('title', 'تیکت‌ها')

@section('content')
    <x-admin.breadcrumb title="تیکت‌ها" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}"/>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">مدیریت تیکت‌ها</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.tickets.index') }}" method="GET" id="ticket-filter-form" class="mb-3">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <label for="ticket-q" class="form-label">جستجو</label>
                        <input type="text" name="q" id="ticket-q" class="form-control" value="{{ request('q') }}" placeholder="عنوان تیکت یا اطلاعات کاربر">
                    </div>
                    <div class="col-md-3">
                        <label for="ticket-status" class="form-label">وضعیت</label>
                        <select name="status" id="ticket-status" class="form-select" onchange="$('#ticket-filter-form button[type=submit]').click()">
                            <option value="">همه</option>
                            <option value="open" @selected(request('status') == 'open')>باز</option>
                            <option value="closed" @selected(request('status') == 'closed')>بسته</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="ticket-department" class="form-label">دپارتمان</label>
                        <select name="department_id" id="ticket-department" class="form-select" onchange="$('#ticket-filter-form button[type=submit]').click()">
                            <option value="">همه</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary ajax-submit">اعمال</button>
                        <a href="{{ route('admin.tickets.index') }}" class="btn btn-light">پاک</a>
                    </div>
                </div>
            </form>

            <div class="ajax-table">
                @include('admin.tickets.components.table_and_pagination', ['tickets' => $tickets])
            </div>
        </div>
    </div>
@endsection
