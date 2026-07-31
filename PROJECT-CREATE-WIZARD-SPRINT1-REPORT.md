# گزارش پیاده‌سازی Sprint 1 ویزارد ثبت پروژه

## خلاصه نتیجه

فرم فعلی ثبت پروژه، بدون تغییر Backend و Database، به یک Wizard پنج‌مرحله‌ای تبدیل شد. تمام فیلدهای قبلی همچنان در همان `form` قرار دارند و فقط نمایش کارت‌ها، پیمایش مراحل و اعتبارسنجی پیش از حرکت به مرحله بعد با CSS و JavaScript کنترل می‌شود.

## مراحل پیاده‌سازی‌شده

1. معرفی پروژه: عنوان و توضیحات فنی
2. نوع همکاری و حوزه تخصصی
3. پردازش‌ها و سطح تخصص
4. مهارت‌ها، زمان و بودجه
5. فایل‌ها و بازبینی: ورودی فایل فعلی و اسکلت Preview؛ محتوای کامل Preview عمداً پیاده‌سازی نشده است.

## رفتار UI

- Stepper پنج‌مرحله‌ای دسکتاپ و نمایش فشرده «مرحله X از ۵» در موبایل اضافه شد.
- Progress Bar با ورود به هر مرحله به‌روزرسانی می‌شود.
- «مرحله بعد» فقط مرحله جاری را اعتبارسنجی می‌کند.
- «مرحله قبل» بدون اعتبارسنجی و پاک‌کردن داده‌ها بازمی‌گردد.
- مراحل طی‌شده قابل کلیک و مراحل آینده غیرفعال‌اند.
- فقط دکمه مرحله پنجم `submit` است.
- نوار عملیات موبایل sticky است و reduced motion رعایت شده است.

## سازگاری فرم و Payload

- همه inputهای موجود داخل همان `#projectForm` حفظ شدند.
- نام inputهای ثابت پیش و پس از تغییر، خودکار مقایسه و یکسان تأیید شد.
- بدنه `buildHiddenInputs()` بدون تغییر تأیید شد.
- کل بلوک Submit شامل `FormData`، `fetch`، headerها و مدیریت پاسخ بدون تغییر تأیید شد.
- initialization جدیدی برای Choices.js اضافه نشد؛ selectorهای مهارت و پردازش همچنان فقط از factory فعلی `createChipCardSelector()` ساخته می‌شوند.
- Draft یا ذخیره محلی اضافه نشد.

## اعتبارسنجی مرحله‌ای

- مرحله ۱: قواعد native فعلی عنوان و توضیحات
- مرحله ۲: نوع همکاری و ۱ تا ۳ حوزه
- مرحله ۳: ۱ تا ۳ پردازش و حداقل یک سطح برای هر پردازش
- مرحله ۴: سطح و سابقه مهارت انتخابی، مدت و قواعد فعلی بودجه
- مرحله ۵: Submit نهایی با validation و payload‌سازی قبلی

اعتبارسنجی Backend حذف یا جایگزین نشده است.

## فایل‌های تغییرکرده در Sprint 1

- `resources/views/user/projects/create.blade.php`
- `PROJECT-CREATE-WIZARD-SPRINT1-REPORT.md`

هیچ Route، Controller، Request، Action، Model، Migration یا جدول دیتابیس در این Sprint تغییر نکرد.

## Verification

### کامپایل Blade

```bash
php artisan view:cache
```

نتیجه: موفق.

### تست مرتبط ثبت پروژه

```bash
php artisan test tests/Feature/Uploads/ProjectUploadValidationTest.php
```

نتیجه: ۲۲ تست و ۴۸ assertion پاس؛ سناریوی `project creation without files remains unchanged` نیز پاس شد.

### کل تست‌های پروژه

```bash
php artisan test
```

نتیجه: ۱۲۵ تست و ۴۸۸ assertion پاس، بدون failure.

### کنترل Diff

```bash
git diff --check -- resources/views/user/projects/create.blade.php
```

نتیجه: بدون خطای whitespace.

## خارج از محدوده Sprint 1

- Preview کامل و پویا
- Draft خودکار یا بازیابی Refresh
- مدیریت پیشرفته تغییر dependency هنگام تغییر Work Type یا Domain
- هرگونه تغییر Backend یا Database