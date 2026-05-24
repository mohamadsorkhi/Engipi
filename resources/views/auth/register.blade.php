@extends('layouts.master-without-nav')
@section('title')
   کاربر ثبت نام
@endsection
@section('content')

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                @include('auth.partials.auth-header')

                <div class="row justify-content-center mb-3">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">
                                        ثبت نام در {{ config('app.name') }}
                                    </h5>
                                    <p class="text-muted">همین حالا اکانت رایگان
                                        {{ config('app.name') }} خود را ایجاد کنید
                                    </p>
                                </div>
                                <div class="p-2 mt-4">

                                    @if(session('project_saved'))
                                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            اطلاعات پروژه شما ذخیره شد. برای تکمیل ثبت‌نام، اطلاعات زیر را وارد کنید.
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">نام <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" id="first_name" required minlength="2" maxlength="255">
                                                <div class="form-text">حداقل ۲ کاراکتر</div>
                                                <div class="invalid-feedback"><span></span></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" id="last_name" required minlength="2" maxlength="255">
                                                <div class="form-text">حداقل ۲ کاراکتر</div>
                                                <div class="invalid-feedback"><span></span></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="mobile" class="form-label">شماره موبایل <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control dir-ltr" name="mobile" value="{{ old('mobile') }}" id="mobile" placeholder="09123456789" required pattern="^09\d{9}$">
                                            <div class="form-text">مثال: 09123456789</div>
                                            <div class="invalid-feedback"><span></span></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">ایمیل <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control dir-ltr" name="email" value="{{ old('email') }}" id="useremail" required>
                                            <div class="form-text">مثال: user@example.com</div>
                                            <div class="invalid-feedback"><span></span></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword" class="form-label">رمز عبور <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                   class="form-control dir-ltr"
                                                   name="password"
                                                   id="userpassword" required minlength="8">
                                            <div class="form-text">حداقل ۸ کاراکتر</div>
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="input-password">تایید رمز عبور <span
                                                    class="text-danger">*</span></label>
                                            <input type="password"
                                                   class="form-control dir-ltr"
                                                   name="password_confirmation" id="input-password" required minlength="8">
                                             <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                        </div>

                                        <div class="mt-3">
                                            <button class="btn btn-success w-100 ajax-submit" type="submit">
                                                 <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                                ثبت نام
                                            </button>
                                        </div>
                                        <hr>
                                        <div class="mt-1">
                                            <h6>
                                                حساب کاربری دارید؟ بر روی دکمه زیر کلیک کنید!
                                            </h6>
                                            <a href="{{ route('login') }}"
                                               class="btn btn-primary w-100">
                                                ورود به سایت
                                            </a>
                                        </div>

                                        <hr>
                                        <div class="mt-1">
                                            <p class="text-muted small text-center mb-2">
                                                کارفرما هستید و می‌خواهید اول پروژه‌تان را ثبت کنید؟
                                            </p>
                                            <a href="{{ route('guest.project') }}"
                                               class="btn btn-outline-warning w-100">
                                                <i class="ri-briefcase-line me-1"></i>
                                                اول پروژه‌ام را ثبت می‌کنم
                                            </a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        @include('auth.partials.auth-footer')
    </div>
    <!-- end auth-page-wrapper -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
@endsection
