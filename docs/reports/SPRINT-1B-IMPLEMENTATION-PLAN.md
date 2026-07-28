# Sprint 1B â€” User-Skill API and Project Attachment Hardening Plan

> **Plan date:** 2026-07-16
> **Status:** Proposed; implementation requires human approval
> **Change type in this task:** Architecture analysis and execution planning only
> **Production code, schema, dependencies, routes, configuration, and data changed:** None

## Objective

Complete the API and project-file portion of Sprint 1 security hardening without disrupting the active browser skill workflow or legitimate project access. Sprint 1B will:

1. correct and authenticate `POST /api/user-skill`;
2. define and test its ownership, validation, duplicate, and response contracts;
3. restrict new project uploads to an approved document allowlist;
4. store new project attachments privately and deliver them only through an authorized application endpoint; and
5. transition existing public project attachments through a reversible, observable process rather than manual production edits.

Dependency remediation, deployment-pipeline work, broad role refactoring, and unrelated Sprint 1 findings remain outside Sprint 1B.

## Source Documents Reviewed

- `docs/PROJECT_MASTER_PLAN.md`
- `docs/AI_PROJECT_RULES.md`
- `docs/reports/Sprint-1A-FINAL-REPORT.md`

Sprint 1A established the isolated feature-test foundation and the canonical matched-project authorization rule. Sprint 1B must build on those controls rather than duplicate or weaken them.

## Current Architecture Findings

### User-skill API

| Area | Current state | Consequence |
|---|---|---|
| Route | `POST /api/user-skill` points to `Api\SkillController@store`, which does not exist. | The endpoint cannot complete its advertised operation. |
| Intended controller | `Api\UserSkillController@store` exists and accepts `skill_id`. | Correcting only the controller target would expose a state change without authentication. |
| Middleware | The route receives only the `api` group (`throttle:api` and bindings). It has no `auth:sanctum`. | `Auth::id()` is null for unauthenticated requests, and ownership is not established. |
| Sanctum | Sanctum is installed; `User` uses `HasApiTokens`; no active API route uses Sanctum. Stateful SPA middleware is disabled in the API group. | The least-surprising API contract is bearer-token authentication unless a separate first-party SPA/session design is approved. |
| Active browser flow | The actual skill editor posts JSON to named web route `user.skills.store` inside `auth` and `active_role:specialist`; it bulk-syncs skill level and years. | The API is not a current in-repository browser dependency and must not replace or silently alter the web bulk-sync contract. |
| Persistence | `UserSkillController` performs an existence check and then `create()`. The pivot has level/years/custom fields, while the API supplies only `skill_id`. | Duplicate behavior is intended as `409`, but concurrent requests are not protected by a demonstrated database unique constraint. New rows have nullable proficiency metadata. |
| Schema history | The migration named `add_unique_index_to_user_skills_table` has empty `up()` and `down()` bodies. | Uniqueness must not be assumed. A new constraint would require duplicate-data inventory and explicit migration approval. |
| Consumers | No application code outside the route/controller references `/api/user-skill`. | Contract changes are low in known client impact, but unknown external consumers must still be treated as possible. |

### Project attachments

| Area | Current state | Consequence |
|---|---|---|
| Upload entry point | The full employer project-create flow passes `files[]` through `StoreProjectRequest` to `CreateProjectAction`. The simple project-create flow does not upload files. | Sprint 1B can remain focused on one active upload path. |
| Validation | Each item is validated only as `file|max:10240`. | Active or executable content and unexpected document formats are accepted. |
| Storage | `CreateProjectAction` writes to the `public` disk under `project-files/{project UUID}`. Laravel generates the stored filename. | The generated name is helpful, but files bypass application authorization through `/storage`. |
| Metadata | `project_files` stores UUID, project UUID, path, original name, detected MIME type, and size; it has no disk/visibility column. | A mixed public/private transition is ambiguous unless storage location is encoded or resolved deliberately. |
| Direct links | Employer project detail, specialist matched-project detail, admin project detail, and a legacy matched-project view call `Storage::url($file->path)`. | All rendered attachment links currently bypass policies. |
| Existing read rules | Employer show uses an inline owner check; specialist matched detail uses `ProjectPolicy::viewMatchedProject`; admin routes use authenticated admin middleware. | Attachment authorization can reuse these established resource rules, with a dedicated policy ability that makes each allowed audience explicit. |
| Deletion | Employer project deletion, admin project deletion, and admin user deletion delete attachment paths only from the `public` disk. | Mixed storage requires coordinated deletion behavior so private files are not orphaned and legacy files are not missed. |
| Test environment | PHPUnit uses isolated SQLite and the local filesystem setting. Sprint 1A supplies authorization fixture helpers. | File tests can use `Storage::fake()` and synthetic uploads without touching real storage or external systems. |

