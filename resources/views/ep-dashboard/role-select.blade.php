<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>انتخاب نقش | EngPis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/ep-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ep-dashboard.css') }}">
</head>
<body>

<div class="center-screen">
    <div class="role-wrap">
        <div class="role-head">
            <h3>به EngPis خوش آمدید!</h3>
            <p>با چه نقشی می‌خواهید وارد شوید؟</p>
        </div>

        <div class="role-grid">
            {{-- Employer card --}}
            <form method="POST" action="{{ route('profiles.store') }}">
                @csrf
                <input type="hidden" name="type" value="employer">
                <div class="role emp">
                    <div class="chip"><i class="ri-briefcase-line"></i></div>
                    <h4>کارفرما هستم</h4>
                    <p>پروژه‌های خود را ثبت کنید و با متخصصین حرفه‌ای همکاری کنید</p>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="ri-arrow-left-line"></i>ورود به عنوان کارفرما
                    </button>
                </div>
            </form>

            {{-- Specialist card --}}
            <form method="POST" action="{{ route('profiles.store') }}">
                @csrf
                <input type="hidden" name="type" value="specialist">
                <div class="role spec">
                    <div class="chip"><i class="ri-user-star-line"></i></div>
                    <h4>متخصص هستم</h4>
                    <p>مهارت‌هایتان را ثبت کنید و با پروژه‌های مناسب match شوید</p>
                    <button type="submit" class="btn btn-accent btn-lg">
                        <i class="ri-arrow-left-line"></i>ورود به عنوان متخصص
                    </button>
                </div>
            </form>
        </div>

        <p class="role-note">می‌توانید بعداً هر دو نقش را داشته باشید.</p>
    </div>
</div>

</body>
</html>
