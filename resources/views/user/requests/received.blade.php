@extends('layouts.master')

@section('title', 'درخواست‌های دریافتی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="bp-panel2">
                <div class="bp-ph">
                    <h5 class="mb-0"><i class="ri-inbox-line text-primary me-2"></i>درخواست‌های دریافتی</h5>
                </div>
                <div class="bp-pb-table">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0 mt-3">
                            <i class="ri-information-line me-2"></i>
                            هنوز درخواستی برای پروژه‌های شما ارسال نشده است.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle bp-table mb-0">
                                <thead class="text-muted">
                                    <tr>
                                        <th>پروژه</th>
                                        <th>متخصص</th>
                                        <th>پیام</th>
                                        <th>وضعیت</th>
                                        <th>تاریخ</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <a href="{{ route('user.projects.show', $request->project) }}" class="fw-medium text-primary">
                                                    {{ Str::limit($request->project->title, 30) }}
                                                </a>
                                            </td>
                                            <td class="fw-medium">{{ $request->user->name }}</td>
                                            <td>{{ Str::limit($request->message, 40) ?: '-' }}</td>
                                            <td>
                                                <x-request-status-badge :status="$request->status" />
                                            </td>
                                            <td class="text-muted">{{ $request->created_at }}</td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <div class="d-flex gap-1">
                                                        <form action="{{ route('user.requests.accept', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-soft-success btn-sm ajax-submit" title="پذیرش">
                                                                <i class="ri-check-line"></i> پذیرش
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('user.requests.reject', $request) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" title="رد">
                                                                <i class="ri-close-line"></i> رد
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <form action="{{ route('user.requests.revert', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-soft-warning btn-sm ajax-submit" title="بازگردانی">
                                                            <i class="ri-refresh-line"></i> بازگردانی
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
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
    </style>
@endsection
