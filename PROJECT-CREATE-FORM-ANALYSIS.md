# گزارش فنی صفحه ثبت پروژه (Create Project)

## دامنه بررسی

این گزارش روی فرم اصلی ثبت پروژه در پنل یکپارچه کاربر متمرکز است:

- URL: `/user/projects/create`
- Route name: `user.projects.create`
- View: `resources/views/user/projects/create.blade.php`

این همان فرمی است که لینک‌های داشبورد، سایدبار و فهرست پروژه‌های کارفرما به آن اشاره می‌کنند. یک فرم ساده‌تر و موازی نیز در `/employer/projects/create` وجود دارد که در بخش «مسیر موازی» توضیح داده شده است، اما منظور اصلی از Create Project در UI فعلی، فرم `/user/projects/create` است.

---

## 1. Route صفحه ثبت پروژه

Route اصلی از یک resource route ساخته می‌شود:

```php
// routes/web.php
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        require __DIR__.'/user.php';
    });

// routes/user.php
Route::middleware('active_role:employer')->group(function () {
    Route::resource('projects', ProjectController::class);
});
```

Routeهای مؤثر:

| کاربرد | Method | URI | Route name |
|---|---|---|---|
| نمایش فرم اصلی | `GET` | `/user/projects/create` | `user.projects.create` |
| ثبت فرم اصلی | `POST` | `/user/projects` | `user.projects.store` |

Middlewareها:

- `web`
- `auth`
- `active_role:employer`

بنابراین کاربر باید وارد شده باشد، پروفایل کارفرما داشته باشد و نقش فعال session او `employer` باشد. `EnsureActiveRole` در صورت نبود نقش فعال، آن را برای کاربران تک‌پروفایله تنظیم می‌کند یا کاربر را به انتخاب نقش هدایت می‌کند.

### مسیر ساده موازی

در `routes/web.php` مسیر دیگری نیز وجود دارد:

| کاربرد | Method | URI | Route name |
|---|---|---|---|
| نمایش فرم ساده | `GET` | `/employer/projects/create` | `employer.projects.create` |
| ثبت فرم ساده | `POST` | `/employer/projects` | `employer.projects.store` |

این مسیر نیز پشت `auth` و `active_role:employer` است، اما از `createSimple()`، `storeSimple()`، `SimpleStoreProjectRequest` و View جداگانه `resources/views/employer/projects/create.blade.php` استفاده می‌کند.

---

## 2. Controller و Action

### نمایش فرم اصلی

فایل:

`app/Http/Controllers/Employer/ProjectController.php`

متد:

```php
public function create()
```

این متد:

1. حوزه‌ها را همراه `processes.skills` دریافت می‌کند:

   ```php
   SkillDomain::with('processes.skills')->orderBy('name')->get();
   ```

2. همه Skillها را با فیلدهای `id`, `name`, `skill_type`, `subdomain_id` و رابطه `subdomain.domain` دریافت می‌کند.
3. Skillها را در PHP بر اساس نام حوزه، زیرحوزه و نام Skill مرتب می‌کند.
4. داده‌ها را به `user.projects.create` می‌فرستد.

### ثبت فرم اصلی

Controller method:

```php
public function store(StoreProjectRequest $request, CreateProjectAction $action)
```

وظایف Controller:

1. دریافت داده validateشده از `StoreProjectRequest`.
2. استخراج فایل‌های آپلودی از `files`.
3. فراخوانی:

   ```php
   $action->execute(Auth::user(), $validated, $files);
   ```

4. بازگرداندن JSON موفق:

   ```json
   {
     "status": "success",
     "message": "پروژه با موفقیت ثبت شد.",
     "redirect": "/user/projects"
   }
   ```

### Action ذخیره‌سازی

فایل:

`app/Actions/Employer/CreateProjectAction.php`

متد:

```php
execute(User $user, array $data, array $files = []): Project
```

کل عملیات دیتابیس در `DB::transaction()` انجام می‌شود:

1. یافتن پروفایل `employer` کاربر.
2. ساخت Project و تولید `short_id` هشت‌کاراکتری یکتا.
3. اتصال حوزه‌ها به `project_domains`.
4. گروه‌بندی سطوح هر Process و ذخیره آرایه JSON سطوح در `project_processes.desired_levels`.
5. اتصال Skillها همراه سطح و سابقه به `project_skills`.
6. ذخیره فایل‌ها روی disk خصوصی `local`.
7. ساخت رکوردهای `project_files`.

