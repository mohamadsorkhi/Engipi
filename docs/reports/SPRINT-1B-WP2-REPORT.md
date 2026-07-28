# Sprint 1B WP-2 â€” User-Skill API Correction and Authentication Report

> **Report date:** 2026-07-16
> **Work package:** WP-2 â€” Correct and authenticate the user-skill API
> **Status:** Implementation and local verification complete; waiting for human approval
> **Database schema/dependency changes:** None
> **Commit, push, or deployment:** Not performed

## Objective

Correct `POST /api/user-skill` as one authenticated, ownership-safe change while preserving its path, single-add payload, success/duplicate response semantics, API rate limiting, and the separate browser skill-management workflow.

## Scope

Completed:

- Applied the approved wording correction to the WP-1 report.
- Repeated the mandatory repository-wide endpoint-consumer search before changing the route or middleware.
- Corrected the route target to `Api\UserSkillController@store`.
- Added `auth:sanctum` without enabling stateful SPA middleware globally.
- Added specialist-profile authorization and UUID/existence validation through a focused FormRequest.
- Ensured the authenticated user is always the user-skill association owner.
- Moved the transactional duplicate check/create operation into a focused action.
- Serialized same-user additions by locking the authenticated user's database row during the transaction.
- Preserved JSON `200` success and `409` duplicate response messages.
- Made all WP-1 contract tests and the complete Feature suite green.

Excluded:

- No browser route, specialist browser controller, Blade template, JavaScript, session, CSRF, or active-role behavior changed.
- No WP-3 upload validation or later attachment work was started.
- No migration, schema constraint, data backfill, dependency, configuration, token-issuance flow, or global Sanctum middleware change was introduced.
- No production or external database/storage/system was contacted.

## Pre-change Repository Consumer Verification

Before modifying `routes/api.php`, the approved repository-wide search was repeated:

```text
rg -n -i --hidden --glob '!vendor/**' --glob '!node_modules/**' --glob '!storage/**' --glob '!.git/**' --glob '!bootstrap/cache/**' "(/api/user-skill|api/user-skill|user-skill|UserSkillController)" .
```

Observed repository consumers/references:

- the broken route in `routes/api.php`;
- the intended controller in `app/Http/Controllers/Api/UserSkillController.php`;
- the approved WP-1 contract tests; and
- project plans, audit records, and reports.

The only browser-related match used the distinct `/save-user-skills` path. Within this repository, no frontend caller, mobile client, or external-integration implementation depending on `/api/user-skill` was found. This repository-only conclusion does not rule out undocumented consumers maintained elsewhere.

No compatibility blocker was found within the repository, so WP-2 proceeded under the approved bearer-token contract.

## Architecture Decisions

### Bearer-token Sanctum boundary

The API route now uses `auth:sanctum` inside the existing `api` middleware group. This retains `throttle:api` and route bindings while producing JSON `401` before controller execution for unauthenticated requests.

Stateful SPA middleware remains disabled. The existing browser skill editor continues to use its authenticated web route, session, CSRF token, and active specialist-role middleware.

### Durable specialist eligibility

`StoreUserSkillRequest::authorize()` requires the authenticated user to own a `user_profiles` row whose type is `specialist`. It does not depend on the mutable `active_role` session value, which is unavailable and inappropriate for a bearer-token API contract.

An authenticated employer-only user receives `403` before any write.

### Authenticated-user ownership

The controller passes `$request->user()` to the action. No request `user_id` is validated or consumed. Supplying another user's UUID therefore cannot redirect the write.

### Focused validation

The FormRequest requires `skill_id` to be present, a UUID, and an existing skill ID. Missing, malformed, and unknown identifiers return JSON `422` through Laravel's standard validation response.

### Transaction and same-user serialization

`AddUserSkillAction` opens a database transaction, locks the authenticated user's row with `lockForUpdate()`, checks for the existing user/skill pair, and creates only when absent.

Locking one durable row serializes concurrent skill additions for the same user only when every writer uses `AddUserSkillAction`; it does not serialize direct database writes or other application paths that bypass the action. The database still lacks a demonstrated composite unique constraint on `user_skills`, so this application-level lock is not a replacement for a future database unique constraint and WP-2 does not claim database-enforced uniqueness.

### Response compatibility

