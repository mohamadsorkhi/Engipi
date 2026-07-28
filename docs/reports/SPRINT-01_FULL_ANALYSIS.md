# SPRINT-01 â€” Full Project Analysis

> **Project:** Engineering Marketplace
> **Analysis date:** 2026-07-14
> **Analysis type:** Read-only repository review
> **Application changes:** None
> **Database changes:** None
> **Dependency changes:** None

## Objective

Analyze the complete Engineering Marketplace repository and establish an evidence-based baseline for security, stability, architecture, data design, performance, maintainability, and user experience before future implementation begins.

## Scope

The review covered the repository structure, Laravel architecture, active and inactive routes, controllers, Actions, models, middleware, validation, authorization, migrations, seeders, Blade views, frontend assets, authentication, role-specific panels, messaging, notifications, search, uploads, deployment, suspected dead code, duplicated logic, and technical debt.

The review excluded `vendor`, `node_modules`, and `.git` implementation internals except where manifests or generated artifact size were relevant. No production environment values or data were inspected or disclosed.

## Files Changed

- Added `docs/reports/SPRINT-01_FULL_ANALYSIS.md`.
- No application, configuration, dependency, asset, migration, or database file was changed.

## Technical Decisions

- Active behavior is defined by `RouteServiceProvider`, `routes/web.php`, `routes/user.php`, `routes/admin.php`, and `routes/api.php`, not by every route file present in the repository.
- Suspected dead code is labeled as a **candidate** until runtime references and business ownership are confirmed.
- Security findings distinguish confirmed reachable vulnerabilities from latent defects in inactive or broken paths.
- Recommendations favor small, reversible changes rather than a rewrite.

---

# 1. Executive Summary

The Engineering Marketplace is a Laravel 11/PHP 8.2 Persian-language freelancing platform focused on engineering projects. Its active product supports registration and login, employer and specialist profiles, engineering domain/skill classification, project creation, project matching, collaboration requests, messaging, tickets, and a custom admin panel.

The project has a useful domain foundation and several positive implementation patterns: UUIDs, FormRequests, Action classes, transactions, foreign keys, unique constraints, Eloquent relationships, CSRF protection, and ownership checks on major employer project and ticket operations.

It is not yet a safe foundation for rapid feature expansion. The most important blockers are:

1. No automated test suite or policy layer.
2. Confirmed project-detail authorization gap for specialists.
3. Publicly addressable project attachments and insufficient upload type validation.
4. Unrestricted messaging targets and unrelated project associations.
5. A broken state-changing API route with no authentication design.
6. Fragmented role and permission concepts.
7. Memory-heavy message and matching queries.
8. A 172.63 MB generated public build containing 4,242 files.
9. Extensive inactive/legacy route, controller, view, and asset material.
10. Non-atomic and non-reproducible deployment practices.

## Overall assessment

| Area | Assessment | Summary |
|---|---|---|
| Product foundation | Good | Core marketplace workflows exist and are understandable. |
| Security | High risk | Multiple authorization and file-access weaknesses require immediate work. |
| Stability | Medium-high risk | Broken API path, no tests, and fragmented implementations increase regression risk. |
| Database | Medium risk | Strong relational base, but historical transitions and missing indexes/schema support need review. |
| Performance | Medium-high risk | Unbounded message loading, matching ID materialization, repeated counts, and oversized assets. |
| Maintainability | High debt | No policies/services, duplicate role systems, inactive code, large views, inconsistent formatting. |
| UI/UX | Functional but inconsistent | Strong theme base, but large inline implementations, accessibility uncertainty, and duplicate journeys. |
| Operational readiness | High risk | Deployment lacks reproducible build, health checks, atomicity, and documented rollback. |

The recommended approach is stabilization first: tests, authorization, private file delivery, API correction, and deployment safety. Performance and architecture cleanup should follow after behavior is protected by tests.

---

# 2. Current Architecture

## Runtime and framework

| Component | Current state |
|---|---|
| Framework | Laravel Framework 11.47.0 |
| PHP | Required `^8.2`; analyzed CLI runtime 8.2.12 |
| Database | Eloquent-based relational schema; MySQL is the default configured driver |
| Rendering | Server-rendered Blade |
| Frontend | Velzon 4.3.0, Bootstrap 5.3.3, Vite 5, Sass, PostCSS, RTL tooling |
| Authentication | Laravel UI web-session authentication |
| API authentication | Sanctum installed but not applied to active API routes |
| IDs | UUIDs for core domain entities; mixed UUID and bigint pivot/utility IDs |

## Application shape

The application is a traditional Laravel MVC codebase with an additional Action layer:

