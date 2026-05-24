@extends('layouts.master')

@section('title', 'انتخاب نقش')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="text-center mb-5 mt-2">
            <h3 class="mb-1">به {{ config('app.name') }} خوش آمدید!</h3>
            <p class="text-muted">با چه نقشی می‌خواهید وارد شوید؟</p>
        </div>

        <div class="row g-4">

            {{-- EMPLOYER --}}
            <div class="col-md-6">
                <div class="card border border-dashed h-100">
                    <div class="card-body text-center p-4 p-lg-5">

                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title bg-primary-subtle text-primary rounded-circle"
                                  style="font-size: 2.2rem;">
                                <i class="ri-briefcase-line"></i>
                            </span>
                        </div>

                        <h4 class="mb-2">کارفرما هستم</h4>
                        <p class="text-muted mb-4">
                            پروژه‌های خود را ثبت کنید<br>و با متخصصین همکاری کنید
                        </p>

                        <form action="{{ route('profiles.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="profile_type" value="employer">
                            <button type="submit" class="btn btn-primary btn-lg w-100 ajax-submit">
                                <span class="spinner-border spinner-border-sm me-1"
                                      role="status" style="display:none;"></span>
                                ورود به عنوان کارفرما
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            {{-- SPECIALIST --}}
            <div class="col-md-6">
                <div class="card border border-dashed h-100">
                    <div class="card-body text-center p-4 p-lg-5">

                        <div class="avatar-lg mx-auto mb-3">
                            <span class="avatar-title bg-success-subtle text-success rounded-circle"
                                  style="font-size: 2.2rem;">
                                <i class="ri-user-star-line"></i>
                            </span>
                        </div>

                        <h4 class="mb-2">متخصص هستم</h4>
                        <p class="text-muted mb-4">
                            مهارت‌هایتان را ثبت کنید<br>و با پروژه‌های مناسب match شوید
                        </p>

                        {{-- step 1: show entry button --}}
                        <div id="specialist-btn">
                            <button type="button"
                                    class="btn btn-success btn-lg w-100"
                                    onclick="showSpecialistForm()">
                                ورود به عنوان متخصص
                            </button>
                        </div>

                        {{-- step 2: headline form (hidden until btn click) --}}
                        <div id="specialist-form" style="display:none;">
                            <form action="{{ route('profiles.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="profile_type" value="specialist">
                                <div class="mb-3 text-start">
                                    <label class="form-label fw-medium">
                                        عنوان تخصصی <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="headline"
                                        id="headline-input"
                                        class="form-control"
                                        placeholder="مثلاً: مهندس مکانیک متخصص در ANSYS"
                                        required
                                        minlength="2"
                                        maxlength="255"
                                    >
                                    <div class="form-text">حداقل ۲ کاراکتر</div>
                                </div>
                                <button type="submit" class="btn btn-success btn-lg w-100 ajax-submit">
                                    <span class="spinner-border spinner-border-sm me-1"
                                          role="status" style="display:none;"></span>
                                    ثبت و ادامه
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <p class="text-center text-muted small mt-4">
            می‌توانید بعداً هر دو نقش را داشته باشید.
        </p>

    </div>
</div>

@endsection

@push('scripts')
<script>
function showSpecialistForm() {
    document.getElementById('specialist-btn').style.display  = 'none';
    document.getElementById('specialist-form').style.display = 'block';
    document.getElementById('headline-input').focus();
}
</script>
@endpush
