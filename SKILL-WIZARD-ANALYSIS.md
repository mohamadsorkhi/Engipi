# گزارش فنی فرم ثبت مهارت (Skill Select)

## محدوده و نتیجه کلی

این گزارش فقط وضعیت فعلی صفحه انتخاب و ثبت مهارت متخصص را تحلیل می‌کند. در زمان تهیه گزارش هیچ Route، Controller، Model، Migration، JavaScript یا View اجرایی تغییر نکرده است.

نتیجه کلی این است که فرم فعلی را می‌توان بدون تغییر Backend و Database به Wizard تبدیل کرد، زیرا تمام انتخاب‌ها هم‌اکنون در state سمت مرورگر نگهداری و در پایان با یک درخواست JSON ارسال می‌شوند. کم‌ریسک‌ترین راه، حفظ کامل stateها، توابع بارگذاری، payload و endpoint فعلی و کنترل نمایش بخش‌ها با wrapperهای مرحله‌ای است.

---

## 1. Route صفحه ثبت مهارت

Route نمایش:

```text
GET /skill-select
name: skill.select
action: SkillSelectController@index
```

Route ثبت نهایی:

```text
POST /save-user-skills
name: skill.save
action: SkillSelectController@saveSkills
```

هر دو Route در `routes/web.php` داخل گروه زیر قرار دارند:

- `web`
- `auth`
- `active_role:specialist`

بنابراین فقط کاربر واردشده با نقش فعال متخصص به صفحه و عملیات ذخیره دسترسی دارد.

دو API مرتبط نیز در `routes/api.php` تعریف شده‌اند:

```text
GET /api/subdomains/{domainId}
GET /api/skills/{subdomain}
```

API اول در فرم فعلی استفاده نمی‌شود؛ API دوم برای بارگذاری Skillهای یک Subdomain استفاده می‌شود.

---

## 2. Controller مربوطه

Controller اصلی:

```text
app/Http/Controllers/SkillSelectController.php
```

### `index()`

- همه Domainها را از مدل `SkillDomain` دریافت می‌کند.
- رابطه `subdomains` را eager-load می‌کند.
- Domainها را بر اساس `name` مرتب می‌کند.
- View با نام `test` را render می‌کند.

عبارت اصلی:

```text
SkillDomain::with('subdomains')->orderBy('name')->get()
```

### `saveSkills(Request $request)`

- Validation را به‌صورت inline با `$request->validate()` انجام می‌دهد.
- تعلق Skillهای ارسالی به Domainهای ارسالی را با query مستقیم بررسی می‌کند.
- Skillهای کاربر را در `user_skills` با `sync()` جایگزین می‌کند.
- Domainهای پروفایل متخصص را در `user_profile_domains` با `sync()` ذخیره می‌کند.
- Processها را مستقیماً از فرم دریافت نمی‌کند؛ Processهای هم‌نام با Skillهای ذخیره‌شده را در Domainهای انتخابی پیدا می‌کند و در `profile_processes` sync می‌کند.
- پاسخ JSON شامل `success`، پیام فارسی و redirect به Route ریشه برمی‌گرداند.

### Controllerهای API

- `App\Http\Controllers\Api\SkillController@index`
  - Skillهای دارای `subdomain_id` درخواست‌شده را از جدول `skills` می‌خواند.
  - فقط `id`، `name` و `skill_type` را برمی‌گرداند.
- `App\Http\Controllers\Api\SubdomainController@index`
  - Subdomainهای Domain را از جدول `subdomains` دریافت می‌کند.
  - نتیجه را ۳۰۰ ثانیه cache می‌کند.
  - این endpoint در JavaScript فعلی Skill Select فراخوانی نمی‌شود.

### Form Request موجود اما استفاده‌نشده

فایل زیر در پروژه وجود دارد:

```text
app/Http/Requests/Specialist/StoreSkillsRequest.php
```

اما `SkillSelectController::saveSkills()` آن را type-hint نکرده و Route فعلی از Validation آن استفاده نمی‌کند. این Request قرارداد متفاوتی مبتنی بر `domains` و `processes` دارد؛ بنابراین نباید هنگام UI-only کردن Wizard با Validation واقعی این صفحه اشتباه گرفته شود.