```text
HTTP request
  -> route middleware
  -> controller
  -> FormRequest validation/authorization where present
  -> Action or direct Eloquent operation
  -> model/database
  -> Blade or JSON response
```

### Application inventory

| Area | Files | Role |
|---|---:|---|
| Actions | 36 | Admin, authentication, employer, specialist, and ticket mutations/queries |
| HTTP | 87 | Controllers, middleware, and FormRequests |
| Models | 15 | Core marketplace entities |
| Rules | 8 | Domain-specific validation |
| Validators | 2 | reCAPTCHA integrations backed by settings |
| Mail | 2 | Custom mailables; one implements `ShouldQueue` |
| Notifications | 1 | Password-reset notification |
| Providers | 5 | App, auth, event, broadcast, and route bootstrapping |

No `app/Services`, `app/Policies`, `app/Jobs`, `app/Events`, or `app/Listeners` directory exists. Action classes are the primary business-operation abstraction.

## Active routing architecture

The route provider loads only:

- `routes/web.php` under the `web` middleware group.
- `routes/api.php` under `/api` and the `api` middleware group.

`routes/web.php` includes:

- `routes/admin.php` inside `auth + admin` middleware.
- `routes/user.php` inside `auth` middleware, with nested active-role checks.

There are 94 active non-vendor routes.

## Panel architecture

| Panel | Active location | Capabilities |
|---|---|---|
| Public | `/`, `/about`, `/terms`, `/post-project` | Landing metrics, public content, guest project pre-registration |
| User/shared | `/user/dashboard`, messages, tickets | Unified dashboard, profile role selection, messaging, support |
| Employer | `/user/projects`, received requests; simplified `/employer/projects/create` | Project CRUD and collaboration decisions |
| Engineer/Specialist | `/user/skills`, matched projects, sent requests | Skill management, matching, collaboration requests |
| Admin | `/admin/*` | Users, profiles, projects, skills, domains, subdomains, processes, tickets |

Legacy Worker, separate Employer, and separate Specialist route files are present but not loaded by the active route provider.

---

# 3. Folder Analysis

## Root folders

| Folder | Analysis |
|---|---|
| `app` | Active application code. Generally well separated by controller namespace and Action domain. |
| `bootstrap` | Standard Laravel bootstrap plus cached artifacts. |
| `config` | Standard Laravel configuration plus Sanctum, Spatie Permission, and Velzon settings. |
| `database` | 35 migrations, one factory, and multiple large engineering taxonomy seeders. |
| `docs` | Master plan, AI operating rules, and task reports. |
| `lang` | Laravel 11-style translations. |
| `resources` | 116 Blade views, 130 JS files, 113 SCSS files, images, fonts, and theme JSON. |
| `public` | Application entry files plus a very large generated build and custom vendor assets. |
| `routes` | Ten route files; only web/API plus files required by web are active. |
| `storage` | Standard application, framework, and log storage. |
| `export` | Separate Blade and blueprint exports; likely deliverables or historical copies rather than runtime code. |
| `deploy-output`, `Engipi` | Present but currently contain no regular files in the inspected tree. Purpose is not documented. |
| `vendor`, `node_modules` | Installed PHP and Node dependencies. |

## Active code versus repository baggage

The repository combines four categories:

1. Active Laravel application code.
2. Legacy marketplace implementations (`Worker`, older panel routes/views).
3. Velzon theme source/demo assets.
4. Exported/static deliverables.

This makes repository-wide searches noisy and increases the chance of editing the wrong implementation. A future cleanup must establish explicit ownership and runtime evidence before deleting anything.

## View distribution

| View area | Count |
|---|---:|
| Admin | 30 |
| User | 19 |
| Components | 18 |
| Layouts | 16 |
| Auth | 8 |
| Employer | 7 |
| Specialist | 6 |
| Worker | 4 |
| Other/public/error/vendor | 8 |

Large templates include `layouts/customizer.blade.php` (55.7 KB), `user/projects/create.blade.php` (50.3 KB), `test.blade.php` (40.3 KB), and `user/projects/edit.blade.php` (32.7 KB). These sizes indicate mixed markup, styling, and behavior in single files.

---

# 4. Feature Inventory

## Authentication

- Registration through Laravel UI's `RegisterController`.
- Login by email or mobile.
- Logout and session regeneration.
- Password reset and confirmation.
- Email-verification scaffolding and default registration event listener.
- Custom password-reset notification.
- Guest-only employer project intake that carries data into registration.

Two generations of auth controllers coexist. `Auth::routes()` primarily activates Laravel UI controllers, while newer-style session/registration controllers appear mostly inactive.

