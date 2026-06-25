@extends('layouts.master')

@section('title', 'پروژه‌های من')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="bp-panel2">
                <div class="bp-ph">
                    <h5 class="mb-0"><i class="ri-briefcase-line text-primary me-2"></i>پروژه‌های من</h5>
                    <a href="{{ route('user.projects.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line align-bottom me-1"></i> ثبت پروژه جدید
                    </a>
                </div>
                <div class="bp-pb-table">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <i class="ri-checkbox-circle-line me-1"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <div id="table-container">
                        @include('user.projects.components.table_and_pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bp-panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
    .bp-ph h5 { font-size: 1rem; font-weight: 700; }
    .bp-pb-table { padding: 8px 20px 20px; }
    .bp-pb-table .alert { margin: 12px 0 0; }
    .table.bp-table thead th {
        color: var(--bp-muted);
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        border-bottom: 1px solid var(--bp-hair);
    }
    .table.bp-table tbody tr { transition: background .15s; }
    .table.bp-table tbody tr:hover { background: var(--bp-tint-blue); }
    .table.bp-table tbody td { border-bottom: 1px solid var(--bp-hair); }
    </style>
@endsection
