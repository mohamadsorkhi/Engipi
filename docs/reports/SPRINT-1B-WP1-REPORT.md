# Sprint 1B WP-1 â€” API Characterization and Contract Lock Report

> **Report date:** 2026-07-16
> **Work package:** WP-1 â€” API characterization and contract lock
> **Status:** Complete; waiting for human approval before WP-2
> **Production feature implementation:** None
> **Commit, push, or deployment:** Not performed

## Objective

Lock the approved behavior of `POST /api/user-skill` in executable feature tests before correcting its route, authentication, or implementation. Confirm the current failure mode, preserve the active browser skill workflow, and establish a precise red baseline for WP-2.

## Scope

Completed in WP-1:

- Updated the approved Sprint 1B plan with the two requested mandatory repository-wide verification steps.
- Defined the user-skill API route, authentication, ownership, eligibility, validation, success, and duplicate contracts in a focused feature-test class.
- Verified the current route/controller and middleware failure state without modifying production code.
- Searched the repository for known `/api/user-skill` consumers.
- Re-ran the existing Sprint 1A authorization suite independently to distinguish pre-existing regressions from the intentional WP-1 red tests.

Explicitly excluded:

- No route target or middleware was changed.
- No API controller, FormRequest, action, policy, model, migration, configuration, dependency, frontend, or production behavior was changed.
- No user-skill row was written outside isolated in-memory test databases.
- WP-2 and later work packages were not started.

## Files Changed

| File | Change |
|---|---|
| `docs/reports/SPRINT-1B-IMPLEMENTATION-PLAN.md` | Added mandatory WP-2 consumer discovery before route/middleware changes and mandatory WP-4 pre/post attachment-location enumeration with a no-direct-link closure check. |
| `tests/Feature/Api/UserSkillApiContractTest.php` | Added seven test-first API contract cases using isolated SQLite and synthetic users, profiles, and skills. |
| `docs/reports/SPRINT-1B-WP1-REPORT.md` | Recorded WP-1 scope, evidence, risks, blockers, and rollback. |

No production file was modified.

## Locked API Contract

| Concern | Approved WP-1 expectation |
|---|---|
| Method/path | `POST /api/user-skill` remains the endpoint. |
| Controller | Route resolves to `App\Http\Controllers\Api\UserSkillController@store`. |
| Authentication | Route includes the existing `api` group and `auth:sanctum`; unauthenticated JSON requests return `401`. |
| Client type | Bearer-token API for Sprint 1B; the existing session/CSRF browser bulk-sync route remains separate. |
| Eligibility | Authenticated user must own a specialist profile; an employer-only user receives `403` and no write. |
| Ownership | The authenticated user is the only possible association owner. A submitted `user_id` cannot redirect the write. |
| Payload | Required `skill_id` UUID referencing an existing skill. |
| Success | A specialist can add a new skill to their own account and receives JSON `200` with a `message`. |
| Validation | Missing, malformed, and unknown skill IDs return JSON `422` with a `skill_id` validation error and no write. |
| Duplicate | An existing user/skill pair returns JSON `409` with a `message` and remains one row. |

The contract intentionally does not modify or reuse the active `user.skills.store` bulk-sync endpoint, whose payload includes level and experience fields and whose authentication depends on the web session, CSRF, and active specialist role.

## Repository Consumer Search

The following repository-wide search was run across hidden and normal repository content while excluding dependency, generated storage, Git metadata, and framework-cache directories:

```text
rg -n -i --hidden --glob '!vendor/**' --glob '!node_modules/**' --glob '!storage/**' --glob '!.git/**' --glob '!bootstrap/cache/**' "(/api/user-skill|api/user-skill|user-skill|UserSkillController)" .
```

Results:

- The production endpoint definition exists in `routes/api.php`.
- The intended, currently unreachable implementation exists in `app/Http/Controllers/Api/UserSkillController.php`.
- The new WP-1 tests reference the endpoint and intended controller.
- Remaining matches are audit, master-plan, Sprint-plan, and historical report references.
- `routes/web.php` and `resources/views/test.blade.php` matched only the distinct `/save-user-skills` browser endpoint.
- No frontend JavaScript/Blade caller of `/api/user-skill` was found.
- Within this repository, no mobile-client or external-integration implementation depending on `/api/user-skill` was found; this repository-only result does not rule out undocumented consumers hosted or maintained elsewhere.

This is repository evidence only; it cannot prove that an undocumented consumer exists nowhere outside the repository. The approved WP-2 plan therefore requires repeating and recording the mandatory search immediately before changing the route or middleware and stopping if a consumer has appeared.

## Verification Results

### New WP-1 contract suite

Command:

```text
php vendor/bin/phpunit tests/Feature/Api/UserSkillApiContractTest.php --testdox --colors=never
```

Result: **expected red baseline â€” 7 tests failed, 8 assertions**.

| Contract test | Current observed failure |
|---|---|
| Intended controller and Sanctum middleware | Route resolves to `Api\SkillController@store`, not `Api\UserSkillController@store`; `auth:sanctum` is absent. |
| Unauthenticated denial | Returns `500`, not `401`. |
| Specialist success | Returns `500`, not `200`. |
| Authenticated-user ownership | Returns `500`, not `200`; ownership behavior is unreachable through the route. |
| Non-specialist denial | Returns `500`, not `403`. |
| Validation | Returns `500`, not `422`. |
| Duplicate conflict | Returns `500`, not `409`. |

All request failures share the known root cause:

```text
BadMethodCallException: Method App\Http\Controllers\Api\SkillController::store does not exist.
```

