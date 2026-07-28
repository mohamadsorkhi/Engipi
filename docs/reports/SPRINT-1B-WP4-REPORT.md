# Sprint 1B WP-4 â€” Protected Project Attachment Delivery and Private Writes Report

> **Report date:** 2026-07-16
> **Work package:** WP-4 â€” Protected delivery and private writes
> **Status:** Implementation and local verification complete; waiting for human approval
> **Legacy public-file migration/removal:** Not performed
> **Commit, push, or deployment:** Not performed

## Objective

Store new project attachments privately, deliver both new and legacy project attachments through one authenticated and policy-protected endpoint, preserve existing legacy public files and URLs until WP-5, and make project/user deletion aware of both storage generations.

## Scope

Completed:

- Applied the two approved WP-3 report clarifications before implementation.
- Performed the mandatory pre-change repository-wide attachment-rendering enumeration.
- Added additive nullable storage-disk metadata to `project_files`.
- Changed only new project-file writes from the public disk to the private local disk.
- Added and registered a dedicated project-file download policy.
- Added one authenticated named download route and thin controller.
- Added safe attachment headers, filename handling, MIME fallback, and missing-file behavior.
- Replaced all four known direct application-rendered project attachment links with the protected route.
- Updated all three known project-file deletion paths to resolve legacy/public and new/private disks explicitly.
- Added focused authorization, delivery, header, storage-generation, deletion, and edge-case tests.
- Performed the mandatory post-change repository-wide link enumeration.

Excluded:

- No legacy public file was copied, moved, renamed, deleted, or reclassified.
- No existing `project_files` row was backfilled or updated.
- Existing public storage paths and static URLs were not removed or disabled.
- No inventory/migration command, batch processing, dry run, production storage inspection, or WP-5 work was started.
- No filesystem configuration, dependency, upload allowlist, unrelated route, or unrelated authorization rule changed.
- No production data, commit, push, or deployment operation occurred.

## Architecture Decisions

### Explicit storage metadata

A nullable `storage_disk` column was added to `project_files`:

- `null` means a legacy record and resolves to `public`;
- `local` identifies a new private attachment;
- new writes always persist `local` explicitly; and
- no disk is guessed through cross-disk existence checks.

`ProjectFile::storageDisk()` centralizes the compatibility rule. This avoids duplicating the null-to-public fallback in controllers and deletion actions.

### Additive transition

The schema migration only adds a nullable string after `path`. It performs no row scan or backfill, so the previous application version can continue ignoring the column during a phased deployment.

The application supports both storage generations immediately. WP-4 changes application-rendered links but intentionally leaves legacy static public URLs physically reachable until the separately approved WP-5 migration/removal process.

### Dedicated file policy

`ProjectFilePolicy::download` owns the attachment decision. It allows the project employer and administrator directly, then delegates specialist eligibility to the already established `ProjectPolicy::viewMatchedProject` Gate ability. The project matching algorithm is not duplicated.

### Authorization before filesystem access

The controller authorizes the bound `ProjectFile` before selecting a disk, checking file existence, reading metadata for delivery, or opening the stream. Unauthorized users receive non-enumerating `404` behavior.

### One protected application route

All active and legacy Blade attachment renderers now use the named `user.project-files.download` route. Signed URLs were not used because project eligibility can change and must be checked at request time.

## Files Changed

### WP-4 production files

| File | Purpose |
|---|---|
| `database/migrations/2026_07_16_000000_add_storage_disk_to_project_files_table.php` | Adds nullable `storage_disk`; no backfill. |
| `app/Models/ProjectFile.php` | Makes disk metadata fillable and resolves null legacy rows to `public`. |
| `app/Policies/ProjectFilePolicy.php` | Defines owner, matched specialist, admin, and non-enumerating denial behavior. |
| `app/Providers/AuthServiceProvider.php` | Registers `ProjectFilePolicy`. |
| `app/Http/Controllers/User/ProjectFileController.php` | Authorizes, resolves disk, validates existence, sanitizes response metadata, and streams attachment downloads. |
| `routes/user.php` | Adds the single authenticated named download route. |
| `app/Actions/Employer/CreateProjectAction.php` | Writes new project attachments to `local` and records `storage_disk = local`. |
| `app/Actions/Employer/DeleteProjectAction.php` | Deletes files from their resolved storage generation. |
| `app/Actions/Admin/DeleteProjectAction.php` | Deletes files from their resolved storage generation. |
| `app/Actions/Admin/DeleteUserAction.php` | Deletes an employer's project files from their resolved storage generation. |
| `resources/views/user/projects/show.blade.php` | Replaces the employer direct storage URL with the protected route. |
| `resources/views/user/matched-projects/show.blade.php` | Replaces the active matched-project direct storage URL. |
| `resources/views/specialist/matched-projects/show.blade.php` | Replaces the legacy specialist direct storage URL. |
| `resources/views/admin/projects/show.blade.php` | Replaces the admin direct storage URL. |