نکته: ذخیره فایل روی filesystem داخل transaction دیتابیس انجام می‌شود، اما filesystem عضو transaction نیست. در صورت خطای دیتابیس بعد از ذخیره فایل، احتمال باقی‌ماندن فایل orphan وجود دارد.

---

## 3. فایل رابط کاربری

فرم اصلی کاملاً Blade و JavaScript خالص است:

`resources/views/user/projects/create.blade.php`

- Framework UI: Blade + Bootstrap/EngiPi Blueprint
- انتخاب‌های چندگانه: `Choices.js`
- Submit: `fetch` + `FormData`
- پیام‌ها: helper سراسری `window.showToast` در `resources/views/layouts/vendor-scripts.blade.php`
- Vue یا React در این فرم استفاده نشده است.

فرم ساده موازی:

`resources/views/employer/projects/create.blade.php`

---

## 4. همه فیلدهای فرم فعلی

### فیلدهای واقعی ارسال‌شونده

| فیلد | نوع UI | اجباری در UI | توضیح |
|---|---|---:|---|
| `_token` | hidden | بله | CSRF |
| `title` | text | بله | عنوان پروژه |
| `description` | textarea | بله | شرح فنی پروژه |
| `work_type` | radio | بله | `remote`, `onsite`, `hybrid` |
| `domains[]` | hidden، ساخته‌شده با JS | بله | UUID حوزه‌های انتخابی، ۱ تا ۳ مورد |
| `processes[n][id]` | hidden، ساخته‌شده با JS | بله در Backend | UUID پردازش |
| `processes[n][level]` | hidden، ساخته‌شده با JS | بله | `practical`, `proficient`, `advanced`؛ برای یک Process چند سطح ممکن است |
| `skills[n][id]` | hidden، ساخته‌شده با JS | خیر | UUID مهارت میدانی |
| `skills[n][level]` | hidden، ساخته‌شده با JS | اگر Skill انتخاب شود | مقدار متنی سطح |
| `skills[n][years_of_experience]` | hidden، ساخته‌شده با JS | اگر Skill انتخاب شود | سابقه ۰ تا ۵۰ سال |
| `duration_days` | number | خیر | مدت پروژه به روز |
| `budget_min` | hidden | خیر | حداقل بودجه؛ مقدار خام بدون separator |
| `budget_max` | hidden | خیر | حداکثر بودجه؛ مقدار خام بدون separator |
| `files[]` | file multiple | خیر | فایل‌های پیوست |

### کنترل‌های UI که مستقیماً Submit نمی‌شوند

| کنترل | کاربرد |
|---|---|
| `domain-search-input` | جست‌وجوی بصری کارت‌های حوزه |
| checkboxهای `.domain-checkbox` | انتخاب حوزه؛ JS در زمان submit آنها را به `domains[]` تبدیل می‌کند |
| `processes` | select چندگانه Choices.js؛ خود select name ندارد |
| checkboxهای سطح Process | JS آنها را به `processes[n][...]` تبدیل می‌کند |
| `skills-search-input` | فیلتر بصری مهارت‌ها |
| `skills` | select چندگانه Choices.js؛ خود select name ندارد |
| `.skill-level` | سطح Skill؛ در زمان submit به hidden input تبدیل می‌شود |
| `.skill-years` | سابقه Skill؛ در زمان submit به hidden input تبدیل می‌شود |
| `budget_min` و `budget_max` نمایشی | inputهای text بدون name برای نمایش جداکننده هزارگان |

### فیلد موجود در Backend ولی غایب در فرم اصلی

`deadline_date` در `StoreProjectRequest`، `CreateProjectAction`، Model و جدول `projects` پشتیبانی می‌شود، اما در `resources/views/user/projects/create.blade.php` هیچ input مربوط به آن وجود ندارد. این فیلد در فرم ساده `/employer/projects/create` نمایش داده می‌شود.

همچنین `seo_title` و `seo_description` در Model/Table وجود دارند، ولی بخشی از فرم ثبت فعلی یا Action ثبت نیستند.

---

## 5. Validation فیلدها

مرجع Backend:

`app/Http/Requests/Employer/StoreProjectRequest.php`