These failures are intentional test-first evidence for WP-2. WP-1 did not alter production code to make them pass.

An earlier `php artisan test tests/Feature/Api/UserSkillApiContractTest.php` attempt reached the 30-second command timeout without returning console output. The direct PHPUnit rerun completed normally in 8.316 seconds and produced the results above. No test process remained under tool control.

### Existing regression suite

Command:

```text
php vendor/bin/phpunit tests/Feature/Authorization --colors=never
```

Result: **passed â€” 28 tests, 94 assertions** in 8.389 seconds.

This verifies that the pre-existing Sprint 1A authorization suite remains green and that the new failures are isolated to the locked, not-yet-implemented WP-2 contract.

### Complete feature suite

Command:

```text
php vendor/bin/phpunit --testsuite Feature --colors=never
```

Result: **expected nonzero exit â€” 35 tests, 102 assertions, 7 failures**. The 28 pre-existing authorization tests passed; the seven new WP-1 contract tests failed for the documented current route/controller defect. This intermediate test-first state must not be treated as release-ready before WP-2 makes the locked contract pass.

### Route inspection

Command:

```text
php artisan route:list --path=user-skill -v
```

Result:

- `POST api/user-skill` currently resolves to `Api\SkillController@store` with only the `api` middleware group.
- `POST save-user-skills` remains the separate named web route with `web`, authentication, and active specialist-role middleware.

### Syntax and style

| Check | Result |
|---|---|
| `php -l tests/Feature/Api/UserSkillApiContractTest.php` | Passed; no syntax errors. |
| `php vendor/bin/pint --test tests/Feature/Api/UserSkillApiContractTest.php` | Passed. |
| `git diff --check` | Passed. |
| Scoped debug-pattern scan | No actual debug call was found. The text pattern matched `in_array()` because `ray(` is a substring; manual review confirmed it is a false positive. |
| `git status --short` | Only the pre-existing untracked `.claude/` directory and the three approved WP-1/plan paths are shown; `.claude/` was not modified. |

## Technical Decisions

1. WP-1 uses test-first red contract tests rather than characterizing the unsafe `500` as desirable behavior.
2. Tests use `Sanctum::actingAs()` to express the approved bearer-token authentication boundary without changing middleware in WP-1.
3. Specialist eligibility is represented by durable `user_profiles.type = specialist`, not mutable web-session `active_role` state.
4. The ownership test submits another user's UUID deliberately and requires any successful write to remain attached to the authenticated user.
5. Validation covers missing, malformed, and well-formed-but-unknown UUIDs separately.
6. Duplicate behavior remains the existing intended `409` contract; concurrency serialization remains WP-2 implementation work and MySQL verification remains required.

## Risks

| Risk | Status / mitigation |
|---|---|
| The complete feature suite is intentionally red until WP-2. | Seven failures are isolated, documented, and caused by the approved known defect. Existing authorization tests remain green. Do not merge/release this intermediate state independently. |
| An undocumented external consumer may exist outside the repository. | WP-2 has a mandatory repeated repository search and human compatibility gate; external stakeholder/inventory confirmation remains necessary if such consumers are plausible. |
| `403` for employer-only callers may later conflict with an API-wide non-enumeration convention. | The status is now explicit and must be reviewed before WP-2; changing it requires updating the approved contract and tests, not an incidental implementation choice. |
| The API creates skill rows without level/experience metadata. | This is the preserved single-add contract; product normalization with the browser bulk-sync format is outside WP-1 and must not be silently introduced. |
| SQLite does not validate MySQL row-lock behavior. | WP-2 must add/verify serialization behavior on MySQL-compatible staging and report the limitation. |
| The schema does not demonstrate a user/skill composite unique constraint. | WP-2 must not claim database-enforced uniqueness; the approved transaction/lock approach and remaining race risk must be tested and documented. |

## Remaining Blockers for WP-2

WP-2 must not begin until human approval is given for this report. Once approved, WP-2 still requires:

1. repeat the mandatory repository-wide consumer search immediately before changing the route/middleware;
2. stop for review if any frontend, mobile, integration, test, or contract consumer is found;
3. confirm bearer-token `auth:sanctum` and specialist-profile eligibility remain approved;
4. implement the route correction and authentication atomically;
5. implement authenticated-user ownership, validation, duplicate handling, and same-user serialization;
6. make all seven WP-1 contract tests pass without changing the active browser skill endpoint; and
7. run the complete feature suite plus MySQL-compatible locking verification before handoff.

## Rollback Notes

WP-1 changed no production behavior, schema, configuration, dependencies, data, or storage.

To roll back WP-1 only:

1. remove `tests/Feature/Api/UserSkillApiContractTest.php`;
2. revert the two approved plan additions only if the human decision itself is withdrawn; and
3. remove this WP-1 report if the entire work package is abandoned before becoming historical project evidence.

No database rollback, data deletion, file operation, cache clear, dependency operation, deployment, or external-system action is required.

## Future Recommendations

- After WP-2, perform a separate duplicate inventory before proposing a real composite unique constraint on `user_skills`.
- Define API versioning, token abilities, expiration/revocation, and client issuance before adding more authenticated API mutations.
- Keep the active browser bulk-sync contract covered independently so future API work cannot regress it.

## Approval Status

WP-1 analysis, contract tests, repository search, isolated regression verification, and reporting are complete.

Current status: **waiting for human approval before WP-2**.

- No production feature implementation was performed.
- No commit was created.
- No push was performed.
- No deployment was performed.
