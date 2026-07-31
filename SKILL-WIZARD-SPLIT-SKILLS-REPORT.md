# گزارش Sprint — SKILL-WIZARD-SPLIT-SKILLS

## نتیجه

Wizard ثبت مهارت از ۵ مرحله به ۶ مرحله تبدیل شد و صفحه مشترک انتخاب مهارت به دو مرحله مستقل «پردازشی / نرم‌افزاری» و «میدانی» تقسیم شد. تمام تغییرات اجرایی در View، CSS و JavaScript صفحه انجام شده‌اند و Backend، Route، Controller، Model، API، Database، Migration و payload تغییری نکرده‌اند.

## فایل‌های تغییرکرده در این Sprint

- `resources/views/test.blade.php`
- `SKILL-WIZARD-SPLIT-SKILLS-REPORT.md`

Worktree پیش از شروع این Sprint تغییرات دیگری داشت. فهرست بالا فقط فایل‌های مربوط به Sprint حاضر را بیان می‌کند.

## ساختار جدید ۶ مرحله‌ای

1. انتخاب حوزه
2. انتخاب گرایش
3. انتخاب مهارت‌های پردازشی / نرم‌افزاری
4. انتخاب مهارت‌های میدانی
5. تعیین سطح و سابقه
6. بازبینی و ثبت

Stepper دسکتاپ شش indicator مستقل دارد. وضعیت Current با Blue، Completed با Teal و تیک، و Future با Slate/Gray نمایش داده می‌شود. عنوان‌ها برای عرض‌های متوسط کوچک‌تر شده‌اند تا Layout شکسته نشود.

Progress موبایل از فرمول زیر استفاده می‌کند:

```javascript
const progress = (currentWizardStep / 6) * 100;
```

بنابراین مقدار آن برای مراحل مختلف به‌صورت پویا از حدود 16.67 تا 100 درصد محاسبه می‌شود و متن موبایل «مرحله X از ۶» است.

## تفکیک Software و Field

### مرحله ۳

فقط container مهارت‌های غیر Field در این پنل قرار دارد:

- عنوان و توضیح مستقل پردازشی
- Search مستقل `skillSearchSoftware`
- container مستقل `skillsContainerSoftware`
- رنگ و accent آبی
- grid پنج‌ستونه در Desktop بزرگ، چهارستونه در Desktop کوچک‌تر، دو ستونه Tablet و تک‌ستونه Mobile

### مرحله ۴

فقط container مهارت‌های دارای `skill_type === 'field'` در این پنل قرار دارد:

- عنوان و توضیح مستقل میدانی
- Search مستقل `skillSearchField`
- container مستقل `skillsContainerField`
- رنگ و accent Teal
- حداکثر چهار ستون Desktop، دو ستون Tablet و یک ستون Mobile
- Card بزرگ‌تر با متن حداکثر سه‌خطی و line-height خواناتر
- CTA و Modal پیشنهاد مهارت فقط در همین مرحله نمایش داده می‌شوند.

تابع موجود `getSkillsContainer(skillType)` همچنان کارت دریافتی از API را فقط به container متناظر اضافه می‌کند؛ داده جدید یا endpoint جدیدی ساخته نشده است.

## حفظ selectedSkills

تنها source of truth مهارت‌ها همچنان همان state قبلی است:

```javascript
let selectedSkills = [];
```

هیچ stateای با نام `selectedSoftwareSkills` یا `selectedFieldSkills` ایجاد نشده است. تفکیک نوع صرفاً برای container نمایشی و شمارش Summary انجام می‌شود.

محدودیت مجموع ۵ مهارت نیز همان guard قبلی را روی `selectedSkills.length` استفاده می‌کند؛ بنابراین مجموع Software و Field حداکثر ۵ باقی می‌ماند.

## Validation مرحله ۳ و ۴

Validation جدید مطابق نیاز Sprint است:

```javascript
if (step === 3) return true;
if (step === 4) return selectedSkills.length > 0;
```

