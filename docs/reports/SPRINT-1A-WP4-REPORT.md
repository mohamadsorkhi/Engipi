# Sprint 1A WP-4 — Messaging Authorization and Project Association Report

> **Completion date:** 2026-07-15  
> **Work package:** Sprint 1A — WP-4 only  
> **Database schema changes:** None  
> **Dependency changes:** None  
> **Route or frontend changes:** None

## Objective

Prevent authenticated users from opening unrelated conversations, sending messages to unauthorized recipients, or attaching unrelated projects while preserving valid project-based messaging, existing validation/response contracts, and legitimate legacy conversations.

## Scope

Completed WP-4 work:

- Added and registered a focused `MessagePolicy` for conversation viewing and message sending.
- Defined project participant eligibility from existing projects and collaboration requests.
- Authorized conversation reads before message queries, protected user rendering, or `read_at` updates.
- Authorized message sends before `Message::create()`.
- Moved the existing inline message validation rules/messages into a dedicated FormRequest without changing their contracts.
- Blocked unrelated conversation access with `404` to reduce user/resource enumeration.
- Blocked unrelated recipients and invalid project associations using the existing redirect-with-validation-errors response style.
- Preserved pending and accepted employer/applicant messaging in both directions.
- Preserved existing two-party legacy threads, including threads whose collaboration request was later rejected.
- Prevented legacy threads from attaching projects unrelated to the exact participant pair.
- Preserved guest, self-thread, self-message, successful redirect, success flash, body limits, and rendering behavior.

Explicitly excluded and not changed:

- Message query optimization, pagination, indexes, realtime delivery, attachments, blocking/reporting, moderation, or rate limiting.
- Collaboration request and matched-project behavior completed in WP-2/WP-3.
- Routes, Blade views/frontend, models/relationships, database migrations, dependencies, and deployment configuration.
- Commit, push, deployment, or production actions.

## Changed Files

| File | Change |
|---|---|
| `app/Policies/MessagePolicy.php` | Added focused conversation-view and message-send authorization with project relationship and legacy-thread rules. |
| `app/Providers/AuthServiceProvider.php` | Registered `MessagePolicy` for the `Message` model alongside the existing `ProjectPolicy`. |
| `app/Http/Requests/User/StoreMessageRequest.php` | Moved the existing receiver/body/project validation rules and Persian messages from the controller into a dedicated FormRequest. |
| `app/Http/Controllers/User/MessagesController.php` | Added read/send authorization before protected queries or writes and adopted the dedicated FormRequest while preserving routes/views/redirects. |
| `tests/Feature/Authorization/MessageAuthorizationCharacterizationTest.php` | Replaced unsafe behavior characterizations with authorization coverage and added project/legacy/invalid-recipient cases. |
| `docs/reports/SPRINT-1A-WP4-REPORT.md` | Added this WP-4 completion report. |

No route, view, model, relationship, migration, dependency, or frontend file was modified in WP-4.

## Authorization Design

### Documented business rule

WP-4 implements the compatibility rule proposed in `docs/SPRINT_1A_IMPLEMENTATION_PLAN.md`:

1. A new conversation is permitted only between a project's employer and the specialist who submitted a `pending` or `accepted` collaboration request for that project.
2. The relationship is bidirectional: either the employer or applicant may view the empty/new thread and send the first message.
3. The unchanged conversation Blade form sends without a `project_id`. That send is allowed when any eligible pending/accepted relationship exists between the exact pair.
4. When `project_id` is supplied, it must identify a project for which the exact sender/recipient pair is the employer/applicant pair and the request is pending or accepted.
5. A rejected request cannot initiate a new conversation.
6. Any existing two-party message history is treated as a legitimate legacy thread and may be viewed and continued without a project ID, including after a request is rejected.
7. The legacy exception does not authorize attaching an unrelated project.
8. Self-conversations and self-messages remain denied.

These rules are contained in `MessagePolicy`; the controller does not duplicate participant or collaboration conditions.

### Conversation viewing

`viewConversation` receives the authenticated user and route-bound participant:

- self access returns the existing `403` behavior;
- an existing message in either direction authorizes the legacy thread;
- otherwise, a pending/accepted employer/applicant relationship in either direction authorizes the conversation;
- all other existing user IDs return `404` before messages are queried, read state is changed, or the participant is rendered;
- nonexistent route-bound user UUIDs continue to return route-model-binding `404`.

The route does not expose a separate conversation entity or thread ID; its `{user}` identifier is the conversation counterpart. WP-4 therefore treats unauthorized counterpart identifiers as unauthorized thread access.

### Message sending

The send path executes in this order:

1. Existing authentication/active-role middleware runs.
2. `StoreMessageRequest` applies the unchanged receiver, body, and optional project validation.
3. The validated receiver and optional project are resolved.
4. `Gate::inspect('sendMessage', ...)` invokes `MessagePolicy`.
5. A denial returns through `back()->withErrors(['body' => ...])`, matching the existing validation-error response format.
6. Only an allowed send reaches `Message::create()`.
7. Success retains the existing named-route redirect and success flash.

Invalid/nonexistent recipient and project identifiers remain validation failures. Authorization handles valid identifiers that are not related to the authenticated user.

## Tests Added or Updated

`MessageAuthorizationCharacterizationTest` now contains fourteen tests:

1. Guest message-index access redirects to login.
2. An existing participant can open a thread, and incoming unread messages are marked read.
3. Pending project participants can open a new thread and send through the unchanged unlinked form.
4. An accepted project's employer can send a project-linked message to the specialist.
5. An unrelated existing user receives `404` and their name is not rendered.
6. An unrelated recipient/project send is rejected and creates no message.
7. An existing legacy conversation without a project relationship can continue.
8. An existing conversation can continue without a project after its collaboration request is rejected.
9. A legacy conversation cannot attach an unrelated project.
10. A rejected relationship without message history cannot open or start a conversation.
11. A nonexistent route recipient UUID returns `404`.
12. A nonexistent send recipient UUID retains existing validation behavior and creates no message.
13. A self-message retains redirect/error behavior and is not persisted.
14. A self-conversation retains the existing `403` response.

WP-4 focused result: **14 tests passed with 54 assertions**.

Full WP-1 through WP-4 feature result after the final handoff run: **28 tests passed with 94 assertions**.

## Commands Executed and Results

| Command/check | Result |
|---|---|
| Read the master plan, full Sprint 1 analysis, Sprint 1A plan, and WP-1/WP-2/WP-3 reports in full | Completed before implementation. |
| Inspected working tree, messaging controller/model, project/request/user relationships, policy registration, active routes, views, migrations, and message tests/helpers | Completed before changes. |
| Initial multi-file patch | Failed safely because controller text context did not match terminal encoding; no partial patch was applied. Changes were reapplied as smaller verified patches. |
| `php artisan test --filter=MessageAuthorizationCharacterizationTest` after initial implementation | Passed: 11 tests, 44 assertions. |
| Focused messaging test after pending/accepted and legacy-rejection coverage | Passed: 13 tests, 53 assertions. |
| `php artisan test --testsuite=Feature` before final self-thread addition | Passed: 27 tests, 93 assertions, 4.27 seconds. |
| Final `php artisan test --filter=MessageAuthorizationCharacterizationTest` | Passed: 14 tests, 54 assertions, 3.04 seconds. |
| Final `php artisan test --testsuite=Feature` | Passed: 28 tests, 94 assertions; recorded during final handoff verification. |
| `php artisan route:list --except-vendor -v` | Passed; 94 active routes remain registered. |
| `php -l` on the five changed/new WP-4 PHP/test files | Passed; no syntax errors. |
| Scoped `vendor/bin/pint --test ...` | Passed for all five WP-4 PHP/test files. |
| `git diff --check` | Passed during scoped review and final handoff verification. |
| Working-tree and scoped diff review | Completed; no schema, route, view, dependency, model, relationship, or unrelated application change was introduced. |

## Results

- Unrelated users can no longer open one another's conversation pages.
- Valid but unauthorized recipient/project identifiers cannot be used to create messages.
- Project-linked messages require an exact pending/accepted employer/applicant relationship.
- Existing valid threads continue without a migration or data rewrite.
- Rejected relationships cannot initiate new conversations but do not strand an existing thread.
- Authorization runs before message retrieval/read-state mutation and before message creation.
- Existing validation, successful redirect, success flash, route names, views, and body limits remain unchanged.
- All WP-1 through WP-3 tests continue to pass.

## Risks

| Risk | Current handling |
|---|---|
| Historical arbitrary conversations created before WP-4 remain usable. | Deliberate backward-compatible legacy rule; future moderation/blocking can restrict specific pairs with an approved data/UX design. |
| Pending requests permit messaging before employer acceptance. | Explicit documented WP-4 rule from the Sprint 1A plan; changing to accepted-only requires a product decision and test updates. |
| A rejected relationship with existing history may continue unlinked. | Deliberate compatibility rule; supplied project IDs still require a currently eligible relationship. |
| The unchanged form omits project context. | Pair authorization uses an eligible relationship or existing thread; explicit project association is validated whenever a caller supplies it. |
| Policy checks add relationship existence queries. | Correctness was prioritized; conversation/query optimization and indexes belong to Sprint 2. |
| No block/report/moderation rule exists. | Outside WP-4; existing relationships alone define access until those features are approved. |
| Admins do not receive an implicit private-message override. | Intentional least privilege; an admin needs the same relationship or existing thread unless a separately audited moderation workflow is introduced. |
| SQLite behavior can differ from production MySQL. | Isolated HTTP behavior is covered; production-like engine verification remains appropriate before release. |

## Remaining Sprint 1A Items

1. **WP-5 — Initial policy registration and regression completion:** review final policy/HTTP matrix consistency, run the complete Sprint 1A regression and scoped quality checks, inspect the cumulative diff, and complete sprint-level handoff documentation.
2. Active-role/admin hardening and rate limiting listed in the broader Sprint 1 roadmap remain outside the approved WP-4 implementation unless assigned to a later bounded work package.
3. Sprint 1B API/upload work and Sprint 1C dependency/operational work remain outside Sprint 1A WP-4.

## Rollback

WP-4 can be rolled back by reverting the message policy/registration, dedicated FormRequest, messaging controller authorization changes, messaging test updates, and this report. No database rollback, data cleanup, route restoration, frontend rollback, dependency rollback, or operational action is required. WP-1 through WP-3 changes should remain intact.

No commit, push, or deployment was performed.

## Approval Status

WP-4 implementation and verification are complete. Work is stopped before WP-5. Human approval is required before further Sprint 1A implementation.