| فیلد | Validation سمت Backend | Validation/رفتار سمت Client |
|---|---|---|
| `title` | `required`, `string`, `max:191` | `required`, `minlength=5`, `maxlength=255` |
| `description` | `required`, `string` | `required`, `minlength=20` و شمارنده |
| `work_type` | `required`, یکی از `remote/onsite/hybrid` | radio required |
| `domains` | `required`, `array`, `min:1`, `max:3` | JS حداقل ۱ و حداکثر ۳ |
| `domains.*` | `required`, `uuid`, `exists:skill_domains,id` | UUID از data attribute سرور |
| `processes` | `required`, `array`, `min:1` | JS برای خود تعداد Process حداقل ۱ را enforce نمی‌کند |
| `processes.*.id` | `required`, `uuid`, `exists:processes,id` | hidden input ساخته‌شده از داده سرور |
| `processes.*.level` | `required`, یکی از `practical/proficient/advanced` | حداقل یک level برای هر کارت انتخابی |
| `skills` | `nullable`, `array` | اختیاری |
| `skills.*.id` | `required`, `uuid`, `exists:skills,id` | hidden input از Skill انتخابی |
| `skills.*.level` | `required`, `string`, `max:50` | select سطح؛ وجود مقدار بررسی می‌شود |
| `skills.*.years_of_experience` | `required`, `integer`, `min:0`, `max:50` | number با min/max و بررسی JS |
| `duration_days` | `nullable`, `integer`, `min:1` | number با `min=1` |
| `deadline_date` | `nullable`, `date`, `after:today` | در فرم اصلی وجود ندارد |
| `budget_min` | `nullable`, `numeric`, `min:0` | فقط رقم ASCII، فرمت هزارگان |
| `budget_max` | `nullable`, `numeric`, `min:0`, `gte:budget_min` | `setCustomValidity` برای max < min |
| `files` | `nullable`, `array` | input multiple |
| `files.*` | `bail`, `file`, `max:10240`, `AllowedProjectDocument`, `InspectedProjectDocument` | فقط متن «حداکثر ۱۰MB»؛ accept یا validation پیشرفته client ندارد |

### Validation رابطه‌ای

در `withValidator()` بررسی می‌شود که تمام Processهای ارسال‌شده متعلق به یکی از Domainهای ارسال‌شده باشند.

برای Skillها در فرم اصلی بررسی Backend وجود ندارد که Skill انتخابی حتماً متعلق به Domain انتخابی باشد. UI Skillها را بر اساس `work_type` فیلتر می‌کند، نه بر اساس Domain انتخابی. بنابراین UUID معتبر هر Skill از نظر Request پذیرفته می‌شود، حتی اگر با حوزه‌های پروژه نامرتبط باشد.

### Validation امنیت فایل

هر فایل:

- حداکثر ۱۰MB
- extension/MIME مجاز:
  - PDF
  - TXT
  - CSV
  - DOC/DOCX
  - XLS/XLSX
  - ZIP
- بررسی MIME واقعی با `AllowedProjectDocument`
- اسکن fail-closed توسط `ProjectDocumentInspector`
- implementation فعلی: ClamAV از طریق socket
- در دسترس نبودن scanner، timeout، خطای inspection یا malware همگی باعث رد فایل می‌شوند.

---

## 6. Model و Tableهای ذخیره‌سازی

### Model اصلی

`app/Models/Project.php`

جدول:

`projects`

فیلدهای اصلی ثبت‌شده:

- `id` UUID
- `short_id`
- `employer_id`
- `employer_profile_id`
- `title`
- `description`
- `work_type`
- `view_count` با مقدار اولیه صفر
- `duration_days`
- `deadline_date`
- `budget_min`
- `budget_max`
- timestamps

### جداول وابسته

| داده | Model/Relation | Table |
|---|---|---|
| حوزه‌ها | `Project::domains()` | `project_domains` |
| پردازش‌ها/تخصص‌های پردازشی | `Project::processes()` | `project_processes` |
| مهارت‌ها | `Project::skills()` | `project_skills` |
| فایل‌ها | `Project::files()` / `ProjectFile` | `project_files` |
| کارفرما | `Project::employer()` | `users` |
| پروفایل کارفرما | `Project::employerProfile()` | `user_profiles` |

### Pivotها

`project_domains`:

- `project_id`
- `skill_domain_id`
- timestamps
- primary مرکب

`project_processes`:

- `project_id`
- `process_id`
- `desired_levels` به صورت JSON
- timestamps
- primary مرکب

`project_skills`:

- `project_id`
- `skill_id`
- `level`
- `years_of_experience`
- timestamps
- primary مرکب

`project_files`:

- UUID `id`
- `project_id`
- `path`
- `storage_disk`
- `original_name`
- `mime_type`
- `size`
- timestamps

---

## 7. ارتباط فرم با داده‌های تخصصی، بودجه، زمان و فایل

### حوزه تخصصی

- داده از `SkillDomain` خوانده می‌شود.
- کاربر ۱ تا ۳ حوزه را انتخاب می‌کند.
- انتخاب‌ها هنگام submit به `domains[]` تبدیل می‌شوند.
- در `project_domains` ذخیره می‌شوند.

### تخصص/پردازش

در نام‌گذاری فعلی پروژه، `Process` نزدیک‌ترین مفهوم به «تخصص/پردازش تخصصی» است:

- `SkillDomain::processes()` منبع داده است.
- Processهای موجود هر Domain در `data-processes` کارت Domain serialize می‌شوند.
- با انتخاب Domain، JS مجموعه Processهای قابل انتخاب را بازسازی می‌کند.
- حداکثر سه Process در UI قابل انتخاب است، ولی Backend فقط حداقل یک مورد را enforce می‌کند و max ندارد.
- هر Process می‌تواند چند سطح مطلوب داشته باشد.
- Action سطوح تکراری یک Process را گروه‌بندی و JSON می‌کند.

### مهارت‌ها

- همه Skillها در Controller load می‌شوند.
- گروه‌بندی نمایشی بر اساس `Domain > Subdomain` است.
- Skillها بر اساس `work_type` فیلتر می‌شوند:
  - حضوری: فقط `skill_type=field`
  - دورکاری: فقط `skill_type=software`
  - ترکیبی: همه Skillها
- تغییر `work_type` می‌تواند Skillهای انتخاب‌شده‌ای را که دیگر معتبر دیده نمی‌شوند، از state حذف کند.
- Skillها اختیاری‌اند؛ در صورت انتخاب، سطح و سابقه الزامی است.
- ذخیره در `project_skills` انجام می‌شود.

### بودجه

- دو input نمایشی text برای separator هزارگان وجود دارد.
- دو hidden input با nameهای واقعی `budget_min` و `budget_max` Submit می‌شوند.
- Backend اعداد غیرمنفی و `max >= min` را بررسی می‌کند.
- ستون‌ها `decimal(12,2)` هستند و واحد UI «تومان» است؛ واحد در دیتابیس به‌صورت جداگانه ذخیره نمی‌شود.

### زمان

- `duration_days` در فرم اصلی وجود دارد و اختیاری است.
- `deadline_date` در Backend/Table وجود دارد ولی در فرم اصلی غایب است.
- هیچ قانون رابطه‌ای بین `duration_days` و `deadline_date` وجود ندارد.

### فایل‌ها

- چند فایل با `files[]` ارسال می‌شوند.
- validation و ClamAV قبل از Action انجام می‌شود.
- فایل‌ها در `storage/app/private` متناظر با disk `local` و مسیر `project-files/{project_uuid}` ذخیره می‌شوند.
- metadata در `project_files` ثبت می‌شود.

---

## 8. JavaScript و Componentهای مرتبط

تمام JavaScript اختصاصی داخل همان Blade قرار دارد:

`resources/views/user/projects/create.blade.php`

بخش‌های اصلی:

1. `allSkillsData`: داده serializeشده Skillها برای فیلتر frontend.
2. `createChipCardSelector()`: wrapper مشترک روی `Choices.js`.
3. stateهای JS:
   - `selectedSkillsState`
   - `selectedProcessesState`
   - `allProcessesMap`
4. `renderSkillCard()` / `removeSkillCard()`
5. جست‌وجوی Domain.
6. جست‌وجوی Skill.
7. `filterSkillsByWorkType()`
8. `renderProcessCard()` / `removeProcessCard()`
9. `updateProcessesOptions()`
10. شمارنده description.
11. فرمت budget و sync با hidden input.
12. `buildHiddenInputs()`
13. `showErrors()`
14. submit AJAX با `fetch`.