## User profiles and roles

- Employer and specialist profiles can coexist for one user.
- `session('active_role')` controls current panel behavior.
- Profile creation and update are available.
- Specialist skills, experience, domains, and legacy process mappings are stored.
- Admins can inspect and delete profiles.

There are overlapping role systems: `users.role`, `users.is_admin`, profile type, active session role, and installed Spatie Permission configuration.

## Employer functionality

- Create full or simplified projects.
- Add domains, processes, skills, budgets, deadlines, and files.
- List, view, edit, update, and delete owned projects.
- Review received collaboration requests.
- Accept, reject, or revert requests.
- View dashboard metrics in a legacy/separate controller, although the active unified dashboard is under `User`.

## Engineer/Specialist functionality

- Select up to five initial skills through a large dedicated selection view.
- Maintain skills and experience.
- Save specialist domains and legacy process mappings.
- List projects matched through three matching paths.
- View project details.
- Send and list collaboration requests.

The active Specialist detail controller omits the match check that exists in the inactive Worker implementation.

## Messaging

- Conversation list for messages involving the authenticated user.
- Two-party thread view.
- Read timestamps.
- Optional project association.
- Plain-text messages escaped before line-break rendering.

There is no participant relationship policy, blocking, reporting, attachment support, realtime transport, or per-user message rate limit.

## Tickets

- User ticket creation, listing, viewing, messaging, closing, and reopening.
- Admin ticket filtering, viewing, replying, closing, and reopening.
- Configurable active ticket departments.
- Ownership checks are present on user ticket actions.

## Admin panel

- Dashboard counts and recent records.
- Search/filter users, profiles, projects, and tickets.
- Manage skills, domains, subdomains, processes, users, projects, profiles, and ticket departments.
- Full ticket operations.

Admin access uses `is_admin` middleware rather than the installed Spatie Permission system.

## Notifications and mail

- Password-reset notification is active.
- Default email-verification listener is registered.
- Two custom mailables exist and depend on the `Setting` model.
- `NotifyEmail` implements `ShouldQueue`; no custom Job classes exist.
- Queue configuration exists, but the project has only a failed-jobs migration and no application `jobs` table migration.

## Search

Search is limited to SQL `LIKE` filters in admin user/profile/project/ticket pages. There is no public marketplace search engine, Scout integration, full-text index, saved search, ranking, or search analytics.

## API

- Public subdomain lookup.
- Public skill lookup.
- Broken user-skill POST route.
- No API Resources, versioning, consistent envelope, mobile API, or applied Sanctum authentication.

## Uploads

- Project files up to 10 MB each.
- Public-disk storage under project UUID folders.
- Original filename, MIME, and size metadata persisted.
- Deletion actions remove stored files.
- No allowlist, malware scan, private delivery controller, content-disposition policy, or download audit.

---

# 5. Database Analysis

## Core schema

| Domain | Tables |
|---|---|
| Identity | `users`, `user_profiles`, password resets, personal access tokens |
| Taxonomy | `skill_domains`, `subdomains`, `skills`, `processes` |
| Specialist capability | `user_skills`, `user_profile_domains`, `profile_processes` |
| Projects | `projects`, `project_domains`, `project_skills`, `project_processes`, `project_files` |
| Collaboration | `requests` |
| Messaging | `messages` |
| Support | `ticket_departments`, `tickets`, `ticket_messages` |
| Operations | `failed_jobs` |

## Strengths

- UUID primary keys for core entities reduce predictable sequential identifiers.
- Foreign keys and cascade/null-on-delete behavior are widely used.
- Pivot tables generally use composite primary or unique constraints.
- One profile per user/type is enforced.
- Duplicate user-skill and user-project request records are constrained.
- Money uses decimal fields rather than floating point.
- Project owner/date and ticket status indexes exist.
- Ticket messages have ticket/date and actor indexes.

## Design concerns

### Role-model inconsistency

`users.role` uses `worker`, `employer`, and `admin`, while profiles use `employer` and `specialist`. `is_admin` duplicates the admin state. This creates ambiguous authority and inconsistent terminology.

### Historical taxonomy transitions

Projects and profiles migrated from single `skill_domain_id` columns to many-to-many pivot tables. Historical migrations remove and recreate those columns in `down()`. Rollback is lossy when multiple domains exist because only the first domain can be restored.

### Skill relationships overlap

Skills contain process and subdomain relationships while a separate `skill_subdomain` pivot also exists. This permits competing representations unless one path is explicitly legacy.

### Missing schema represented in code