The existing intended controller messages and status behavior were preserved:

- new association: JSON `200` with `message`;
- existing association: JSON `409` with `message`.

No new response field or route name was introduced.

## Files Changed

### WP-2 production files

| File | Purpose |
|---|---|
| `routes/api.php` | Correct route target and apply `auth:sanctum`. |
| `app/Http/Controllers/Api/UserSkillController.php` | Coordinate validated request, authenticated identity, action result, and preserved JSON responses. |
| `app/Http/Requests/Api/StoreUserSkillRequest.php` | Specialist-profile authorization and `skill_id` validation. |
| `app/Actions/Api/AddUserSkillAction.php` | Transactional, same-user-serialized duplicate check and insert. |

### Previously approved WP-1/plan files present in the working tree

| File | WP-2 interaction |
|---|---|
| `tests/Feature/Api/UserSkillApiContractTest.php` | No WP-2 contract expansion; all seven red WP-1 tests now pass against the implementation. |
| `docs/reports/SPRINT-1B-WP1-REPORT.md` | Only the user-requested repository-scope wording correction was applied before WP-2. |
| `docs/reports/SPRINT-1B-IMPLEMENTATION-PLAN.md` | Unchanged during WP-2; contains the previously approved verification gates. |
| `docs/reports/SPRINT-1B-WP2-REPORT.md` | This implementation and verification report. |

The pre-existing untracked `.claude/` directory was not modified.

## Route Diff

### Before WP-2

```text
POST api/user-skill -> Api\SkillController@store
middleware: api
```

The target method did not exist, and the endpoint returned `500` before validation or mutation.

### After WP-2

```text
POST api/user-skill -> Api\UserSkillController@store
middleware: api, App\Http\Middleware\Authenticate:sanctum
```

The inherited `api` group continues to provide `throttle:api` and route bindings.

Route inspection also confirmed that the browser route remains:

```text
POST save-user-skills -> SkillSelectController@saveSkills
middleware: web, Authenticate, EnsureActiveRole:specialist
```

No existing route path or browser route middleware was changed.

## Test Evidence

### WP-1 API contract suite

```text
php vendor/bin/phpunit tests/Feature/Api/UserSkillApiContractTest.php --colors=never
```

Result: **passed â€” 7 tests, 27 assertions, 2.510 seconds**.

Coverage includes:

- intended controller and Sanctum middleware;
- unauthenticated `401` and no write;
- specialist success and own-user persistence;
- submitted cross-user ownership ignored;
- employer-only `403` and no write;
- missing, malformed, and unknown skill validation; and
- duplicate `409` with one remaining row.

### Complete Feature suite

```text
php artisan test --testsuite=Feature
```

Result: **passed â€” 35 tests, 121 assertions, 5.43 seconds**.

This includes all seven API contract tests and the 28 Sprint 1A authorization tests.

### Route inspection

```text
php artisan route:list --path=user-skill -v
```

Result: passed; the API and browser route details match the Route Diff section.

### Syntax

`php -l` passed for:

- `app/Actions/Api/AddUserSkillAction.php`
- `app/Http/Controllers/Api/UserSkillController.php`
- `app/Http/Requests/Api/StoreUserSkillRequest.php`
- `routes/api.php`

### Style and integrity

| Verification | Result |
|---|---|
| Scoped Pint over the four WP-2 PHP files and WP-1 API test | Passed after scoped formatting corrected two touched-file style issues. |
| `git diff --check` | Passed. |
| Scoped debug scan for `dd`, `dump`, `var_dump`, `ray`, `console.log`, and `debugger` | No matches. |
| Changed-file review | Production diff is limited to the route, API controller, API FormRequest, and API action. |
| Browser workflow diff review | No changes under `routes/web.php`, `routes/user.php`, `Specialist\SkillController`, or `resources/views/user/skills/index.blade.php`. |
| Dependency/configuration/migration review | No manifest, lockfile, configuration, or migration changed. |

### MySQL-compatible locking verification

Not run. The approved automated test environment is forced to isolated SQLite `:memory:`, where `lockForUpdate()` cannot demonstrate MySQL row-lock semantics. No approved isolated MySQL/staging connection was available, and project rules prohibit pointing tests at production or reading/using undisclosed environment credentials.