---

## 3. Blade / Component مربوط به فرم

View اصلی:

```text
resources/views/test.blade.php
```

با وجود نام عمومی `test.blade.php`، این فایل View واقعی Route `skill.select` است.

این صفحه Vue یا React Component ندارد و تمام رابط در Blade، CSS و JavaScript خام همان فایل پیاده‌سازی شده است.

اجزای اصلی صفحه:

- dropdown سفارشی Domain
- chipهای Domain انتخاب‌شده
- dropdown سفارشی Subdomain
- chipهای Subdomain انتخاب‌شده
- بخش Skillهای پردازشی/نرم‌افزاری
- بخش مهارت‌های میدانی
- جست‌وجوی مستقل برای هر نوع Skill
- Modal پیشنهاد مهارت جدید
- کارت‌های Skill انتخاب‌شده
- دکمه نهایی `#saveBtn`

فرم اصلی Skill Select یک `<form>` HTML معمولی ندارد. دکمه `#saveBtn` از نوع `button` است و ثبت با JavaScript و `fetch` انجام می‌شود. Modal پیشنهاد مهارت فرم مستقل `#skillSuggestionForm` دارد و خارج از قرارداد ثبت نهایی Skillهاست.

---

## 4. JavaScriptهای مرتبط

JavaScript اصلی داخل همان `resources/views/test.blade.php` قرار دارد.

### داده اولیه Server-side

دو ساختار با `@json` به JavaScript منتقل می‌شوند:

- `domainSubdomainsMap`: نگاشت UUID هر Domain به آرایه Subdomainهای آن
- `domainsData`: آرایه `id` و `name` Domainها

### state اصلی

```text
selectedDomains
loadedSubdomainsByDomain
selectedSubdomains
selectedSkills
```

### توابع Domain

- `ddPopulate()`
- `ddClose()`
- `ddReset()`
- `onDomainPick()`
- `deselectDomain()`
- `renderSelectedDomains()`

### توابع Subdomain

- `sdPopulate()`
- `sdEnable()`
- `sdDisable()`
- `sdReset()`
- `sdClose()`
- `onSubdomainPick()`
- `renderSelectedSubdomains()`

### توابع Skill

- `getSkillTypeTheme()`
- `getSkillIcon()`
- `getSkillsContainer()`
- `filterSkillsContainer()`
- `refreshSkillFilters()`
- `clearSkillSelection()`
- `removeSkillsBySubdomain()`
- `renderSelectedSkills()`

### ثبت نهایی

Listener دکمه `saveBtn`:

1. `selectedSkills` را به payload تبدیل می‌کند.
2. به `/save-user-skills` درخواست POST JSON می‌فرستد.
3. CSRF token را از meta tag می‌خواند.
4. در موفقیت پیام را با `alert` نمایش داده و redirect می‌کند.
5. خطاهای 422 را به متن تخت تبدیل کرده و با `alert` نمایش می‌دهد.

### JavaScript پیشنهاد Skill

یک `DOMContentLoaded` جداگانه برای `#skillSuggestionForm` وجود دارد که:

- FormData می‌سازد.
- به Route `skill-suggestions.store` ارسال می‌کند.
- خطاها را کنار فیلدهای Modal نمایش می‌دهد.

این جریان مستقل است و Wizard اصلی نباید listener یا فرم آن را دوباره initialize کند.

هیچ Choices.js، Vue یا React در فرم اصلی Skill Select استفاده نشده است.

---

## 5. Validationهای فعلی

### Validation سمت UI

#### Domain

- Domain تکراری پذیرفته نمی‌شود.
- حداکثر ۲ Domain قابل انتخاب است.
- حداقل Domain به‌صورت مستقیم در یک تابع validation جداگانه بررسی نمی‌شود، اما بدون Domain امکان انتخاب Subdomain و Skill در مسیر عادی UI وجود ندارد.

#### Subdomain

- Subdomain تکراری پذیرفته نمی‌شود.
- حداکثر ۲ Subdomain قابل انتخاب است.
- Subdomain فقط از مجموعه متعلق به Domainهای انتخاب‌شده قابل انتخاب است.

#### Skill