### WP-4 tests

| File | Purpose |
|---|---|
| `tests/Feature/Authorization/ProjectFileDownloadAuthorizationTest.php` | Covers authorization, both disks, headers, filenames, missing records/files, and all deletion actions. |
| `tests/Feature/Uploads/ProjectUploadValidationTest.php` | Updates the approved-upload assertion to require a private local write and explicit disk metadata. |

### Approved documentation-only update before WP-4

| File | Change |
|---|---|
| `docs/reports/SPRINT-1B-WP3-REPORT.md` | Clarifies the OOXML generic-ZIP compatibility exception and preservation of files accepted while WP-3 was active. |

`docs/reports/SPRINT-1B-WP4-REPORT.md` is this report. Previously approved WP-1 through WP-3 changes remain in the working tree. The pre-existing untracked `.claude/` directory was not modified.

## Authorization Model

| Actor / relationship | Result | Reason |
|---|---|---|
| Guest | Redirected to login by existing outer `auth` middleware. | No file record or bytes are delivered. |
| Project employer | Allowed. | `project.employer_id` equals authenticated user ID. |
| Canonically matched specialist | Allowed. | Existing `ProjectPolicy::viewMatchedProject` Gate ability allows the project. |
| Administrator | Allowed. | Preserves current explicit admin project-detail access. |
| Unmatched specialist | `404`. | Does not reveal attachment or project eligibility. |
| Unrelated authenticated employer/user | `404`. | No ownership or canonical matched-project basis. |
| Rejected/excluded specialist | `404`. | Existing canonical matched-project policy excludes the project. |
| Nonexistent project-file UUID | Route binding `404`. | No record disclosure. |
| Authorized user, missing physical file | `404`. | Record exists but unavailable storage state is not exposed as a path/error detail. |

There is no implicit admin impersonation, message-thread, collaboration-message, or arbitrary authenticated-user override.

## Download Flow

```text
GET /user/project-files/{projectFile}/download
  -> web + auth middleware
  -> UUID route-model binding
  -> ProjectFilePolicy::download
  -> resolve storage_disk (null => public, local => private)
  -> verify physical path exists
  -> sanitize download name and MIME metadata
  -> stream as attachment with security headers
```

Important ordering:

1. authorization occurs before disk selection and existence checks;
2. only authorized requests can reach filesystem operations;
3. the path comes from database metadata, not request input;
4. the original filename is display/header metadata only and is never used as a filesystem path; and
5. the download is streamed through Laravel's filesystem abstraction rather than exposed through a generated storage URL.

## Route Changes

One route was added under the existing authenticated `/user` group:

```text
GET|HEAD user/project-files/{projectFile}/download
name: user.project-files.download
controller: User\ProjectFileController@download
middleware: web, Authenticate
```

Active route count changed intentionally from 94 to 95. No route was removed, renamed, or otherwise changed.

The route does not require `active_role`; authorization is based on durable project relationships and explicit admin status, allowing owners, eligible specialists, and administrators to use one endpoint without session-role coupling.

## Storage Strategy

| Record generation | Database metadata | Physical disk | Delivery |
|---|---|---|---|
| Existing legacy | `storage_disk = null` | `public` | Protected route dual-read; old static URL remains physically available until WP-5. |
| New WP-4 | `storage_disk = local` | `local` (`storage/app`) | Protected route only; no public storage URL is generated. |

New files retain the existing generated path shape `project-files/{project UUID}/{generated filename}` but reside under the private disk root.

Deletion actions call `ProjectFile::storageDisk()` for each row, so mixed legacy/public and new/private projects are cleaned up correctly. No broad directory deletion or computed recursive operation was introduced.

## Attachment Rendering Enumeration

### Pre-change result

Repository-wide inspection identified four direct project-attachment renderers:

1. `resources/views/user/projects/show.blade.php`
2. `resources/views/user/matched-projects/show.blade.php`
3. `resources/views/specialist/matched-projects/show.blade.php`
4. `resources/views/admin/projects/show.blade.php`