The implementation uses Laravel's standard transaction and `lockForUpdate()` query path, but concurrent same-user behavior must still be verified on an approved MySQL-compatible non-production environment before release. This is disclosed as an operational verification gap, not reported as passed.

## Risk Assessment

| Risk | Assessment / mitigation |
|---|---|
| Undocumented off-repository consumer expects public or cookie/session access. | Repository search found none, but cannot exclude external consumers. Path/payload/success/duplicate semantics are preserved; authentication is the intentional security change. Confirm integrations before release. |
| API clients lack a token issuance/revocation workflow. | Sanctum support exists, but token lifecycle design is outside WP-2. Do not advertise the endpoint to new clients until issuance, abilities, expiration, and revocation are approved. |
| Same-user concurrency differs on MySQL. | Standard row locking is implemented; isolated MySQL concurrency verification remains required before release. |
| No database composite unique constraint exists. | User-row locking serializes this application path. Direct writes or future code that bypasses the action could still create duplicates; inventory and constraint work remain a separate approved task. |
| Skill rows created by the API have nullable level/experience metadata. | Preserves the existing single-add API design and current schema. Do not silently merge it with the richer browser bulk-sync contract. |
| Employer users who also own a specialist profile are eligible regardless of browser active role. | Intentional durable API rule approved in WP-1; bearer-token access does not rely on session role. |
| Locking the user row serializes all API skill additions for one user. | Critical section is short and affects only concurrent writes for the same user. Measure if API volume grows. |
| Validation/existence check races with skill deletion. | The foreign key remains defense in depth. A concurrent deletion may return a server/database error; rare race normalization is not covered by WP-2. |

## Database, Security, Compatibility, and Deployment Impact

- **Database schema:** None.
- **Production data:** None modified during implementation or tests.
- **Dependencies:** None.
- **Configuration:** None.
- **Security:** The state-changing API now requires Sanctum authentication, durable specialist eligibility, authenticated-user ownership, and validated skill identity.
- **Compatibility:** Method, path, payload, success status/message, duplicate status/message, and API throttling are preserved. The formerly unauthenticated broken route now intentionally rejects unauthenticated callers.
- **Browser workflow:** Unchanged.
- **Deployment:** Not performed. Release should wait for external-consumer confirmation and approved MySQL concurrency verification.

## Rollback Procedure

WP-2 has no migration, dependency, configuration, or automated data mutation to reverse.

To roll back WP-2 code:

1. revert `routes/api.php`, `app/Http/Controllers/Api/UserSkillController.php`, `app/Http/Requests/Api/StoreUserSkillRequest.php`, and `app/Actions/Api/AddUserSkillAction.php` as one focused unit;
2. retain WP-1 tests and reports as historical evidence where possible, understanding that the API tests will return to their documented red baseline;
3. do not delete user-skill associations created by authenticated callers while WP-2 code was active, because they are legitimate user-owned data;
4. rerun the complete Feature suite and inspect the route in a non-production environment; and
5. do not weaken authentication alone as a partial rollback. If the endpoint must be disabled, prefer a reviewed explicit disablement rather than restoring an unsafe public mutation.

No schema rollback is required. Application data created while WP-2 was active remains valid user-owned data and must not be deleted as part of rollback. No cache clear, file operation, dependency operation, external-system action, commit, push, or deployment is required.

## Remaining Work for WP-3

WP-3 is not started. After human approval, it should remain limited to project-upload allowlisting and characterization:

1. approve the exact extension/MIME matrix, including whether ZIP and engineering-native formats are allowed;
2. preserve the existing 10 MiB per-file limit;
3. add representative allowed and denied upload fixtures;
4. reject executable, HTML, SVG, script, macro-enabled, and extension/MIME-mismatched content;
5. verify mixed multi-file validation produces no project, attachment row, or stored-file side effect;
6. verify no-file project creation remains unchanged; and
7. avoid starting private storage, protected downloads, disk metadata, or legacy migration work reserved for WP-4 and later.

## Approval Status

WP-2 implementation and local verification are complete.

Current status: **waiting for human approval before WP-3**.

- All WP-1 API contract tests are green.
- The complete Feature suite is green.
- MySQL-compatible concurrency verification remains pending in an approved non-production environment.
- No commit was created.
- No push was performed.
- No deployment was performed.
