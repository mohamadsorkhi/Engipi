# Sprint 1A WP-2 — Canonical Project Visibility and Matched-Detail Authorization Report

> **Completion date:** 2026-07-15  
> **Work package:** Sprint 1A — WP-2 only  
> **Database schema changes:** None  
> **Dependency changes:** None  
> **Route changes:** None

## Objective

Protect the active specialist matched-project detail route with a focused Laravel policy that uses the existing canonical project-matching scope, returns `404` for ineligible specialist access, and authorizes before protected relationships or analytics are accessed.

## Scope

Completed WP-2 work:

- Added and registered a focused `ProjectPolicy` ability for matched-project detail viewing.
- Reused `Project::forWorkerMatches($user)` without duplicating or refactoring matching rules.
- Added policy authorization at the start of `MatchedProjectController::show()`.
- Changed unmatched, own-project, and rejected/excluded specialist access to `404`.
- Ensured denied requests do not load controller relationships, query the user's sent request, or increment `view_count`.
- Preserved successful matched-project view behavior, view name, view data, route name, and successful view-count increment.
- Preserved the existing administrator middleware-bypass behavior through an explicit policy allowance.
- Updated the WP-1 matched-project characterization tests for the new authorization boundary.

Explicitly excluded and not changed:

- Collaboration-request authorization (WP-3).
- Messaging authorization and project association rules (WP-4).
- The matching algorithm or its performance characteristics.
- Routes, views, response formats, middleware, database migrations, dependencies, and deployment configuration.
- Commit, push, deployment, or production actions.

## Changed Files

| File | Change |
|---|---|
| `app/Policies/ProjectPolicy.php` | Added the focused `viewMatchedProject` policy ability. |
| `app/Providers/AuthServiceProvider.php` | Registered `ProjectPolicy` for the `Project` model and removed the unused `Gate` import. |
| `app/Http/Controllers/Specialist/MatchedProjectController.php` | Added policy authorization as the first operation in `show()` before relationship loading and analytics mutation. |
| `tests/Feature/Authorization/MatchedProjectAuthorizationCharacterizationTest.php` | Inverted the unmatched-project characterization and added own-project and rejected-project denial coverage. |
| `docs/reports/SPRINT-1A-WP2-REPORT.md` | Added this completion report. |

WP-1 files remain present in the working tree and were used as the approved test foundation. No database schema, route, view, dependency, or matching-scope file was modified in WP-2.

## Authorization Logic

The `viewMatchedProject` ability applies the following sequence:

1. Preserve existing administrator access by allowing users with `is_admin`.
2. Build a `Project` query using the existing `forWorkerMatches($user)` scope.
3. Restrict that query to the bound project's primary key.
4. Use `exists()` to determine whether the project is in the specialist's current matched result set.
5. Return `Response::allow()` for a match or `Response::denyAsNotFound()` otherwise.

The controller calls:

```php
$this->authorize('viewMatchedProject', $project);
```

before obtaining related project data, checking an existing collaboration request, incrementing `view_count`, or rendering the existing view.

Because eligibility comes directly from `forWorkerMatches`, the detail route automatically retains the current scope rules, including:

- requiring a specialist profile;
- matching through the existing skill/process paths;
- excluding rejected projects;
- excluding projects owned by the same user.

No matching condition was copied into the policy or controller.

## Tests Added or Updated

`MatchedProjectAuthorizationCharacterizationTest` now contains six tests:

1. Guest matched-project index access redirects to login.
2. A matched specialist receives the existing detail view and increments `view_count` once.
3. An unmatched specialist receives `404`, and `view_count` remains unchanged.
4. A specialist receives `404` for their own project even when its skills match, and `view_count` remains unchanged.
5. A matched project excluded by a rejected collaboration request returns `404`, and `view_count` remains unchanged.
6. An employer active role retains the existing middleware redirect, and `view_count` remains unchanged.

WP-2 focused result: **6 tests passed with 15 assertions**.

Full WP-1/WP-2 feature result: **15 tests passed with 42 assertions**.

The WP-1 collaboration-request and messaging characterization tests continue to pass, including tests that document authorization gaps intentionally left for WP-3 and WP-4.

## Commands Executed and Results

