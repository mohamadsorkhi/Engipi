# گزارش استانداردسازی رابطه Skill/Subdomain — WP3

## تصمیم معماری

رابطه canonical آینده بین Skill و Subdomain، جدول pivot زیر است:

```text
skill_subdomain(skill_id, subdomain_id)
```

ستون legacy زیر در WP3 حفظ شده و هیچ مقدار آن تغییر نکرده است:

```text
skills.subdomain_id
```

هیچ Model، Controller، API یا UI در این مرحله تغییر نکرد؛ بنابراین مصرف‌کنندگان فعلی همچنان بدون شکست کار می‌کنند.

## 1. وضعیت production قبل از Backfill

Audit فقط با queryهای read-only و dry-run انجام شد. Command نوشتنی روی production اجرا نشد.

| شاخص | تعداد |
|---|---:|
| کل Skillها | 1303 |
| Skill دارای `subdomain_id` | 1303 |
| Skill بدون `subdomain_id` | 0 |
| رابطه موجود در `skill_subdomain` | 0 |
| رابطه مستقیم معتبر | 1303 |
| رابطه مستقیم با Subdomain نامعتبر | 0 |
| رابطه از قبل همسان در pivot | 0 |
| رابطه لازم برای Backfill | 1303 |

Dry-run production:

```text
Skills checked: 1303
Relations pending: 1303
Already existing: 0
Skipped: 0
```

اجرای بدون `--dry-run` و بدون `--force` در محیط production عمداً مسدود شد و هیچ داده‌ای تغییر نکرد.

## 2. Skillهای بدون Subdomain

تعداد: صفر.

Query گزارش، برای هر Skill با `subdomain_id IS NULL` این موارد را استخراج می‌کند:

- ID
- نام Skill
- `skill_type`
- `process_id`
- Domain احتمالی از مسیر `processes.skill_domain_id`

در production فعلی هیچ ردیفی برای گزارش وجود نداشت. Command در صورت مشاهده چنین Skillهایی فقط جدول گزارش چاپ می‌کند، آن‌ها را skip می‌کند و هیچ Subdomainی assign نمی‌کند.

## 3. Command امن Backfill

فایل:

```text
app/Console/Commands/BackfillSkillSubdomains.php
```

دستورها:

```bash
php artisan skills:backfill-subdomains --dry-run
php artisan skills:backfill-subdomains
```

گزینه‌ها:

- `--dry-run`: گزارش بدون insert
- `--chunk=500`: اندازه batch، بین 1 تا 5000
- `--force`: اجازه اجرای نوشتنی روی production فقط برای rollout تأییدشده

رفتار:

- فقط Skillهایی را پردازش می‌کند که `subdomain_id` معتبر دارند.
- فقط در `skill_subdomain` insert می‌کند.
- از `insertOrIgnore` استفاده می‌کند.
- هیچ delete، update، merge یا assign برای Skillهای NULL انجام نمی‌دهد.
- UUID و تمام ستون‌های Skill/Subdomain دست‌نخورده می‌مانند.
- رابطه موجود را دوباره ایجاد نمی‌کند.
- Skillهای NULL یا دارای Subdomain نامعتبر را جداگانه گزارش و skip می‌کند.

## 4. تصمیم Migration و Index

در WP3 migration جدیدی ساخته نشد، زیرا indexهای لازم برای pivot از قبل موجودند:

- Unique: `skill_subdomain(skill_id, subdomain_id)`
- Index معکوس: `skill_subdomain(subdomain_id)` که همراه Foreign Key ساخته شده است.
- Primary Key روی `skill_subdomain.id`

افزودن index تازه تکراری و بدون منفعت بود. ستون `skills.subdomain_id` نیز در این مرحله حذف یا تغییر نکرد. Migration WP2 روی دیتابیس تستی، Unique طبیعی Skill را اضافه می‌کند؛ production در WP3 migrate نشد.

## 5. نتیجه تست SQLite

### سناریوی استاندارد

```text
migrate -> db:seed -> backfill -> backfill
```

به‌دلیل اینکه Seederهای WP1 pivot را idempotent می‌سازند:

| اجرا | Created | Existing | Skipped |
|---|---:|---:|---:|
| Backfill اول | 0 | 1303 | 0 |
| Backfill دوم | 0 | 1303 | 0 |

### سناریوی legacy مشابه production

فقط روی SQLite موقت، pivot پاک شد تا وضعیت production شبیه‌سازی شود؛ Skillها و ستون مستقیم حذف یا تغییر نکردند.

| اجرا | Created | Existing | Skipped |
|---|---:|---:|---:|
| Backfill اول | 1303 | 0 | 0 |
| Backfill دوم | 0 | 1303 | 0 |

بعد از Backfill:

- Skillها: 1303
- Pivotها: 1303
- زوج‌های distinct: 1303
- رابطه مستقیم بدون pivot متناظر: 0
- Duplicate pivot: 0

## 6. بررسی ارتباط‌ها و Orphanها

پیش از تغییر، production برای مسیرهای زیر صفر orphan داشت:

- `user_skills -> skills`
- `project_skills -> skills`
- `profile_processes -> processes`
- `project_processes -> processes`
- `skill_subdomain -> skills`
- `skill_subdomain -> subdomains`

پس از Backfill روی SQLite نیز هر شش مقدار صفر ماند. Command فقط pivot جدید می‌سازد و هیچ‌یک از جدول‌های User/Project/Process را تغییر نمی‌دهد.

## 7. تست خودکار

فایل:

```text
tests/Feature/SkillSubdomainBackfillTest.php
```

موارد پوشش‌داده‌شده:

- ایجاد فقط رابطه missing
- idempotency اجرای دوم
- حفظ UUID و `skills.subdomain_id`
- عدم assign خودکار Skill بدون Subdomain
- گزارش و skip Skill بدون Subdomain

نتیجه: 2 تست و 18 assertion موفق.

## 8. مشکلات باقی‌مانده

- Model و queryهای فعلی هنوز در بخش‌هایی از `skills.subdomain_id` استفاده می‌کنند؛ طبق محدودیت WP3 تغییر نکردند.
- production هنوز pivot خالی دارد و برای rollout واقعی به backup، dry-run مجدد و اجرای کنترل‌شده Command با `--force` نیاز دارد.
- حذف ستون legacy تا زمانی که همه read/write pathها به Pivot مهاجرت نکرده‌اند ایمن نیست.
- Skillهای آینده با Subdomain NULL باید قبل از حذف ستون legacy، سیاست مشخص assignment داشته باشند؛ WP3 هیچ assignment حدسی انجام نمی‌دهد.

## 9. پیشنهاد WP4 — بدون اجرا

1. اجرای `skills:backfill-subdomains --dry-run` روی staging snapshot.
2. مقایسه countها و orphanها، سپس اجرای واقعی backfill در staging.
3. مهاجرت read pathهای Model/Controller/API به Pivot با تست contract.
4. dual-write موقت برای مسیرهای ایجاد/ویرایش Skill، تا ستون legacy و pivot همگام بمانند.
5. پس از یک دوره validation، nullable/legacy usage audit و طراحی migration حذف `skills.subdomain_id` در مرحله مستقل.

## تأیید محدودیت‌ها

- هیچ Skill حذف یا merge نشد.
- هیچ نام یا UUID تغییر نکرد.
- User Skill و Project Skill تغییر نکرد.
- UI، Controller، API، Model و Seeder تغییر نکرد.
- production مستقیم تغییر نکرد.
- commit و push انجام نشد.