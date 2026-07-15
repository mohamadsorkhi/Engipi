# Sprint 1A WP-3 — Collaboration Request Target Authorization Report

> **Completion date:** 2026-07-15  
> **Work package:** Sprint 1A — WP-3 only  
> **Database schema changes:** None  
> **Dependency changes:** None  
> **Route or view changes:** None

## Objective

Prevent specialists from creating collaboration requests for projects outside their canonical matched-project result set while preserving valid request creation, the successful JSON response contract, duplicate-request handling, and input-validation behavior.

## Scope

Completed WP-3 work:

- Added a distinct `requestCollaboration` ability to the existing focused `ProjectPolicy`.
- Reused `Project::forWorkerMatches($user)` as the sole eligibility rule.
- Shared the policy's canonical match existence check between matched-detail viewing and collaboration-request authorization without copying matching conditions.
- Inspected authorization before duplicate lookup and before `StoreCollaborationRequestAction` executes.
- Denied unmatched, own, and rejected/excluded project targets with HTTP `422` and the existing JSON error shape.
- Preserved FormRequest validation for malformed and nonexistent project IDs.
- Preserved the successful matched-project request response and persistence behavior.
- Preserved duplicate-request response status, JSON status field, and single-row invariant.
- Updated the WP-1 collaboration-request characterization tests.

Explicitly excluded and not changed:

- Messaging authorization and project association rules (WP-4).
- Matched-project visibility behavior completed in WP-2.
- Project matching rules or query optimization.
- Routes, views/frontend, middleware, FormRequest rules/messages, actions, database migrations, dependencies, and deployment configuration.
- Commit, push, deployment, or production actions.

## Changed Files

| File | Change |
|---|---|
| `app/Policies/ProjectPolicy.php` | Added `requestCollaboration` and extracted the existing scope-based eligibility query into a private helper shared by the two project abilities. |
| `app/Http/Controllers/Specialist/RequestController.php` | Resolved the validated project, inspected the policy before duplicate lookup/creation, returned a JSON `422` denial, and removed an existing unused model import. |
| `tests/Feature/Authorization/CollaborationRequestAuthorizationCharacterizationTest.php` | Inverted unmatched-target behavior; added own, rejected/excluded, malformed, and nonexistent target tests; strengthened the duplicate fixture so it remains policy-eligible. |
| `docs/reports/SPRINT-1A-WP3-REPORT.md` | Added this WP-3 completion report. |

No migration, route, view, dependency, matching model/scope, FormRequest, action, or unrelated controller was modified in WP-3.

## Authorization Logic

The request path now uses this order:

1. Existing route middleware handles authentication and the specialist active role.
2. `StoreCollaborationRequestRequest` validates `project_id` as required, UUID-formatted, and present in `projects`.
3. The controller resolves the validated project.
4. `Gate::inspect('requestCollaboration', $project)` invokes `ProjectPolicy`.
5. The policy applies `Project::forWorkerMatches($user)`, restricts it to the target project key, and checks existence.
6. A denied target returns HTTP `422` with the existing `{status: "error", message: ...}` response shape, and processing stops.
7. Only an authorized target reaches the existing duplicate-request check.
8. Only an authorized, non-duplicate target reaches `StoreCollaborationRequestAction`.

The policy does not reproduce skill, process, rejection, profile, or project-owner conditions. Those remain defined only by `forWorkerMatches`.

Consequently, the canonical scope denies:

- projects that do not match the specialist;
- projects owned by the same user, including dual-profile users;
- projects excluded because the specialist has a rejected request;
- all targets for a caller without an eligible specialist matched result.

Malformed and nonexistent IDs remain validation failures rather than policy decisions. This avoids querying or authorizing an invalid target and preserves the established FormRequest behavior.

## Tests Added or Updated

`CollaborationRequestAuthorizationCharacterizationTest` now contains eight tests:

1. Guest submission retains the existing unauthorized response and creates no request.
2. A specialist can submit for a matched project and retains the current success JSON/persistence contract.
3. An unmatched project returns `422` with `status: error` and creates no request.
4. A specialist's own project returns `422` even when skills match and creates no request.
5. A rejected/excluded project returns `422`; authorization occurs before duplicate handling, and no additional row is created.
6. A malformed project ID is rejected by validation and creates no request.
7. A well-formed nonexistent project UUID is rejected by validation and creates no request.
8. A duplicate request for an otherwise eligible matched project retains the existing `422`/`status: error` response and single-row count.

