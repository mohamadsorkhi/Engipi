# گزارش پیاده‌سازی Sprint 2 ویزارد ثبت مهارت

## 1. نتیجه

Sprint 2 ویزارد Skill Select بر اساس اسناد زیر تکمیل شد:

- `SKILL-WIZARD-ANALYSIS.md`
- `SKILL-WIZARD-DESIGN.md`
- `SKILL-WIZARD-SPRINT1-REPORT.md`

در این Sprint، Preview واقعی مرحله پنجم، خلاصه آماری، لینک‌های ویرایش، پیام خطای Inline، وضعیت Loading ثبت، انیمیشن مرحله‌ها و Summary چسبان دسکتاپ پیاده‌سازی شدند.

تنها فایل اجرایی تغییرکرده در محدوده Sprint 2:

```text
resources/views/test.blade.php
```

هیچ Route، Controller، Model، Form Request، API، Migration، Database یا payload تغییر نکرد.

---

## 2. Preview واقعی مرحله پنجم

Skeleton مرحله پنجم با Preview واقعی جایگزین شد.

Preview شامل موارد زیر است:

- حوزه‌های انتخاب‌شده
- گرایش‌های انتخاب‌شده
- نام مهارت
- نوع مهارت
- گرایش مرتبط با مهارت
- سطح
- سابقه بر حسب سال

### منبع داده

Preview فقط از stateهای موجود ساخته می‌شود:

```text
selectedDomains
selectedSubdomains
selectedSkills
```

برای تبدیل Domain ID به نام، از `domainsData` موجود در همان صفحه استفاده می‌شود. برای نام گرایش نیز همان `selectedSubdomains` و `skill.subdomainId` استفاده می‌شوند.

تابع `renderWizardPreview()`:

- هیچ `fetch` یا درخواست جدیدی ندارد.
- هیچ داده‌ای را در state تغییر نمی‌دهد.
- پیش از هر render سه container را خالی می‌کند.
- سپس DOM را از state جاری بازسازی می‌کند.
- داده‌های پویا را با `textContent` نمایش می‌دهد.

در نتیجه ورود دوباره به Preview باعث duplicate شدن ردیف‌ها یا Cardها نمی‌شود.

---

## 3. خلاصه آماری Preview

بالای Preview پنج شاخص نمایش داده می‌شود:

1. تعداد حوزه‌ها
2. تعداد گرایش‌ها
3. تعداد کل مهارت‌ها
4. تعداد مهارت‌های نرم‌افزاری
5. تعداد مهارت‌های میدانی

تفکیک نوع مهارت بر اساس همان `skill.skillType` موجود انجام می‌شود:

```text
field → میدانی
سایر مقادیر → نرم‌افزاری
```

هیچ جدول یا منبع داده جدیدی برای این محاسبه ایجاد نشده است.

---

## 4. لینک‌های ویرایش

برای بخش‌های Preview لینک ویرایش اضافه شد:

| بخش | مقصد |
|---|---|
| حوزه‌ها | مرحله ۱ |
| گرایش‌ها | مرحله ۲ |
| مهارت‌ها | مرحله ۳ |
| سطح و سابقه | مرحله ۴ |

تمام لینک‌ها با یک initialization و از طریق `data-preview-edit-step` به navigation موجود متصل شده‌اند.

کلیک روی ویرایش:

- فقط `showWizardStep()` را اجرا می‌کند.
- state را reset نمی‌کند.
- API را فراخوانی نمی‌کند.
- Card یا listener جدید نمی‌سازد.

---

## 5. حفظ state در Back و Edit

رفتار Back از Sprint 1 حفظ شد:

```text
showWizardStep(currentWizardStep - 1)
```

هم Back و هم لینک‌های ویرایش فقط مرحله فعال را تغییر می‌دهند. مقادیر زیر حفظ می‌شوند:

- Domainهای انتخاب‌شده
- Subdomainهای انتخاب‌شده
- Skillهای انتخاب‌شده
- Level هر Skill
- Years هر Skill
- کارت‌های بارگذاری‌شده Skill

Preview در بازگشت مجدد از همان state حفظ‌شده ساخته می‌شود.

---

## 6. Summary چسبان دسکتاپ

یک Summary داخل Card و بالای محتوای مرحله‌ها اضافه شد.

Summary شامل:

- تعداد حوزه
- تعداد گرایش
- تعداد مهارت
- تعداد نرم‌افزاری
- تعداد میدانی

ویژگی‌های نمایش:

- در دسکتاپ `position: sticky` دارد.
- هنگام حرکت بین مراحل قابل مشاهده باقی می‌ماند.
- با هر تغییر state به‌روزرسانی می‌شود.
- در موبایل مخفی است تا فضای عمودی فرم اشغال نشود؛ آمار کامل در مرحله پنجم نمایش داده می‌شود.

---

## 7. خطاهای Inline مرحله پنجم

خطاهای ثبت نهایی دیگر با `alert()` نمایش داده نمی‌شوند.

یک container مستقل در مرحله پنجم اضافه شد:

```text
#wizardSubmitAlert
```

رفتار:

- خطاهای validation سمت Backend در همان مرحله نمایش داده می‌شوند.
- خطاهای nested با خط جدید از هم جدا می‌شوند.
- خطای شبکه نیز به‌صورت Inline نمایش داده می‌شود.
- کاربر در مرحله پنجم باقی می‌ماند.
- هیچ state یا Preview پاک نمی‌شود.
- در تلاش بعدی، پیام قبلی پیش از Submit پاک می‌شود.

