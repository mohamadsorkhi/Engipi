# Sprint 1A WP-1 â€” Isolated Test Foundation and Characterization Report

> **Completion date:** 2026-07-15
> **Work package:** Sprint 1A â€” WP-1 only
> **Application authorization fixes:** None
> **Database schema changes:** None
> **Dependency changes:** None

## Objective

Create an isolated Laravel feature-test foundation, make the existing user factory compatible with the current users schema, and characterize the current authorization behavior of matched projects, collaboration requests, and messaging before any authorization fix is implemented.

## Scope

Completed WP-1 work:

- Added a PHPUnit 10 configuration that forces the `testing` environment and an in-memory SQLite database.
- Added the Laravel application test bootstrap required by the repository's classic bootstrap structure.
- Corrected `UserFactory` to generate current `users` table fields.
- Added reusable synthetic fixture helpers under the authorization feature-test structure.
- Added positive, middleware, validation, and known-vulnerability characterization tests.
- Verified that the complete repository migration chain used by `RefreshDatabase` succeeds on SQLite in memory.

Explicitly excluded and not changed:

- Controllers, policies, routes, middleware, and business logic.
- Database migrations or production data.
- Composer/npm dependencies and lockfiles.
- Authorization fixes planned for WP-2 through WP-4.
- Commit, push, deployment, and external-system changes.

## Modified Files

| File | Change |
|---|---|
| `phpunit.xml` | Added isolated PHPUnit configuration with forced SQLite `:memory:`, array cache/session/mail, synchronous queue, local filesystem, fixed testing key, and disabled result caching. |
| `tests/CreatesApplication.php` | Added the application creation and console-kernel bootstrap trait required by the current project structure. |
| `tests/TestCase.php` | Added the project base test case. |
| `tests/Feature/Authorization/AuthorizationTestCase.php` | Added `RefreshDatabase` and focused synthetic fixture helpers for users, profiles, projects, matching skills, collaboration requests, and messages. |
| `tests/Feature/Authorization/MatchedProjectAuthorizationCharacterizationTest.php` | Added matched-project route and current access behavior characterization. |
| `tests/Feature/Authorization/CollaborationRequestAuthorizationCharacterizationTest.php` | Added collaboration-request middleware, success, duplicate, and current target-access characterization. |
| `tests/Feature/Authorization/MessageAuthorizationCharacterizationTest.php` | Added message route, thread/read-state, self-message, and current participant/project-access characterization. |
| `database/factories/UserFactory.php` | Replaced obsolete `name` output with current user fields and explicitly associated the factory with `User`. |
| `docs/reports/SPRINT-1A-WP1-REPORT.md` | Added this WP-1 completion report. |

No file under `app/`, `routes/`, `config/`, or `database/migrations/` was modified.

## Tests Created

### Matched-project characterization

1. Guest access to the matched-project index redirects to login.
2. A specialist can view a matched project and increments `view_count` once.
3. **Known unsafe current behavior:** a specialist can view an unmatched project and increments its `view_count`.
4. An employer active role is redirected away from matched-project detail without incrementing the counter.

### Collaboration-request characterization

1. A guest cannot submit a collaboration request and no row is created.
2. A specialist can submit a request for a matched project using the current JSON success contract.
3. **Known unsafe current behavior:** a specialist can submit a request for an unmatched project.
4. A duplicate request returns the current `422` error contract and does not create a second row.

### Messaging characterization

1. A guest is redirected from the message index.
2. An existing participant can open a thread, and an incoming unread message is marked read.
3. **Known unsafe current behavior:** an authenticated active-role user can open a thread with an unrelated user.
4. **Known unsafe current behavior:** a user can send to an unrelated receiver while associating an unrelated project.
5. A self-message is rejected and not persisted.

Total: **13 tests and 38 assertions**.

The tests labeled as known unsafe behavior intentionally assert the current permissive response. They are characterization tests, not approval of that behavior. The relevant tests must be inverted when the corresponding authorization fixes are approved and implemented.

## Commands Executed and Results

