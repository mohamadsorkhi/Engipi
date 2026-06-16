@extends('layouts.master')

@section('title', 'جزئیات تیکت')

@section('content')
    <x-admin.breadcrumb title="جزئیات تیکت" parent="تیکت‌ها" parentUrl="{{ route('user.tickets.index') }}"/>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold">{{ $ticket->subject }}</div>
                        <div class="text-muted small">دپارتمان: {{ $ticket->department?->name ?? '-' }}</div>
                    </div>
                    <div class="d-flex gap-2">
                        @if($ticket->status === 'open')
                            <form action="{{ route('user.tickets.close', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از بستن تیکت اطمینان دارید؟">
                                    بستن
                                </button>
                            </form>
                        @else
                            <form action="{{ route('user.tickets.reopen', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-soft-success btn-sm ajax-submit">
                                    باز کردن مجدد
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body" style="max-height: 520px; overflow:auto;">
                    <div class="d-flex flex-column gap-2">
                        @foreach($ticket->messages as $msg)
                            @php
                                $isAdmin = (bool) $msg->admin_id;
                            @endphp

                            <div class="d-flex {{ $isAdmin ? 'justify-content-start' : 'justify-content-end' }}">
                                <div class="p-3 rounded" style="max-width: 80%; background: {{ $isAdmin ? '#f1f5f9' : '#e6f7ff' }}; border: 1px solid {{ $isAdmin ? '#e2e8f0' : '#b6e7ff' }};">
                                    <div class="small text-muted mb-1">
                                        {{ $isAdmin ? ($msg->admin?->full_name ?? 'پشتیبانی') : 'شما' }}
                                        - {{ $msg->created_at }}
                                    </div>
                                    <div>{!! nl2br(e($msg->message)) !!}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer">
                    @if($ticket->status !== 'open')
                        <div class="alert alert-warning mb-0 text-center">این تیکت بسته شده است.</div>
                    @else
                        <form action="{{ route('user.tickets.message', $ticket) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">پیام جدید <span class="text-danger">*</span></label>
                                <textarea name="message" class="form-control" rows="3" required minlength="1" maxlength="5000" placeholder="پیام خود را بنویسید..."></textarea>
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <button type="submit" class="btn btn-primary ajax-submit">
                                <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                ارسال
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">اطلاعات تیکت</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">وضعیت</span>
                            <span class="fw-medium">{{ $ticket->status === 'open' ? 'باز' : 'بسته' }}</span>
                        </li>
                        @if($ticket->closed_by)
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">بسته شده توسط</span>
                                <span class="fw-medium">{{ $ticket->closed_by === 'admin' ? 'ادمین' : 'کاربر' }}</span>
                            </li>
                        @endif
                        @if($ticket->closed_at)
                            <li class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">تاریخ بستن</span>
                                <span class="fw-medium">{{ $ticket->closed_at }}</span>
                            </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">تاریخ ایجاد</span>
                            <span class="fw-medium">{{ $ticket->created_at }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