- `Setting` is an active model dependency for captcha and mail, but no settings-table migration exists in the repository.
- `ConsultationController` references a missing `Consultation` model and questionnaire/answer relationships, and no consultation migrations exist.
- Spatie Permission is configured, but package permission-table migrations are absent from the project migration directory.
- Database queue configuration expects a `jobs` table if selected, but only `failed_jobs` is created.

These may depend on pre-existing production tables, unpublished vendor migrations, or inactive code. The repository is not self-contained enough to recreate every referenced feature confidently.

### Missing or weak constraints/indexes

- `messages` has no explicit composite indexes for conversation lookup or unread-message lookup.
- `subdomains` lacks an explicit unique `(skill_domain_id, name)` constraint.
- `ticket_departments.name` is not unique.
- Message participants are not constrained against self-messaging at database level.
- Several finite states are database enums, making future state expansion migration-dependent.

### Seeder safety

Multiple seeders truncate taxonomy and pivot tables. `SkillDomainSeeder`, `SkillSeeder`, and `SubdomainSeeder` are destructive and unsafe for accidental production execution. One seeder retains `dump()` diagnostics. Large taxonomy seeders also contain thousands of lines of embedded data and overlapping responsibilities.

## Database recommendations

1. Document the canonical taxonomy representation before adding fields.
2. Inventory actual production schema without changing it and reconcile missing migrations.
3. Add characterization tests for migration-up from an empty database in an isolated environment.
4. Mark destructive seeders clearly and prevent production execution.
5. Add indexes only after staging `EXPLAIN` and production-volume simulation.
6. Do not attempt schema cleanup until active code paths and data cardinality are known.

---

# 6. Security Audit

## Critical/high findings

### SEC-01 â€” Matched-project detail IDOR

`Specialist\MatchedProjectController::show()` accepts any bound project, loads employer and attachment data, increments its view count, and renders it without checking `Project::forWorkerMatches($user)`. An authenticated specialist with a project UUID can read an unmatched project and alter its analytics.

### SEC-02 â€” Public project attachments

Project uploads use the public disk and views emit direct `Storage::url()` links. Static delivery bypasses Laravel authentication and authorization. Anyone who obtains a URL can download the file regardless of project access.

### SEC-03 â€” Arbitrary file types on a public disk

Project files are validated only as files under 10 MB. HTML, SVG, or other active content may be hosted publicly, creating phishing, stored-content, malware, or XSS risk depending on browser and server behavior.

### SEC-04 â€” Broken unauthenticated state-changing API

`POST /api/user-skill` maps to `Api\SkillController::store()`, which does not exist. The intended `UserSkillController` derives identity from `Auth::id()` but the API group has no authentication middleware. Correcting only the route would create an unsafe write path.

### SEC-05 â€” Vulnerable locked dependencies

The prior Composer audit for the current lockfile reported 24 advisories affecting 14 packages. Dependency remediation must be performed separately with full regression testing; this analysis did not update or reinstall packages.

## Medium findings

### SEC-06 â€” Unrestricted messaging recipients

Any authenticated active-role user can message any existing user UUID. There is no collaboration, project, block, privacy, or prior-conversation authorization rule.

### SEC-07 â€” Unrelated project association in messages

`project_id` validation checks only existence. A sender can associate another employer's project with an unrelated conversation.

### SEC-08 â€” Arbitrary collaboration request targets

Specialists may submit requests to any existing project UUID, regardless of matching visibility, ownership, or whether it is their own employer project under a dual-profile account.

### SEC-09 â€” No policies

Authorization is distributed across route middleware, controller comparisons, FormRequest `authorize()`, roles, profiles, and `is_admin`. The absence of a canonical policy layer makes omissions likely.

### SEC-10 â€” Admin request defense-in-depth gaps

Several admin FormRequests authorize any authenticated user and rely on the outer admin route group. Current routes remain protected, but reuse or middleware regression could expose mutations.

### SEC-11 â€” Host-header protection disabled

`TrustHosts` is commented out in the global middleware stack.

### SEC-12 â€” Role state can become stale

`active_role` is not consistently revalidated against current profile ownership on every request. Administrators bypass active-role checks entirely.

### SEC-13 â€” User enumeration through conversation route

An authenticated user can open `/user/messages/{user}` for an arbitrary valid UUID and learn that account's name even without a prior relationship.

## Positive controls

- Web CSRF middleware is enabled.
- Passwords use Laravel hashing/authentication.
- Login regenerates session state.
- Project owner CRUD and ticket operations contain ownership enforcement.
- Employer collaboration decisions use a FormRequest that checks project ownership.
- Profile updates verify the profile belongs to the authenticated user.
- Blade rich-text-like output observed in project, message, and ticket views uses `e()` before `nl2br()`.
- `.env` is ignored by Git and no secret values were included in this report.

