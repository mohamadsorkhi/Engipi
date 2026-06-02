<nav class="nav" id="ep-nav">
    <div class="container nav-inner">
        <a class="brand" href="{{ url('/') }}#top"><span class="accent">Eng</span>Pis</a>
        <ul class="nav-links">
            <li><a class="nav-link" href="#how-it-works">چگونه کار می‌کند؟</a></li>
            <li><a class="nav-link" href="#domains">حوزه‌های تخصصی</a></li>
            <li><a class="nav-link" href="#features">مزایا</a></li>
            <li><a class="nav-link" href="#about">درباره ما</a></li>
        </ul>
        <div class="nav-actions">
            <a class="nav-login" href="{{ route('login') }}">ورود</a>
            <a class="btn-nav" href="{{ route('register') }}">ثبت‌نام رایگان</a>
        </div>
    </div>
</nav>
<script>
    (function () {
        var nav = document.getElementById('ep-nav');
        window.addEventListener('scroll', function () {
            nav.classList.toggle('pinned', window.scrollY > 60);
        }, { passive: true });
    })();
</script>