- مرحله ۳ اختیاری است و بدون انتخاب مهارت پردازشی می‌توان ادامه داد.
- برای عبور از مرحله ۴ حداقل یک Skill در مجموع لازم است.
- فقط Software، فقط Field یا ترکیبی از هر دو معتبر است.
- مرحله ۵ همان validation قبلی Level و Years را برای تمام اعضای `selectedSkills` اجرا می‌کند.

## جلوگیری از API call و Card تکراری

در رفت‌وبرگشت مرحله ۳ و ۴ فقط `showWizardStep()` اجرا می‌شود. این تابع:

- فقط visibility پنل‌ها و UI navigation را تغییر می‌دهد.
- `fetch` اجرا نمی‌کند.
- Card جدید نمی‌سازد.
- listener جدید ثبت نمی‌کند.
- state یا Searchها را reset نمی‌کند.

درخواست `/api/skills/{subdomainID}` همچنان فقط هنگام انتخاب واقعی Subdomain در `onSubdomainPick()` اجرا می‌شود. Skeleton فقط پیرامون همین fetch واقعی نمایش داده و در `finally` حذف می‌شود.

## Summary

Summary قبلی حفظ شده و از `selectedSkills` محاسبه می‌شود:

- تعداد حوزه
- تعداد گرایش
- تعداد کل مهارت
- تعداد پردازشی
- تعداد میدانی

`renderSelectedSkills()` و عملیات افزودن/حذف همچنان `updateWizardControls()` را فراخوانی می‌کنند؛ در نتیجه Summary هر دو مرحله فوراً به‌روز می‌شود.

## Level و Years

پنل قبلی مرحله ۴ به مرحله ۵ منتقل شد و منطق آن تغییر نکرد. تمام مهارت‌های پردازشی و میدانی از `selectedSkills` render می‌شوند و badge نوع مهارت Blue یا Teal باقی مانده است. مقدارهای Level و Years هنگام Back/Next در همان objectهای state حفظ می‌شوند.

## Preview و Edit links

Preview قبلی به مرحله ۶ منتقل شد و همچنان حوزه، گرایش، نوع مهارت، Level و Years را نمایش می‌دهد.

مقصد Editها:

| بخش | مرحله |
|---|---:|
| حوزه | 1 |
| گرایش | 2 |
| مهارت‌های پردازشی | 3 |
| مهارت‌های میدانی | 4 |
| سطح و سابقه | 5 |

Submit button فقط در مرحله ۶ نمایش داده می‌شود.

## Payload و endpoint

ساخت payload بدون تغییر باقی مانده است:

```javascript
const dataToSave = selectedSkills.map(function (skill) {
    return { skill_id: skill.id, level: skill.level, years: skill.years };
});

body: JSON.stringify({ skills: dataToSave, domains: selectedDomains })
```

Endpoint بدون تغییر:

```text
POST /save-user-skills
```

## Modal پیشنهاد مهارت

Modal فقط در مرحله ۴ قرار دارد و موارد زیر بدون تغییر مانده‌اند:

- `skillSuggestionModal`
- `skillSuggestionForm`
- Route فرم
- CSRF و FormData
- listener مستقل
- عدم انتخاب خودکار Skill پیشنهادی

تنها یک Modal و یک listener مربوط به آن در DOM وجود دارد.

## Empty State و Search

- Empty State پردازشی توضیح می‌دهد که نبود Software Skill مانع ادامه نیست.
- Empty State میدانی توضیح مستقل دارد و CTA پیشنهاد مهارت زیر آن باقی می‌ماند.
- دو Search state مستقل DOM دارند و هرکدام فقط container همان نوع را filter می‌کنند.
- Back/Next مقدار input جست‌وجو را reset نمی‌کند.

## Accessibility و Responsive

- `aria-current="step"` برای indicator جاری حفظ شد.
- Skill Cardها `aria-pressed` و keyboard activation دارند.
- هر Search دارای label و `aria-label` قابل دسترس است.
- focus-visible و reduced-motion از UI Polish قبلی حفظ شدند.
- Gridها در breakpointهای Desktop/Tablet/Mobile بدون width ثابت و با `minmax(0, 1fr)` تعریف شدند تا horizontal scroll ایجاد نکنند.