- حداکثر ۵ Skill قابل انتخاب است.
- دکمه ذخیره تا زمان انتخاب حداقل یک Skill غیرفعال است.
- سطح از میان گزینه‌های زیر انتخاب می‌شود:
  - مبتدی
  - متوسط
  - حرفه‌ای
- سابقه در UI از ۱ تا ۳۰ سال قابل انتخاب است.
- جست‌وجو فقط نمایش کارت‌ها را فیلتر می‌کند و state انتخاب را تغییر نمی‌دهد.

### Validation واقعی Backend در `saveSkills()`

| فیلد | قواعد |
|---|---|
| `skills` | `required`, `array`, `min:1`, `max:5` |
| `skills.*.skill_id` | `required`, `uuid`, `exists:skills,id`, `distinct` |
| `skills.*.level` | `required`, `string`, `max:50` |
| `skills.*.years` | `required`, `integer`, `min:0`, `max:50` |
| `domains` | `nullable`, `array`, `max:2` |
| `domains.*` | `uuid`, `exists:skill_domains,id` |

Validation تکمیلی:

- اگر Domain ارسال شده باشد، همه Skillهای ارسالی باید از طریق `skills.subdomain_id → subdomains.skill_domain_id` متعلق به یکی از Domainهای ارسالی باشند.
- در غیر این صورت پاسخ 422 با خطای عدم تطابق Skill و Domain برمی‌گردد.

پیام سفارشی فعلی فقط برای این موارد تعریف شده است:

- بیشتر از ۵ Skill
- Skill تکراری

سایر پیام‌ها از ترجمه پیش‌فرض Laravel استفاده می‌کنند.

### تفاوت مهم UI و Backend

- UI سابقه را ۱ تا ۳۰ محدود می‌کند.
- Backend مقدار ۰ تا ۵۰ را می‌پذیرد.
- Backend مقدار `level` را فقط string تا ۵۰ کاراکتر می‌سنجد و enum مشخصی enforce نمی‌کند.
- Domain در Backend nullable است، ولی مسیر طبیعی UI عملاً به Domain نیاز دارد.

---

## 6. نحوه بارگذاری Domain

Domainها در درخواست اولیه صفحه و در `SkillSelectController@index` بارگذاری می‌شوند:

```text
SkillDomain::with('subdomains')->orderBy('name')->get()
```

سپس آرایه `domainsData` در Blade ساخته و با `ddPopulate(domainsData)` داخل dropdown سفارشی قرار داده می‌شود.

برای Domain درخواست AJAX جداگانه وجود ندارد.

حداکثر دو Domain در `selectedDomains` نگهداری می‌شود. حذف Domain:

- Domain را از state حذف می‌کند.
- Subdomainهای متعلق به آن را حذف می‌کند.
- Skillهای متعلق به Subdomainهای حذف‌شده را نیز حذف می‌کند.
- dropdown Subdomain را دوباره می‌سازد یا غیرفعال می‌کند.

---

## 7. نحوه بارگذاری Subdomain

Subdomainها همراه Domainها در همان query اولیه eager-load می‌شوند و در Blade به شکل زیر وارد JavaScript می‌شوند:

```text
domainSubdomainsMap[domainId] = [{ id, name }, ...]
```

پس از انتخاب Domain:

- Subdomainهای آن از `domainSubdomainsMap` خوانده می‌شوند.
- در `loadedSubdomainsByDomain` نگهداری می‌شوند.
- همه Subdomainهای Domainهای انتخاب‌شده flatten شده و dropdown سفارشی Subdomain با آنها پر می‌شود.

هرچند endpoint زیر وجود دارد، فرم فعلی از آن استفاده نمی‌کند:

```text
GET /api/subdomains/{domainId}
```

حداکثر دو Subdomain در `selectedSubdomains` نگهداری می‌شود.

---

## 8. نحوه بارگذاری Process

فرم فعلی Process را در UI بارگذاری، نمایش یا ارسال نمی‌کند.

پس از Submit، Controller این روند را اجرا می‌کند:

1. رکورد Skillهای انتخاب‌شده را از جدول `skills` می‌خواند.
2. برای هر Skill در جدول `processes` به دنبال Process با `name` دقیقاً مساوی نام Skill می‌گردد.
3. جست‌وجو را به Domainهای انتخاب‌شده محدود می‌کند.
4. level فارسی Skill را با map زیر به level Process تبدیل می‌کند:
   - مبتدی → `practical`
   - متوسط → `proficient`
   - حرفه ای → `advanced`
