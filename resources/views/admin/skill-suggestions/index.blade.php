@extends('layouts.master')

@section('title', 'پیشنهاد مهارت‌ها')

@section('content')
    <x-admin.breadcrumb title="پیشنهاد مهارت‌ها" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}" />

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @php
        $statusLabels = [
            'pending' => ['label' => 'در انتظار بررسی', 'class' => 'warning'],
            'approved' => ['label' => 'تأیید شده', 'class' => 'success'],
            'rejected' => ['label' => 'رد شده', 'class' => 'danger'],
        ];
    @endphp

    <div class="card bp-suggestions-card">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h5 class="card-title mb-1">پیشنهاد مهارت‌ها</h5>
                <p class="text-muted small mb-0">پیشنهادهای متخصصان را بررسی و به فهرست اصلی مهارت‌ها اضافه کنید.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-2" aria-label="فیلتر نوع مهارت">
                <a class="btn btn-sm {{ $skillType === null ? 'btn-primary' : 'btn-soft-primary' }}" href="{{ route('admin.skill-suggestions.index', ['status' => $status]) }}">همه</a>
                <a class="btn btn-sm {{ $skillType === 'software' ? 'btn-primary' : 'btn-soft-primary' }}" href="{{ route('admin.skill-suggestions.index', ['status' => $status, 'skill_type' => 'software']) }}">پردازشی</a>
                <a class="btn btn-sm {{ $skillType === 'field' ? 'btn-primary' : 'btn-soft-primary' }}" href="{{ route('admin.skill-suggestions.index', ['status' => $status, 'skill_type' => 'field']) }}">میدانی</a>
            </div>
            <div class="d-flex flex-wrap gap-2" aria-label="فیلتر وضعیت پیشنهادها">
                @foreach($statusLabels as $value => $meta)
                    <a href="{{ route('admin.skill-suggestions.index', ['status' => $value, 'skill_type' => $skillType]) }}"
                       class="btn btn-sm {{ $status === $value ? 'btn-'.$meta['class'] : 'btn-soft-'.$meta['class'] }}">
                        {{ $meta['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="card-body p-0">
            @if($suggestions->isEmpty())
                <div class="text-center py-5 px-3">
                    <div class="avatar-lg mx-auto mb-3">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-2"><i class="ri-inbox-line"></i></span>
                    </div>
                    <h6>پیشنهادی در این وضعیت وجود ندارد</h6>
                    <p class="text-muted small mb-0">با ثبت پیشنهادهای جدید، موارد اینجا نمایش داده می‌شوند.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>متخصص</th>
                                <th>مهارت پیشنهادی</th>
                                <th>نوع مهارت</th>
                                <th>حوزه</th>
                                <th>توضیح</th>
                                <th>تاریخ ارسال</th>
                                <th>وضعیت</th>
                                <th class="text-end">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suggestions as $suggestion)
                                <tr>
                                    <td class="fw-semibold">{{ $suggestion->user?->full_name ?? 'کاربر حذف‌شده' }}</td>
                                    <td>{{ $suggestion->skill_name }}</td>
                                    <td><span class="badge bg-info-subtle text-info">{{ $suggestion->skill_type === 'software' ? 'پردازشی' : 'میدانی' }}</span></td>
                                    <td>
                                        <span class="d-block">{{ $suggestion->subdomain?->domain?->name ?? '—' }}</span>
                                        <small class="text-muted">{{ $suggestion->subdomain?->name ?? '—' }}</small>
                                    </td>
                                    <td class="suggestion-description">{{ $suggestion->description ?: '—' }}</td>
                                    <td>{{ $suggestion->created_at?->format('Y/m/d H:i') ?? '—' }}</td>
                                    <td><span class="badge bg-{{ $statusLabels[$suggestion->status]['class'] }}-subtle text-{{ $statusLabels[$suggestion->status]['class'] }}">{{ $statusLabels[$suggestion->status]['label'] }}</span></td>
                                    <td class="text-end">
                                        @if($suggestion->status === 'pending')
                                            <div class="d-flex justify-content-end gap-2">
                                                <form method="POST" action="{{ route('admin.skill-suggestions.approve', $suggestion) }}" onsubmit="return confirm('این پیشنهاد تأیید و به لیست مهارت‌ها اضافه شود؟')">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success" type="submit"><i class="ri-check-line me-1"></i>تأیید</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.skill-suggestions.reject', $suggestion) }}" onsubmit="return confirm('این پیشنهاد رد شود؟')">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-danger" type="submit"><i class="ri-close-line me-1"></i>رد</button>
                                                </form>
                                            </div>
                                        @else
                                            <small class="text-muted">{{ $suggestion->reviewer?->full_name ?? 'ادمین' }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if($suggestions->hasPages())
            <div class="card-footer">{{ $suggestions->links() }}</div>
        @endif
    </div>
@endsection

@push('styles')
<style>
.bp-suggestions-card { border: 1px solid var(--bp-border); box-shadow: var(--bp-sh-sm); }
.suggestion-description { min-width: 220px; max-width: 340px; white-space: normal; line-height: 1.8; }
@media (max-width: 767.98px) {
    .bp-suggestions-card .card-header .btn { flex: 1 1 auto; }
    .bp-suggestions-card .table { min-width: 980px; }
}
</style>
@endpush