## نتیجه تست‌ها

### Blade compile

```text
php artisan view:cache
PASS — Blade templates cached successfully.
```

### تست‌های Skill

```text
php artisan test --filter=Skill
PASS — 17 tests, 57 assertions.
```

Suiteهای موفق:

- `Tests\Feature\Api\UserSkillApiContractTest`
- `Tests\Feature\Authorization\CollaborationRequestAuthorizationCharacterizationTest`
- `Tests\Feature\Authorization\MatchedProjectAuthorizationCharacterizationTest`
- `Tests\Feature\SkillSuggestionWorkflowTest`

### کنترل‌های استاتیک

- شش panel و شش indicator یکتا: PASS
- progress پویا بر مبنای ۶: PASS
- مرحله ۳ اختیاری: PASS
- شرط مرحله ۴ روی مجموع `selectedSkills`: PASS
- Level/Years در مرحله ۵: PASS
- یک `selectedSkills` به‌عنوان source of truth: PASS
- Search و container مستقل Software/Field: PASS
- یک save listener و یک Modal: PASS
- Edit link مراحل ۱ تا ۵: PASS
- payload و endpoint قبلی: PASS
- `git diff --check`: PASS

## محدودیت و تست دستی پیشنهادی

مرورگر تعاملی در محیط اجرای فعلی در دسترس نبود. موارد زیر با ساختار کد و CSS بررسی شدند اما بهتر است در مرورگر پروژه نیز به‌صورت دستی تست شوند:

- ظاهر Stepper شش‌مرحله‌ای در عرض‌های میانی
- نبود horizontal scroll در دستگاه واقعی
- هماهنگی ارتفاع کارت‌های میدانی با عنوان‌های بسیار بلند
- سناریوهای 5 Software، 5 Field و ترکیب 3+2
- حفظ ظاهر selected Card و متن Search بعد از چند بار Back/Next
- نمایش و بسته‌شدن Modal پیشنهاد مهارت در مرحله ۴
## Step 5 Validation UX

UX validation مرحله «سطح و سابقه» تکمیل شد. این تغییر فقط در `resources/views/test.blade.php` انجام شده و فایل گزارش حاضر نیز به‌روزرسانی شده است.

### رفتار Continue

منطق اصلی `isWizardStepValid(5)` حفظ شده است، اما دکمه Continue در مرحله ۵ دیگر به‌دلیل ناقص‌بودن Level/Years غیرفعال نمی‌شود. پس از کلیک:

1. تمام اعضای `selectedSkills` بررسی می‌شوند.
2. اگر همه Level و Years معتبر باشند، Wizard طبق رفتار قبلی وارد مرحله ۶ می‌شود.
3. اگر حداقل یک مقدار ناقص باشد، navigation متوقف و validation UI اجرا می‌شود.

### پیام و Error State

پس از اولین تلاش ناموفق، پیام زیر با `role="alert"` بالای Skill Cardها نمایش داده می‌شود:

```text
برای ادامه، سطح مهارت و سابقه فعالیت همه مهارت‌های انتخاب‌شده را مشخص کنید.
```

فقط کارت‌های ناقص کلاس `bp-sid-card--validation-error` می‌گیرند. Border قرمز ملایم، background نرم Danger و ring کم‌رنگ با Design System فعلی هماهنگ شده‌اند.

برای هر field پیام مستقل ساخته می‌شود:

- Level ناقص: «سطح مهارت را انتخاب کنید.»
- Years ناقص: «سابقه فعالیت را انتخاب کنید.»

هر select ناقص دارای `aria-invalid="true"` و `aria-describedby` متصل به پیام خودش است.

### پاک‌شدن فوری خطا

پس از change هر select، فقط validation همان وضعیت فعلی دوباره ارزیابی می‌شود:

- انتخاب Level، خطای Level را فوراً حذف می‌کند.
- اگر Years هنوز ناقص باشد، خطای Years و Error State کارت باقی می‌مانند.
- پس از کامل‌شدن هر دو field، Error State کارت حذف می‌شود.
- پس از کامل‌شدن تمام Skillها، پیام کلی نیز خودکار مخفی می‌شود.

### Scroll و Focus

پس از Continue ناموفق:

- اولین Skill Card ناقص با `scrollIntoView` و `block: "center"` در دید قرار می‌گیرد.
- رفتار عادی smooth و در `prefers-reduced-motion` برابر auto است.
- focus روی اولین select ناقص همان کارت قرار می‌گیرد.

### Regression و state

- `selectedSkills`، ساختار `level` و `years` تغییر نکردند.
- Back/Next هیچ مقدار انتخاب‌شده‌ای را reset یا بازسازی نمی‌کند.
- تنها یک listener برای `wizardNextBtn` وجود دارد.
- payload و endpoint ثبت بدون تغییر باقی مانده‌اند.

### سناریوهای کنترل‌شده

رفتار پیاده‌سازی برای سناریوهای زیر بررسی شد:

- هیچ Level/Years انتخاب نشده: هر دو field و Card خطا می‌گیرند.
- فقط Level انتخاب شده: فقط Years نامعتبر باقی می‌ماند.
- فقط Years انتخاب شده: فقط Level نامعتبر باقی می‌ماند.
- یک Skill ناقص میان چند Skill کامل: فقط کارت همان Skill مشخص می‌شود.
- همه Skillها کامل: مرحله ۶ باز می‌شود.
- اصلاح field ناقص: خطای همان field بلافاصله حذف می‌شود.
- Back/Next: مقادیر از objectهای موجود `selectedSkills` خوانده و حفظ می‌شوند.

### نتیجه تست این تغییر

```text
php artisan view:cache
PASS — Blade templates cached successfully.

php artisan test --filter=Skill
PASS — 17 tests, 57 assertions.

git diff --check
PASS
```

کنترل‌های استاتیک تکمیلی نیز برای قابل‌کلیک‌بودن Continue مرحله ۵، پیام کلی، Error State کارت، پیام‌های Level/Years، `aria-invalid`، `aria-describedby`، scroll/focus، یکتایی listener و ثابت‌ماندن payload/endpoint همگی PASS شدند.

تست تعاملی واقعی سناریوها در مرورگر در محیط فعلی در دسترس نبود؛ منطق DOM و مسیرهای event به‌صورت استاتیک بررسی شده‌اند و یک مرور دستی نهایی در Desktop و Mobile پیشنهاد می‌شود.
## Mobile Dropdown Layering Fix

مشکل overlap منوی Domain/Subdomain با navigation چسبان موبایل اصلاح شد. تغییر اجرایی فقط در `resources/views/test.blade.php` انجام شد و هیچ Backend، API، state، payload یا business logic تغییر نکرد.

### علت اصلی stacking context

منوی Dropdown از ابتدا `position: absolute` و `z-index: 200` داشت و sticky navigation موبایل `position: sticky` و `z-index: 20` داشت. با وجود بزرگ‌تر بودن z-index منو، پنل مرحله هنگام navigation کلاس زیر را دریافت می‌کرد:

```css
.bp-wizard__panel.is-entering { animation: bpWizardPanelIn 250ms ease both; }
```

به‌دلیل `animation-fill-mode: both`، مقدار نهایی `transform: translateY(0)` پس از پایان animation روی پنل باقی می‌ماند. `transform` یک stacking context مستقل می‌سازد و منوی دارای z-index بالا را داخل stacking context پنل محبوس می‌کرد؛ در مقابل، sticky navigation به‌عنوان sibling positioned می‌توانست روی آن قرار بگیرد.

Card اصلی و Card body transform نداشتند. Header سراسری نیز در این Sprint تغییر نکرد و منشأ overlap محلی نبود. عامل تعیین‌کننده، stacking context پنل متحرک و سپس ترتیب لایه sticky navigation بود.

