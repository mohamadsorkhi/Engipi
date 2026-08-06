# Laravel Project Audit Report

## 1. Executive summary

This is a Persian-language engineering/freelancing marketplace built with Laravel 11 and the Velzon Bootstrap admin theme. Users can maintain employer and specialist profiles, publish projects, match specialists to projects, send collaboration requests, exchange messages, and open support tickets.

| Area | Result |
|---|---|
| Laravel | 11.47.0 |
| PHP requirement | `^8.2` |
| Local PHP | 8.2.12 |
| Routes | 94 active application routes |
| Application PHP files | 158 |
| Tests | PHPUnit feature and unit test suite present |
| Authentication | Laravel UI/session authentication |
| API authentication | Sanctum installed but not applied |
| Admin panel | Custom `/admin` panel |
| Payment gateway | None detected |
| Queued jobs | None detected |
| Custom events/listeners | None detected |
| Deployment | GitHub Actions over FTPS |
| PHP style | Laravel Pint installed; code currently fails broad Pint compliance |
| Dependency security | Composer audit found 24 advisories affecting 14 packages |
| Overall risk | Medium-to-high until API, authorization, dependencies, and deployment are corrected |

The route contract coverage now verifies each reviewed route's complete HTTP method set. Public project and landing-preview routes are also checked explicitly to prevent accidental addition of `auth`, `admin`, `active_role`, `role`, or `profile` middleware while preserving the production route definitions.

---

## 2. Project structure

Approximately 1,680 files exist outside `vendor`, `node_modules`, and `.git`. Much of that volume is static Velzon theme material, images, fonts, demo JavaScript, and exported copies rather than application logic.

| Path | Purpose |
|---|---|
| `app/Actions` | Business operations separated from controllers |
| `app/Http/Controllers` | Admin, API, auth, employer, specialist, user, and legacy worker controllers |
| `app/Http/Requests` | Validation and some authorization |
| `app/Http/Middleware` | Authentication, admin, profile, locale, and active-role enforcement |
| `app/Models` | Eloquent domain models |
| `app/Rules`, `app/Validators` | Phone, national ID, Persian text, captcha, and ownership validation |
| `app/Mail`, `app/Notifications` | Email and password-reset notifications |
| `app/Providers` | Routes, authentication, events, broadcasts |
| `bootstrap` | Laravel bootstrap and cached framework data |
| `config` | Laravel configuration plus Sanctum, permissions, and Velzon |
| `database/migrations` | User, project, skill, request, ticket, and message schema |
| `database/seeders` | Engineering domains, subdomains, and skills |
| `public` | Public web assets and built/vendor resources |
| `resources/views` | Blade pages and components |
| `resources/js`, `resources/css` | Vite frontend source and extensive Velzon assets |
| `resources/json` | Velzon sample/demo datasets |
| `routes` | Web, API, admin, user, specialist, employer, worker, test, channel, and console routes |
| `storage` | Logs, cache, sessions, views, and uploaded files |
| `lang`, `resources/lang` | Duplicate/new-and-legacy translation trees |
| `export` | Standalone Blueprint and Blade exports |
| `deploy-output`, `Engipi` | Deployment/distribution artifacts |
| `.github/workflows` | FTPS deployment workflow |
| `vendor` | Installed PHP dependencies |
| `node_modules` | Installed Node dependencies |

The active route provider loads only `routes/web.php` and `routes/api.php`. Files such as `routes/test.php`, `routes/worker.php`, `routes/employer.php`, and `routes/specialist.php` are not currently loaded directly.

---

## 3. Framework and runtime

The installed framework reports:

```text
Laravel Framework 11.47.0
PHP 8.2.12
```

`composer.json` requires:

```json
"php": "^8.2",
"laravel/framework": "^11.0"
```

The application uses Laravel 11 packages but retains the Laravel 10-and-earlier-style `Http\Kernel`, `Console\Kernel`, and `RouteServiceProvider` organization. That is supported in this upgraded project but differs from a fresh Laravel 11 skeleton.

---

## 4. Composer packages

### Production dependencies

