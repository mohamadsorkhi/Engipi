@extends('layouts.master-without-nav')
@section('title')
   ورود
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
                                    <h5 class="text-primary">ورود به {{ config('app.name') }}</h5>
                                    <p class="text-muted">با اطلاعات حساب کاربری خود وارد شوید</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="login" class="form-label">ایمیل یا شماره موبایل <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control dir-ltr" value="{{ old('login') }}" id="login" name="login" placeholder="ایمیل یا 09123456789" required>
                                            <div class="invalid-feedback"><span></span></div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="password-input">رمز عبور <span
                                                    class="text-danger">*</span></label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password"
                                                       class="form-control dir-ltr password-input pe-5"
                                                       name="password"
                                                       id="password-input" required>
                                            <div class="float-end">
                                                <a href="{{ route('password.update') }}" class="text-muted">رمز عبور را فراموش کرده اید؟</a>
                                            </div>
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                                <div class="invalid-feedback">
                                                    <span></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                   id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">مرا به خاطر
                                                بسپار</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-success w-100 ajax-submit" type="submit">
                                                 <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                                ورود
                                            </button>
                                        </div>
                                        <hr>
                                        <div class="mt-1">
                                            <h6>
                                                اگر حساب ندارید ثبت‌نام کنید
                                            </h6>
                                            <a href="{{ route('register') }}"
                                               class="btn btn-primary w-100">
                                                ثبت نام </a>
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
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/particles.js/particles.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/particles.app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/password-addon.init.js') }}"></script>

@endsection