## Proposed Architecture Decisions

These are recommendations for approval, not implemented decisions.

### AD-1: Keep the API distinct from the browser skill editor

Preserve the route and request shape `POST /api/user-skill` with `{ "skill_id": "<uuid>" }`, point it to `Api\UserSkillController@store`, and protect it with `auth:sanctum` plus the existing `throttle:api` group.

- Treat it as a token-authenticated API endpoint for Sprint 1B.
- Do not enable stateful SPA middleware globally merely to support this unused endpoint.
- Do not change `user.skills.store`, its bulk-sync payload, CSRF/session behavior, or frontend.
- Resolve the user from the authenticated request, never from a submitted user ID.
- Require the authenticated user to own a specialist profile; do not depend on mutable `active_role` session state for a bearer-token API request.
- Preserve the known success JSON message and `409` duplicate meaning where practical; normalize unauthenticated requests to JSON `401` and validation failures to JSON `422`.

Before implementation, the maintainer should confirm that no external client expects cookie/session authentication. If a first-party SPA is the real consumer, stateful Sanctum configuration, CORS, CSRF, and domain settings require a separate reviewed design.

### AD-2: Serialize same-user skill additions without a Sprint 1B schema change

Use a transaction that locks the authenticated user row, checks the user-skill pair, and creates only when absent. This preserves the intended `409` contract and serializes concurrent additions for the same user without assuming the missing unique index.

A database unique constraint remains the stronger invariant, but it is deferred until a production-safe duplicate inventory and cleanup decision are approved. Sprint 1B must document this limitation rather than claim database-enforced uniqueness.

### AD-3: Use a narrow project-document allowlist

Recommended initial formats are PDF, plain text, CSV, Microsoft Word (`doc`, `docx`), Microsoft Excel (`xls`, `xlsx`), and ZIP. The exact extension/MIME pairs must be represented by one authoritative validation rule or configuration-backed list and covered by tests.

- Keep the existing 10 MiB per-file limit.
- Validate both extension and detected MIME type; a renamed executable must fail.
- Reject HTML, SVG, JavaScript, executable/script formats, and macro-enabled Office formats by default.
- Continue using server-generated stored filenames.
- Keep the original name only as display/download metadata; sanitize it for response headers and never use it as a storage path.
- Serve all approved formats as downloads, not inline content.
- Malware scanning is recommended future defense-in-depth, but adding a scanning service/dependency is outside Sprint 1B.

The final business allowlist is an approval item because accepting CAD/native engineering formats safely requires a product-specific list and MIME test corpus.

### AD-4: Add a dedicated attachment authorization ability

Add a `ProjectFilePolicy::download` ability (or an equivalently explicit file-level policy) and register it. Authorization is evaluated before checking the filesystem or returning metadata.

| Actor / relationship | Proposed result |
|---|---|
| Guest | Denied by `auth`; no file response. |
| Project employer | Allowed when `project.employer_id` equals the authenticated user ID. |
| Eligible specialist | Allowed only when the parent project satisfies the same canonical rule as `ProjectPolicy::viewMatchedProject`. |
| Administrator | Allowed through the existing explicit admin rule, consistent with current admin project detail access. |
| Unmatched specialist, unrelated authenticated user, stale/invalid file UUID | `404` with no storage metadata disclosure. |

The policy must reuse a shared Project-policy decision or a canonical query; it must not copy the matching algorithm into the controller.

### AD-5: Deliver files through a named web download route

Add an authenticated GET route such as `user/project-files/{projectFile}/download` with a stable route name. The controller will:

1. authorize the bound `ProjectFile` before storage access;
2. resolve the approved private/legacy location;
3. return a streamed download with a sanitized original filename;
4. set an explicit stored MIME type or safe fallback, `Content-Disposition: attachment`, `X-Content-Type-Options: nosniff`, and conservative cache behavior; and
5. return non-enumerating `404` behavior when the record or authorized file is unavailable.

All four known Blade direct-link surfaces must use the named route. No signed URL alone replaces authorization, because eligibility can change.

### AD-6: Encode storage location explicitly for a safe transition

Preferred design: add a nullable `disk` (or `storage_disk`) column to `project_files` in a small backward-compatible migration.