| Package | Constraint | Installed |
|---|---:|---:|
| `php` | `^8.2` | 8.2.12 locally |
| `guzzlehttp/guzzle` | `^7.2` | 7.10.0 |
| `laravel/framework` | `^11.0` | 11.47.0 |
| `laravel/sanctum` | `^4.0` | 4.2.1 |
| `laravel/tinker` | `^2.9` | 2.10.2 |
| `laravel/ui` | `^4.2` | 4.6.1 |
| `morilog/jalali` | `3.*` | 3.4.2 |
| `spatie/laravel-permission` | `^6.23` | 6.23.0 |

### Development dependencies

| Package | Constraint | Installed |
|---|---:|---:|
| `fakerphp/faker` | `^1.23` | 1.24.1 |
| `laravel/pint` | `^1.13` | 1.26.0 |
| `laravel/sail` | `^1.26` | 1.48.1 |
| `mockery/mockery` | `^1.6` | 1.6.12 |
| `nunomaduro/collision` | `^8.0` | 8.5.0 |
| `phpunit/phpunit` | `^10.5` | 10.5.59 |
| `spatie/laravel-ignition` | `^2.4` | 2.9.1 |

Transitive dependencies are recorded in `composer.lock`; the table above contains every explicitly declared Composer package.

---

## 5. npm packages

The package is identified as Velzon 4.3.0.

### Runtime dependencies

```text
@ckeditor/ckeditor5-build-classic
@openrouter/sdk
@simonwep/pickr
@tarekraafat/autocomplete.js
aos
apexcharts
bootstrap
card
chart.js
choices.js
cleave.js
dom-autoscroller
dragula
dropzone
echarts
feather-icons
fg-emoji-picker
filepond
filepond-plugin-file-encode
filepond-plugin-file-validate-size
filepond-plugin-image-exif-orientation
filepond-plugin-image-preview
flatpickr
fullcalendar
glightbox
gmaps
gridjs
isotope-layout
jsvectormap
leaflet
list.js
list.pagination.js
masonry-layout
moment
multi.js
node-waves
nouislider
particles.js
prismjs
quill
rater-js
shepherd.js
simplebar
sortablejs
sweetalert2
swiper
toastify-js
wnumb
```

### Development dependencies

```text
@popperjs/core
axios
fs
fs-extra
laravel-vite-plugin
lodash
popper.js
postcss
postcss-import
resolve-url-loader
rimraf
rtlcss
sass
sass-loader
vite
```

Notable issues:

- Both `@popperjs/core` and obsolete `popper.js` are installed.
- `fs` is an unnecessary browser/npm placeholder package; Node already provides `fs`.
- `moment` 2.24.0 and Quill 1.x are old.
- Dropzone uses a beta release.
- The frontend dependency set is much larger than the active application appears to require.
- `@openrouter/sdk` is declared, but no clear active application integration was found.

---

## 6. Application domain and data model

Core models include:

- `User`, `UserProfile`
- `SkillDomain`, `Subdomain`, `Skill`, `Process`, `UserSkill`
- `Project`, `ProjectFile`
- Collaboration `Request`
- `Message`
- `Ticket`, `TicketDepartment`, `TicketMessage`
- `Setting`

UUIDs are used extensively. The schema generally includes foreign keys, cascades, unique constraints, and useful indexes. Important uniqueness rules include:

- Unique user email/mobile
- One profile per user/type
- One user-skill association
- One collaboration request per user/project
- Unique domain/process combinations
- Unique project short IDs

---

## 7. Authentication and authorization

Authentication is primarily Laravel UI’s session-based system:

```php
Auth::routes();
```

Features include:

- Login by email or mobile
- Registration
- Logout
- Password reset
- Password confirmation
- Email-verification scaffolding
- Session regeneration after login
- Login throttling through Laravel’s authentication trait
- CSRF protection on web routes

Users can maintain employer and specialist profiles. The chosen profile is held in `session('active_role')` and enforced through `EnsureActiveRole`.

Admin access is custom and based on `users.is_admin`.

Spatie Permission is installed and configured, but the active application mainly uses:

- `is_admin`
- The `role` column
- User profiles and `active_role`

This creates three overlapping authorization concepts. Spatie permissions do not appear central to active route protection.

Sanctum and personal-access-token migrations are present, but the API routes do not use `auth:sanctum`.

---

## 8. Admin panel

