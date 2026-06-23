@extends('layouts.master')

@section('title', 'ثبت تیکت جدید')

@section('content')
    <x-admin.breadcrumb title="ثبت تیکت جدید" parent="تیکت‌ها" parentUrl="{{ route('user.tickets.index') }}"/>

    <div class="bp-fcard">
        <div class="bp-fh">
            <div class="bp-fh-icon"><i class="ri-customer-service-2-line"></i></div>
            <h5>ثبت تیکت</h5>
        </div>
        <div class="bp-fb">
            <form action="{{ route('user.tickets.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">دپارتمان (اختیاری)</label>
                    <select name="department_id" class="form-select">
                        <option value="">انتخاب کنید...</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}">{{ $dep->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"><span></span></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">عنوان <span class="text-danger">*</span></label>
                    <input type="text" name="subject" class="form-control" required minlength="5" maxlength="255" placeholder="مثال: مشکل در ثبت پروژه">
                    <div class="form-text">حداقل ۵ کاراکتر</div>
                    <div class="invalid-feedback"><span></span></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">متن پیام <span class="text-danger">*</span></label>
                    <textarea name="message" class="form-control" rows="6" required minlength="10" placeholder="مشکل خود را کامل توضیح دهید..."></textarea>
                    <div class="form-text">حداقل ۱۰ کاراکتر</div>
                    <div class="invalid-feedback"><span></span></div>
                </div>

                <button type="submit" class="btn btn-primary ajax-submit">
                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                    ثبت تیکت
                </button>
            </form>
        </div>
    </div>

    <style>
    .bp-fcard { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
    .bp-fh { padding: 16px 24px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; gap: 10px; }
    .bp-fh-icon { width: 34px; height: 34px; border-radius: var(--bp-r); background: var(--bp-tint-blue); color: var(--bp-blue); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex: none; }
    .bp-fh h5 { font-size: 1rem; font-weight: 700; margin: 0; }
    .bp-fb { padding: 24px; }
    </style>
@endsection
