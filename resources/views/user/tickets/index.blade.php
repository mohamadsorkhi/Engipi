@extends('layouts.master')

@section('title', 'تیکت‌ها')

@section('content')
    <x-admin.breadcrumb title="تیکت‌ها" parent="داشبورد" parentUrl="{{ route('user.dashboard') }}"/>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">لیست تیکت‌ها</h5>
            <a href="{{ route('user.tickets.create') }}" class="btn btn-primary btn-sm">ثبت تیکت جدید</a>
        </div>
        <div class="card-body">
            @if($tickets->isEmpty())
                <div class="alert alert-info text-center mb-0">تیکتی یافت نشد.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-borderless table-centered align-middle mb-0">
                        <thead class="table-light">
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
                                        <span class="badge bg-success">باز</span>
                                    @else
                                        <span class="badge bg-secondary">بسته</span>
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
@endsection