5. نتیجه را در `profile_processes` با `sync()` ثبت می‌کند.

بنابراین Process یک داده مشتق‌شده در Backend است، نه انتخاب مستقل فرم.

نکته حساس: UI مقدار «حرفه‌ای» با نیم‌فاصله/همزه را تولید می‌کند، اما key کنترلر «حرفه ای» است. در صورت عدم تطابق دقیق رشته، fallback برابر `practical` می‌شود. Wizard نباید متن یا valueهای level را تغییر دهد، زیرا این وابستگی موجود حساس است و خودش نیازمند بررسی Backend جداگانه است.

---

## 9. نحوه بارگذاری Skill

پس از انتخاب Subdomain، تابع `onSubdomainPick()` این endpoint را فراخوانی می‌کند:

```text
GET /api/skills/{subdomainId}
```

`Api\SkillController@index` از جدول `skills` رکوردهای دارای `subdomain_id` موردنظر را خوانده و فیلدهای زیر را برمی‌گرداند:

- `id`
- `name`
- `skill_type`

سپس UI:

- Skillهای `software` را در بخش پردازش‌ها/نرم‌افزار قرار می‌دهد.
- Skillهای `field` را در بخش مهارت‌های میدانی قرار می‌دهد.
- برای هر Skill کارت، انتخاب سطح، انتخاب سابقه و دکمه افزودن می‌سازد.
- Skill انتخاب‌شده را در `selectedSkills` ذخیره می‌کند.

بارگذاری Skill به‌صورت append انجام می‌شود تا Skillهای Subdomainهای قبلی نیز باقی بمانند. حذف Subdomain، Skillهای مرتبط با همان Subdomain را از state و DOM حذف می‌کند.

---

## 10. نحوه ثبت نهایی مهارت‌ها

فرم main submit معمولی ندارد. کلیک روی `#saveBtn` payload زیر را می‌سازد:

```json
{
  "skills": [
    {
      "skill_id": "uuid",
      "level": "متوسط",
      "years": 3
    }
  ],
  "domains": ["domain-uuid"]
}
```

درخواست:

```text
POST /save-user-skills
Content-Type: application/json
X-CSRF-TOKEN: <meta csrf-token>
```

ذخیره Backend:

1. Skillها با pivot data زیر در `user_skills` sync می‌شوند:
   - `level`
   - `years_of_experience`
2. Domainها برای اولین پروفایل `specialist` کاربر در `user_profile_domains` sync می‌شوند.
3. Processهای مشتق‌شده در `profile_processes` sync می‌شوند.
4. پاسخ موفق باعث redirect به `route('root')` می‌شود.

`sync()` به این معناست که مجموعه ارسال‌شده جایگزین مجموعه قبلی می‌شود؛ Skillها یا Domainهایی که در payload جدید نیستند ممکن است detach شوند.

---

## 11. ارتباط با جدول‌ها

### `skill_domains`

- منبع Domainهای صفحه
- کلید اصلی UUID
- `name` یکتا
- یک Domain چند Subdomain و چند Process دارد.
- انتخاب‌های کاربر از طریق `user_profile_domains` به پروفایل متخصص متصل می‌شوند.

### `subdomains`

- دارای `skill_domain_id` با foreign key به `skill_domains`
- واسط اصلی نسبت‌دادن Skill به Domain
- همراه Domainها در بارگذاری اولیه صفحه دریافت می‌شود.
- خود Subdomain انتخاب‌شده مستقیماً در payload نهایی یا pivot مستقل کاربر ذخیره نمی‌شود.

### `processes`

- دارای `skill_domain_id`
- نام در هر Domain یکتا است.
- در فرم انتخاب نمی‌شود.
- Controller بر اساس برابری نام Process و Skill آن را مشتق می‌کند.
- انتخاب نهایی در `profile_processes` همراه level ذخیره می‌شود.

### `skills`

