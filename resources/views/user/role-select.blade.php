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
                <div class="bp-role bp-role-emp h-100">
                    <div class="card-body text-center p-4 p-lg-5">

                        <div class="bp-role-chip mx-auto mb-3">
                            <i class="ri-briefcase-line"></i>
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
                <div class="bp-role bp-role-spec h-100">
                    <div class="card-body text-center p-4 p-lg-5">

                        <div class="bp-role-chip mx-auto mb-3">
                            <i class="ri-user-star-line"></i>
                        </div>

                        <h4 class="mb-2">متخصص هستم</h4>
                        <p class="text-muted mb-4">
                            مهارت‌هایتان را ثبت کنید<br>و با پروژه‌های مناسب match شوید
                        </p>

                        {{-- step 1: show entry button --}}
                        <div id="specialist-btn">
                            <button type="button"
                                    class="btn bp-btn-teal btn-lg w-100"
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
                                <button type="submit" class="btn bp-btn-teal btn-lg w-100 ajax-submit">
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

@push('styles')
<style>
.bp-role {
    position: relative;
    background: #fff;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    overflow: hidden;
    transition: transform .25s var(--bp-ease), box-shadow .25s, border-color .25s;
}
.bp-role::before { content: ''; position: absolute; inset: 0 0 auto 0; height: 4px; }
.bp-role-emp::before { background: var(--bp-blue); }
.bp-role-spec::before { background: var(--bp-teal); }
.bp-role:hover { transform: translateY(-5px); box-shadow: var(--bp-sh-lg); }
.bp-role-emp:hover { border-color: var(--bp-blue); }
.bp-role-spec:hover { border-color: var(--bp-teal); }
.bp-role-chip {
    width: 66px; height: 66px;
    border-radius: var(--bp-r-lg);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem;
}
.bp-role-emp .bp-role-chip { background: var(--bp-tint-blue); color: var(--bp-blue); }
.bp-role-spec .bp-role-chip { background: var(--bp-tint-teal); color: var(--bp-teal); }

.bp-btn-teal {
    background: var(--bp-teal) !important;
    border-color: var(--bp-teal) !important;
    color: #fff !important;
    font-weight: 700 !important;
}
.bp-btn-teal:hover {
    background: var(--bp-teal-d) !important;
    border-color: var(--bp-teal-d) !important;
    color: #fff !important;
}
</style>
@endpush

@push('scripts')
<script>
function showSpecialistForm() {
    document.getElementById('specialist-btn').style.display  = 'none';
    document.getElementById('specialist-form').style.display = 'block';
    document.getElementById('headline-input').focus();
}
</script>
@endpush
