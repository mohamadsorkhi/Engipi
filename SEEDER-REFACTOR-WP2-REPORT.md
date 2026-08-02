# گزارش استانداردسازی Taxonomy مهارت‌ها — WP2

## 1. وضعیت Duplicateها در دیتابیس فعلی

ممیزی با queryهای read-only روی محیط فعلی (`production`) انجام شد. هیچ migration یا Seeder روی production اجرا نشد.

| نوع | کلید بررسی | گروه Duplicate | IDهای تکراری | اتصال User/Project |
|---|---|---:|---|---|
| Skill | `skill_type + subdomain_id + name` | 0 | ندارد | موضوعیت ندارد |
| Subdomain | `skill_domain_id + name` | 0 | ندارد | موضوعیت ندارد |
| Process | `skill_domain_id + name` | 0 | ندارد | موضوعیت ندارد |

Command جدید نیز همین نتیجه را گزارش کرد:

```text
Skill duplicate groups: 0
Subdomain duplicate groups: 0
Process duplicate groups: 0
```

## 2. وضعیت فعلی schema و migrationها

### skill_domains

- `id`: UUID / `char(36)`، Primary Key
- `name`: `varchar(191)`، NOT NULL
- Unique موجود: `skill_domains_name_unique (name)`
- Foreign Key ورودی در این جدول تعریف نشده است.

### subdomains

- `id`: UUID / `char(36)`، Primary Key
- `skill_domain_id`: UUID، NOT NULL
- `name`: `varchar(191)`، NOT NULL
- Index موجود: `subdomains_skill_domain_id_index`
- Foreign Key: `skill_domain_id -> skill_domains.id` با `cascadeOnDelete`
- پیش از WP2 Unique مرکب نداشت.

### skills

- `id`: UUID / `char(36)`، Primary Key
- `process_id`: UUID، nullable
- `subdomain_id`: UUID، nullable
- `name`: `varchar(191)`، NOT NULL
- `skill_type`: `varchar(191)`، NOT NULL، default=`software`
- Index موجود: `(process_id, subdomain_id, name)` غیر Unique
- Foreign Key: `process_id -> processes.id` با `nullOnDelete`
- برای `subdomain_id` در schema فعلی Foreign Key تعریف نشده است.
- پیش از WP2 Unique طبیعی نداشت.

### processes

- `id`: UUID / `char(36)`، Primary Key
- `skill_domain_id`: UUID، NOT NULL
- `name`: `varchar(191)`، NOT NULL
- Unique موجود: `processes_skill_domain_id_name_unique (skill_domain_id, name)`
- Foreign Key: `skill_domain_id -> skill_domains.id` با `cascadeOnDelete`

### skill_subdomain

- `id`: unsigned bigint auto-increment، Primary Key
- `skill_id`: UUID، NOT NULL
- `subdomain_id`: UUID، NOT NULL
- Unique موجود: `skill_subdomain_skill_id_subdomain_id_unique`
- هر دو Foreign Key با `cascadeOnDelete` تعریف شده‌اند.

## 3. Migration اضافه‌شده

```text
database/migrations/2026_08_02_000001_add_unique_constraints_to_skill_taxonomy_tables.php
```

Migration قبل از هر DDL، Duplicateهای هر پنج کلید را بررسی می‌کند. در صورت وجود Duplicate، با پیام شامل key و IDها متوقف می‌شود و هیچ merge/delete انجام نمی‌دهد.

Migration با `Schema::getIndexes()` فقط Uniqueهای غایب را اضافه می‌کند. در وضعیت فعلی production، تغییر مؤثر مورد انتظار فقط این دو مورد است:

- `subdomains(skill_domain_id, name)` با نام `taxonomy_subdomains_domain_name_unique`
- `skills(skill_type, subdomain_id, name)` با نام `taxonomy_skills_type_subdomain_name_unique`

Uniqueهای موجود Domain، Process و pivot دوباره ساخته نمی‌شوند. متد `down()` نیز فقط indexهایی را حذف می‌کند که نام اختصاصی همین migration را دارند.

## 4. Command بررسی

