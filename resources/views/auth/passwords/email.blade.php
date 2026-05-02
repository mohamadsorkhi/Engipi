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
                                    <h5 class="text-primary">رمز عبور را فراموش کرده اید؟</h5>
                                    <p class="text-muted">بازنشانی رمز عبور</p>

                                    <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop" colors="primary:#0ab39c" class="avatar-xl"><template shadowrootmode="open"><div class="body" style="--lord-icon-primary-base: #0ab39c; --lord-icon-secondary-base: #08a88a;"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 500" width="500" height="500" preserveAspectRatio="xMidYMid meet" style="width: 100%; height: 100%; transform: translate3d(0px, 0px, 0px); content-visibility: visible;"><defs><clipPath id="__lottie_element_2"><rect width="500" height="500" x="0" y="0"></rect></clipPath><g id="__lottie_element_12"><g transform="matrix(2.93650484085083,0,0,2.93650484085083,312.9947204589844,257.6353759765625)" opacity="1" style="display: block;"><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path fill="rgb(18,19,49)" fill-opacity="1" d=" M-25.75359344482422,-22.70638084411621 C-32.37440872192383,-22.86842918395996 -33.96039962768555,-19.185134887695312 -29.29295539855957,-14.48657512664795 C-29.29295539855957,-14.48657512664795 -8.70704460144043,6.236575126647949 -8.70704460144043,6.236575126647949 C-4.039601802825928,10.935133934020996 3.4421119689941406,10.840704917907715 7.989482402801514,6.025842189788818 C7.989482402801514,6.025842189788818 25.510517120361328,-12.52584171295166 25.510517120361328,-12.52584171295166 C30.05788803100586,-17.3407039642334 28.37441062927246,-21.38157081604004 21.75359344482422,-21.54361915588379 C21.75359344482422,-21.54361915588379 -25.75359344482422,-22.70638084411621 -25.75359344482422,-22.70638084411621z"></path><path stroke-linecap="butt" stroke-linejoin="miter" fill-opacity="0" stroke-miterlimit="4" stroke="rgb(8,168,138)" stroke-opacity="1" stroke-width="0" d=" M-25.75359344482422,-22.70638084411621 C-32.37440872192383,-22.86842918395996 -33.96039962768555,-19.185134887695312 -29.29295539855957,-14.48657512664795 C-29.29295539855957,-14.48657512664795 -8.70704460144043,6.236575126647949 -8.70704460144043,6.236575126647949 C-4.039601802825928,10.935133934020996 3.4421119689941406,10.840704917907715 7.989482402801514,6.025842189788818 C7.989482402801514,6.025842189788818 25.510517120361328,-12.52584171295166 25.510517120361328,-12.52584171295166 C30.05788803100586,-17.3407039642334 28.37441062927246,-21.38157081604004 21.75359344482422,-21.54361915588379 C21.75359344482422,-21.54361915588379 -25.75359344482422,-22.70638084411621 -25.75359344482422,-22.70638084411621z"></path></g></g></g><filter id="__lottie_element_20" filterUnits="objectBoundingBox" x="0%" y="0%" width="100%" height="100%"><feComponentTransfer in="SourceGraphic"><feFuncA type="table" tableValues="1.0 0.0"></feFuncA></feComponentTransfer></filter><mask id="__lottie_element_12_2" mask-type="alpha"><g filter="url(#__lottie_element_20)"><rect width="500" height="500" x="0" y="0" fill="#ffffff" opacity="0"></rect><use xlink:href="#__lottie_element_12"></use></g></mask></defs><g clip-path="url(#__lottie_element_2)"><g transform="matrix(2.93650484085083,0,0,2.93650484085083,311.52056884765625,228.5228729248047)" opacity="1" style="display: block;"><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(8,168,138)" stroke-opacity="0" stroke-width="4.2" d=" M-15.53499984741211,7.499000072479248 C-16.777000427246094,6.619999885559082 -17.961999893188477,5.624000072479248 -19.072999954223633,4.511000156402588 C-19.07341957092285,4.510671615600586 -42.375,-18.836000442504883 -42.375,-18.836000442504883 M42.37900161743164,-18.832000732421875 C42.37900161743164,-18.832000732421875 19.07522201538086,4.512470722198486 19.07522201538086,4.512470722198486 C18.341999053955078,5.247000217437744 17.57699966430664,5.931000232696533 16.784000396728516,6.563000202178955"></path></g><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(9,179,156)" stroke-opacity="1" stroke-width="4.2" d=" M-42.37900161743164,-18.836000442504883 C-42.37900161743164,-18.836000442504883 -7.064230442047119,16.547101974487305 -7.064230442047119,16.547101974487305 C-3.1654815673828125,20.453393936157227 3.1657800674438477,20.453691482543945 7.064896583557129,16.547765731811523 C7.064896583557129,16.547765731811523 42.375,-18.823999404907227 42.375,-18.823999404907227"></path></g><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(9,179,156)" stroke-opacity="1" stroke-width="4.2" d=" M-42.98400115966797,-19.56999969482422 C-42.98400115966797,-19.56999969482422 -42.98400115966797,37.41400146484375 -42.98400115966797,37.41400146484375 C-42.98400115966797,37.41400146484375 43.05500030517578,37.41400146484375 43.05500030517578,37.41400146484375 C43.05500030517578,37.41400146484375 43.05500030517578,-19.562000274658203 43.05500030517578,-19.562000274658203 C43.05500030517578,-19.562000274658203 -42.98400115966797,-19.56999969482422 -42.98400115966797,-19.56999969482422z"></path></g></g><g mask="url(#__lottie_element_12_2)" style="display: block;"><g transform="matrix(2.93650484085083,0,0,2.93650484085083,311.52056884765625,228.5228729248047)" opacity="1"><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(9,179,156)" stroke-opacity="1" stroke-width="4.2" d=" M0.6079999804496765,4.756999969482422 C7.821000099182129,4.869999885559082 14.994999885559082,7.318999767303467 20.489999771118164,12.102999687194824 C20.49005699157715,12.102505683898926 41.75,30.608999252319336 41.75,30.608999252319336 M-41.25,30.25 C-41.25,30.25 -20.274538040161133,12.062816619873047 -20.274538040161133,12.062816619873047 C-14.520000457763672,7.072999954223633 -6.934999942779541,4.638999938964844 0.6079999804496765,4.756999969482422"></path></g></g></g><g transform="matrix(3.700000047683716,0,0,3.700000047683716,250,250)" opacity="1" style="display: block;"><g opacity="1" transform="matrix(1,0,0,1,0,0)"><path stroke-linecap="round" stroke-linejoin="round" fill-opacity="0" stroke="rgb(9,179,156)" stroke-opacity="1" stroke-width="3.5" d=" M-35.39699935913086,-21.5 C-42.0369987487793,-21.5 -50.06100082397461,-21.5 -52.85599899291992,-21.5 M-35.39699935913086,-10.215999603271484 C-42.0369987487793,-10.215999603271484 -50.06100082397461,-10.215999603271484 -52.85599899291992,-10.215999603271484 M-35.35599899291992,0.9319999814033508 C-41.99599838256836,0.9319999814033508 -50.02000045776367,0.9319999814033508 -52.814998626708984,0.9319999814033508 M-35.35599899291992,12.215999603271484 C-41.99599838256836,12.215999603271484 -50.02000045776367,12.215999603271484 -52.814998626708984,12.215999603271484 M-35.4109992980957,23.364999771118164 C-42.05099868774414,23.364999771118164 -50.07500076293945,23.364999771118164 -52.869998931884766,23.364999771118164"></path></g></g><g class="com" style="display: none;"><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g><g><path></path></g></g></g></svg></div></template>
                                    </lord-icon>

                                </div>

                                <div class="alert border-0 alert-warning text-center mb-2 mx-2" role="alert">ایمیل خود را وارد کنید و دستورالعمل ها برای شما ارسال خواهد شد!</div>
                                <div class="p-2">
                                    <form method="POST" action="{{ route('password.email') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="form-label">ایمیل</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="ایمیل را وارد کنید">
                                            <div class="invalid-feedback">
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class="text-center mt-4">
                                            <button class="btn btn-success ajax-submit w-100" type="button">ارسال لینک بازنشانی</button>
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
    <script src="{{ URL::asset('build/js/pages/eva-icon.init.js') }}"></script>
@endsection