وابستگی‌ها:

- `choices.js` در `package.json`
- Bootstrap
- Toastify از طریق `window.showToast`
- assetهای عمومی در `layouts/vendor-scripts.blade.php`

نکته حساس: Blade به‌طور مستقیم asset اختصاصی Choices.js را import نمی‌کند و انتظار دارد `Choices` در bundle/assetهای عمومی در دسترس باشد. اگر bundle تغییر کند یا Choices حذف شود، انتخاب Process و Skill مختل می‌شود.

Vue/React component وجود ندارد.

---

## 9. روند فعلی Submit

1. کاربر فیلدهای معمولی را تکمیل می‌کند.
2. Domain، Process و Skill در state و کنترل‌های سفارشی JS نگهداری می‌شوند.
3. event `submit` رفتار native را متوقف می‌کند.
4. `buildHiddenInputs()`:
   - hidden inputهای قبلی Domain/Process/Skill را حذف می‌کند.
   - Domainهای انتخابی را به `domains[]` تبدیل می‌کند.
   - Process و levelهای انتخابی را به آرایه `processes` تبدیل می‌کند.
   - Skillها، level و years را به آرایه `skills` تبدیل می‌کند.
5. دکمه غیرفعال و spinner نمایش داده می‌شود.
6. `new FormData(form)` ساخته می‌شود؛ فایل‌ها نیز در آن قرار می‌گیرند.
7. POST با `fetch(form.action)` به `/user/projects` ارسال می‌شود.
8. Headerهای CSRF و `Accept: application/json` ارسال می‌شوند.
9. `StoreProjectRequest` authorization و validation را اجرا می‌کند.
10. فایل‌ها اسکن می‌شوند.
11. `CreateProjectAction` Project، pivotها و فایل‌ها را ثبت می‌کند.
12. در موفقیت toast نمایش داده شده و پس از ۱۲۰۰ms به `user.projects.index` redirect می‌شود.
13. در خطای 422، `showErrors()` خطاها را روی section/field مربوط نشان می‌دهد.
14. در خطای شبکه یا خطای دیگر toast خطا نمایش داده و دکمه دوباره فعال می‌شود.

فرم از repopulation مبتنی بر `old()` استفاده نمی‌کند، چون submit اصلی AJAX است و سرور JSON برمی‌گرداند. state تا زمانی که صفحه reload نشود در DOM باقی می‌ماند.

---

## 10. امکان تبدیل به Wizard بدون تغییر Backend/Database

بله. فرم را می‌توان بدون تغییر Backend، Controller، Request، Action، Model، route یا دیتابیس به Wizard چندمرحله‌ای تبدیل کرد.

دلیل:

- Backend فقط payload نهایی را می‌بیند و به ترتیب نمایش فیلدها وابسته نیست.
- همه مراحل می‌توانند داخل همان `<form>` باقی بمانند.
- در حرکت بین مراحل هیچ request لازم نیست.
- در مرحله آخر همان `buildHiddenInputs()`, `FormData` و `fetch` فعلی قابل استفاده است.
- فایل انتخاب‌شده تا زمانی که همان DOM input حفظ شود، در input باقی می‌ماند.

روش کم‌ریسک این است که sectionهای موجود فقط داخل containerهای مرحله‌ای قرار بگیرند و با CSS/JS مخفی و نمایش داده شوند؛ inputها نباید destroy، clone یا خارج از form منتقل شوند.

---

## 11. نقاط حساس و ریسک Bug

### 11.1 ناسازگاری Validation عنوان

- HTML: `maxlength=255`
- Backend: `max:191`
- HTML: `minlength=5`
- Backend: حداقل طول ندارد

Wizard باید قواعد مرحله‌ای خود را با Backend هماهنگ کند؛ در غیر این صورت کاربر ممکن است مرحله را رد کند ولی در submit نهایی 422 بگیرد.

### 11.2 ناسازگاری توضیحات

- HTML حداقل ۲۰ کاراکتر دارد.
- Backend فرم اصلی فقط `required|string` است.
- فرم ساده Backend `min:20` دارد.

### 11.3 Process در Backend اجباری ولی در JS عملاً enforce نشده

کامنت `buildHiddenInputs()` می‌گوید Process اختیاری است و انتخاب صفر Process را معتبر می‌داند، اما `StoreProjectRequest` دارای `required|array|min:1` است. نتیجه، خطای 422 در submit نهایی است.