A custom Blade/Bootstrap panel exists at `/admin`, protected by `auth` and `admin` middleware.

It manages:

- Dashboard statistics
- Users and profiles
- Projects
- Skills
- Skill domains and subdomains
- Processes
- Ticket departments
- Support tickets

This is not Nova, Filament, Backpack, Voyager, or another packaged admin framework. It uses the Velzon Bootstrap theme.

---

## 9. Payment gateways

No active payment gateway was found.

There are no Composer integrations, controllers, services, routes, or configuration for Stripe, PayPal, Zarinpal, IDPay, NextPay, or comparable providers.

Theme images such as `success-payment.png` are presentation assets and do not constitute payment integration.

---

## 10. Queues, jobs, events, and broadcasting

### Queues

Laravel’s queue configuration and `failed_jobs` migration exist. The default fallback is `sync`.

No application job classes, `ShouldQueue` implementations, or application-level dispatch calls were found.

### Events

`EventServiceProvider` contains Laravel’s standard registration mapping:

```php
Registered::class => [
    SendEmailVerificationNotification::class,
]
```

No custom domain events or listeners were found.

### Broadcasting

A standard private user channel is declared in `routes/channels.php`, but no application broadcasts were detected. The default broadcaster fallback is `null`.

### Scheduling

No scheduled tasks are active.

---

## 11. APIs

Three routes are registered under `/api` and throttled to 60 requests per minute:

| Method | Endpoint | Intended behavior |
|---|---|---|
| GET | `/api/subdomains/{domainId}` | Return cached subdomains |
| GET | `/api/skills/{subdomain}` | Return skills |
| POST | `/api/user-skill` | Save a skill for a user |

Findings:

1. The GET endpoints are public and return lookup data.
2. `POST /api/user-skill` incorrectly points to `Api\SkillController@store`, but `SkillController` has no `store()` method.
3. The intended implementation appears to be `Api\UserSkillController@store`.
4. Even that controller depends on `Auth::id()` while no API authentication middleware is configured.
5. If used anonymously, it could attempt to insert a null `user_id`.
6. Sanctum is installed but unused.
7. Route parameters have no UUID constraints.
8. Skills are not cached, while subdomains are cached for five minutes.
9. No API resource classes or consistent response envelope are used.

This API needs correction before being considered production-ready.

---

## 12. Deployment configuration

Deployment runs from `.github/workflows/deploy.yml` after pushes to `main`.

It:

1. Checks out the repository.
2. Uploads Laravel application files to `/laravel_app/` over FTPS.
3. Uploads `public/` separately to `/public_html/`.

Secrets are correctly referenced through GitHub Actions secrets.

Risks and omissions:

- No `composer install --no-dev --optimize-autoloader`.
- No Node build step.
- `public/build` is excluded from both Git and FTP deployment.
- No migrations are run.
- No config, route, view, or event caching is performed.
- No maintenance mode or atomic release/symlink strategy.
- No health check or rollback.
- FTP deployment can leave a partially uploaded release.
- Verbose FTP logs may expose operational metadata.
- `public/index.php` and `public/.htaccess` are ignored locally, so fresh deployments rely on files already existing on the server.
- `vendor` is not excluded, meaning local vendor contents may be uploaded across operating systems.
- No explicit PHP runtime or extension verification.
- Deployment artifacts such as `deploy-output` and `Engipi` are not meaningfully documented.

A built artifact or SSH-based atomic deployment would be substantially safer.

---

## 13. Coding style and architecture

Positive patterns:

- Controllers delegate many mutations to action classes.
- Form requests handle validation.
- Transactions are used for multi-table project writes.
- Eloquent relationships are generally well modeled.
- UUIDs, foreign keys, cascades, and unique indexes are used.
- Blade output normally uses escaped `{{ ... }}`.
- Pagination is used for most major lists.
- CSRF protection remains enabled.

Inconsistencies:

- Laravel Pint is installed, but `pint --test` reports broad formatting differences across application, route, and database PHP files.
- Return types and method parameter types are inconsistent.
- Some controllers use imported models; others use fully qualified names inline.
- Manual authorization checks, FormRequest authorization, middleware, `role`, `is_admin`, profiles, and Spatie permissions overlap.
- No policy classes are registered.
- Controllers and Blade files contain substantial mixed UI/business JavaScript.
- Some Persian comments/text appear mojibake-encoded in command output.
- The README is effectively empty/unreadable.
- There is no visible automated test suite.
- Several legacy Laravel auth controllers coexist with Laravel UI controllers.

