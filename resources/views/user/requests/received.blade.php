@extends('layouts.master')

@section('title', 'درخواست‌های دریافتی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">درخواست‌های دریافتی</h5>
                </div>
                <div class="card-body">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز درخواستی برای پروژه‌های شما ارسال نشده است.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle mb-0">
                                <thead class="text-muted table-light">
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
@endsection