### 11.4 محدودیت سه Process فقط در UI

UI حداکثر سه Process را enforce می‌کند، اما Backend برای `processes` max ندارد. این یک constraint صرفاً frontend است.

### 11.5 Skill با Domain در Backend cross-check نمی‌شود

Processها با Domain بررسی می‌شوند، ولی Skillهای فرم اصلی فقط باید UUID موجود باشند. در صورت دستکاری request می‌توان Skill نامرتبط با Domain ثبت کرد.

### 11.6 تغییر Work Type، Skillهای انتخابی را حذف می‌کند

`filterSkillsByWorkType()` Skillهای ناسازگار را از state و کارت‌ها حذف می‌کند. در Wizard اگر Work Type در مرحله‌ای قبل از Skill باشد، بازگشت و تغییر آن باید هشدار واضح داشته باشد، وگرنه کاربر بدون اطلاع انتخاب‌های مرحله مهارت را از دست می‌دهد.

### 11.7 فیلد Deadline غایب

Backend و Database آن را پشتیبانی می‌کنند اما فرم اصلی نمایش نمی‌دهد. طراحی Wizard نباید به اشتباه آن را یک تغییر Backend تلقی کند؛ افزودن input می‌تواند بدون Backend change باشد، ولی این کار خارج از تبدیل صرف layout محسوب می‌شود و باید تصمیم محصول باشد.

### 11.8 hidden inputهای پویا

Domain/Process/Skill name واقعی ندارند و payload فقط در `buildHiddenInputs()` ساخته می‌شود. هر refactor که این تابع را حذف، زودتر اجرا یا containerها را clone کند می‌تواند payload را خراب کند.

### 11.9 state دوگانه Choices + کارت‌ها + objectهای JS

برای Process و Skill سه منبع state وجود دارد:

- Choices/select
- کارت‌های DOM
- objectهای `selected...State`

Wizard نباید هنگام hide/show کردن مرحله، Choices را دوباره initialize کند؛ initialization دوباره می‌تواند eventهای تکراری، selection تکراری یا از دست رفتن state بسازد.

### 11.10 فایل‌ها

- input فایل نباید clone یا بازسازی شود؛ browser مقدار آن را پاک می‌کند.
- اسکن ClamAV ممکن است submit مرحله آخر را طولانی کند.
- scanner fail-closed است؛ unavailable بودن ClamAV باعث رد کل request می‌شود.
- filesystem و DB transaction واحد نیستند.

### 11.11 نمایش خطا در مرحله مخفی

`showErrors()` اکنون به element خطادار scroll می‌کند. در Wizard، قبل از scroll باید مرحله حاوی اولین خطا فعال شود؛ در غیر این صورت خطا داخل step مخفی باقی می‌ماند.

### 11.12 Submit با Enter

وجود دکمه‌های «بعدی» داخل form باید با `type="button"` باشد. فقط دکمه نهایی باید `type="submit"` بماند تا Enter باعث submit زودهنگام نشود.

### 11.13 browser validation روی stepهای مخفی

فیلدهای HTML required در step مخفی ممکن است validation native را متوقف کنند ولی قابل focus نباشند (`invalid form control is not focusable`). قبل از submit نهایی باید step خطادار نمایش داده شود یا navigation validation درست انجام شود.

### 11.14 مسیر فرم ساده موازی

دو Create flow با View و Request متفاوت وجود دارد. تبدیل فقط فرم اصلی به Wizard مسیر ساده را تغییر نمی‌دهد، اما اختلاف رفتارها باقی می‌ماند:

- فرم ساده `work_type=remote` را تحمیل می‌کند.
- دقیقاً یک Domain می‌گیرد.
- Process و فایل ندارد.
- deadline دارد.
- submit معمولی و redirect server-side است.

### 11.15 داده حجیم اولیه

Controller همه Skillها و Domainها همراه Processها را در اولین render بارگذاری و بخشی را JSON می‌کند. Wizard این هزینه را کم نمی‌کند؛ فقط نمایش را مرحله‌بندی می‌کند.

---

## 12. تقسیم‌بندی پیشنهادی Wizard

پیشنهاد کم‌ریسک: ۵ مرحله.

### مرحله ۱: معرفی پروژه

- `title`
- `description`

