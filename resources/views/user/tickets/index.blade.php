@extends('layouts.master')

@section('title', 'تیکت‌ها')

@section('content')
    <x-admin.breadcrumb title="تیکت‌ها" parent="داشبورد" parentUrl="{{ route('user.dashboard') }}"/>

    <div class="bp-panel2">
        <div class="bp-ph">
            <h5 class="mb-0"><i class="ri-customer-service-2-line text-primary me-2"></i>لیست تیکت‌ها</h5>
            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary btn-sm">ثبت تیکت جدید</a>
        </div>
        <div class="bp-pb-table">
            @if($tickets->isEmpty())
                <div class="alert alert-info text-center mb-0 mt-3">تیکتی یافت نشد.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-borderless table-centered align-middle bp-table mb-0">
                        <thead>
                        <tr>
                            <th>عنوان</th>
                            <th>دپارتمان</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td class="fw-medium">{{ $ticket->subject }}</td>
                                <td>{{ $ticket->department?->name ?? '-' }}</td>
                                <td>
                                    @if($ticket->status === 'open')
                                        <span class="bp-st bp-st-open"><i class="ri-checkbox-blank-circle-fill"></i>باز</span>
                                    @else
                                        <span class="bp-st bp-st-closed"><i class="ri-checkbox-circle-line"></i>بسته</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $ticket->created_at }}</td>
                                <td>
                                    <a href="{{ route('user.tickets.show', $ticket) }}" class="btn btn-soft-primary btn-sm">
                                        <i class="ri-eye-line align-bottom"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $tickets->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
    .bp-panel2 { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-ph { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid var(--bp-hair); }
    .bp-ph h5 { font-size: 1rem; font-weight: 700; }
    .bp-pb-table { padding: 8px 20px 20px; }
    .table.bp-table thead th { color: var(--bp-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; border-bottom: 1px solid var(--bp-hair); }
    .table.bp-table tbody tr:hover { background: var(--bp-tint-blue); }
    .table.bp-table tbody td { border-bottom: 1px solid var(--bp-hair); }

    .bp-st { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: var(--bp-r); }
    .bp-st-open { background: var(--bp-tint-green); color: var(--bp-c-green); }
    .bp-st-closed { background: var(--bp-surface); color: var(--bp-muted); border: 1px solid var(--bp-border); }
    </style>
@endsection