### اصلاح stacking و z-index

لایه‌ها به‌صورت زیر تنظیم شدند:

- Sticky navigation موبایل: `z-index: 100`
- wrapper Dropdown در حالت باز: `z-index: 110`
- Dropdown menu/listbox: `z-index: 1000`

همچنین یک listener ثابت `animationend` برای هر Wizard panel ثبت شد که پس از پایان animation، کلاس `is-entering` را حذف می‌کند. در نتیجه transform نهایی روی پنل باقی نمی‌ماند و stacking context موقت به stacking context دائمی تبدیل نمی‌شود.

listenerها فقط یک بار هنگام initialization ثبت می‌شوند و Back/Next listener جدیدی ایجاد نمی‌کند.

### Overflow والدها

برای containerهای محلی زیر `overflow: visible` به‌صورت صریح اعمال شد:

```css
.bp-wizard-card,
#skillWizardRoot,
.bp-wizard__panel { overflow: visible; }
```

در Mobile، محدودیت افقی قبلی صفحه برای جلوگیری از horizontal scroll حفظ شد:

```css
.bp-skill-wizard-page {
    overflow-x: clip;
    overflow-y: visible;
}
```

بنابراین منوی عمودی clip نمی‌شود و در عین حال layout افقی Wizard از viewport خارج نمی‌شود.

### max-height و scroll داخلی

مقدار fallback منو برای Desktop و Mobile به شکل زیر تغییر کرد:

```css
max-height: min(320px, 50vh);
overflow-y: auto;
overscroll-behavior: contain;
-webkit-overflow-scrolling: touch;
```

در زمان بازشدن، `placeDropdown()` فضای واقعی میان trigger و بالای sticky navigation را اندازه می‌گیرد و max-height inline را حداکثر تا 320px، 50vh و فضای قابل‌استفاده محدود می‌کند. در نتیجه لیست بلند داخل خودش scroll می‌شود و گزینه‌های پایین پشت navigation قرار نمی‌گیرند.

### جهت بازشدن

اگر فضای پایین کمتر از 180px باشد و فضای بالا بیشتر باشد، کلاس `ep-select--open-up` فعال می‌شود و menu با `bottom: calc(100% + 4px)` بالای trigger باز می‌شود.

این محاسبه برای هر دو مورد زیر اجرا می‌شود:

- Domain Dropdown
- Subdomain Dropdown

در resize نیز placement Dropdown باز دوباره محاسبه می‌شود. انتخاب option همچنان توابع قبلی را اجرا می‌کند و Dropdown طبق رفتار قبلی بسته می‌شود.

### Accessibility و Regression

ساختارهای زیر بدون تغییر حفظ شدند:

- `role="combobox"`
- `role="listbox"`
- `role="option"`
- `aria-expanded`
- keyboard activation با Enter/Space و بستن با Escape

عرض و alignment Desktop تغییر نکرد. Back/Next، selected state، API و payload نیز بدون تغییر باقی ماندند.

### نتیجه تست‌ها

```text
php artisan view:cache
PASS — Blade templates cached successfully.

php artisan test --filter=Skill
PASS — 17 tests, 57 assertions.

git diff --check
PASS
```

کنترل‌های استاتیک زیر نیز PASS شدند:

- menu بالاتر از sticky navigation
- wrapper باز بالاتر از navigation
- overflow عمودی والدها برابر visible
- max-height وابسته به viewport
- scroll داخلی listbox
- placement رو‌به‌بالا
- حذف stacking context انیمیشن بعد از پایان
- placement برای Domain و Subdomain
- حفظ ARIA و payload
- عدم ایجاد Next listener تکراری

تست مرورگر تعاملی در viewport واقعی در محیط فعلی در دسترس نبود. بررسی دستی Domain/Subdomain با لیست کوتاه و بلند در یک Mobile viewport واقعی، خصوصاً کلیک آخرین option و scroll هم‌زمان listbox، همچنان پیشنهاد می‌شود.