- Existing rows remain null and are interpreted as legacy `public` records.
- New rows are written to the private `local` disk and record `local` explicitly.
- New code dual-reads by metadata: `local` for new records, `public` for null/legacy records.
- Deletion code uses the recorded/resolved disk.
- No existing row is rewritten by the schema migration.

This is clearer and safer than guessing by `exists()` across disks. It does not by itself close exposure of legacy public files; those remain public until migrated and removed from the public disk.

If the maintainer does not approve a schema change, Sprint 1B must stop after allowlisting and protected-link delivery design, or approve a separately documented path-prefix convention. Silent disk guessing is not recommended.

## Execution Plan

Each work package requires a focused diff, targeted tests, a report in `docs/reports/`, and human review before moving to operational file migration.

### WP-1 â€” API characterization and contract lock

**Purpose:** Establish expected behavior before exposing the intended controller.

Planned work:

- Add feature tests for the currently intended request and JSON response shapes.
- Record route name/method/path and middleware expectations.
- Verify no current web UI calls the API.
- Confirm the specialist-profile eligibility rule and token-only decision.
- Add tests for unauthenticated `401`, missing/invalid/nonexistent skill `422`, successful own-user insert, duplicate `409`, no cross-user write, and non-specialist denial.
- Include a concurrency-oriented service/action test if the locking logic is extracted; disclose SQLite limitations and verify locking on MySQL-compatible staging before release.

**Approval gate:** API authentication mode and specialist eligibility rule accepted.

### WP-2 â€” Correct and authenticate the user-skill API

**Purpose:** Make the state-changing endpoint functional without changing the browser flow.

Planned work:

- Before changing the route target or middleware, perform and record a mandatory repository-wide search across application code, Blade/JavaScript assets, mobile-client code, integration code, tests, and documentation to identify every `/api/user-skill` consumer. Do not proceed if a frontend, mobile client, or external integration contract is found until its authentication and compatibility requirements are reviewed and approved.
- Point the route to `Api\UserSkillController@store`.
- Apply `auth:sanctum`; retain the API rate limiter.
- Introduce a focused FormRequest for JSON validation/authorization if it clarifies the contract.
- Use the authenticated request user and a transaction/row lock for duplicate-safe creation.
- Preserve success and duplicate response semantics; add a stable status field only if explicitly approved as a contract addition.
- Run API tests, the complete feature suite, route inspection, syntax, Pint, and diff checks.

**Rollback:** Revert the route/controller/request/tests as one focused work package. No schema or data rollback is expected; valid skill rows created while active remain legitimate user-owned data and must not be deleted automatically.

### WP-3 â€” Upload allowlist and characterization

**Purpose:** Reject unsafe new project attachments before changing storage.

Planned work:

- Lock the approved extension/MIME matrix and 10 MiB limit in tests.
- Move file validation to one reusable authoritative rule/list only if it removes real duplication.
- Test every allowed family, executable/HTML/SVG/script denial, extension/MIME mismatch, oversize files, multiple-file mixed validity, and no project/file/database write after validation failure.
- Confirm project creation remains transactional and successful no-file creation is unchanged.

**Approval gate:** Exact file allowlist accepted.

**Rollback:** Revert the validation rule/list and tests. No stored files or schema are affected by this work package.

### WP-4 â€” Protected delivery and private writes

**Purpose:** Ensure new attachments are private and every application download is authorized.

Planned work:

- Before and after implementation, perform and record an explicit repository-wide enumeration of every project-attachment rendering/download location across Blade templates, controllers, resources, JavaScript, mail, and integration code. The post-change verification must demonstrate that no project-attachment link still uses direct `Storage::url()` delivery or otherwise bypasses the authorized download route.
- Add and register the download policy.
- Add the authenticated named download route and thin controller.
- Add the nullable disk migration only after explicit schema approval.
- Write new uploads to private `local` storage with server-generated names and explicit disk metadata.
- Replace all known direct `Storage::url()` attachment links with the named download route.
- Update employer/admin/user deletion actions to delete from the recorded disk and retain transaction/error behavior.
- Test the complete access matrix, no-enumeration responses, missing physical files, response headers, original-name handling, private write location, and deletion on both storage generations.
- Verify route count/diff intentionally, run the complete feature suite, migration checks against fresh SQLite and MySQL-compatible staging, syntax, Pint, and `git diff --check`.

**Deployment note:** Deploying WP-4 stops public exposure for new uploads only. Legacy URLs remain reachable until WP-5 completes.

**Rollback:** Roll application code back while leaving the additive nullable column in place if needed. Before reverting to code that reads only the public disk, copy any new private files to their legacy public paths through an approved operational procedure; never delete the private source until verification succeeds.