WP-3 focused result: **8 tests passed with 25 assertions**.

Full WP-1 through WP-3 feature result: **19 tests passed with 56 assertions**.

## Commands Executed and Results

| Command/check | Result |
|---|---|
| Read the master plan, full Sprint 1 analysis, Sprint 1A plan, WP-1 report, and WP-2 report in full | Completed before implementation. |
| Inspected working tree, project policy, specialist request controller/FormRequest/action, collaboration tests/helpers, and unique request migration | Completed. |
| `php artisan test --filter=CollaborationRequestAuthorizationCharacterizationTest` | Passed: 8 tests, 25 assertions. |
| Initial scoped `vendor/bin/pint --test ...` | Reported a pre-existing unused import and line-ending issue in the modified `RequestController`. |
| Removed unused `ProjectRequest` import and ran `vendor/bin/pint app/Http/Controllers/Specialist/RequestController.php` | Passed; formatter normalized the reported line ending. |
| `php artisan test --testsuite=Feature` | Passed: 19 tests, 56 assertions, 3.71 seconds in the recorded full run. |
| `php artisan route:list --except-vendor -v` | Passed; 94 active routes remain registered. |
| `php -l` on the three changed WP-3 PHP/test files | Passed; no syntax errors. |
| Final scoped `vendor/bin/pint --test ...` | Passed for all three WP-3 PHP/test files. |
| `git diff --check` | Passed before report creation; repeated during final handoff review. |
| Working-tree and scoped diff review | Completed; no schema, route, view, dependency, matching-scope, or unrelated business-logic change was introduced. |

One combined parallel verification invocation returned a nonzero aggregate result because it included the initial scoped Pint findings. All relevant checks were rerun after the mechanical cleanup and passed as recorded above.

## Results

- Specialists can create collaboration requests only for projects currently returned by their canonical matching scope.
- Unmatched, own, and rejected/excluded targets are blocked before request creation.
- Invalid target identifiers remain blocked by existing validation.
- Valid matched-project request creation retains its current successful JSON contract.
- Duplicate handling remains a `422` JSON error and preserves the existing row.
- The matching algorithm remains unchanged and is not duplicated.
- All earlier WP-1 and WP-2 tests continue to pass.
- Active route count remains unchanged.

## Risks

| Risk | Current handling |
|---|---|
| A future `forWorkerMatches` change automatically changes request eligibility. | Intentional canonical behavior; request tests must accompany any approved matching-rule change. |
| A rejected request is both an existing row and an excluded match. | Authorization deliberately runs first, so rejected/excluded status is enforced before generic duplicate handling; the existing row remains untouched. |
| The policy's scope-based check inherits current memory/query costs. | Correctness was prioritized; matching optimization remains outside WP-3 and belongs to Sprint 2. |
| `Gate::inspect` denial is converted to a controller JSON response rather than thrown as an authorization exception. | This preserves the endpoint's established `422` error contract while still making the policy the authorization source. |
| Policy denial text and user-facing controller text are separate. | Only the controller's Persian error is exposed by this HTTP flow; future centralized API error work may consolidate messages without changing the rule. |
| SQLite behavior can differ from production MySQL. | The isolated suite proves application behavior; production-like database verification remains appropriate before release. |
| Concurrent duplicate submissions still rely on the database unique constraint. | Existing uniqueness defense remains unchanged; concurrency response normalization is outside WP-3. |

## Remaining Sprint 1A Items

1. **WP-4 — Messaging authorization and project association:** approve the participant/status compatibility rule, prevent unrelated thread access and account enumeration, restrict recipients, and validate project context while preserving approved legacy-thread behavior.
2. **WP-5 — Initial policy registration and regression completion:** confirm the final policy/HTTP access matrix, run the complete Sprint 1A regression and review, and finish sprint-level documentation.
3. Sprint 1B API/upload work and Sprint 1C dependency/operational work remain outside WP-3.

## Rollback

WP-3 can be rolled back by reverting the `requestCollaboration` policy/helper change, controller authorization block/import changes, collaboration test updates, and this report. No database rollback, data cleanup, route restoration, dependency rollback, or operational action is required. WP-1 and WP-2 changes should remain intact.

No commit, push, or deployment was performed.

## Approval Status

WP-3 implementation and verification are complete. Work is stopped before WP-4. Human approval is required before further Sprint 1A implementation.