Each used `Storage::url($file->path)` and bypassed application authorization. No additional project-attachment link was found in controllers, API resources, JavaScript, mail, integration/export templates, or tests. Theme/demo attachment icons without project-file delivery logic were not consumers.

### Post-change result

The explicit post-change scan:

```text
rg -n "Storage::url\(" app routes resources tests export
```

returned no matches.

A route-use enumeration found exactly the four Blade renderers above, all using:

```text
route('user.project-files.download', $file)
```

This proves the repository's application-rendered project attachment links no longer bypass the protected route. It does not claim that already published legacy static URLs have stopped working; the user explicitly deferred their removal to WP-5.

## Security Headers and Metadata Handling

Downloads include:

| Header/behavior | Value / purpose |
|---|---|
| `Content-Disposition` | `attachment`; generated by Laravel's download response to prevent approved inline rendering. |
| `X-Content-Type-Options` | `nosniff`; prevents browser MIME sniffing. |
| `Cache-Control` | `private, no-store` directives (framework may normalize order); avoids shared/private persistence. |
| `Pragma` | `no-cache`; conservative compatibility behavior. |
| `Content-Type` | Stored MIME only when it matches a conservative media-type syntax; otherwise `application/octet-stream`. |

Download filenames:

- normalize backslashes before applying `basename`;
- remove carriage-return and newline characters;
- fall back to `attachment` when empty; and
- are passed to Laravel/Symfony's attachment response handling.

Tests verify that path segments and header control characters do not survive into `Content-Disposition`, and malformed legacy MIME metadata falls back to `application/octet-stream`.

## Test Evidence

### Focused WP-4 authorization/delivery suite

```text
php vendor/bin/phpunit tests/Feature/Authorization/ProjectFileDownloadAuthorizationTest.php --colors=never
```

Final result: **passed â€” 12 tests, 36 assertions, 3.301 seconds**.

Coverage includes:

- guest denial;
- owner download of a null-metadata legacy public file;
- matched specialist download of a private file;
- administrator download;
- unmatched specialist and unrelated employer `404`;
- authorized missing-file `404`;
- nonexistent record `404`;
- attachment, content type, `nosniff`, private/no-store, and no-cache headers;
- path/control-character filename sanitization and malformed MIME fallback; and
- employer project deletion, admin project deletion, and admin user deletion across mixed public/private file generations.

### WP-3 upload regression

```text
php vendor/bin/phpunit tests/Feature/Uploads/ProjectUploadValidationTest.php --colors=never
```

Result: **passed â€” 22 tests, 48 assertions**. The accepted-upload case verifies one private local file, explicit `storage_disk = local`, and zero public files.

### Complete Feature suite

```text
php artisan test --testsuite=Feature
```

Final result: **passed â€” 69 tests, 205 assertions, 7.73 seconds**.

This includes Sprint 1A authorization, WP-1/WP-2 API, WP-3 upload, and WP-4 attachment coverage.

### Routes, migrations, syntax, style, and integrity

| Verification | Result |
|---|---|
| `php artisan route:list --except-vendor -v` | Passed; 95 routes with one intentional protected-download addition. |
| Fresh SQLite migrations through `RefreshDatabase` | Passed in all focused and complete tests; nullable column is present and both null/local records work. |
| `php -l` over all changed/new WP-4 PHP files | Passed. |
| Scoped Pint on new controller, policy, migration, tests, and already-clean provider | Passed. |
| Scoped Pint across all touched legacy actions/model/route files | Reported six pre-existing whole-file line-ending/formatting issues. They were not broadly auto-formatted to avoid unrelated churn; syntax, tests, minimal diffs, and new-file style are green. |
| `git diff --check` | Passed. |
| Scoped debug scan | No `dd`, `dump`, `var_dump`, `ray`, `console.log`, or `debugger` matches. |
| Post-change `Storage::url()` scan | No matches under `app`, `routes`, `resources`, `tests`, or `export`. |
| Dependency/configuration scan | No dependency manifest, lockfile, or filesystem configuration changed. |

No production-like MySQL migration rehearsal or real filesystem deployment was run because no approved isolated staging target was provided. SQLite verifies application/migration compatibility locally; MySQL deployment rehearsal remains required before release.

## Remaining Risks

