@extends('layouts.master')

@section('title', 'انتخاب نقش')

@section('content')

@php
    $hasEmployer       = $profiles->where('type', 'employer')->isNotEmpty();
    $hasSpecialist     = $profiles->where('type', 'specialist')->isNotEmpty();
    $employerProfile   = $profiles->firstWhere('type', 'employer');
    $specialistProfile = $profiles->firstWhere('type', 'specialist');
@endphp

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        {{-- Header --}}
        <div class="text-center mb-5 mt-2">
            <h3 class="mb-1">با چه نقشی وارد می‌شوید؟</h3>
            <p class="text-muted">می‌توانید بعداً هر دو نقش را داشته باشید.</p>
        </div>

        <div class="row g-4">

            {{-- ═══════════════════════════════════════
                 EMPLOYER CARD
            ═══════════════════════════════════════ --}}
            <div class="col-md-6 d-flex">

                @if($hasEmployer)

                    {{-- Existing profile → click whole card to activate --}}
                    <form action="{{ route('profile.activate') }}" method="POST"
                          id="form-employer" class="w-100">
                        @csrf
                        <input type="hidden" name="type" value="employer">
                        <button type="submit"
                                class="w-100 h-100 border-0 bg-transparent p-0 text-start role-submit-btn">
                            <div class="card role-card role-card--employer h-100 mb-0">
                                <div class="card-body text-center p-4 p-lg-5">

                                    <div class="avatar-xl mx-auto mb-4">
                                        <span class="avatar-title bg-primary text-white rounded-circle"
                                              style="font-size:2.2rem;">
                                            <i class="ri-briefcase-4-line"></i>
                                        </span>
                                    </div>

                                    <h4 class="mb-2 fw-semibold">کارفرما هستم</h4>
                                    <p class="text-muted mb-4">
                                        پروژه‌های مهندسی ثبت کنید<br>
                                        و با متخصصین همکاری کنید
                                    </p>

                                    <span class="btn btn-primary px-4">
                                        ورود به داشبورد کارفرما
                                        <i class="ri-arrow-left-line ms-1"></i>
                                    </span>

                                </div>
                            </div>
                        </button>
                    </form>

                @else

                    {{-- No profile yet → show creation form inline --}}
                    <div class="card role-card role-card--employer-new h-100 mb-0 w-100">

                        <div class="card-body text-center p-4 p-lg-5" id="employer-cta">
                            <div class="avatar-xl mx-auto mb-4">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle"
                                      style="font-size:2.2rem;">
                                    <i class="ri-briefcase-4-line"></i>
                                </span>
                            </div>
                            <h4 class="mb-2 fw-semibold">کارفرما هستم</h4>
                            <p class="text-muted mb-4">
                                پروژه‌های مهندسی ثبت کنید<br>
                                و با متخصصین همکاری کنید
                            </p>
                            <button type="button" class="btn btn-outline-primary px-4"
                                    onclick="showForm('employer')">
                                <i class="ri-add-line me-1"></i>ایجاد پروفایل کارفرما
                            </button>
                        </div>

                        <div id="employer-form" style="display:none;"
                             class="card-body border-top pt-4">
                            <form action="{{ route('profiles.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="profile_type" value="employer">
                                <div class="mb-4 text-start">
                                    <label class="form-label fw-medium">نام شرکت
                                        <span class="text-muted fw-normal">(اختیاری)</span>
                                    </label>
                                    <input type="text" name="company_name"
                                           class="form-control" maxlength="255"
                                           placeholder="شرکت یا سازمان شما">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 ajax-submit">
                                    <span class="spinner-border spinner-border-sm me-1"
                                          role="status" style="display:none;"></span>
                                    ثبت و ورود به داشبورد
                                </button>
                                <button type="button" class="btn btn-link btn-sm w-100 mt-2 text-muted"
                                        onclick="hideForm('employer')">انصراف</button>
                            </form>
                        </div>

                    </div>

                @endif
            </div>


            {{-- ═══════════════════════════════════════
                 SPECIALIST CARD
            ═══════════════════════════════════════ --}}
            <div class="col-md-6 d-flex">

                @if($hasSpecialist)

                    {{-- Existing profile → click whole card to activate --}}
                    <form action="{{ route('profile.activate') }}" method="POST"
                          id="form-specialist" class="w-100">
                        @csrf
                        <input type="hidden" name="type" value="specialist">
                        <button type="submit"
                                class="w-100 h-100 border-0 bg-transparent p-0 text-start role-submit-btn">
                            <div class="card role-card role-card--specialist h-100 mb-0">
                                <div class="card-body text-center p-4 p-lg-5">

                                    <div class="avatar-xl mx-auto mb-4">
                                        <span class="avatar-title bg-success text-white rounded-circle"
                                              style="font-size:2.2rem;">
                                            <i class="ri-user-star-line"></i>
                                        </span>
                                    </div>

                                    <h4 class="mb-2 fw-semibold">متخصص هستم</h4>
                                    <p class="text-muted mb-4">
                                        مهارت‌هایتان را ثبت کنید<br>
                                        و با پروژه‌های مناسب match شوید
                                    </p>

                                    <span class="btn btn-success px-4">
                                        ورود به داشبورد متخصص
                                        <i class="ri-arrow-left-line ms-1"></i>
                                    </span>

                                </div>
                            </div>
                        </button>
                    </form>

                @else

                    {{-- No profile yet → show creation form inline --}}
                    <div class="card role-card role-card--specialist-new h-100 mb-0 w-100">

                        <div class="card-body text-center p-4 p-lg-5" id="specialist-cta">
                            <div class="avatar-xl mx-auto mb-4">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle"
                                      style="font-size:2.2rem;">
                                    <i class="ri-user-star-line"></i>
                                </span>
                            </div>
                            <h4 class="mb-2 fw-semibold">متخصص هستم</h4>
                            <p class="text-muted mb-4">
                                مهارت‌هایتان را ثبت کنید<br>
                                و با پروژه‌های مناسب match شوید
                            </p>
                            <button type="button" class="btn btn-outline-success px-4"
                                    onclick="showForm('specialist')">
                                <i class="ri-add-line me-1"></i>ایجاد پروفایل متخصص
                            </button>
                        </div>

                        <div id="specialist-form" style="display:none;"
                             class="card-body border-top pt-4">
                            <form action="{{ route('profiles.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="profile_type" value="specialist">
                                <div class="mb-3 text-start">
                                    <label class="form-label fw-medium">
                                        عنوان تخصصی <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="headline"
                                           class="form-control" id="specialist-headline"
                                           placeholder="مثلاً: مهندس مکانیک متخصص در ANSYS"
                                           required minlength="2" maxlength="255">
                                    <div class="form-text">حداقل ۲ کاراکتر</div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 ajax-submit">
                                    <span class="spinner-border spinner-border-sm me-1"
                                          role="status" style="display:none;"></span>
                                    ثبت و ورود به داشبورد
                                </button>
                                <button type="button" class="btn btn-link btn-sm w-100 mt-2 text-muted"
                                        onclick="hideForm('specialist')">انصراف</button>
                            </form>
                        </div>

                    </div>

                @endif
            </div>

        </div>{{-- /.row --}}

        {{-- Profile edit section (secondary, collapsed) --}}
        @if($hasEmployer || $hasSpecialist)
        <div class="text-center mt-5">
            <button class="btn btn-link text-muted btn-sm"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#profile-edit-section">
                <i class="ri-edit-line me-1"></i>ویرایش اطلاعات پروفایل
            </button>

            <div class="collapse mt-3 text-start" id="profile-edit-section">
                <div class="row g-3 justify-content-center">

                    @if($hasEmployer)
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header py-2">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="ri-briefcase-line me-1 text-primary"></i>پروفایل کارفرما
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profiles.update', $employerProfile) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">نام شرکت (اختیاری)</label>
                                        <input type="text" name="company_name" class="form-control"
                                               value="{{ $employerProfile->company_name }}" maxlength="255">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary ajax-submit">
                                        <span class="spinner-border spinner-border-sm" role="status" style="display:none;"></span>
                                        ذخیره
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($hasSpecialist)
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-header py-2">
                                <h6 class="mb-0 fw-semibold">
                                    <i class="ri-user-star-line me-1 text-success"></i>پروفایل متخصص
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('profiles.update', $specialistProfile) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label">عنوان تخصصی <span class="text-danger">*</span></label>
                                        <input type="text" name="headline" class="form-control"
                                               value="{{ $specialistProfile->headline }}"
                                               required minlength="2" maxlength="255">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">بیوگرافی (اختیاری)</label>
                                        <textarea name="bio" class="form-control"
                                                  rows="3">{{ $specialistProfile->bio }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-success ajax-submit">
                                        <span class="spinner-border spinner-border-sm" role="status" style="display:none;"></span>
                                        ذخیره
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
.role-card {
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}
.role-card--employer,
.role-card--specialist {
    border-width: 2px !important;
}
.role-card--employer {
    border-color: var(--vz-primary) !important;
}
.role-card--specialist {
    border-color: var(--vz-success) !important;
}
.role-card--employer-new,
.role-card--specialist-new {
    border-style: dashed !important;
    border-width: 2px !important;
}
.role-submit-btn:hover .role-card,
.role-submit-btn:focus .role-card {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
}
.role-submit-btn:focus {
    outline: none;
}
</style>
@endpush

@push('scripts')
<script>
function showForm(role) {
    document.getElementById(role + '-cta').style.display  = 'none';
    document.getElementById(role + '-form').style.display = 'block';
    const headline = document.getElementById('specialist-headline');
    if (role === 'specialist' && headline) headline.focus();
}
function hideForm(role) {
    document.getElementById(role + '-form').style.display = 'none';
    document.getElementById(role + '-cta').style.display  = 'block';
}
</script>
@endpush