## Security priority

Security work should begin with tests, SEC-01 through SEC-04, then messaging/request policy rules, then centralized policies and middleware hardening.

---

# 7. Performance Audit

## Backend hot spots

### Conversation listing

`MessagesController::index()` loads every message involving the user, eager-loads relationships, groups all records in PHP, and calculates unread counts in memory. Cost grows linearly with lifetime message history.

### Project matching

`Project::forWorkerMatches()` materializes several collections of skill names and project IDs before constructing the final query. Large taxonomies and marketplaces will increase memory, query count, and `WHERE IN` size.

### Landing page

The root route performs multiple counts and loads all domains with subdomain counts on uncached public requests.

### Project forms

Project creation and editing load broad domain/process/skill relationship sets. Rendering large taxonomies in Blade and JavaScript will degrade response and browser interaction time.

### Dashboard counts

Some dashboard metrics use `get()->count()` where a database `count()` would avoid model hydration.

### Search

Admin searches use leading/trailing wildcard `LIKE '%query%'`, which generally cannot use normal B-tree indexes efficiently.

## Frontend and assets

| Metric | Current value |
|---|---:|
| Public build files | 4,242 |
| Public build size | 172.63 MB |
| Resource JavaScript files | 130 / 3.08 MB |
| Resource SCSS files | 113 / 0.61 MB |
| Blade views with inline scripts | 33 |
| Blade views with inline styles | 23 |

The Vite plugin copies the entire resource image, JSON, font, and JavaScript trees plus configured package distributions into `public/build`. `emptyOutDir` is false, so stale output can survive builds. The output structure may overwrite generated entry names or accumulate unused assets.

## Caching and queues

- Subdomain lookup caches for five minutes.
- Skills lookup is not cached.
- Admin taxonomy changes do not visibly invalidate lookup keys.
- Default fallbacks are file cache, file session, null broadcast, and sync queue unless environment configuration overrides them.
- Custom queued mail may fail under a database queue configuration because no `jobs` migration exists.

## Performance recommendations

1. Establish query-count and response-time baselines with representative data.
2. Replace conversation in-memory grouping with latest-message and unread-count subqueries.
3. Rewrite matching using `exists`, joins, or subqueries while preserving exact results.
4. Cache landing metrics and taxonomy data with explicit invalidation.
5. Load project taxonomy progressively through authorized lookup endpoints.
6. Audit Vite entries and copy only referenced assets.
7. Enable clean deterministic builds after proving required files.
8. Add measured indexes for messages and matching pivots through backward-compatible migrations.

---

# 8. Code Quality Audit

## Strengths

- 36 Action classes reduce mutation logic in many controllers.
- FormRequests are used for most complex employer, admin, specialist, and ticket inputs.
- Transactions protect multi-table project creation/update.
- Models have clear relationships and mostly deliberate fillable fields.
- Controller namespaces map to product panels.
- PHP syntax check passed across `app`, `routes`, and `database`.

## Weaknesses

### No tests

There is no `tests` directory or PHPUnit configuration file in the project root. Composer declares test tooling, but no automated safety net exists.

### No policies or service boundary

Policies are absent. Actions are useful but inconsistent: some controllers use them, others contain direct query/mutation logic. There is no Services layer for cross-cutting domains or integrations.

### Formatting inconsistency

Laravel Pint's read-only test reports broad formatting differences across application, route, and database PHP files. Formatting should be isolated from behavior changes to avoid unreviewable diffs.

### Large controllers/views and mixed concerns

`SkillSelectController` combines validation, taxonomy integrity checks, skill synchronization, domain synchronization, legacy process derivation, and response logic. Large project Blade templates contain substantial CSS and JavaScript state management.

### Model accessors override framework date casts

Several models convert timestamp attributes directly to Jalalian objects using accessors while also declaring datetime casts. This may surprise serialization, comparisons, tests, and framework features expecting Carbon.

### Inconsistent response styles

Actions return JSON, redirects, or views inconsistently. Some endpoints use inline validation while similar endpoints use FormRequests. Error formats are not standardized.

## Duplicate logic

- Active Specialist and inactive Worker controllers overlap dashboards, skills, matching, and requests.
- Separate Employer/Specialist route files overlap unified `routes/user.php`.
- Project creation has full, simplified authenticated, and guest-registration flows.
- Two authentication-controller generations coexist.
- Skill/domain/process mappings are implemented through both current skill pivots and legacy profile-process paths.
- Admin controllers repeatedly render partial tables after mutations.
- Project create/edit views duplicate significant client-side logic.
- `lang` and `resources/lang` duplicate translation trees.

