@extends('layouts.master')

@section('title', 'جزئیات تیکت')

@section('content')
    <x-admin.breadcrumb title="جزئیات تیکت" parent="تیکت‌ها" parentUrl="{{ route('user.tickets.index') }}"/>

    <div class="row">
        <div class="col-lg-8">
            <div class="bp-conv">
                <div class="bp-ch">
                    <div>
                        <h4 class="mb-0"><i class="ri-customer-service-2-line text-primary me-2"></i>{{ $ticket->subject }}</h4>
                        <div class="bp-ch-sub">دپارتمان: {{ $ticket->department?->name ?? '-' }}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($ticket->status === 'open')
                            <span class="bp-st bp-st-open"><i class="ri-checkbox-blank-circle-fill"></i>باز</span>
                            <form action="{{ route('user.tickets.close', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از بستن تیکت اطمینان دارید؟">
                                    بستن
                                </button>
                            </form>
                        @else
                            <span class="bp-st bp-st-closed"><i class="ri-checkbox-circle-line"></i>بسته</span>
                            <form action="{{ route('user.tickets.reopen', $ticket) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-soft-success btn-sm ajax-submit">
                                    باز کردن مجدد
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="bp-conv-body">
                    @foreach($ticket->messages as $msg)
                        @php
                            $isAdmin = (bool) $msg->admin_id;
                            $name = $isAdmin ? ($msg->admin?->full_name ?? 'پشتیبانی') : 'شما';
                        @endphp

                        <div class="bp-msg {{ $isAdmin ? 'bp-msg-agent' : 'bp-msg-me' }}">
                            <div class="bp-msg-av">{{ mb_substr($name, 0, 1) }}</div>
                            <div class="bp-msg-bub">
                                <div class="bp-msg-nm">
                                    {{ $name }}
                                    @if($isAdmin)
                                        <span class="bp-agent-tag"><i class="ri-customer-service-2-fill"></i>پشتیبانی</span>
                                    @endif
                                </div>
                                <div class="bp-msg-tx">{!! nl2br(e($msg->message)) !!}</div>
                                <div class="bp-msg-tm">{{ $msg->created_at }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bp-composer">
                    @if($ticket->status !== 'open')
                        <div class="alert alert-warning mb-0 text-center w-100">این تیکت بسته شده است.</div>
                    @else
                        <form action="{{ route('user.tickets.message', $ticket) }}" method="POST" class="w-100">
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
            <div class="bp-aside-card">
                <h6>اطلاعات تیکت</h6>
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">وضعیت</span>
                    <span class="bp-mini-v">{{ $ticket->status === 'open' ? 'باز' : 'بسته' }}</span>
                </div>
                @if($ticket->closed_by)
                    <div class="bp-mini-stat">
                        <span class="bp-mini-k">بسته شده توسط</span>
                        <span class="bp-mini-v">{{ $ticket->closed_by === 'admin' ? 'ادمین' : 'کاربر' }}</span>
                    </div>
                @endif
                @if($ticket->closed_at)
                    <div class="bp-mini-stat">
                        <span class="bp-mini-k">تاریخ بستن</span>
                        <span class="bp-mini-v">{{ $ticket->closed_at }}</span>
                    </div>
                @endif
                <div class="bp-mini-stat">
                    <span class="bp-mini-k">تاریخ ایجاد</span>
                    <span class="bp-mini-v">{{ $ticket->created_at }}</span>
                </div>
            </div>
        </div>
    </div>

    <style>
    .bp-conv { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); display: flex; flex-direction: column; min-height: 560px; }
    .bp-ch { padding: 18px 22px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .bp-ch h4 { font-size: 1.05rem; font-weight: 800; }
    .bp-ch-sub { font-size: .8rem; color: var(--bp-muted); font-family: ui-monospace, monospace; margin-top: 3px; }
    .bp-conv-body { padding: 22px; flex: 1; display: flex; flex-direction: column; gap: 18px; background: var(--bp-surface); max-height: 520px; overflow: auto; }

    .bp-msg { display: flex; gap: 12px; max-width: 80%; }
    .bp-msg-av { width: 38px; height: 38px; border-radius: var(--bp-r); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; flex: none; font-size: .9rem; background: var(--bp-teal); }
    .bp-msg-bub { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 12px 15px; }
    .bp-msg-nm { font-size: .78rem; font-weight: 700; color: var(--bp-ink); margin-bottom: 5px; display: flex; align-items: center; gap: 6px; }
    .bp-msg-tx { font-size: .9rem; color: var(--bp-text); line-height: 1.8; }
    .bp-msg-tm { font-size: .7rem; color: var(--bp-muted); margin-top: 6px; font-family: ui-monospace, monospace; }
    .bp-msg-me { margin-inline-start: auto; flex-direction: row-reverse; }
    .bp-msg-me .bp-msg-av { background: var(--bp-blue); }
    .bp-msg-me .bp-msg-bub { background: var(--bp-blue); border-color: var(--bp-blue); }
    .bp-msg-me .bp-msg-nm, .bp-msg-me .bp-msg-tx { color: #fff; }
    .bp-msg-me .bp-msg-tm { color: rgba(255,255,255,.7); }
    .bp-agent-tag { display: inline-flex; align-items: center; gap: 5px; font-size: .72rem; color: var(--bp-teal); font-weight: 700; }

    .bp-composer { padding: 16px 20px; border-top: 1px solid var(--bp-hair); }

    .bp-st { display: inline-flex; align-items: center; gap: 4px; font-size: .72rem; font-weight: 700; padding: 3px 9px; border-radius: var(--bp-r); }
    .bp-st-open { background: var(--bp-tint-green); color: var(--bp-c-green); }
    .bp-st-closed { background: var(--bp-surface); color: var(--bp-muted); border: 1px solid var(--bp-border); }

    .bp-aside-card { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); padding: 20px; }
    .bp-aside-card h6 { font-size: .92rem; margin-bottom: 14px; font-weight: 700; }
    .bp-mini-stat { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--bp-hair); font-size: .85rem; }
    .bp-mini-stat:last-child { border-bottom: 0; }
    .bp-mini-k { color: var(--bp-muted); }
    .bp-mini-v { font-weight: 700; color: var(--bp-ink); text-align: left; }
    </style>
@endsection
