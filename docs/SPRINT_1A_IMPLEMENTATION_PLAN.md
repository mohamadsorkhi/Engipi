# Sprint 1A — Tests and Access Control Implementation Plan

> **Status:** Proposed; implementation requires human approval  
> **Plan date:** 2026-07-15  
> **Source documents:** `docs/PROJECT_MASTER_PLAN.md` and `docs/reports/SPRINT-01_FULL_ANALYSIS.md`  
> **Application changes in this planning task:** None

## 1. Objective

Establish a repeatable authorization-test foundation and close the confirmed cross-user access paths in matched projects, collaboration requests, and messaging without changing database schema, dependencies, public route names, or unrelated product behavior.

Sprint 1A is complete only when the access rules in this document are enforced at the HTTP boundary, protected by automated feature tests, and centralized through a small initial policy layer while existing proven ownership checks remain in place.

## 2. Scope

### Included

- Create an isolated Laravel test harness suitable for authorization feature tests.
- Repair the existing `UserFactory` and add only the focused factories or test builders needed by Sprint 1A.
- Record and test the access-control matrix for matched projects, collaboration requests, and direct messages.
- Prevent a specialist from viewing a project outside `Project::forWorkerMatches()`.
- Ensure a denied matched-project request does not increment `projects.view_count`.
- Prevent collaboration requests for inaccessible projects and projects owned by the same user.
- Prevent arbitrary message recipients, unrelated project associations, self-messaging, and account enumeration through the conversation route.
- Introduce initial Laravel policies for the affected model-level rules without removing existing controller, FormRequest, or middleware checks that are already effective.
- Preserve current route names, response formats for successful requests, Persian/RTL presentation, and existing records.

### Excluded

- The user-skill API correction and API authentication design (Sprint 1B).
- Upload MIME restrictions, private attachment delivery, or file migration (Sprint 1B).
- Dependency upgrades or advisory remediation (Sprint 1C).
- Active-role/admin redesign, rate limiting, `TrustHosts`, CSP, or broad FormRequest hardening.
- Message query optimization, pagination, caching, indexes, or other performance work (Sprint 2).
- Removal of Worker, legacy controller, route, view, or translation code.
- Database migrations, production-data changes, deployment changes, dependency changes, and broad formatting.

## 3. Evidence and Current Boundaries

| Area | Current behavior | Risk to address |
|---|---|---|
| Matched-project detail | `Specialist\MatchedProjectController::show()` accepts any route-bound `Project` and increments its view count. | An authenticated specialist can read an unmatched project and modify its analytics. |
| Matched-project list | `Project::forWorkerMatches($user)` defines the current visible result set and excludes the user's projects and rejected requests. | Detail and list rules are inconsistent. |
| Collaboration request | `StoreCollaborationRequestRequest` validates only authentication and project existence. | A specialist can request any known project UUID. |
| Messaging thread | `MessagesController::show()` permits every non-self user UUID. | Arbitrary account enumeration and thread access are possible. |
| Message creation | `MessagesController::store()` validates only receiver/project existence plus self-messaging. | A message can target an unrelated user or reference an unrelated project. |
| Authorization structure | `AuthServiceProvider::$policies` is empty; checks are distributed across middleware, controllers, and FormRequests. | Omissions are difficult to detect and rules are hard to reuse. |
| Tests | No `tests` directory or PHPUnit configuration was found; `UserFactory` still emits a nonexistent `name` field instead of the current user columns. | No safe regression boundary exists for authorization changes. |

The implementation must treat the active unified routes in `routes/user.php` as canonical. Inactive Worker routes and controllers may be used only as behavioral reference and must not be modified in this sprint.

## 4. Proposed Access-Control Matrix

Unless a row states otherwise, unauthenticated access is handled by the existing `auth` middleware and users lacking the required active role are handled by `EnsureActiveRole`.

