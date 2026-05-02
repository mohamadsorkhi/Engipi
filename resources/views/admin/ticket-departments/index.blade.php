@extends('layouts.master')

@section('title', 'دپارتمان‌های تیکت')

@section('content')
    <x-admin.breadcrumb title="دپارتمان‌های تیکت" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}"/>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">مدیریت دپارتمان‌ها</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">ایجاد دپارتمان</button>
        </div>
        <div class="card-body">
            <div class="ajax-table">
                @include('admin.ticket-departments.components.table_and_pagination', ['departments' => $departments])
            </div>
        </div>
    </div>

    @include('admin.ticket-departments.components.modals')
@endsection
