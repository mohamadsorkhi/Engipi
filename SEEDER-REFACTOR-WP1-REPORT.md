# گزارش بازطراحی Seederها — WP1

## دامنه کار

فقط Seederهای taxonomy مهارت بررسی و اصلاح شدند. هیچ Controller، Model، View، Route، API، migration یا داده taxonomy تغییر نکرد.

## فایل‌های تغییرکرده

- `database/seeders/SkillDomainSeeder.php`
- `database/seeders/SubdomainSeeder.php`
- `database/seeders/SkillSeeder.php`
- `database/seeders/FieldSkillsSeeder.php`
- `database/seeders/DomainSeeder.php` (فقط DocBlock deprecation)
- `database/seeders/EngineeringSkillsSeeder.php` (فقط DocBlock deprecation)
- `database/seeders/SkillsDataSeeder.php` (فقط DocBlock deprecation)

`DatabaseSeeder.php` بررسی شد و از قبل ترتیب مرجع صحیح زیر را داشت؛ تغییری لازم نبود: SkillDomainSeeder، SubdomainSeeder، SkillSeeder، FieldSkillsSeeder.

## truncateها و Foreign Key handling حذف‌شده

تمام `truncate()`ها و `SET FOREIGN_KEY_CHECKS` از چهار Seeder مرجع حذف شدند. Seederها دیگر هیچ‌یک از جدول‌های کاربری، پروژه‌ای یا taxonomy موجود را پاک نمی‌کنند.

## حفظ UUIDهای قبلی

پیش از insert، رکورد موجود با کلید طبیعی مناسب جست‌وجو می‌شود و ID آن بدون تغییر استفاده می‌شود:

- Domain: `name`
- Subdomain: `skill_domain_id + name`
- Process: `skill_domain_id + name`
- Skill: `skill_type + subdomain_id + name`

UUID فقط برای رکورد واقعاً جدید تولید می‌شود. اگر `process_id` یک Skill نرم‌افزاری موجود ناهماهنگ باشد، همان Skill و UUID حفظ و فقط ارتباط process اصلاح می‌شود.

## جلوگیری از داده تکراری

- همه عملیات هر Seeder داخل transaction انجام می‌شود.
- parentهای Domain/Subdomain پیش از insert کامل اعتبارسنجی می‌شوند؛ نبود parent باعث exception و rollback می‌شود.
- pivot `skill_subdomain` با `insertOrIgnore` و unique موجود جدول روی `(skill_id, subdomain_id)` ایجاد می‌شود.
- ارتباط‌های قبلی detach یا حذف نمی‌شوند.
- آرایه‌های Domain، Subdomain، Software Skill و Field Skill با نسخه اصلی Git مقایسه شدند و بدون تغییر هستند.

## Seederهای قدیمی

`DomainSeeder`، `EngineeringSkillsSeeder` و `SkillsDataSeeder` از `DatabaseSeeder` فراخوانی نمی‌شوند و حذف نشدند. روی هر سه DocBlock زیر اضافه شد:

```php
/**
 * @deprecated
 * Legacy seeder. Do not call from DatabaseSeeder.
 */
```

## نتیجه اجرای دوباره

آزمایش روی SQLite موقت و جداگانه انجام شد؛ محیط برنامه `production` بود و دیتابیس اصلی عمداً لمس نشد.

| جدول | بعد از اجرای اول | بعد از اجرای دوم |
|---|---:|---:|
| users | 1 | 1 |
| user_profiles | 0 | 0 |
| user_skills | 0 | 0 |
| projects | 0 | 0 |
| project_skills | 0 | 0 |
| skill_domains | 16 | 16 |
| subdomains | 78 | 78 |
| skills | 1303 | 1303 |
| skill_subdomain | 1303 | 1303 |
| processes | 386 | 386 |

اجرای دوم:

- Domain جدید: 0
- Subdomain جدید: 0
- Process جدید: 0
- Software Skill جدید: 0
- Field Skill جدید: 0
- Pivot جدید: 0
- duplicate بر اساس همه کلیدهای طبیعی بالا: 0
- UUIDهای Domain/Subdomain/Process/Skill: کاملاً ثابت

## تست‌ها

- `php -l` برای هر 8 Seeder: موفق
- migration کامل روی SQLite موقت: موفق
- دو اجرای پیاپی `php artisan db:seed --force`: موفق
- `SkillSuggestionWorkflowTest`: 9 تست و 30 assertion موفق

## تأیید عدم حذف اطلاعات

در هیچ‌یک از چهار Seeder مرجع دستور delete، truncate یا غیرفعال‌سازی Foreign Key باقی نمانده است. count جدول‌های کاربری و پروژه‌ای در اجرای اول و دوم ثابت ماند. بنابراین در تست انجام‌شده هیچ اطلاعات کاربر یا پروژه حذف نشده است.

## ریسک‌ها و ابهام‌های باقی‌مانده

- برای Skill کلید unique دیتابیسی روی `(skill_type, subdomain_id, name)` وجود ندارد. اجرای معمول و ترتیبی Seeder idempotent است، اما دو اجرای کاملاً هم‌زمان می‌توانند race condition ایجاد کنند. طبق محدودیت WP1 migration جدید ساخته نشد.
- دیتابیس production برای آزمون دست‌نخورده ماند؛ پیش از rollout بهتر است همین سناریو روی staging با snapshot/backup اجرا شود.

## پیشنهاد مرحله بعد — بدون اجرا

در WP2 پس از بررسی duplicateهای احتمالی staging، unique indexهای طبیعی برای Subdomain و Skill اضافه شوند و برای rollout یک dry-run/verification command در نظر گرفته شود.