## Dead-code candidates

The following are not active through the inspected route provider and require confirmation before removal:

- `routes/worker.php`, `routes/employer.php`, `routes/specialist.php`, `routes/test.php`.
- `app/Http/Controllers/Worker/*`.
- Separate Employer/Specialist dashboard controllers.
- `Specialist/ProjectListController`.
- `User/ConsultationController`, which references missing domain classes.
- `HomeController` methods not referenced by active routes.
- Newer-style auth controllers not used by `Auth::routes()`.
- `Api/UserSkillController` until the broken route is intentionally repaired.
- Worker/specialist/export views not referenced by active controllers.
- Many Velzon page scripts, demo JSON files, images, fonts, and libraries.

Dead-code removal must be reference-tested and performed in small batches after feature tests exist.

---

# 9. UI/UX Audit

## Strengths

- Consistent Bootstrap/Velzon visual foundation.
- Persian content and RTL orientation are established.
- Dedicated employer, specialist, user, and admin experiences exist.
- Forms generally provide server-side validation and user-facing errors.
- Shared components exist for cards, rows, badges, breadcrumbs, and admin form controls.
- AJAX feedback and spinners appear in major forms.

## Problems

### Duplicate journeys

Legacy Worker/Specialist/Employer views coexist with unified User views. It is difficult to determine which is canonical, and product behavior can diverge between copies.

### Oversized page implementations

Project create/edit, skill selection, login, registration, landing, and profile selection combine markup, CSS, and JavaScript. This impedes reuse, testing, and consistent fixes.

### Accessibility uncertainty

There is no automated accessibility testing. The theme uses icon-heavy controls, dynamic modals, inline interaction logic, and color-coded status. Keyboard behavior, focus management, contrast, accessible names, and error announcements require manual and automated review.

### Inconsistent panel terminology

The schema and old UI use Worker while the current product uses Specialist/Engineer. Mixed terminology can confuse users and developers.

### Error and state consistency

Endpoints return mixed redirects and JSON. Authorization often returns generic `403`, while other paths redirect to profile selection. This can produce inconsistent browser and AJAX experiences.

### Large client payload

The frontend artifact and broad theme dependencies can slow first visits, especially on mobile networks. Copying whole package and asset trees also complicates caching.

### Missing marketplace discovery UX

There is no public search, favorites, saved searches, portfolio, notification center, rating/review, or structured proposal experience. The current product is functional but closer to a matching/request MVP than a mature marketplace.

## UI/UX recommendations

1. Map canonical journeys before visual modernization.
2. Extract shared project/skill form modules after characterization tests.
3. Establish design tokens and component rules without rewriting the theme.
4. Audit WCAG accessibility on authentication, project, messaging, ticket, and admin flows.
5. Standardize loading, empty, success, validation, authorization, and server-error states.
6. Measure and reduce per-page CSS/JS rather than removing packages globally.

---

# 10. Technical Debt

## Structural debt

- No policies, tests, or clear integration Service layer.
- Mixed legacy and current routing/controller/view systems.
- Multiple overlapping role and authorization models.
- Application references to missing schema/models.
- Active and inactive authentication implementations coexist.

## Data debt

- Historical single-domain to pivot transitions.
- Competing skill/process/subdomain representations.
- Missing repository migrations for settings, permissions, jobs, and consultation-related code.
- Destructive, overlapping taxonomy seeders.
- Missing message query indexes.

## Frontend debt

- 172.63 MB public build.
- Theme demos and assets copied broadly.
- Large inline page scripts and styles.
- Duplicate panel views.
- Numerous older or redundant frontend dependencies.

## Operational debt

- Non-atomic FTPS deployment.
- No reproducible dependency and Vite build in CI.
- No automated tests in deployment.
- No health check, rollback automation, or release artifact strategy.
- No explicit production cache/queue operational documentation.

## Documentation debt

- Root README is insufficient.
- Runtime ownership of legacy/export/deployment directories is undocumented.
- API contracts, authorization matrix, data dictionary, and operational runbooks are absent.

---

# 11. Critical Bugs

