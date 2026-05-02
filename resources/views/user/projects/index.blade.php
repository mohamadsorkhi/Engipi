@extends('layouts.master')

@section('title', 'پروژه‌های من')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">پروژه‌های من</h5>
                    <a href="{{ route('user.projects.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه جدید
                    </a>
                </div>
                <div class="card-body">
                    <div id="table-container">
                        @include('user.projects.components.table_and_pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
