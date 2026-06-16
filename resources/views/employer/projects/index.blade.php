@extends('layouts.master')

@section('title', 'مدیریت پروژه‌ها')

@section('content')
    <x-admin.breadcrumb title="پروژه‌های من" parent="داشبورد" parentUrl="{{ route('employer.dashboard') }}" />

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="card-title mb-0">لیست پروژه‌های شما</h4>
        <a href="{{ route('employer.projects.create') }}" class="btn btn-primary">
            <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه جدید
        </a>
    </div>

    <div class="row">
        @if($projects->isEmpty())
            <div class="col-lg-12">
                 <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info text-center mb-0">
                            شما هنوز هیچ پروژه‌ای ثبت نکرده‌اید.
                        </div>
                    </div>
                </div>
            </div>
        @else
            @foreach($projects as $project)
                <div class="col-lg-12">
                    <x-project-card :project="$project" :is-owner="true" />
                </div>
            @endforeach
        @endif
    </div>
@endsection