| Resource/action | Employer | Specialist | Admin | Denial behavior |
|---|---|---|---|---|
| List matched projects | Denied by active-role middleware | Allowed for own computed matches | Preserve current middleware bypass; do not expand admin behavior | Existing redirect/middleware response |
| View matched-project detail | Denied by active-role middleware | Allowed only when the project exists in `Project::forWorkerMatches(user)` | Preserve current behavior, covered by characterization test | `404` to avoid confirming project existence |
| Create collaboration request | Denied by active-role middleware | Allowed only for a currently matched project not owned by the user and without an existing request | No new admin capability | Validation-style `422` JSON compatible with the current form |
| Open message thread | Allowed only under the messaging relationship rule | Allowed only under the messaging relationship rule | No implicit access to private user messages | `404` for an unauthorized or unknown counterpart |
| Send message without `project_id` | Allowed only for an already-existing thread between the same two users | Same | No new admin capability | Validation error; no row created |
| Send message with `project_id` | Allowed only when sender and receiver are the project's employer/applicant pair and their request is eligible | Same | No new admin capability | Validation error; no row created |
| Read/mark messages as read | Only messages where the authenticated user is sender or receiver, after thread authorization | Same | No implicit access | `404`; no `read_at` mutation |

### Messaging relationship rule requiring approval

The recommended compatibility rule for Sprint 1A is:

1. A project-linked conversation is valid only between the project's `employer_id` and the user who submitted a collaboration request for that project.
2. `pending` and `accepted` requests may initiate and continue a conversation. A `rejected` request may not initiate a new thread.
3. A previously existing two-party thread may continue without a `project_id`, including legacy messages, so the sprint does not strand legitimate users.
4. A new unlinked thread cannot be initiated merely by knowing another user's UUID.
5. When `project_id` is supplied, it must be valid for the exact sender/receiver pair even if an unlinked legacy thread exists.
6. Self-messaging remains denied.

This rule must be approved before the messaging implementation task starts. If the product owner requires messaging only after acceptance, or requires employers to initiate contact before a request exists, that decision changes tests and policy behavior but not the rest of this plan.

## 5. Implementation Work Packages

Each work package should be a separate reviewable change. Runtime authorization changes must not begin until WP-1 provides passing characterization tests for the affected route.

### WP-1 — Isolated test foundation and characterization

**Purpose:** Make later changes measurable without modifying runtime behavior.

Planned work:

- Add a PHPUnit configuration compatible with the installed PHPUnit 10/Laravel 11 stack and an isolated test environment.
- Default tests to SQLite in memory only after confirming every Sprint 1A migration used by the fixtures is SQLite-compatible. If not compatible, document and use a dedicated non-production test database configured by environment variables.
- Never read `.env` secrets or allow the test connection to resolve to a non-test database.
- Add `tests/TestCase.php`, a minimal application bootstrap, and focused `Feature/Authorization` tests.
- Correct `database/factories/UserFactory.php` to emit current required user fields (`first_name`, `last_name`, `mobile`, `email`, `password`, `active`, and `is_admin`) without changing application behavior.
- Add focused factories or explicit fixture builders for `UserProfile`, `Project`, `Request`, `Message`, and the minimum matching taxonomy/pivots. Avoid broad seeding because current taxonomy seeders truncate data.
- Characterize authentication, `active_role`, successful matched-project viewing, existing request creation response shape, valid thread rendering, and valid message creation.

Acceptance criteria:

- The suite runs from a clean checkout with one documented command.
- The test database is isolated and reset between tests.
- No production service, database, filesystem, email, queue, or network endpoint is contacted.
- Characterization tests pass before runtime behavior changes.
- Known vulnerable cases are represented as explicitly named tests that initially demonstrate the gap or are added with the corresponding fix; the branch is never handed off with intentional failures.

### WP-2 — Canonical project visibility rule and matched-detail fix

**Purpose:** Make matched-project detail authorization equivalent to list visibility.

Planned work:

- Introduce a focused `ProjectPolicy` ability for specialist matched-project viewing.
- Reuse the current `Project::forWorkerMatches($user)` semantics through an existence query; do not copy the matching algorithm into the controller or policy.
- Authorize before loading protected relationships and before incrementing `view_count`.
- Return `404` for an authenticated specialist who does not have the project in the current matched result set.
- Keep the successful view, route name, eager-loaded relationships, sent-request display, and view-count increment behavior unchanged.