| ID | Bug | Impact | Recommended first response |
|---|---|---|---|
| BUG-C01 | `POST /api/user-skill` calls nonexistent `SkillController::store()` | Public 500/error path; unsafe if naively corrected | Add tests, decide auth mode, then correct route and authentication together |
| BUG-C02 | Specialist matched-project detail does not enforce matching | Cross-project data disclosure and unauthorized view-count mutation | Reuse matching existence rule before loading/incrementing |
| BUG-C03 | Project attachments use direct public storage URLs | Authorization bypass for uploaded files | Introduce authorized private download path with backward-compatible transition |
| BUG-C04 | Project upload accepts arbitrary file types | Public hosting of active/malicious content | Add approved allowlist and safe delivery headers |
| BUG-C05 | No automated test suite | High probability of undetected security and behavior regressions | Build isolated characterization tests before fixes |

---

# 12. Medium Priority Issues

1. Messaging accepts arbitrary recipients.
2. Messages accept unrelated project IDs.
3. Collaboration requests can target inaccessible projects.
4. Conversation route permits account UUID/name enumeration.
5. No centralized policy layer.
6. Stale active-role session state and broad admin bypass.
7. Admin FormRequests rely too heavily on route middleware.
8. Conversation index loads all messages into memory.
9. Matching scope materializes large ID collections.
10. Landing metrics execute repeated uncached queries.
11. Taxonomy cache invalidation is incomplete.
12. Project skill update synchronization may not preserve pivot metadata correctly.
13. Public build copies excessive assets and can retain stale files.
14. Missing jobs/settings/permission/consultation schema makes clean installation incomplete.
15. Destructive taxonomy seeders are insufficiently guarded.
16. Locked dependency advisories require isolated remediation.
17. Deployment does not create a deterministic tested artifact.

---

# 13. Low Priority Issues

1. TrustHosts middleware is disabled.
2. Route parameters do not consistently use UUID constraints.
3. `ticket_departments.name` and domain-local subdomain names lack uniqueness constraints.
4. A seeder contains `dump()` output; captcha validators contain commented `dd()` lines.
5. Worker versus Specialist terminology is inconsistent.
6. Return types, method types, imports, and response formats are inconsistent.
7. Jalalian accessors may surprise framework serialization.
8. Large terms/about pages are embedded directly in Blade.
9. Both `lang` and `resources/lang` are maintained.
10. Inactive cache-clear route code remains in the repository.
11. Search uses unscalable wildcard SQL and exists only in admin pages.
12. No CSP was identified.
13. No accessibility or browser automation exists.
14. README and operational documentation remain incomplete.

---

# 14. Recommended Refactors

These refactors should not begin until tests protect current behavior.

## R1 â€” Characterization test foundation

Add route, authentication, role, project ownership, matching, request, message, ticket, profile, API, and upload tests using an isolated database.

## R2 â€” Incremental policy layer

Add Project, CollaborationRequest, Message, Ticket, and UserProfile policies. Initially keep existing checks as defense in depth; remove duplication only after equivalence is proven.

## R3 â€” Canonical role model

Document which of `role`, `is_admin`, profile types, active role, and Spatie permissions is authoritative. Introduce a phased compatibility plan rather than changing all roles at once.

## R4 â€” Canonical panel implementation

Confirm unified `/user` routes as canonical, then deprecate inactive Worker/Employer/Specialist implementations one bounded area at a time.

## R5 â€” Message query and authorization service

Separate messaging authorization from transport/query concerns. Replace in-memory conversation aggregation with database-side latest/unread queries.

## R6 â€” Project matching query object/service

Preserve the three current matching paths but move them into a focused, testable query abstraction. Optimize only after result equivalence is established.

## R7 â€” Private file delivery

Create an authorized download controller and storage abstraction. Support existing public records during a phased migration; never edit production file records manually.

## R8 â€” Project form frontend modules

Extract duplicated project create/edit taxonomy state into Vite modules and shared Blade components without changing request contracts.

## R9 â€” Taxonomy model cleanup

Document current versus legacy relations before deprecating `process_id`, direct `subdomain_id`, or the many-to-many alternatives. Do not remove columns until production usage is measured.

## R10 â€” Deterministic asset and deployment pipeline

Build a clean artifact in CI with locked dependencies, tests, Vite output, health checks, and rollback. Retain FTPS only as transport if hosting constraints require it.

---

# 15. Sprint Roadmap

## Sprint 1A â€” Tests and access control

- Build isolated characterization tests.
- Fix matched-project authorization.
- Restrict collaboration request targets.
- Define messaging permissions.
- Add initial policies without removing working checks.

## Sprint 1B â€” API and uploads

- Correct/authenticate user-skill API as one change.
- Add API response and validation tests.
- Restrict project upload types.
- Introduce authorized download endpoint design.
- Plan backward-compatible file migration.

## Sprint 1C â€” Operational stability