### WP-5 â€” Legacy attachment inventory and migration rehearsal

**Purpose:** Prove that existing public files can be moved safely and reversibly.

Planned work:

- Build an idempotent, restartable Artisan command or equivalent reviewed operation; do not use a web endpoint.
- Provide dry-run mode that reports counts and aggregate sizes only, without names, paths, UUIDs, or user data in logs.
- Classify database row/file states: public present, private present, both present, neither present, and metadata mismatch.
- Copy each legacy file to private storage, verify existence/size (and checksum where operationally practical), update only that row's disk metadata, and then remove the public copy only after successful verification.
- Support bounded batches, restart, failure reporting, and safe interruption.
- Rehearse on a sanitized production-like snapshot/storage copy, including files missing from disk and orphaned disk files.
- Document runtime, storage headroom, backup, rollback copy-back, and post-run sampling.

**Approval gate:** Human review of dry-run results, backup readiness, storage capacity, and rollback rehearsal before any production execution.

**Rollback:** For migrated rows, copy verified private files back to public storage before resetting metadata to legacy/public. Never bulk-delete either generation until counts and checks pass.

### WP-6 â€” Final regression and handoff

**Purpose:** Confirm scope, compatibility, and closure evidence.

Planned work:

- Run the complete feature suite and focused API/file suites.
- Inspect active routes and middleware.
- Verify all known production Blade attachment links use the protected route.
- Verify new uploads never appear on the public disk.
- Verify migrated legacy paths are no longer directly served after the approved migration.
- Review diff/status for unrelated edits, debug code, secrets, generated files, migration safety, and dependency changes.
- Record remaining risks, MySQL/staging evidence, exact rollback, and approval status in the Sprint 1B final report.

## Expected Files in Implementation Scope

Exact names may be refined during each approved work package, but production changes should remain within these areas:

- `routes/api.php`
- `routes/user.php` or another existing authenticated web route group
- `app/Http/Controllers/Api/UserSkillController.php`
- a focused API FormRequest/action if justified
- `app/Http/Controllers/.../ProjectFileController.php`
- `app/Policies/ProjectFilePolicy.php`
- `app/Policies/ProjectPolicy.php` only to expose/reuse the canonical decision safely
- `app/Providers/AuthServiceProvider.php`
- `app/Http/Requests/Employer/StoreProjectRequest.php`
- `app/Actions/Employer/CreateProjectAction.php`
- the three deletion actions that currently assume the public disk
- `app/Models/ProjectFile.php`
- one additive migration for disk metadata, if approved
- the four Blade attachment-link surfaces identified above
- focused tests under `tests/Feature/` and reusable synthetic fixture helpers
- per-work-package and final reports under `docs/reports/`

No dependency, Vite asset, broad role model, unrelated controller, production configuration, or deployment workflow change is planned.

## Acceptance Criteria

Sprint 1B is ready for final approval only when:

- `POST /api/user-skill` invokes a real controller and requires `auth:sanctum`.
- An unauthenticated or ineligible caller cannot create any user-skill row.
- The endpoint can mutate only the authenticated user's association and has documented success, `401`, `403`/`404`, `409`, and `422` behavior.
- Duplicate requests do not create duplicate rows in tested sequential and same-user serialized execution; the absence or presence of a database unique invariant is reported accurately.
- The current authenticated browser bulk-sync workflow remains unchanged and passes regression tests.
- New project uploads accept only the approved size/extension/MIME combinations.
- New project attachments are not written beneath public storage.
- Every application attachment link targets an authenticated, policy-protected route.
- Employer, eligible matched specialist, and administrator access work; unrelated and unmatched access does not reveal record or storage existence.
- Downloads use attachment disposition and `nosniff` with safe filename handling.
- Both private and legacy attachment deletion paths are covered.
- Legacy public files have a reviewed inventory, migration rehearsal, and rollback procedure; production migration is not run without separate approval.
- No dependencies are changed and no unrelated production code is modified.
- All targeted and complete feature tests pass, with MySQL-specific gaps disclosed.

## Risks and Mitigations

