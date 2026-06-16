@extends('layouts.master')

@section('title', 'مدیریت پردازش‌ها')

@section('content')
    <x-admin.breadcrumb title="پردازش‌ها" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">لیست پردازش‌ها</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProcessModal">
                        <i class="ri-add-line align-bottom me-1"></i> افزودن پردازش
                    </button>
                </div>
                <div class="card-body">
                    <div id="table-container">
                        @include('admin.processes.components.table_and_pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Process Modal -->
    <div class="modal fade" id="addProcessModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن پردازش</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.processes.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">حوزه تخصصی <span class="text-danger">*</span></label>
                            <select class="form-select" name="skill_domain_id" required>
                                <option value="">انتخاب کنید...</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نام پردازش <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary ajax-submit">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