- Address dependency advisories in controlled batches.
- Add reproducible CI build and tests.
- Validate Vite artifacts and deployment exclusions.
- Add staging health check and rollback procedure.

## Sprint 2 â€” Performance

- Optimize message conversation queries.
- Cache public counts/taxonomy with invalidation.
- Profile and optimize project matching.
- Add evidence-backed indexes.
- Reduce generated asset scope.

## Sprint 3 â€” Architecture

- Consolidate policy usage and role definitions.
- Deprecate legacy panels/routes/controllers.
- Reconcile missing schema/model references.
- Standardize FormRequests, responses, typing, and code style.

## Sprint 4 â€” UI/UX

- Establish canonical journeys and shared components.
- Extract inline JS/CSS incrementally.
- Complete accessibility and mobile review.
- Standardize user feedback states.

## Later product sprints

- Marketplace search and discovery.
- Portfolios, favorites, saved searches, and notifications.
- Verified ratings and reviews.
- Wallet/payment architecture only after security, data integrity, and deployment maturity.
- Mobile/public API and AI recommendations after versioned API and observability are established.

---

# 16. Estimated Development Order

| Order | Work package | Why now | Complexity | Dependency |
|---:|---|---|---|---|
| 1 | Test harness and authorization matrix | Makes every later change safer | Medium | None |
| 2 | Matched-project IDOR fix | Confirmed high-impact reachable issue | Lowâ€“Medium | Tests |
| 3 | Collaboration request visibility | Shares the same project access rule | Lowâ€“Medium | Tests, project rule |
| 4 | Messaging authorization | Prevents abuse and false project associations | Medium | Tests, business decision |
| 5 | API route and authentication correction | Repairs broken public endpoint safely | Medium | Tests, auth-mode decision |
| 6 | Upload allowlist and authorized download | Protects project data and hosted content | High | Policy, storage transition plan |
| 7 | Policy/middleware hardening | Centralizes proven authorization rules | Medium | Tests and initial fixes |
| 8 | Dependency security updates | Removes known advisories without mixing feature changes | Mediumâ€“High | Regression suite |
| 9 | Reproducible CI/deployment artifact | Reduces release and rollback risk | High | Tests, dependency state |
| 10 | Message/landing/cache optimization | Low-risk performance gains after correctness | Medium | Baselines |
| 11 | Matching optimization and indexes | Higher-risk query/data work | Mediumâ€“High | Result fixtures, staging data |
| 12 | Legacy/dead-code retirement | Reduces maintenance surface safely | Medium | Tests, runtime confirmation |
| 13 | UI component extraction and asset reduction | Improves UX and build size | High | Canonical views, browser checks |
| 14 | New marketplace features | Builds on a stable platform | High | Security, architecture, operations |
| 15 | Wallet and payments | Highest trust and data-integrity requirement | Very high | All foundational work |

---

## Risks

- This is a static repository analysis; production-only schema, traffic patterns, server rules, feature flags, and external integrations may change the effective risk.
- Suspected dead code may have consumers outside the active route provider or repository.
- Dependency advisory counts can change over time and require a fresh audit before remediation.
- Asset usage can be dynamic; unused-file removal requires runtime/browser verification.
- No tests exist to prove behavior, so refactoring estimates include characterization work.

## Tests Performed

| Check | Result |
|---|---|
| `php artisan --version` | Laravel Framework 11.47.0 |
| `php -v` | PHP 8.2.12 CLI |
| `php artisan route:list --except-vendor -v` | Passed; 94 active routes inspected with middleware |
| PHP syntax lint over `app`, `routes`, and `database` | Passed for all inspected PHP files |
| Laravel Pint read-only test | Failed with broad formatting differences; no files were formatted |
| Test suite discovery | No `tests` directory or PHPUnit XML configuration found |
| Repository/file inventory | Completed |
| Static authorization, validation, upload, query, notification, search, and debug scans | Completed |
| Public build measurement | 4,242 files / 172.63 MB |
| Application runtime/browser tests | Not performed; no server or test environment was started |
| Database migrations | Not run |
| Dependency installation/update | Not run |

## Future Recommendations

1. Approve a test-only first task before changing runtime behavior.
2. Confirm the intended project visibility and messaging business rules.
3. Inventory the production schema safely and compare it with repository migrations without modifying data.
4. Identify external consumers of legacy routes, exports, and generated assets.
5. Establish staging that matches the production database engine, filesystem, queue, cache, and web-server behavior.
6. Treat wallet, payment, mobile API, and AI features as blocked until core authorization, tests, and deployment controls are mature.

## Approval Status

Analysis and documentation are complete. No commit or push was performed. Implementation must wait for human review and approval.