---

## 14. Bugs and correctness risks

### High priority

1. **Broken API route**

   `routes/api.php` maps `POST /api/user-skill` to `SkillController@store`, which does not exist. `UserSkillController@store` appears to be the intended target.

2. **Unauthenticated user-skill API**

   The intended controller uses `Auth::id()`, but the API group has no `auth:sanctum` or session-stateful middleware.

3. **Deployment does not build or deploy Vite output**

   `public/build` is ignored and excluded, and the workflow never runs `npm run build`. A clean deployment will not receive compiled assets.

### Medium priority

4. **Matched-project detail lacks match authorization**

   `Specialist\MatchedProjectController@show` displays any bound project UUID to a specialist, even if the project is not in their matched result set.

5. **Message recipients are unrestricted**

   Any authenticated user can message any other known user UUID. No accepted collaboration, project relationship, block state, or privacy rule is enforced.

6. **Message `project_id` is unrestricted**

   Validation checks only that the project exists, not that sender/receiver are related to it.

7. **Conversation list loads all messages**

   The message index fetches every message involving the user and groups them in PHP. This will become memory-intensive.

8. **View count can be inflated**

   Every specialist project-page request increments `view_count`, including repeated refreshes and potentially automated traffic.

9. **Potential project skill synchronization mismatch**

   Create code builds pivot data containing level/experience, while update logic calls `sync($data['skills'])`. If update validation returns arrays rather than an ID-keyed pivot map, it can produce incorrect pivot records.

10. **Stale cache after admin mutations**

    Subdomains are cached as `subdomains_{domainId}`. Admin create/update/delete actions do not visibly invalidate those keys, so the API may return stale data for five minutes.

11. **Unused public maintenance route file**

    `routes/test.php` contains a dangerous public `/clear-all-cache` endpoint. It is not currently loaded, but accidentally requiring this file would expose cache-clearing commands without authentication.

12. **Inactive/legacy route files can mislead maintenance**

    `worker.php`, `employer.php`, and `specialist.php` describe routing systems that are not loaded by the route provider.

### Lower priority

13. The project contains both `lang/` and `resources/lang/`, increasing the chance of updating the wrong translation set.

14. A seeder contains a `dump()` diagnostic.

15. Public landing statistics execute several database counts on every uncached request.

16. No explicit route parameter constraints are used for UUID-bound resources.

17. No tests were found to catch broken routes or authorization regressions.

---

## 15. Duplicated and obsolete code

No byte-for-byte duplicate active PHP/Blade files were detected, but substantial conceptual duplication exists:

- `Worker` and `Specialist` controllers implement similar functionality.
- `routes/worker.php`, `routes/specialist.php`, and unified `routes/user.php` overlap.
- `routes/employer.php` overlaps employer sections in `routes/user.php`.
- Multiple Laravel authentication controller sets coexist.
- `lang` and `resources/lang` duplicate localization structure.
- `export/engipi-blade` contains copies of routes, views, CSS, and fonts.
- `export/engipi-blueprint` duplicates frontend design deliverables.
- Theme demo JavaScript and JSON appear largely unrelated to the active product.
- Project create and edit Blade templates contain large, similar blocks of inline JavaScript and CSS.
- Role checking exists in several middleware implementations.
- `CheckRoleOrPermission` appears inconsistent with Spatie’s standard relationship/API and may be obsolete.

Recommended cleanup is to identify one canonical employer/specialist implementation, archive exports outside the application repository, and remove unused Velzon demo assets.

---

## 16. Security findings

### High

1. **Outdated vulnerable Composer lockfile**

   `composer audit --locked` found **24 advisories affecting 14 packages**. Reported affected packages include PHPUnit, PsySH, League CommonMark, and multiple Symfony components such as HTTP Foundation, Mailer, Mime, Routing, Process, and YAML.

   Production dependencies should be updated immediately. Development-only advisories still matter in developer and CI environments.

2. **Broken and unauthenticated state-changing API**

   The user-skill endpoint is both incorrectly routed and missing authentication protection.