| Risk | Mitigation |
|---|---|
| An unknown client expects unauthenticated or session-based API access. | Preserve path/payload, search known consumers, document bearer-token decision, and obtain approval before WP-2. |
| `active_role` is session-based and unsuitable for tokens. | Authorize API eligibility against durable specialist-profile ownership, not session state. |
| Concurrent duplicate skill inserts occur without a unique index. | Serialize on the authenticated user row; backlog a unique constraint after duplicate inventory. |
| MIME detection differs across environments. | Test representative fixtures on CI/staging and use explicit extension/MIME pair mappings with conservative denial. |
| Approved engineering files are blocked. | Start narrow, log no sensitive file data, and expand the allowlist only through reviewed fixtures and business need. |
| Changing links creates a false sense of privacy while legacy URLs remain live. | Treat legacy migration/removal as a required, separately approved closure step. |
| Mixed public/private records cause missed downloads or orphaned files. | Store explicit disk metadata, cover both generations in tests, and inventory before migration. |
| Application rollback cannot see new private files. | Keep the additive column, document copy-back, and verify before reverting storage-aware code. |
| Unauthorized requests reveal file existence via status or timing. | Authorize before disk access and normalize denial to `404`. |
| Large legacy migration exhausts disk or times out. | Dry-run aggregate sizing, batch operation, headroom check, restartability, and rehearsal. |
| Database transaction succeeds while filesystem cleanup fails, or vice versa. | Make storage failure visible, use idempotent cleanup behavior, and explicitly test/report orphan recovery; do not claim filesystem/database atomicity. |

## Verification Plan

No tests are required to pass for this documentation-only planning task beyond reviewing the created Markdown and diff. During implementation, use:

```text
php artisan test --testsuite=Feature
php artisan route:list --except-vendor -v
vendor/bin/pint --test <scoped PHP files>
php -l <each changed PHP file>
git diff --check
git status --short
```

Additional implementation verification:

- `Storage::fake('local')` and `Storage::fake('public')` for private/legacy behavior;
- `UploadedFile::fake()` fixtures with representative allowed and denied contents;
- response header and streamed-download assertions;
- fresh migration and rollback rehearsal in isolated databases;
- MySQL-compatible staging verification for locking and migration behavior;
- an approved dry run of the legacy migration command against a sanitized production-like copy.

Tests must never use production databases, production storage, real tokens, or personal file metadata.

## Database, Dependency, Deployment, and Security Impact

### This planning task

- **Database:** None.
- **Dependencies:** None.
- **Deployment/configuration:** None.
- **Production data/files:** None.
- **Security behavior:** None; findings and proposed controls only.

### Proposed Sprint 1B implementation

- **Database:** One preferred additive nullable disk-metadata column; no automatic backfill in the migration. A later approved command updates rows incrementally.
- **Dependencies:** None planned.
- **Deployment:** Storage-aware code must deploy before legacy migration. Public-file removal is a separately approved operation.
- **Security:** Authenticated skill mutation, upload allowlisting, private storage, policy-protected downloads, non-enumerating denial, and safe download headers.

## Rollback Strategy

1. Keep API, validation, protected-delivery/private-write, and legacy-migration changes in separate work packages so each can be reverted independently.
2. Do not remove the additive disk column during an emergency application rollback; old code ignores it and dropping it could destroy transition state.
3. Never revert storage-aware code until new private files have been inventoried and made accessible to the rollback version through an approved copy-back.
4. Never delete migrated private files during rollback until public restoration and metadata verification succeed.
5. Do not delete user-skill rows merely because the API is rolled back; valid authenticated mutations are user data.
6. After rollback, rerun the complete feature suite and manually verify the affected workflow in a non-production environment.

## Approval Decisions Required

Before implementation begins, approve or revise:

1. bearer-token `auth:sanctum` for `/api/user-skill`, without enabling global stateful SPA middleware;
2. specialist-profile ownership as the durable API eligibility rule;
3. preservation of the single-add `{skill_id}` API contract and `409` duplicate behavior;
4. the initial attachment extension/MIME allowlist;
5. owner + canonical matched specialist + administrator download access;
6. the additive nullable disk-metadata migration; and
7. the WP-1 through WP-6 execution order and separate approval gate before any legacy production-file migration.

## Future Recommendations

- Inventory duplicate `user_skills` rows and add a real composite unique constraint in a separately approved backward-compatible migration.
- Decide and document API versioning, token abilities, token expiration/revocation, and first-party SPA versus mobile-client authentication before expanding the API.
- Evaluate malware scanning/quarantine for untrusted documents once an operational scanning service is approved.
- Extend protected file delivery to future message, ticket, portfolio, and milestone attachments through the same documented security principles.
- Continue remaining admin/active-role and rate-limit hardening in a separately bounded Sprint 1 work package; keep dependency/deployment remediation in Sprint 1C as recommended by the Sprint 1A report.

## Approval Status

Current status: **waiting for human review and approval**.

No production code, route, schema, dependency, configuration, data, file storage, Git history, or external system was modified while preparing this plan.