Validation قبل از Next:

- عنوان required و حداکثر ۱۹۱
- توضیحات required
- در صورت حفظ UX فعلی، حداقل ۲۰ کاراکتر توضیح

### مرحله ۲: نحوه اجرا و حوزه تخصصی

- `work_type`
- انتخاب `domains`، حداقل ۱ و حداکثر ۳

دلیل هم‌گروهی: نوع اجرا روی فیلتر Skillهای مرحله بعد اثر دارد و Domain روی Processها اثر می‌گذارد.

### مرحله ۳: تخصص‌ها / پردازش‌های مورد نیاز

- انتخاب Process
- انتخاب یک یا چند level برای هر Process

Validation:

- حداقل یک Process
- هر Process حداقل یک level
- حداکثر سه Process مطابق رفتار UI فعلی

### مرحله ۴: مهارت‌های میدانی و شرایط پروژه

- Skillهای اختیاری
- level و years برای Skillهای انتخابی
- `duration_days`
- `budget_min`
- `budget_max`
- در صورت تصمیم محصول: `deadline_date` که Backend از قبل پشتیبانی می‌کند

این مرحله اختیاری/نیمه‌اختیاری است و امکان Skip برای Skillها باید وجود داشته باشد.

### مرحله ۵: فایل‌ها و بازبینی نهایی

- `files[]`
- خلاصه خواندنی از:
  - عنوان
  - نوع همکاری
  - حوزه‌ها
  - Processها و levelها
  - Skillها و سابقه
  - بودجه و مدت
  - نام فایل‌های انتخابی
- checkbox تأیید صحت اطلاعات صرفاً در صورت نیاز UX؛ نباید بدون تصمیم محصول به payload/Backend اضافه شود.
- دکمه نهایی «ثبت پروژه»

---

## Recommended Implementation Plan

کم‌ریسک‌ترین روش، تبدیل تدریجی همان Blade به یک Wizard سمت Client بدون تغییر قرارداد Backend است:

1. قبل از تغییر، برای payload موفق فرم اصلی و خطاهای 422 تست Feature/Browser characterization اضافه شود.
2. یک wrapper ثابت داخل همان `<form id="projectForm">` ایجاد شود.
3. کارت‌های فعلی بدون بازنویسی fieldها داخل پنج container با `data-step` قرار گیرند.
4. header مرحله‌ها، progress bar و دکمه‌های «قبلی/بعدی» اضافه شوند.
5. دکمه‌های navigation حتماً `type="button"` و فقط `submitBtn` نهایی `type="submit"` باشد.
6. instanceهای Choices فقط یک‌بار در `DOMContentLoaded` ساخته شوند؛ تغییر step صرفاً با class/CSS انجام شود.
7. یک تابع `validateStep(stepIndex)` اضافه شود که فقط navigation را کنترل کند و قواعد آن دقیقاً با `StoreProjectRequest` هماهنگ باشد.
8. تابع فعلی `buildHiddenInputs()`، ساخت `FormData`، `fetch`, `showErrors()` و response contract بدون تغییر باقی بمانند.
9. `showErrors()` توسعه داده شود تا از روی field/error key مرحله متناظر را فعال کند و سپس scroll انجام دهد.
10. input فایل در DOM ثابت بماند و هیچ‌وقت clone/re-render نشود.
11. state انتخاب‌ها هنگام رفت‌وبرگشت بین مراحل حفظ شود.
12. پیش از Wizard یا هم‌زمان با آن، سه ناسازگاری کم‌ریسک UI اصلاح شوند:
    - `maxlength` عنوان از ۲۵۵ به ۱۹۱
    - enforce حداقل یک Process در navigation/submit client
    - تصمیم صریح درباره نمایش یا عدم نمایش `deadline_date`
13. مسیر ساده `/employer/projects/create` در فاز اول دست‌نخورده بماند تا scope محدود باشد.
14. تست‌های نهایی حداقل شامل desktop/mobile navigation، بازگشت بین مراحل، تغییر Work Type پس از انتخاب Skill، خطای مرحله مخفی، حفظ فایل و submit موفق multipart باشند.

این رویکرد کم‌ریسک است چون فقط لایه نمایش و navigation را تغییر می‌دهد و payload نهایی، route، Request، Controller، Action، Model و Database فعلی را حفظ می‌کند.