- جدول اصلی همه Skillها
- دارای `process_id` nullable
- دارای `subdomain_id` nullable
- دارای `skill_type` با مقدار پیش‌فرض `software`
- endpoint مهارت‌ها بر اساس `subdomain_id` فیلتر می‌کند.
- انتخاب کاربر از طریق `user_skills` ذخیره می‌شود.

### `field_skills`

در schema فعلی جدول یا Model مستقلی با نام `field_skills` وجود ندارد.

فایل `FieldSkillsSeeder` مهارت‌های میدانی را داخل همان جدول `skills` درج می‌کند و مشخصه زیر را قرار می‌دهد:

```text
skill_type = field
```

بنابراین در طراحی Wizard نباید endpoint، Model یا جدول موازی `field_skills` فرض یا ایجاد شود. تفکیک مهارت نرم‌افزاری و میدانی صرفاً با ستون `skills.skill_type` انجام می‌شود.

### جداول واسط مؤثر در Submit

#### `user_skills`

- `user_id`
- `skill_id`
- `level`
- `years_of_experience`
- `is_custom`
- `custom_title`
- unique مرکب روی `user_id + skill_id`

#### `user_profile_domains`

- `profile_id`
- `skill_domain_id`
- کلید اصلی مرکب

#### `profile_processes`

- `profile_id`
- `process_id`
- `level` با مقادیر `practical`, `proficient`, `advanced`
- کلید اصلی مرکب

---

## 12. نقاط حساس که نباید تغییر کنند

1. **قرارداد payload نهایی**
   - کلیدهای `skills`, `skill_id`, `level`, `years`, `domains` باید ثابت بمانند.

2. **endpoint و روش ارسال**
   - POST به `/save-user-skills`
   - JSON body
   - CSRF header

3. **stateهای مرکزی**
   - `selectedDomains`
   - `loadedSubdomainsByDomain`
   - `selectedSubdomains`
   - `selectedSkills`

4. **وابستگی حذف داده**
   - حذف Domain باید Subdomain و Skillهای وابسته را هماهنگ حذف کند.
   - حذف Subdomain باید Skillهای همان Subdomain را حذف کند.

5. **حدود انتخاب**
   - حداکثر ۲ Domain
   - حداکثر ۲ Subdomain
   - حداکثر ۵ Skill

6. **بارگذاری غیرهمزمان Skill**
   - مرحله‌بندی نباید باعث اجرای دوباره `onSubdomainPick()` یا duplicate شدن کارت‌ها و listenerها شود.

7. **تفکیک `skill_type`**
   - `software` و `field` هر دو از جدول `skills` می‌آیند.

8. **`sync()` در Backend**
   - Submit ناقص می‌تواند Skillهای قبلی را detach کند. Preview یا Back نباید state را بازسازی ناقص یا پاک کند.

9. **اولین پروفایل specialist**
   - Controller با `first()` پروفایل متخصص را پیدا می‌کند. UI نباید فرض کند profile ID در payload لازم است.

10. **منطق مشتق‌سازی Process**
    - Process با تطبیق دقیق نام Skill پیدا می‌شود.
    - value سطح‌ها نباید در یک تغییر UI-only عوض شود.

11. **Form Request استفاده‌نشده**
    - `StoreSkillsRequest` قرارداد صفحه فعلی نیست و نباید مبنای Wizard قرار گیرد.

12. **Modal پیشنهاد Skill**
    - فرم، endpoint و listener مستقل دارد و نباید در submit اصلی Wizard ادغام شود.

13. **عدم بازیابی انتخاب‌های موجود**
    - `index()` مهارت‌های فعلی کاربر را به View نمی‌فرستد و صفحه با state خالی شروع می‌شود. تبدیل UI نباید به‌اشتباه ادعا کند edit-state را بازیابی می‌کند.

14. **مدیریت خطا**
    - خطاها فعلاً با `alert` نمایش داده می‌شوند. تغییر محل نمایش خطا ممکن است UI-only باشد، اما mapping خطاهای nested باید دقیق حفظ شود.

15. **امنیت رندر نام Skill**
    - بخشی از کارت Skill با `innerHTML` ساخته می‌شود و نام Skill در آن interpolation شده است. هر بازطراحی باید از افزودن interpolationهای جدید برای داده پویا خودداری کند و ترجیحاً از `textContent` استفاده کند.

16. **API Subdomain**
    - endpoint موجود است اما صفحه فعلی از داده eager-loaded استفاده می‌کند. تغییر ناخواسته به API، رفتار cache/network و failure state جدیدی وارد می‌کند و برای Wizard UI-only لازم نیست.

---

## 13. آیا بدون تغییر Backend می‌توان فرم را Wizard کرد؟

بله.

دلایل:

- همه داده‌های میانی در JavaScript نگهداری می‌شوند.
- Domain و Subdomain پیش از Submit هیچ write سمت سرور ندارند.
- Skillها با fetch مستقل خوانده می‌شوند.
- تنها write اصلی در پایان و با یک payload مشخص انجام می‌شود.
- دکمه فعلی از قبل `type="button"` است و submit تحت کنترل JavaScript قرار دارد.

### رویکرد کم‌ریسک

- بخش‌های فعلی داخل wrapperهای مرحله‌ای قرار گیرند.
- هیچ element ID، state variable، تابع یا listener فعلی rename نشود.
- Next و Back فقط visibility مرحله‌ها را تغییر دهند.
- Back هیچ state یا DOM تولیدشده‌ای را reset نکند.
- فقط مرحله نهایی همان handler فعلی `saveBtn` را اجرا کند.
- endpointها، payload و validation Backend بدون تغییر بمانند.
- JavaScript فعلی فقط یک بار در `DOMContentLoaded` initialize شود.
- Modal پیشنهاد Skill خارج از navigation state اصلی باقی بماند و از هر مرحله مرتبط قابل بازشدن باشد.

### محدودیت

چون state فقط در حافظه مرورگر است، Refresh همه انتخاب‌ها را از بین می‌برد. حل این مسئله به Draft/local storage نیاز دارد و باید خارج از Sprint UI-only اولیه باشد.

---

## پیشنهاد تقسیم فرم به مراحل

پیشنهاد: **۴ مرحله**

### مرحله ۱: انتخاب حوزه

- dropdown Domain
- Domainهای انتخاب‌شده
- راهنمای حداکثر دو Domain

Validation برای Next:

- حداقل یک Domain
- حداکثر دو Domain

### مرحله ۲: انتخاب گرایش

- dropdown Subdomain
- Subdomainهای انتخاب‌شده
- وضعیت نبود Subdomain برای Domain

Validation برای Next:

- حداقل یک Subdomain
- حداکثر دو Subdomain
- همه Subdomainها متعلق به Domainهای state باشند.

### مرحله ۳: انتخاب مهارت و تجربه

- Skillهای نرم‌افزاری/پردازشی
- مهارت‌های میدانی
- جست‌وجوی مستقل
- سطح و سابقه
- CTA پیشنهاد مهارت جدید

Validation برای Next:

- حداقل یک Skill
- حداکثر پنج Skill
- هر Skill دارای level و years معتبر

### مرحله ۴: بازبینی و ثبت

- Domainهای انتخاب‌شده
- Subdomainهای انتخاب‌شده
- Skillهای انتخاب‌شده
- نوع Skill، سطح و سابقه
- لینک ویرایش هر بخش
- همان دکمه و handler فعلی ذخیره

### دلیل انتخاب چهار مرحله

- Domain و Subdomain وابسته‌اند اما هرکدام تصمیم شناختی مشخصی دارند.
- انتخاب Skill سنگین‌ترین بخش است و باید فضای مستقل داشته باشد.
- ثبت نهایی با `sync()` جایگزین‌کننده است؛ Preview مستقل پیش از ذخیره ریسک حذف ناخواسته انتخاب‌ها را کاهش می‌دهد.
- تقسیم به پنج مرحله برای حجم فعلی داده‌ها کلیک اضافی ایجاد می‌کند؛ چهار مرحله تعادل مناسب‌تری دارد.

---

## جمع‌بندی

تبدیل صفحه به Wizard از نظر معماری فعلی کاملاً ممکن است و به Backend یا Database جدید نیاز ندارد. کلید موفقیت، حفظ state و handlerهای موجود، جلوگیری از initialization دوباره، نگه‌داشتن payload فعلی و توجه ویژه به اثر `sync()` و منطق نام‌محور Processهاست.