| Command/check | Result |
|---|---|
| Read `docs/PROJECT_MASTER_PLAN.md`, `docs/reports/SPRINT-01_FULL_ANALYSIS.md`, and `docs/SPRINT_1A_IMPLEMENTATION_PLAN.md` in full | Completed before implementation. |
| Repository, route/bootstrap, migration, model, controller, request, and working-tree inspection commands | Completed; confirmed classic application bootstrap, no existing test directory/PHPUnit configuration, and no policy layer. |
| `php artisan test --testsuite=Feature` (initial run) | Passed: 13 tests, 38 assertions. PHPUnit process duration 29.71 seconds; command wall time 51.2 seconds on first warm-up run. |
| Scoped `vendor/bin/pint --test ...` (initial run) | Failed only on `database/factories/UserFactory.php` line endings. No semantic style issue was reported. |
| `vendor/bin/pint database/factories/UserFactory.php` | Passed; mechanically corrected the line ending reported by Pint. |
| `php artisan test --testsuite=Feature` (final run) | Passed: 13 tests, 38 assertions, 3.45 seconds. |
| `php artisan route:list --except-vendor -v` | Passed; 94 active routes remain registered. |
| `php -l` on all seven changed/new PHP source and test files | Passed; no syntax errors. |
| Scoped `vendor/bin/pint --test ...` (final run) | Passed for all seven PHP files. |
| `git diff --check` | Passed before report creation; final diff check repeated during handoff review. |
| Working-tree and diff review | Completed; no controller, policy, route, middleware, migration, dependency, or business-logic file changed. |
| Removal check for `.phpunit.cache` | Generated cache was removed; `cacheResult="false"` prevents future test-result cache artifacts from this configuration. |

One combined parallel diagnostic invocation returned a nonzero result because it included the initial Pint line-ending failure. Its test, route, and syntax checks were rerun separately/finally and passed as recorded above.

## Results

- The Laravel test application boots successfully with the installed dependencies.
- The repository migration chain completes in an isolated SQLite in-memory database through `RefreshDatabase`.
- The test environment is forced by `phpunit.xml`; it does not use the normal configured database.
- User factory records now conform to the current UUID-based users schema.
- Current successful behavior and the audited authorization gaps have executable regression coverage.
- No authorization behavior was changed.
- No test contacted a production database, network service, mail transport, queue worker, or external filesystem.

## Remaining Issues

The following findings remain intentionally unresolved because WP-1 authorizes tests only:

1. Unmatched specialists can view matched-project detail routes and increment project analytics.
2. Collaboration requests can target unmatched projects.
3. Authenticated active-role users can open threads with unrelated users, enabling account discovery.
4. Messages can target unrelated recipients and reference unrelated projects.
5. No project or message policies exist yet.
6. The characterization suite covers the active Sprint 1A paths only; it is not a complete application test suite.
7. Active-role/admin edge cases beyond the specified characterization paths remain for later approved work.

## Risks

| Risk | Current handling |
|---|---|
| Unsafe behavior could be mistaken for intended behavior because its test passes. | Vulnerable tests include `current_behavior` in their names and are explicitly identified in this report; they must be inverted during the authorized fix. |
| SQLite behavior can differ from production MySQL behavior. | SQLite is used for safe, fast isolation. Database-engine-specific authorization/query behavior still requires proportional MySQL/staging verification in later work. |
| The users migration inserts a synthetic default admin during every fresh migration. | It remains isolated inside the in-memory test database and is destroyed with the test connection; the migration was not changed in WP-1. |
| Factories/helpers may grow into duplicated business logic. | Helpers are deliberately narrow fixture builders; matching still uses the application scope in HTTP tests. |
| Only selected authorization paths are characterized. | WP-2 through WP-4 must add negative assertions and side-effect checks as each fix is implemented. |
| A caller could override testing configuration outside the standard command. | The documented command uses `phpunit.xml`, whose critical environment variables are forced; tests must not be invoked with a modified configuration against an existing database. |

## Rollback

WP-1 can be rolled back by reverting only the files listed in **Modified Files**, excluding pre-existing planning documentation. No database rollback, data cleanup, dependency rollback, or operational action is required. No commit, push, or deployment was performed.

## Approval Status

WP-1 implementation and verification are complete. Work is stopped before WP-2. Human approval is required before any authorization fix or further Sprint 1A work begins.
