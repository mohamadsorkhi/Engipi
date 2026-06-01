@extends('layouts.master')

@section('title', 'پیام‌ها')

@section('content')
    <x-admin.breadcrumb title="پیام‌ها" parent="داشبورد" parentUrl="{{ route('user.dashboard') }}"/>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">صندوق پیام‌ها</h5>
        </div>
        <div class="card-body p-0">
            @if($conversations->isEmpty())
                <div class="alert alert-info text-center m-3 mb-0">هنوز پیامی ندارید.</div>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($conversations as $conv)
                        <li class="list-group-item list-group-item-action px-4 py-3">
                            <a href="{{ route('user.messages.show', $conv->other) }}" class="text-decoration-none text-dark d-flex align-items-center gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-18">
                                        {{ mb_substr($conv->other->first_name ?? '؟', 0, 1) }}
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">{{ $conv->other->full_name }}</span>
                                        <span class="text-muted small">{{ $conv->latest->created_at }}</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <p class="text-muted small mb-0 text-truncate flex-grow-1">
                                            {{ Str::limit($conv->latest->body, 80) }}
                                        </p>
                                        @if($conv->unread > 0)
                                            <span class="badge bg-danger rounded-pill flex-shrink-0">{{ $conv->unread }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection
