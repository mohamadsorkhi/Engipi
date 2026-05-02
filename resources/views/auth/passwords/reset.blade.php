@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.password-reset')
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

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">فراموشی رمز عبور؟</h5>
                                    <p class="text-muted">بازنشانی رمز عبور</p>
                                </div>

                                <div class="alert border-0 alert-warning text-center mb-2 mx-2" role="alert">
                                    رمز عبور جدید خود را وارد کنید و بر روی بازنشانی کلیک کنید.
                                </div>
                                <div class="p-2">
                                    <form class="form-horizontal" method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label">ایمیل</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="useremail" name="email" placeholder="ایمیل را وارد کنید" value="{{ $email ?? old('email') }}" id="email">
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword">رمز عبور</label>
                                            <input type="password" class="form-control dir-ltr @error('password') is-invalid @enderror" name="password" id="userpassword">
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="userpassword">تایید رمز عبور</label>
                                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control dir-ltr">
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-primary w-md waves-effect ajax-submit waves-light" data-redirect-url="{{url('/')}}" type="button">بازنشانی</button>
                                        </div>

                                    </form><!-- end form -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="my-3 text-center">
                            <p class="mb-1"><a href="{{ route('login') }}"
                                               class="fw-semibold text-primary text-decoration-underline">بازگشت به صفحه ورود</a> </p>
                        </div>

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