پیام موفقیت و redirect قبلی بدون تغییر رفتاری باقی مانده‌اند.

---

## 8. وضعیت Submit

در زمان Submit:

- guard با `isSubmittingSkills` از ارسال مجدد جلوگیری می‌کند.
- دکمه `saveBtn` Disabled می‌شود.
- Spinner داخل همان دکمه نمایش داده می‌شود.
- تا پایان Promise کلیک مجدد اثری ندارد.

پس از پایان درخواست:

- guard آزاد می‌شود.
- Spinner مخفی می‌شود.
- دکمه فقط در صورت معتبر بودن مرحله چهارم دوباره فعال می‌شود.

این تغییر فقط وضعیت UI ارسال را مدیریت می‌کند و payload را تغییر نمی‌دهد.

---

## 9. حفظ payload

ساخت payload نهایی همچنان به شکل قبلی است:

```javascript
const dataToSave = selectedSkills.map(function (skill) {
    return { skill_id: skill.id, level: skill.level, years: skill.years };
});
```

بدنه درخواست نیز دقیقاً بدون تغییر باقی مانده است:

```javascript
body: JSON.stringify({ skills: dataToSave, domains: selectedDomains })
```

Endpoint:

```text
POST /save-user-skills
```

هیچ کلید جدیدی به payload اضافه نشده است.

---

## 10. انیمیشن مرحله‌ها

برای ورود پنل فعال یک انیمیشن حدود ۲۵۰ میلی‌ثانیه اضافه شد:

- Fade کوتاه
- جابه‌جایی عمودی ۸ پیکسل
- اجرا فقط هنگام navigation

برای کاربرانی که کاهش حرکت را در سیستم فعال کرده‌اند:

```css
@media (prefers-reduced-motion: reduce)
```

انیمیشن پنل و transition نوار پیشرفت غیرفعال می‌شوند. همچنین `scrollIntoView` به‌جای smooth از رفتار `auto` استفاده می‌کند.

---

## 11. جلوگیری از duplicate

کنترل‌های انجام‌شده:

- `saveBtn` فقط یک listener دارد.
- لینک‌های ویرایش فقط یک بار initialize می‌شوند.
- `showWizardStep()` هیچ `addEventListener` ندارد.
- `showWizardStep()` هیچ `createElement` ندارد.
- Preview قبل از ساخت مجدد، containerهای قبلی را پاک می‌کند.
- Preview هیچ API را دوباره فراخوانی نمی‌کند.
- Modal پیشنهاد Skill یک نمونه و listener مستقل خود را حفظ کرده است.

---

## 12. Modal پیشنهاد مهارت

Modal پیشنهاد مهارت در Sprint 2 تغییر نکرد:

- ID قبلی
- Form قبلی
- Route قبلی
- CSRF قبلی
- FormData قبلی
- listener مستقل قبلی

Preview، Summary و navigation هیچ وابستگی جدیدی به Modal ندارند.

---

## 13. تست‌های انجام‌شده

### Compile Blade

دستور:

```text
php artisan view:cache
```

نتیجه:

```text
PASS — Blade templates cached successfully.
```

### کنترل‌های استاتیک Sprint 2

نتایج:

```text
payload_unchanged: PASS
preview_has_no_fetch: PASS
preview_reads_all_states: PASS
preview_clears_before_render: PASS
back_only_navigates: PASS
navigation_no_listener_or_cards: PASS
single_save_listener: PASS
single_preview_edit_listener: PASS
inline_submit_errors: PASS
no_submit_error_alert: PASS
spinner_and_lock: PASS
reduced_motion: PASS
single_modal: PASS
```

این آزمون‌ها تأیید کردند:

- payload نهایی تغییر نکرده است.
- Preview فقط از state ساخته می‌شود.
- Preview درخواست Backend ندارد.
- Back انتخاب‌ها را پاک نمی‌کند.
- navigation listener یا Card جدید نمی‌سازد.
- خطاهای Submit Inline هستند.
- guard و Spinner ارسال وجود دارند.
- تنظیم `prefers-reduced-motion` رعایت شده است.
- Modal duplicate نشده است.

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

## 14. کنترل محدوده تغییرات

در Sprint 2 هیچ‌یک از موارد زیر تغییر نکردند:

```text
routes/
app/Http/Controllers/
app/Http/Requests/
app/Models/
routes/api.php
database/
database/migrations/
```

فایل‌های Sprint 2:

```text
resources/views/test.blade.php
SKILL-WIZARD-SPRINT2-REPORT.md
```

---

## جمع‌بندی

Sprint 2، مرحله پنجم را از Skeleton به Preview واقعی تبدیل کرد. تمام داده‌های Preview و Summary از stateهای موجود خوانده می‌شوند و هیچ درخواست جدیدی به Backend وجود ندارد. لینک‌های ویرایش و Back state را حفظ می‌کنند، خطاهای ثبت به‌صورت Inline نمایش داده می‌شوند، ارسال نهایی در برابر کلیک تکراری محافظت شده و انیمیشن کوتاه با رعایت کاهش حرکت اضافه شده است. Backend، API، Database و payload بدون تغییر باقی مانده‌اند.
