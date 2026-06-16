@extends('layouts.master')

@section('title', 'ثبت تیکت جدید')

@section('content')
    <x-admin.breadcrumb title="ثبت تیکت جدید" parent="تیکت‌ها" parentUrl="{{ route('user.tickets.index') }}"/>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">ثبت تیکت</h5>
        </div>
        <div class="card-body">
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
@endsection
