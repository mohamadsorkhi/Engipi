# CLAUDE.md — پروژه EngPis (engpis.ir)

## معرفی پروژه
EngPis یک مارکت‌پلیس تخصصی برای پروژه‌های فنی-مهندسی-دانشگاهی در ایران است.
کارفرماها پروژه‌های مهندسی (متلب، آنسیس، پایتون، پایان‌نامه و...) ثبت می‌کنند
و متخصصان بر اساس مهارت و حوزه‌شان با پروژه‌ها match می‌شوند.

**سایت:** https://engpis.ir
**هدف فعلی:** MVP برای جذب کاربر اولیه

---

## Stack فنی

- **Framework:** Laravel 11
- **PHP:** 8.2+
- **Auth:** Laravel Sanctum + Laravel UI
- **Permissions:** Spatie Laravel Permission (نصب شده، استفاده ناقص)
- **Frontend:** Velzon (Bootstrap 5) + Vite
- **تاریخ شمسی:** morilog/jalali
- **ID ها:** UUID در همه مدل‌ها (incrementing = false)
- **Package manager:** Composer (backend) + npm (frontend)

---

## معماری پروژه

### ساختار پوشه‌ها
```
app/
  Actions/          ← منطق business logic (جدا از Controller)
    Admin/
    Auth/
    Employer/
    Specialist/
    Ticket/
  Http/
    Controllers/
      Admin/
      Auth/
      Employer/
      Specialist/
      User/         ← Unified (هم employer هم specialist)
      Worker/       ← قدیمی، در حال حذف
    Middleware/
  Models/
routes/
  web.php           ← route اصلی
  admin.php         ← پنل ادمین (prefix: /admin)
  user.php          ← unified route فعال (prefix: /user)
  employer.php      ← قدیمی، استفاده نمی‌شود
  specialist.php    ← قدیمی، استفاده نمی‌شود
  worker.php        ← قدیمی، استفاده نمی‌شود
  api.php           ← خالی، باید ساخته شود
```

### مدل‌های اصلی
- **User** — یوزر اصلی (UUID، لاگین با email یا mobile)
- **UserProfile** — پروفایل کاربر (type: 'employer' یا 'specialist'، یک یوزر می‌تواند هر دو داشته باشد)
- **Project** — پروژه مهندسی (توسط employer ثبت می‌شود)
- **Request** — درخواست همکاری (status: pending/accepted/rejected)
- **Skill** — مهارت‌ها
- **SkillDomain** — حوزه‌های تخصصی (مثلاً مکانیک، برق، کامپیوتر)
- **Process** — فرآیندها/ابزارها (مثلاً MATLAB، ANSYS، Python)
- **Ticket / TicketMessage** — سیستم پشتیبانی

### روابط مهم
```
User → hasMany → UserProfile (type: employer | specialist)
User → hasMany → Project (as employer)
User → hasMany → Request (as specialist)
Project → belongsToMany → SkillDomain (project_domains)
Project → belongsToMany → Process (project_processes, with desired_levels JSON)
UserProfile → belongsToMany → SkillDomain (user_profile_domains)
UserProfile → belongsToMany → Process (profile_processes, with level enum)
```

---

## سیستم نقش‌ها (مهم!)

سه لایه موازی وجود دارد — باید یکپارچه شوند:

1. **فیلد `role`** در جدول users (مقادیر: employer | specialist) — قدیمی
2. **فیلد `is_admin`** در جدول users — برای ادمین
3. **Spatie Permissions** — نصب است ولی کامل پیاده‌سازی نشده

**قانون فعلی:**
- ادمین: `is_admin = true`
- کاربر عادی: هر دو پروفایل employer و specialist می‌تواند داشته باشد
- دسترسی‌ها از طریق `ProfileMiddleware` چک می‌شود (نه role)

---

## Middleware های فعال

| نام | کار |
|-----|-----|
| `admin` | چک می‌کند `is_admin = true` |
| `profile:employer` | چک می‌کند پروفایل employer دارد |
| `profile:specialist` | چک می‌کند پروفایل specialist دارد |
| `role` | چک فیلد role (کمتر استفاده می‌شود) |

---

## الگوریتم Matching (مهم‌ترین feature)

در `App\Models\Project::scopeForWorkerMatches()`:
- متخصص بر اساس **Domain + Process + Level** با پروژه‌ها match می‌شود
- پروژه‌هایی که متخصص قبلاً رد شده فیلتر می‌شوند
- از `JSON_CONTAINS` روی فیلد `desired_levels` (JSON) استفاده می‌کند

**نکته performance:** ایندکس‌های `process_id` روی جداول pivot اضافه نشده‌اند.

---

## دستورات مهم

```bash
# اجرای محلی
php artisan serve
npm run dev

# Migration
php artisan migrate
php artisan migrate:fresh --seed

# Cache پاک کردن
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Build فرانت
npm run build
```

---

## نکات مهم برای توسعه

1. **همیشه UUID** استفاده کن، نه integer ID
2. **تاریخ‌ها** باید شمسی نمایش داده شوند (از `morilog/jalali` استفاده کن)
3. **Action classes** را برای business logic بنویس، نه مستقیم در Controller
4. **Route های قدیمی** (employer.php، specialist.php، worker.php) را ویرایش نکن — فقط `user.php` و `admin.php` فعال هستند
5. **API** هنوز ساخته نشده — باید از صفر با Sanctum token-based ساخته شود
6. **پیام‌های خطا** باید فارسی باشند
7. **فیلد `active`** در User وجود دارد — یوزرهای غیرفعال نباید لاگین کنند (هنوز enforce نشده)

---

## اولویت‌های MVP فعلی

1. بهبود UI/UX صفحه اصلی و ثبت‌نام
2. کامل کردن flow ثبت پروژه و پروفایل متخصص
3. بهبود صفحه matched-projects
4. اضافه کردن راه ارتباطی ساده بین طرفین بعد از accept
5. ساختن API برای موبایل (فاز بعدی)

---

## چیزهایی که هنوز وجود ندارند (نساز مگر خواسته شود)

- پرداخت آنلاین
- چت real-time
- اپ موبایل
- سیستم امتیازدهی
- نوتیفیکیشن email/SMS