Required tests:

- A matched specialist receives the detail page and increments the counter exactly once.
- An unmatched specialist receives `404`; the counter and database state remain unchanged.
- A specialist cannot view their own employer project through the matched route.
- A project excluded by the current rejected-request behavior cannot be viewed.
- Missing UUID and invalid UUID behavior remain non-enumerating.
- Employer/no-profile/wrong-active-role behavior remains controlled by middleware.

Acceptance criteria:

- List and detail access derive from the same matching query.
- Authorization occurs before protected data is loaded or analytics are mutated.
- No matching algorithm refactor or performance optimization is included.

### WP-3 — Collaboration-request target authorization

**Purpose:** Ensure requests can be submitted only from the specialist's accessible marketplace set.

Planned work:

- Add a policy ability or reuse the project visibility ability with a distinct `requestCollaboration` rule so future visibility and request eligibility can diverge safely.
- Resolve the validated project and authorize it before `StoreCollaborationRequestAction` executes.
- Retain the database unique constraint and the current duplicate-request response as defense in depth.
- Explicitly deny a dual-profile user from requesting their own project.
- Preserve the current successful JSON contract and existing action class.

Required tests:

- A specialist can request a currently matched project.
- An unmatched project, own project, and rejected/excluded project are denied with `422`, and no request row is created.
- A duplicate request remains denied and does not add a second row.
- Employer/wrong-role and unauthenticated callers remain denied by middleware.
- Invalid, missing, and nonexistent `project_id` values retain validation behavior.

Acceptance criteria:

- Every successful request target is eligible under the canonical project rule.
- Authorization and uniqueness failures produce no partial writes.
- The request action remains responsible only for creation, not HTTP authorization.

### WP-4 — Messaging authorization and project association

**Purpose:** Prevent arbitrary contact, user enumeration, and false project context.

Dependency: approval of the messaging relationship rule in Section 4.

Planned work:

- Introduce a focused messaging authorization abstraction. Prefer a `MessagePolicy` for message-row operations plus one small query/service or policy helper for a prospective participant pair; do not create a broad messaging architecture.
- Move inline message validation to a dedicated FormRequest so participant and project errors are consistently handled.
- Authorize `show(User $user)` before querying messages, exposing user details, or updating `read_at`.
- Authorize message creation before `Message::create()`.
- Scope any supplied project to the exact employer/applicant pair and eligible collaboration request.
- Allow an existing legacy two-party thread to continue without forcing a data migration.
- Use `404` for unauthorized thread reads and validation errors for invalid sends.
- Keep message body limits, escaping behavior, route names, successful redirect, and current synchronous delivery unchanged.

Required tests:

- Valid project employer/applicant pairs can open and send within the approved status rules.
- An existing legacy pair can continue an unlinked thread.
- A user with no eligible relationship cannot open a thread, learn the counterpart through the page, send a message, or cause read timestamps to change.
- Self-thread and self-send remain denied.
- A valid pair cannot attach a different employer's project or another applicant's project.
- A rejected relationship cannot initiate a new thread; existing-thread behavior matches the approved compatibility rule.
- Only the authenticated receiver's messages in the authorized pair are marked read.
- Invalid/nonexistent UUIDs do not reveal whether the user or project exists through distinguishable thread responses.

Acceptance criteria:

- Every new message has an authorized receiver and, when present, authorized project context.
- Denied requests create no messages and mutate no `read_at` values.
- Existing legitimate threads covered by the compatibility rule remain usable.

### WP-5 — Initial policy registration and regression pass

**Purpose:** Finish the policy foundation without broad authorization refactoring.

Planned work:

- Register or verify discovery of the new policies using the Laravel 11 application conventions present in this repository.
- Retain existing middleware and proven controller/FormRequest ownership checks as defense in depth.
- Add direct policy unit coverage only where feature tests cannot clearly exercise a branch.
- Run the complete Sprint 1A suite, route inspection, PHP syntax checks, and a scoped style check on changed PHP files.
- Review the diff for unrelated formatting, secrets, debug output, generated files, and accidental application changes outside the approved work packages.
- Create the required implementation task report in `docs/reports/` only after implementation is approved and completed.

Acceptance criteria:

- Policy behavior and HTTP behavior agree for every matrix row.
- No existing effective authorization check is removed merely because a policy now exists.
- All Sprint 1A automated tests pass and any skipped checks are reported accurately.

## 6. Expected File Impact During Implementation

This is a forecast, not authorization to change the files now.

| Category | Expected files |
|---|---|
| Test bootstrap | `phpunit.xml`, `tests/TestCase.php`, optional test environment example/configuration documentation |
| Test data | `database/factories/UserFactory.php` and narrowly scoped new factories or `tests/Support` builders |
| Feature tests | New files under `tests/Feature/Authorization/` |
| Policies | New `app/Policies/ProjectPolicy.php`, `app/Policies/MessagePolicy.php`, and policy registration only if convention requires it |
| Project access | `app/Http/Controllers/Specialist/MatchedProjectController.php`, `app/Http/Requests/Specialist/StoreCollaborationRequestRequest.php`, and possibly a focused reusable query/helper |
| Collaboration request | `app/Http/Controllers/Specialist/RequestController.php` with the existing action preserved |
| Messaging | `app/Http/Controllers/User/MessagesController.php`, a new dedicated FormRequest, and at most one focused participant-rule abstraction |
| Documentation/report | A completed Sprint 1A report under `docs/reports/`; master-plan status updates only after approval and completion |

Files outside this forecast require explicit scope review before modification. No migration, route rename, Blade redesign, dependency manifest, lockfile, deployment file, or legacy panel file is expected.

## 7. Delivery Order and Review Gates

| Order | Gate | Exit condition |
|---:|---|---|
| 1 | Test-environment safety | Test connection is demonstrably isolated; clean migration/test command is documented. |
| 2 | Characterization baseline | Current allowed paths and response contracts pass before runtime changes. |
| 3 | Project access | Matched detail and collaboration-request tests pass; denied access has no side effects. |
| 4 | Messaging rule approval | Product owner approves statuses, initiation rules, and legacy-thread compatibility. |
| 5 | Messaging access | Pair/project authorization and non-enumeration tests pass. |
| 6 | Sprint regression | Full available suite and proportional static checks pass. |
| 7 | Human release review | Diff, risks, results, rollback, and report are reviewed; no automatic commit, push, or deployment occurs. |

Recommended commit boundaries after human approval are: test foundation; matched-project policy/fix; collaboration-request authorization; messaging authorization; final regression documentation. Commits must not be created automatically.

## 8. Verification Plan

The exact commands may be adjusted to the approved test configuration. Expected checks are:

```text
php artisan test --testsuite=Feature
php artisan test --filter=MatchedProjectAuthorizationTest
php artisan test --filter=CollaborationRequestAuthorizationTest
php artisan test --filter=MessageAuthorizationTest
php artisan route:list --except-vendor -v
php -l <each changed PHP file>
vendor/bin/pint --test <changed PHP files only>
```

Verification evidence must record pass, failure, skip, and environment limitations accurately. Browser checks should confirm Persian error display and the successful matched-project/request/message flows, but browser availability is not a substitute for authorization tests.

### Minimum negative assertions

Every denied-path test must assert both the response and absence of side effects:

- no unauthorized project relationships are rendered;
- `view_count` is unchanged;
- no collaboration request is created;
- no message is created;
- no message is marked read;
- the response does not expose the protected user's name or project details.

## 9. Risks and Mitigations