3. **Deployment integrity risk**

   Non-atomic FTP deployment can expose mixed old/new releases. Dependencies and compiled assets are not reproducibly built in CI.

### Medium

4. **No relationship restriction on messaging**

   Authenticated users may contact arbitrary accounts and associate arbitrary existing projects.

5. **Authorization model fragmentation**

   `is_admin`, `role`, profiles, active session roles, and Spatie permissions coexist. This makes authorization drift more likely.

6. **No policies**

   Ownership checks are scattered through controllers and request classes. Policies would make omissions easier to detect.

7. **TrustHosts middleware disabled**

   Host-header protection is commented out in the global middleware stack.

8. **API metadata enumeration**

   Public skill/subdomain APIs expose identifiers and have no UUID route constraints. This may be intentional, but should be documented.

9. **No Content Security Policy visible**

   The large theme and inline-script usage increases XSS impact if an escaping mistake is introduced.

10. **User-generated rich text needs explicit sanitization**

    Project descriptions, messages, ticket text, and editor integrations should always be escaped or sanitized. Blade escaping is generally present, but CKEditor/Quill usage warrants dedicated HTML sanitization if formatted HTML is accepted.

### Positive controls

- `.env` is ignored by Git.
- No secret values were printed during the audit.
- Web CSRF protection is active.
- Passwords use Laravel authentication infrastructure.
- Session regeneration occurs after login.
- Most mutable inputs use validation.
- Database queries use the query builder/Eloquent rather than string-concatenated SQL.
- Admin routes are middleware-protected.
- Ticket and project ownership checks are generally present.
- Employer request actions are correctly authorized through `ManageRequestRequest`.
- Project updates are correctly authorized through `UpdateProjectRequest`.

---

## 17. Performance recommendations

Prioritized improvements:

1. Replace the conversation-index `get()->groupBy()` with a database query that selects only the latest message and unread count per participant.

2. Cache landing-page statistics and domain lists.

3. Cache the skills lookup API and invalidate domain/subdomain/skill caches after admin mutations.

4. Avoid loading every skill and nested relationship into project create/edit pages. Use paginated/searchable API endpoints.

5. Rewrite `Project::forWorkerMatches()` as joins/subqueries rather than collecting multiple ID sets into PHP and passing them back into `whereIn`.

6. Add or verify indexes for:

   - `messages(sender_id, receiver_id, created_at)`
   - `messages(receiver_id, read_at)`
   - `project_skills(skill_id, project_id)`
   - `project_processes(process_id, project_id)`
   - `requests(user_id, status, created_at)`
   - Frequently filtered status/date columns

7. Queue mail and notifications instead of sending them synchronously.

8. Use Redis/database-backed cache and queue in production instead of file/sync defaults.

9. Run production optimization during deployment:

   ```text
   composer install --no-dev --prefer-dist --optimize-autoloader
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   npm ci
   npm run build
   ```

10. Remove unused theme libraries, demo JS, JSON, fonts, and images to reduce build time and artifact size.

11. Split large inline Blade CSS/JavaScript into versioned Vite modules.

12. Use lazy loading prevention in non-production testing to locate N+1 queries.

13. Add response caching or HTTP cache headers for public lookup endpoints and static public pages.

14. Rate-limit messaging, collaboration requests, guest project submissions, login, registration, password reset, and ticket creation separately.

---

## 18. Recommended remediation order

1. Correct and authenticate `POST /api/user-skill`.
2. Update Composer dependencies to resolve the 24 known advisories.
3. Fix the deployment workflow so dependencies and Vite assets are built reproducibly.
4. Add policies for Project, CollaborationRequest, Ticket, Message, and UserProfile.
5. Restrict project visibility and messaging relationships.
6. Add feature tests for every authorization boundary and API route.
7. Consolidate legacy worker/specialist/employer code.
8. Optimize conversation and project-matching queries.
9. Apply Laravel Pint and establish CI checks.
10. Replace the current README with installation, architecture, environment, queue, build, and deployment documentation.

The application has a reasonable domain structure and several good Laravel practices, but the broken API, dependency advisories, missing tests, oversized legacy/theme footprint, and fragile deployment process are the primary production risks.