| Command/check | Result |
|---|---|
| Read the master plan, full Sprint 1 analysis, Sprint 1A implementation plan, and WP-1 report in full | Completed before implementation. |
| Inspected the working tree, controller, project model/scope, authorization provider, active tests, test helpers, and provider registration | Completed. |
| `php artisan test --filter=MatchedProjectAuthorizationCharacterizationTest` | Passed: 6 tests, 15 assertions. |
| Initial scoped `vendor/bin/pint --test ...` | Reported line-ending issues only in the two modified existing PHP files. |
| `vendor/bin/pint app/Http/Controllers/Specialist/MatchedProjectController.php app/Providers/AuthServiceProvider.php` | Passed; mechanically normalized the reported line endings. |
| `php artisan test --testsuite=Feature` | Passed: 15 tests, 42 assertions, 4.22 seconds in the recorded full run. |
| `php artisan route:list --except-vendor -v` | Passed; 94 active routes remain registered. |
| `php -l` on the four changed/new WP-2 PHP and test files | Passed; no syntax errors. |
| Final scoped `vendor/bin/pint --test ...` | Passed for all four WP-2 PHP and test files. |
| `git diff --check` | Passed before report creation; repeated during final handoff review. |
| Working-tree and scoped diff review | Completed; no route, view, migration, dependency, matching scope, or unrelated application file changed. |

One combined parallel verification invocation returned a nonzero aggregate result because it contained the initial Pint line-ending findings. Each relevant check was rerun after normalization and passed as reported above.

## Results

- Confirmed matched-project detail IDOR access is blocked for ineligible specialists.
- Denied specialist access returns `404` rather than revealing resource authorization state with `403`.
- Denied access does not increment project analytics.
- Matched specialist access retains the existing view and increments the counter once.
- The existing project-matching scope remains the single source of visibility rules.
- Own projects and rejected/excluded projects are denied through the existing scope behavior.
- All existing WP-1 feature tests continue to pass.
- No route name, view, successful response contract, schema, or dependency changed.

## Risks

| Risk | Current handling |
|---|---|
| Any future change to `forWorkerMatches` changes both list and detail eligibility. | This is intentional canonical behavior; matched-detail tests must be updated only when an approved matching-rule change occurs. |
| `forWorkerMatches` materializes matching collections and is not optimized for policy checks. | Correctness was prioritized; matching refactoring/performance remains explicitly outside WP-2 and belongs to Sprint 2. |
| Administrators retain broad access to the specialist detail route. | Preserved for backward compatibility with the current active-role middleware bypass; admin/impersonation hardening remains later Sprint 1 scope. |
| Valid repeated detail requests still increment `view_count`. | WP-2 prevents unauthorized increments only; analytics deduplication remains a separate known issue. |
| SQLite test behavior can differ from production MySQL. | Policy and HTTP behavior are covered in the isolated suite; production-like engine verification remains appropriate before release. |
| Route model binding still queries the base project before policy authorization. | Only the bound model is resolved; protected relationships and analytics are untouched until authorization succeeds, and denial is normalized to `404`. |

## Remaining Sprint 1A Items

1. **WP-3 — Collaboration-request target authorization:** limit request creation to eligible projects, deny own/unmatched/excluded targets, preserve the existing successful JSON contract, and invert the current unsafe WP-1 characterization test.
2. **WP-4 — Messaging authorization:** approve the participant/status compatibility rule, block arbitrary thread access and recipients, and validate project association without stranding legitimate legacy threads.
3. **WP-5 — Initial policy/regression completion:** reconcile policy and HTTP matrix coverage, run the final Sprint 1A regression and review, and complete sprint-level documentation.
4. Sprint 1B API/upload work and Sprint 1C dependency/operational work remain outside Sprint 1A WP-2.

## Rollback

WP-2 can be rolled back by reverting the policy file, policy registration, controller authorization call, matched-project test changes, and this report. No database rollback, data cleanup, route restoration, dependency rollback, or operational action is required. WP-1 test-foundation files should remain intact.

No commit, push, or deployment was performed.

## Approval Status

WP-2 implementation and verification are complete. Work is stopped before WP-3. Human approval is required before further Sprint 1A implementation.