| Risk | Status / mitigation |
|---|---|
| Legacy static public URLs remain reachable. | Explicitly deferred by user instruction. Application links are protected, but confidentiality is not fully closed until WP-5 migrates/removes public copies. |
| Unknown leaked/ indexed legacy URLs may exist. | WP-5 must inventory exposure without logging sensitive paths and remove public copies only after verified private migration. |
| New private files make rollback to public-only code unsafe without preparation. | Keep storage-aware code/column or copy verified private files to public and update metadata through an approved rollback operation before reverting. |
| Migration behavior is not rehearsed on production-like MySQL. | Run additive migration and application checks on approved MySQL staging before deployment. |
| Filesystem and database transactions are not atomic together. | Existing architecture remains. Storage failures are visible, but a rare DB failure after file write can orphan a private file. An idempotent orphan-recovery strategy remains future operational work. |
| Stored legacy MIME can be active content. | Forced attachment plus `nosniff` reduces browser exposure; MIME syntax is sanitized. Malware scanning remains future defense-in-depth. |
| Canonical matching query is collection-heavy. | Correctness is reused from Sprint 1A; optimize only in Sprint 2 without changing authorization results. |
| Project eligibility can change after a link is rendered. | Every request re-runs authorization, which is why static/signed-only authorization was not used. |
| Local disk topology may not be shared across multiple application nodes. | Current filesystem configuration is preserved. A multi-node deployment requires shared private storage before scaling. |
| Public/private path strings can be identical. | Explicit disk metadata disambiguates them. No existence-based disk guessing is used. |

## Database and Deployment Impact

The migration is additive and leaves existing rows unchanged:

```text
project_files.storage_disk VARCHAR NULL
```

Recommended deployment order after staging approval:

1. back up and verify database/storage readiness;
2. verify that no external reporting, backup, analytics, or operational process depends on the historical public storage layout before production rollout, and update or explicitly approve each identified dependency;
3. apply the additive migration while old code is still compatible;
4. deploy storage-aware application code;
5. verify one new private upload and authorized downloads for both a legacy and new record; and
6. do not run any WP-5 migration/removal operation without separate approval.

No deployment was performed in this work package.

## Rollback

### Before deployment or before any WP-4 private file exists

The code can be reverted and the additive migration can be rolled back normally in an isolated/rehearsed environment. No application data exists to transition in this development session.

### After WP-4 has accepted private files

Do not revert to public-only application code or drop `storage_disk` immediately. That would make valid new private attachments unreachable and erase the metadata required to locate them.

Safe operational rollback requires human approval and this order:

1. stop or disable new project-file uploads through an approved maintenance/release mechanism;
2. inventory all `storage_disk = local` rows without exposing filenames/paths in logs;
3. copy each private file to its verified legacy public location, keeping the private source;
4. verify existence, size, and preferably checksum;
5. update that row to `storage_disk = public` only after successful copy verification;
6. deploy the prior public-only application version;
7. verify successful downloads for both untouched legacy public files and files restored from private to public storage, first in non-production/staging and then through approved production checks;
8. reopen write operations only after both legacy-public and restored-public download checks pass; and
9. remove private copies only in a later separately reviewed cleanup, never during emergency rollback.

Prefer leaving the nullable column in place during application rollback because old code ignores it and it preserves transition evidence. Run the migration `down()` only when no private metadata remains and removal is explicitly approved.

Legacy public files remain untouched by WP-4 and need no rollback. Files legitimately uploaded while WP-4 was active remain valid application data and must not be deleted solely because storage behavior is rolled back.

## Remaining Work for WP-5

WP-5 has not started. After explicit approval it must:

1. create an idempotent, restartable CLI-only inventory/migration operation with dry-run and bounded batches;
2. classify public-only, private-only, both-present, neither-present, and metadata-mismatch states;
3. report only aggregate counts/sizes and non-sensitive operational errors;
4. measure storage headroom before copying;
5. copy legacy public files to private storage, verify size/checksum, then update one row at a time;
6. remove each public copy only after private verification and metadata update;
7. rehearse interruption, restart, missing-file, orphan-file, and rollback behavior on a sanitized production-like copy;
8. obtain human approval of dry-run evidence, backup readiness, and rollback rehearsal before any production execution;
9. verify old static public URLs stop serving migrated files only after approved removal; and
10. retain a verified copy-back procedure for rollback.

WP-5 must not perform manual production edits, expose file paths in reports/logs, or delete either generation in bulk without per-file verification.

## Approval Status

WP-4 implementation and local verification are complete.

Current status: **waiting for human approval before WP-5**.

- Targeted and complete Feature suites are green.
- No legacy file was migrated or removed.
- Existing legacy public URLs remain physically available.
- No WP-5 implementation was performed.
- No commit was created.
- No push was performed.
- No deployment was performed.