```text
php artisan skills:check-duplicates
php artisan skills:check-duplicates --json
```

فایل:

```text
app/Console/Commands/CheckSkillTaxonomyDuplicates.php
```

Command فقط SELECT اجرا می‌کند. برای گروه‌های Duplicate احتمالی، key، IDها و تعداد اتصال‌ها را گزارش می‌دهد:

- Skill: `user_skills`، `project_skills`، `skill_subdomain`
- Process: `skills`، `profile_processes`، `project_processes`

هیچ delete، update یا merge در Command وجود ندارد.

## 5. بررسی ارتباط‌ها و orphanها در production

تعداد فعلی:

| جدول | تعداد |
|---|---:|
| skill_domains | 16 |
| subdomains | 78 |
| skills | 1303 |
| processes | 386 |
| skill_subdomain | 0 |
| user_skills | 4 |
| project_skills | 2 |
| profile_processes | 2 |
| project_processes | 2 |

نتیجه orphan check:

- `user_skills -> skills`: 0 orphan
- `project_skills -> skills`: 0 orphan
- `skill_subdomain -> skills`: 0 orphan
- `skill_subdomain -> subdomains`: 0 orphan
- `profile_processes -> processes`: 0 orphan
- `project_processes -> processes`: 0 orphan

هیچ رکوردی حذف یا ادغام نشد.

## 6. نتیجه تست Migration و Seeder

تست فقط روی SQLite موقت داخل workspace انجام شد.

مراحل:

1. `php artisan migrate:fresh --force`
2. `php artisan db:seed --force`
3. `php artisan skills:check-duplicates`
4. اجرای دوباره `php artisan db:seed --force`
5. اجرای دوباره Duplicate check و مقایسه count/UUID

| جدول | اجرای اول | اجرای دوم |
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

نتیجه:

- Duplicate پس از هر دو اجرا: صفر
- countها: ثابت
- UUIDهای Domain/Subdomain/Skill/Process: ثابت
- Seeder error: ندارد
- Uniqueهای پنج‌گانه در SQLite نهایی: موجود و فعال

Feature Test جدید:

```text
tests/Feature/SkillTaxonomyConstraintsTest.php
```

- Command read-only و خروجی clean: موفق
- رد شدن insert تکراری برای هر پنج natural key: موفق
- 2 تست، 9 assertion

## 7. مشکلات و ریسک‌های باقی‌مانده

- `skills.subdomain_id` nullable است. در MySQL و SQLite، Unique مرکب معمولاً چند ردیف با NULL را مجاز می‌داند. Command Duplicateها را حتی برای NULL گزارش می‌کند، اما constraint به‌تنهایی برای NULL تضمین کامل نمی‌دهد.
- `skills.subdomain_id` در schema فعلی Foreign Key ندارد. WP2 طبق محدودیت، نوع ستون یا روابط را تغییر نداد.
- production در حال حاضر `skill_subdomain = 0` دارد؛ WP2 آن را backfill نکرد، چون تغییر داده خارج از محدوده این مرحله است.
- ساخت index روی production می‌تواند lock کوتاه ایجاد کند؛ rollout باید در maintenance window و پس از اجرای مجدد Command انجام شود.

## 8. پیشنهاد WP3 — بدون اجرا

1. تصمیم معماری نهایی درباره منبع اصلی رابطه Skill/Subdomain: ستون `skills.subdomain_id` یا pivot.
2. بررسی و برنامه‌ریزی backfill ایمن `skill_subdomain` بدون حذف ارتباط‌ها.
3. بررسی امکان NOT NULL و Foreign Key برای `skills.subdomain_id` پس از audit رکوردهای NULL.
4. dry-run روی staging با snapshot و سپس rollout کنترل‌شده constraintها.

## تأیید محدودیت‌ها

- مهارت جدید اضافه نشد.
- نام هیچ مهارتی تغییر نکرد.
- داده‌ای حذف یا merge نشد.
- Seeder، UI، Controller و Model تغییر نکرد.
- migration روی production اجرا نشد.
- commit و push انجام نشد.