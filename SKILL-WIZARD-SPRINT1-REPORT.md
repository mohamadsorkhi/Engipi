# گزارش پیاده‌سازی Sprint 1 ویزارد ثبت مهارت

## 1. نتیجه

Sprint 1 صفحه Skill Select بر اساس اسناد زیر پیاده‌سازی شد:

- `SKILL-WIZARD-ANALYSIS.md`
- `SKILL-WIZARD-DESIGN.md`

فرم فعلی به یک Wizard پنج‌مرحله‌ای تبدیل شد:

1. انتخاب حوزه
2. انتخاب گرایش
3. انتخاب مهارت‌ها
4. تعیین سطح و سابقه
5. بازبینی و ثبت

تغییر اجرایی این Sprint فقط در فایل View زیر انجام شد:

```text
resources/views/test.blade.php
```

هیچ Route، Controller، Model، Form Request، API، Migration یا ساختار Database برای این Sprint تغییر نکرد.

---

## 2. موارد پیاده‌سازی‌شده

### Stepper پنج‌مرحله‌ای

- Stepper افقی برای دسکتاپ اضافه شد.
- مرحله فعال با رنگ اصلی مشخص می‌شود.
- مرحله‌های تکمیل‌شده وضعیت completed می‌گیرند.
- مرحله جاری با `aria-current="step"` مشخص می‌شود.

### Progress Bar

- در موبایل Stepper کامل با نسخه فشرده جایگزین می‌شود.
- شماره مرحله به شکل «مرحله X از ۵» نمایش داده می‌شود.
- Progress Bar در هر مرحله به‌ترتیب ۲۰، ۴۰، ۶۰، ۸۰ و ۱۰۰ درصد است.

### Navigation

- دکمه `Next` برای مراحل ۱ تا ۴ اضافه شد.
- دکمه `Back` از مرحله دوم به بعد نمایش داده می‌شود.
- در مرحله پنجم `Next` مخفی و دکمه اصلی `#saveBtn` نمایش داده می‌شود.
- تغییر مرحله فقط با تغییر visibility پنل‌ها انجام می‌شود.

### Validation مرحله‌ای

| مرحله | شرط عبور |
|---|---|
| ۱ | وجود حداقل یک Domain در `selectedDomains` |
| ۲ | وجود حداقل یک Subdomain در `selectedSubdomains` |
| ۳ | وجود حداقل یک Skill در `selectedSkills` |
| ۴ | وجود `level` و `years >= 1` برای همه Skillها |
| ۵ | فقط Submit نهایی |

دکمه Next تا معتبرشدن مرحله جاری Disabled باقی می‌ماند.

### جداسازی انتخاب Skill از Level و Years

- مرحله سوم فقط انتخاب Skill را انجام می‌دهد.
- Skill انتخاب‌شده با همان ساختار موجود وارد `selectedSkills` می‌شود.
- مقدارهای `level` و `years` ابتدا خالی هستند.
- در مرحله چهارم برای هر Skill دو کنترل مستقل ساخته می‌شود:
  - سطح: مبتدی، متوسط، حرفه‌ای
  - سابقه: ۱ تا ۳۰ سال
- تغییر کنترل‌ها مستقیماً همان آبجکت Skill موجود در `selectedSkills` را تکمیل می‌کند.

### Preview مرحله پنجم

- مطابق محدوده Sprint 1 فقط Skeleton پیش‌نمایش ایجاد شد.
- Skeleton شامل بخش‌های حوزه، گرایش و مهارت/سطح/سابقه است.
- Preview واقعی داده‌ها به Sprint 2 واگذار شده است.

### Responsive

- Stepper کامل در دسکتاپ نمایش داده می‌شود.
- در موبایل Stepper فشرده و Progress Bar نمایش داده می‌شود.
- Navigation موبایل sticky است.
- فیلدهای Level و Years در موبایل تک‌ستونه می‌شوند.

---

## 3. حفظ قراردادهای فعلی

stateهای زیر با همان نام و نقش قبلی حفظ شدند:

```text
selectedDomains
loadedSubdomainsByDomain
selectedSubdomains
selectedSkills
```

هیچ state موازی برای داده‌های اصلی فرم ایجاد نشد.

handler موجود `saveBtn` حفظ شد و همچنان payload را به شکل زیر می‌سازد:

```javascript
const dataToSave = selectedSkills.map(function (skill) {
    return { skill_id: skill.id, level: skill.level, years: skill.years };
});
```

بدنه درخواست نهایی نیز بدون تغییر باقی ماند:

```javascript
body: JSON.stringify({ skills: dataToSave, domains: selectedDomains })
```

مقصد درخواست همچنان:

```text
POST /save-user-skills
```

---

## 4. رفتار Back و حفظ state

handler دکمه Back فقط تابع زیر را فراخوانی می‌کند:

```text
showWizardStep(currentWizardStep - 1)
```

تابع تغییر مرحله:

- هیچ‌کدام از stateهای اصلی را reset نمی‌کند.
- Skillها را دوباره از API دریافت نمی‌کند.
- `onSubdomainPick()` را دوباره اجرا نمی‌کند.
- کارت Skill جدید نمی‌سازد.
- listener جدید ثبت نمی‌کند.
- فقط پنل فعال، Stepper، Progress Bar و وضعیت دکمه‌ها را به‌روزرسانی می‌کند.

بنابراین رفت‌وبرگشت میان مراحل باعث از بین‌رفتن Domain، Subdomain، Skill، Level یا Years نمی‌شود.

رفتار وابستگی‌های قبلی فرم دست‌نخورده باقی مانده است؛ برای نمونه حذف Domain یا Subdomain همچنان Skillهای وابسته را طبق منطق قبلی حذف می‌کند.

---

## 5. جلوگیری از initialization و duplicate

- منطق اصلی Wizard داخل همان `DOMContentLoaded` اصلی صفحه initialize می‌شود.
- تغییر مرحله فقط `showWizardStep()` را اجرا می‌کند.
- `showWizardStep()` هیچ listener یا Card جدیدی ایجاد نمی‌کند.
- listener اصلی `saveBtn` فقط یک بار در فایل وجود دارد.
- عنصر `#saveBtn` فقط یک بار در DOM تعریف شده است.
- Modal پیشنهاد Skill و listener مستقل آن بدون تغییر رفتاری باقی ماند.
- Navigation میان مراحل، Modal را initialize مجدد نمی‌کند.

---

## 6. Modal پیشنهاد مهارت

Modal پیشنهاد مهارت جدید مستقل باقی ماند:

- همان ID و Form قبلی
- همان Route
- همان CSRF
- همان listener
- همان FormData
- همان نمایش خطا و موفقیت

Modal در مرحله سوم و پس از بخش مهارت‌های میدانی قابل دسترسی است و وارد Submit اصلی Wizard نمی‌شود.

---

## 7. Refresh و Draft

- هیچ Draft، `localStorage` یا `sessionStorage` اضافه نشد.
- Refresh مطابق رفتار فعلی state را پاک می‌کند.
- صفحه پس از Refresh از مرحله اول و state خالی شروع می‌شود.

---

## 8. تست‌های انجام‌شده

### Compile قالب Blade

دستور:

```text
php artisan view:cache
```

نتیجه:

```text
PASS — Blade templates cached successfully.
```

### تست استاتیک ساختار Wizard

نتایج:

```text
five_panels: PASS
five_indicators: PASS
single_save_id: PASS
single_save_listener: PASS
payload_unchanged: PASS
states_preserved: PASS
no_draft: PASS
back_only_navigates: PASS
navigation_does_not_render: PASS
modal_single: PASS
```

این بررسی‌ها تأیید کردند:

- دقیقاً پنج پنل و پنج Step indicator وجود دارد.
- `saveBtn` و handler آن duplicate نشده‌اند.
- payload نهایی با نسخه قبلی یکسان است.
- چهار state اصلی حفظ شده‌اند.
- Draft اضافه نشده است.
- Back فقط navigation انجام می‌دهد.
- navigation باعث render یا initialization مجدد نمی‌شود.
- Modal فقط یک نمونه دارد.

### تست‌های Laravel مرتبط با Skill

دستور:

```text
php artisan test --filter=Skill
```

نتیجه:

```text
PASS — 17 tests, 57 assertions
```

مجموعه‌های موفق:

- `Tests\Feature\Api\UserSkillApiContractTest`
- `Tests\Feature\Authorization\CollaborationRequestAuthorizationCharacterizationTest`
- `Tests\Feature\Authorization\MatchedProjectAuthorizationCharacterizationTest`
- `Tests\Feature\SkillSuggestionWorkflowTest`

---

## 9. کنترل عدم تغییر Backend

در محدوده Sprint 1 هیچ‌یک از مسیرهای زیر ویرایش نشدند:

```text
routes/
app/Http/Controllers/
app/Http/Requests/
app/Models/
database/migrations/
routes/api.php
```

تنها فایل اجرایی تغییرکرده برای Wizard:

```text
resources/views/test.blade.php
```

فایل گزارش حاضر نیز اضافه شد:

```text
SKILL-WIZARD-SPRINT1-REPORT.md
```

---

## 10. موارد باقی‌مانده برای Sprint 2

- ساخت Preview واقعی از stateهای موجود
- لینک ویرایش هر بخش از Preview
- نمایش خلاصه واقعی Domainها و Subdomainها
- نمایش کارت یا جدول Skillها همراه Level و Years
- بهبود نمایش خطاهای Submit داخل مرحله پنجم

این موارد عمداً در Sprint 1 پیاده‌سازی نشده‌اند.

---

## جمع‌بندی

Sprint 1 با ساختار پنج‌مرحله‌ای، Stepper، Progress Bar، Next/Back، validation مرحله‌ای و Skeleton Preview تکمیل شد. قرارداد Backend، payload نهایی، stateهای اصلی و handler ثبت حفظ شدند. Back داده‌ها را پاک نمی‌کند و تغییر مرحله موجب initialization مجدد، listener تکراری یا Card تکراری نمی‌شود.
