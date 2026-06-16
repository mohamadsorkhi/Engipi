<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
<script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<script src="{{ URL::asset('build/js/mgh/mgh.js?v='.time()) }}"></script>

<script>
/* ── Global toast helper — wraps Toastify for all pages ── */
window.showToast = function (message, type) {
    if (typeof Toastify === 'undefined') {
        alert(message);
        return;
    }
    var isSuccess = type === 'success';
    Toastify({
        text: message,
        duration: 3500,
        gravity: 'top',
        position: 'left',
        stopOnFocus: true,
        style: {
            background: isSuccess
                ? 'linear-gradient(to right, #00b09b, #00d4aa)'
                : 'linear-gradient(to right, #ff5f6d, #ef5350)',
            borderRadius: '8px',
            fontSize: '0.88rem',
            fontFamily: "'Vazirmatn', sans-serif",
            direction: 'rtl',
        },
    }).showToast();
};
</script>

@yield('script')
@stack('scripts')
@yield('script-bottom')