| Risk | Mitigation |
|---|---|
| Matching fixtures become complex because four matching paths exist. | Cover one fixture per path plus exclusion cases; reuse the production scope rather than duplicating it in tests. |
| SQLite differs from the configured MySQL production database. | Prove migration/query compatibility first; use a dedicated MySQL test database if required and disclose remaining engine-specific gaps. |
| Current migrations are not sufficient for a clean test database. | Stop at the test-foundation gate, document the missing schema evidence, and request a separately scoped decision; do not point tests at an existing environment. |
| Stricter messaging blocks legitimate historical threads. | Preserve existing participant pairs without requiring backfill and obtain approval for initiation/status rules before implementation. |
| Returning different errors enables UUID enumeration. | Normalize unauthorized thread/detail reads to `404` and test response bodies for leaked identity data. |
| Authorization changes accidentally alter matching results. | Treat `forWorkerMatches` as characterized behavior and defer query refactoring to Sprint 2. |
| Policies conflict with existing middleware or checks. | Add policies incrementally, keep proven checks, and test both route-level and model-level boundaries. |
| View counting remains vulnerable to repeated valid requests. | Sprint 1A fixes unauthorized increments only; analytics deduplication is a separate backlog item. |

## 10. Database, Security, Compatibility, and Operations Impact

### Database

- No schema migration or production-data change is planned.
- Tests create only isolated synthetic data.
- Existing unique and foreign-key constraints remain unchanged.
- No backfill is required for the proposed legacy-thread compatibility rule.

### Security

- Sprint 1A is intended to close SEC-01, SEC-06, SEC-07, SEC-08, SEC-09 for the affected paths, and SEC-13 as identified in the full analysis.
- SEC-02 through SEC-05 and broader role/admin hardening remain open for later Sprint 1 work.
- `404` is preferred for protected reads; validation-style denial is retained for form submissions where the client already expects field errors.

### Backward compatibility

- Successful route names, payload fields, redirects, views, and message limits remain stable.
- Existing message rows and collaboration requests are not rewritten.
- Existing legitimate two-party threads remain usable under the proposed compatibility rule.
- Some currently possible but unauthorized cross-user actions will intentionally stop working.

### Deployment and operations

- No deployment configuration changes are planned.
- Authorization tasks should be released in the documented order so each focused change can be reverted independently.
- Production validation must use synthetic authorized/unauthorized accounts and must not inspect or modify unrelated private user content.

## 11. Rollback Plan

Each runtime work package must be independently revertible.

1. Revert the affected authorization work package, not the entire sprint, if a legitimate path is blocked.
2. Keep the test foundation and characterization tests unless they are themselves incorrect.
3. Reverting requires no database rollback because no migration or backfill is planned.
4. Do not delete or rewrite messages, requests, projects, or view counts during rollback.
5. Re-run the pre-change characterization suite and the affected successful user journey after rollback.
6. Record the reason, affected rule, and revised decision before attempting a replacement fix.

## 12. Definition of Done

- [ ] The isolated test harness is reproducible and cannot use production services.
- [ ] Current allowed behaviors are characterized before changes.
- [ ] The access-control matrix is implemented and reviewed.
- [ ] Unmatched project detail requests return `404` before data load or view-count mutation.
- [ ] Collaboration requests are limited to eligible matched projects and never to the user's own project.
- [ ] The messaging relationship rule is explicitly approved.
- [ ] Unauthorized users cannot open threads, enumerate accounts, send messages, attach unrelated projects, or alter read state.
- [ ] Initial policies centralize affected model rules while existing defenses remain.
- [ ] Required positive, negative, role, duplicate, and side-effect tests pass.
- [ ] No migrations, dependencies, route renames, UI redesigns, performance refactors, or legacy cleanup are included.
- [ ] The final diff contains only approved Sprint 1A implementation and documentation files.
- [ ] The implementation report records tests, risks, compatibility, security, database impact, and rollback.
- [ ] Human approval is obtained before any commit, push, deployment, or production action.

## 13. Open Decision

Before WP-4 begins, approve or replace the messaging relationship rule in Section 4, specifically:

- whether `pending` requests may initiate messaging or only `accepted` requests may do so;
- whether a rejected applicant with an existing thread may continue it;
- whether employers may initiate project-linked contact before a specialist submits a request.

All other Sprint 1A work can proceed under the access rules already evidenced by the active matched-project workflow.
