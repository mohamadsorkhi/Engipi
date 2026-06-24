# EngPis — نصب روی لاراول (Blade)

تبدیل کامل طرح بلوپرینت به Blade برای پروژه‌ی لاراول ۱۱ (Blade + Vite).
همه‌چیز آفلاین است؛ نیازی به npm یا اینترنت ندارد.

---

## ساختار این بسته
```
engpis-blade/
├── resources/views/        ← فایل‌های Blade
│   ├── layouts/base.blade.php
│   ├── landing.blade.php
│   ├── auth.blade.php
│   ├── dashboard.blade.php
│   ├── profile.blade.php
│   ├── requests.blade.php
│   ├── tickets.blade.php
│   └── projects/
│       ├── create.blade.php
│       └── show.blade.php
├── public/vendor/engpis/   ← CSS و فونت‌ها
│   ├── css/{blueprint.css, app-shell.css}
│   └── fonts/{...}
└── routes-web.php          ← روت‌هایی که باید اضافه کنی
```

---

## مراحل نصب (۳ قدم)

### ۱) کپی فایل‌ها
- محتوای `resources/views/` این بسته را در `resources/views/` پروژه‌ات کپی کن.
- محتوای `public/vendor/engpis/` را در `public/vendor/engpis/` پروژه‌ات کپی کن.

> اگر از قبل فایلی به نام `landing.blade.php` یا `dashboard.blade.php` داری، اول از آن‌ها نسخه‌ی پشتیبان بگیر.

### ۲) افزودن روت‌ها
محتوای `routes-web.php` را به انتهای `routes/web.php` پروژه‌ات اضافه کن.
(اگر روتی با همان آدرس `/` یا `/dashboard` از قبل داری، آدرس‌ها را عوض کن تا تداخل نشود.)

### ۳) اجرا
```bash
php artisan optimize:clear   # پاک‌سازی کش ویو/روت
php artisan serve
```
حالا این آدرس‌ها را باز کن:
- `http://localhost:8000/` ← صفحه‌ی اصلی
- `/auth` ، `/dashboard` ، `/projects/create` ، `/projects/show` ، `/profile` ، `/requests` ، `/tickets`

برای صفحه‌ی انتخاب نقش: `/dashboard?v=role` — برای پروژه‌های پیشنهادی: `/dashboard?v=matched`

---

## نکته‌ی مهم: داده‌ها فعلاً نمونه‌اند
این صفحات **طرح بصری** کامل‌اند و داده‌های نمونه را با جاوااسکریپت در مرورگر نمایش می‌دهند
(داخل بلوک‌های `@verbatim` تا با سینتکس Blade تداخل نکنند).

برای **اتصال به دیتابیس واقعی**، در هر صفحه آرایه‌های نمونه‌ی داخل `<script>` را با
`@foreach` روی داده‌های کنترلر جای‌گزین کن. مثال برای کارت‌های پروژه:

```blade
{{-- به‌جای رندر جاوااسکریپتی، در خود Blade: --}}
@foreach ($projects as $p)
  <div class="proj">
    <div class="pname">{{ $p->title }}</div>
    <div class="pemp"><i class="ri-user-line"></i>{{ $p->employer_name }}</div>
    {{-- ... --}}
  </div>
@endforeach
```
سپس روت `Route::view(...)` را به یک کنترلر تبدیل کن که داده را پاس می‌دهد:
```php
Route::get('/dashboard', [DashboardController::class, 'index'])->name('engpis.dashboard');
```

---

## یکپارچه‌سازی با master layout موجودت (اختیاری)
اگر می‌خواهی این صفحات داخل layout فعلی پروژه‌ات (با هدر/فوتر خودت) بنشینند،
به‌جای `@extends('layouts.base')` از layout خودت استفاده کن و فقط بخش `@section('content')`
را نگه دار. لینک‌های CSS/فونت داخل `layouts/base.blade.php` را هم به `<head>` اصلی منتقل کن.

---

ساخته‌شده بر پایه‌ی مخزن: https://github.com/mohamadsorkhi/Engipi
