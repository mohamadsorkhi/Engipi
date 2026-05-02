@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.password-confirm')
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
                                    <h5 class="text-primary">تایید رمز عبور</h5>
                                    <p class="text-muted">لطفا قبل از ادامه، رمز عبور خود را تایید کنید.</p>
                                </div>
                                <div class="p-2">
                                    <form method="POST" action="{{ route('password.confirm') }}">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="password" class="form-label">رمز عبور</label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="رمز عبور را وارد کنید">
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="button" class="btn btn-success w-100 ajax-submit">
                                                تایید رمز عبور
                                            </button>
                                        </div>

                                        @if (Route::has('password.request'))
                                            <div class="mt-4 text-center">
                                                <a class="text-muted" href="{{ route('password.request') }}">
                                                    رمز عبور خود را فراموش کرده اید؟
                                                </a>
                                            </div>
                                        @endif
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
