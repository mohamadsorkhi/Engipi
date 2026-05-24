@extends('layouts.master-without-nav')

@section('title', 'ثبت پروژه — قبل از عضویت')

@section('content')

<div class="auth-page-wrapper pt-5">

    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>
        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                 xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <div class="auth-page-content">
        <div class="container">

            @include('auth.partials.auth-header')

            <div class="row justify-content-center mb-4">
                <div class="col-md-9 col-lg-7 col-xl-6">

                    {{-- progress badge --}}
                    <div class="text-center mb-3">
                        <span class="badge bg-primary-subtle text-primary fs-12 px-3 py-2">
                            <i class="ri-list-check-2 me-1"></i>
                            مرحله ۱ از ۲ — اطلاعات پروژه
                        </span>
                    </div>

                    <div class="card mt-2">
                        <div class="card-body p-4">

                            <div class="text-center mt-2 mb-4">
                                <div class="avatar-sm mx-auto mb-3">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                        <i class="ri-briefcase-line"></i>
                                    </span>
                                </div>
                                <h5 class="text-primary">پروژه‌ام را ثبت می‌کنم</h5>
                                <p class="text-muted small mb-0">
                                    اطلاعات پروژه را وارد کنید. بعد از ثبت‌نام، پروژه شما به صورت خودکار ایجاد می‌شود.
                                </p>
                            </div>

                            <form action="{{ route('guest.project.store') }}" method="POST">
                                @csrf

                                {{-- title --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        عنوان پروژه <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}"
                                        placeholder="مثلاً: شبیه‌سازی اجزاء محدود در ANSYS"
                                        required
                                        maxlength="191"
                                    >
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- description --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        توضیحات <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control @error('description') is-invalid @enderror"
                                        placeholder="شرح مختصری از پروژه، اهداف و نیازمندی‌ها..."
                                        required
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- work type --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        نوع همکاری <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="work_type"
                                        class="form-select @error('work_type') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">انتخاب کنید</option>
                                        <option value="remote"  {{ old('work_type') === 'remote'  ? 'selected' : '' }}>دورکاری</option>
                                        <option value="onsite"  {{ old('work_type') === 'onsite'  ? 'selected' : '' }}>حضوری</option>
                                        <option value="hybrid"  {{ old('work_type') === 'hybrid'  ? 'selected' : '' }}>ترکیبی</option>
                                    </select>
                                    @error('work_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- budget (optional) --}}
                                <div class="row g-2 mb-4">
                                    <div class="col-6">
                                        <label class="form-label">حداقل بودجه (تومان)</label>
                                        <input
                                            type="number"
                                            name="budget_min"
                                            class="form-control @error('budget_min') is-invalid @enderror"
                                            value="{{ old('budget_min') }}"
                                            min="0"
                                            placeholder="اختیاری"
                                        >
                                        @error('budget_min')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">حداکثر بودجه (تومان)</label>
                                        <input
                                            type="number"
                                            name="budget_max"
                                            class="form-control @error('budget_max') is-invalid @enderror"
                                            value="{{ old('budget_max') }}"
                                            min="0"
                                            placeholder="اختیاری"
                                        >
                                        @error('budget_max')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    ادامه و ثبت‌نام
                                </button>

                            </form>

                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('register') }}" class="text-muted small">
                            <i class="ri-arrow-right-line me-1"></i>
                            بازگشت به ثبت‌نام معمولی
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

    @include('auth.partials.auth-footer')

</div>

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
@endsection
