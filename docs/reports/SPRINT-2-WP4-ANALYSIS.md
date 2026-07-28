# Sprint 2 â€“ WP4 Operational Telemetry Analysis and Implementation Plan

## Status and scope

**Analysis status:** COMPLETE â€” implementation requires human approval.

This document defines the proposed scope for Sprint 2 WP4. No source code, migration, dependency, deployment configuration, or production system was changed while preparing it.

WP4 is the operational-visibility layer for the security and storage work delivered in Sprint 2. The approved roadmap names WP4 as **Operational Telemetry**. The preceding work packages establish its concrete boundaries:

- WP1 added database-backed user-skill integrity.
- WP2 added restart-safe project-file migration, rollback, and orphan-inventory operations with aggregate console reporting.
- WP3 added synchronous, fail-closed project-document inspection and explicitly deferred privacy-safe scanner health and outcome telemetry to WP4.
- WP5 (legacy URL retirement) remains separate and must not be implemented or anticipated here.

WP4 should make WP2 and WP3 operationally observable without changing their security, validation, storage, authorization, or command semantics. It is not a general analytics platform, business audit log, APM deployment, admin dashboard, or incident-management project.

## Evidence reviewed

The analysis reviewed the project rules and roadmap, Sprint 1 reports, Sprint 2 WP1â€“WP3 analyses and implementation reports, the current Git state, and the relevant Laravel code and tests. The most important sources were:

- `docs/PROJECT_MASTER_PLAN.md`
- `docs/AI_PROJECT_RULES.md`
- `docs/reports/AI_PROJECT_MEMORY.md`
- `docs/reports/SPRINT-01_FULL_ANALYSIS.md`
- `docs/reports/SPRINT-1B-FINAL-REPORT.md`
- `docs/reports/SPRINT-2-WP1-FINAL-REPORT.md`
- `docs/reports/SPRINT-2-WP2-ANALYSIS.md`
- `docs/reports/SPRINT-2-WP2-IMPLEMENTATION-REPORT.md`
- `docs/reports/SPRINT-2-WP3-ANALYSIS.md`
- `docs/reports/SPRINT-2-WP3-IMPLEMENTATION-REPORT.md`
- Laravel logging, exception, provider, console, upload-inspection, routing, storage, and test files described below.

The repository already contains an extensive dirty working tree from approved earlier work packages. Those changes are inputs to this analysis and must be preserved. This analysis adds only this report.

## Current state

### Logging and error reporting

- `config/logging.php` contains Laravel's standard `stack`, `single`, `daily`, Slack, Papertrail, stderr, syslog, errorlog, null, and emergency channels.
- The default stack currently contains only `single`, writing to `storage/logs/laravel.log`; its level is controlled by `LOG_LEVEL` and defaults to `debug`.
- There is no dedicated operational channel, structured event schema, retention decision specific to security operations, or redaction processor.
- `app/Exceptions/Handler.php` delegates to Laravel's default exception reporting. It has no custom reportable behavior. This covers uncaught exceptions but not failures intentionally converted into validation results or aggregate command counters.
- A code search found no explicit application calls to `Log::`, `logger()`, or `report()` in the inspected application paths.
- Authentication emits Laravel domain events such as registration, verification, and password reset, but there are no application listeners that turn these into an operational or audit trail.

### Metrics

- There is no metrics client, exporter, registry, scrape endpoint, dashboard, or alert configuration.
- Landing-page database counts are product display values, not operational metrics.
- Adding Prometheus, OpenTelemetry, an APM agent, or another metrics dependency is outside WP4 because dependency changes are prohibited and deployment infrastructure is unspecified.

### Audit events

- There is no immutable application audit-event model or audit table.
- Existing Laravel authentication events are not a substitute for an auditable security ledger.
- WP4 must not create an audit database or imply that operational logs satisfy future requirements for administrator, financial, or user-activity auditing.

### Operational reporting

`app/Console/Commands/MigrateProjectFilesToPrivateStorage.php` provides the strongest existing operational signal. It reports aggregate counts for dry-run, execute, rollback, and orphan modes, including record states, bytes, batches, mismatches, failures, and elapsed milliseconds. This output is privacy-safe and useful, but it exists only in the invoking terminal. It is not durably emitted for alerting or later comparison. Caught exceptions increment `failed` without exposing details, which is safe but makes root-cause classification unavailable.

### Health monitoring

- There is no application health route in `routes/web.php` or `routes/api.php`.
- This project uses the traditional `bootstrap/app.php` structure and does not configure Laravel's newer routing health option.
- There is no health command or service checking database, cache, storage configuration/access, or the malware scanner.
- ClamAV reachability is observed only when a real upload is inspected. An unavailable scanner causes a fail-closed validation rejection, but operators receive no separate health signal.

### Upload and inspection reporting

- `StoreProjectRequest` places `InspectedProjectDocument` after `bail`, `file`, `max:10240`, and `AllowedProjectDocument`, as approved in WP3.
- `ClamAvProjectDocumentInspector` returns typed results: clean, malware detected, scanner unavailable, and inspection failed.
- `InspectedProjectDocument` deliberately returns one generic Persian validation message for every unsafe/unavailable/failure outcome and catches thrown exceptions. It does not log filenames, paths, hashes, signatures, scanner responses, or exceptions.
- The generic external contract is correct, but it means malware rejection, scanner outage, protocol failure, and unexpected inspection exception are indistinguishable operationally.
- Existing upload tests verify fail-closed behavior, no persistence/storage writes after rejection, and response redaction. They do not verify internal telemetry.

### Download reporting

Protected project-file downloads use policies and private storage from Sprint 1B. Expected authorization denials are not explicitly logged. This is intentional for WP4 phase one: logging every authorization denial would create volume, enumeration signals, and sensitive identity context. Uncaught download/storage errors remain covered by Laravel's general exception handler.

## Missing telemetry

The actionable gaps are:

1. No durable, machine-readable outcome for project-document inspection.
2. No distinction between scanner unavailable, inspection/protocol failure, malware rejection, and unexpected inspection exception.
3. No safe inspection-duration signal for timeout or capacity analysis.
4. No durable summary for each WP2 command run and mode.
5. No alertable indication of WP2 mismatches, missing files, orphan counts, rollback failures, or incomplete runs.
6. No non-public readiness check for database, cache, private/public storage, and the scanner.
7. No event naming, severity, field, retention, or redaction contract.
8. No automated proof that telemetry contains only approved aggregate fields.
9. No defined thresholds or runbook handoff for turning emitted events into alerts.

The following are deliberately not treated as WP4 gaps to implement now: user behavior analytics, request tracing across all controllers, business audit history, database-persisted telemetry, public dashboards, vendor APM integration, and download-access auditing.

## Proposed minimal architecture

### Design principles

- Use Laravel's existing PSR-compatible logging stack; add no package.
- Emit structured context using a strict allowlist, not best-effort removal of dangerous fields.
- Keep telemetry failures from changing successful business behavior. Logging itself must never make a clean upload or safe command fail.
- Preserve fail-closed inspection behavior independently of telemetry availability.
- Use logs as a source for downstream counters; do not build process-local counters that reset or diverge between workers.
- Use bounded, low-cardinality dimensions only.
- Keep health checks non-public until authentication, rate limiting, hosting routing, and information-disclosure policy are approved.

### Components

1. **Operational telemetry contract**
   - A small `OperationalTelemetry` interface accepts a defined event name, severity, and allowlisted scalar context.
   - A Laravel-log implementation writes to a dedicated channel.
   - A no-op/fake implementation makes unit and feature tests deterministic.

2. **Typed event vocabulary**
   - An enum or constants class defines event names and prevents arbitrary message strings.
   - Context is normalized by the telemetry service. Unknown keys, nested objects, free text, and non-scalar values are rejected or discarded before reaching the logger.
   - Metric label dimensions are encoded as bounded fields; durations and counts remain numeric values, never labels.

3. **Dedicated operational log channel**
   - Add a daily local channel, proposed name `operations`, with configurable level and bounded retention (14 days by default, matching the existing daily channel).
   - Output remains compatible with Laravel/Monolog and can later be shipped by hosting infrastructure.
   - Do not add credentials, remote endpoints, or environment values to the repository.

4. **Inspection telemetry decorator**
   - A `TelemetryProjectDocumentInspector` decorates the existing `ProjectDocumentInspector`.
   - It times the synchronous inspection, emits exactly one terminal outcome event, and rethrows unexpected exceptions so the existing validation rule continues to apply its approved generic fail-closed response.
   - The ClamAV adapter, rule ordering, validation message, request, action, storage flow, and inspector result types remain unchanged.
   - The container binds the existing ClamAV adapter inside the decorator. Tests can continue replacing the contract with the current fake.

5. **WP2 command summary telemetry**
   - Emit one terminal summary after the existing aggregate report, containing mode, exit classification, elapsed time, and current aggregate counters.
   - Emit a validation-failure event for invalid or mutually exclusive options without recording raw command input.
   - Ensure every return path emits at most one terminal event. A `finally`-based design or a single finalization method should cover handled completion; process termination (`SIGKILL`, host crash) cannot reliably emit an end event and is addressed by an optional start event plus missing-completion alert.
   - Do not emit record IDs, paths, disk roots, checksums, exception messages, or per-file events.

6. **Non-public operational health command**
   - Add `operations:health` for schedulers and deployment checks; do not add an HTTP route in WP4.
   - The command returns success only when required checks pass and prints component names plus `healthy`, `degraded`, or `unhealthy`â€”never connection strings, hosts, paths, credentials, exception text, or scanner output.
   - Checks must be read-only where possible, strictly time-bounded, and individually isolated so one failure does not hide the other statuses.

### Health indicators

| Indicator | Check | Healthy condition | Failure meaning |
|---|---|---|---|
| application | Container/config boot | Command reaches the health service | Application cannot boot if absent entirely |
| database | Minimal read-only connection query | Query completes within the command timeout | Readiness failure |
| cache | Driver availability using a dedicated short-lived probe only if safely supported | Put/get/delete round trip succeeds | Degraded or unhealthy according to deployment use |
| private storage | Disk resolves and a configured root is accessible | Adapter check succeeds without enumerating names | Upload/download readiness failure |
| public storage | Disk resolves and configured root is accessible | Adapter check succeeds | WP2 rollback/migration readiness failure |
| malware scanner | Bounded protocol health request (for ClamAV, `PING`/`PONG`) | Expected exact response arrives | Upload readiness failure because WP3 fails closed |

The implementation must decide cache criticality. Because current session/queue/cache drivers may differ by environment, the recommended default is **degraded** for cache failure unless production configuration makes cache essential to authentication or requests. Database, private storage, and scanner failures should be **unhealthy** for upload readiness. Public storage failure should be unhealthy for WP2 migration/rollback operations but need not take the normal application out of service.

The command must not write sample project files. A storage check that cannot prove writability without mutation should honestly report accessibility rather than claim end-to-end write health. A separately approved synthetic write/read/delete probe may be added later with a dedicated telemetry prefix.

## Metrics and event taxonomy

These are logical metrics derived from structured events by the eventual log collector. WP4 does not introduce an in-application metrics database.

### Project-document inspection

| Event/category | Level | Derived metric |
|---|---:|---|
| `project_document.inspection.completed` with outcome `clean` | info | `project_document_inspections_total{outcome=clean}` |
| same event with outcome `malware_detected` | warning | `project_document_inspections_total{outcome=malware_detected}` |
| same event with outcome `scanner_unavailable` | error | `project_document_inspections_total{outcome=scanner_unavailable}` |
| same event with outcome `inspection_failed` | error | `project_document_inspections_total{outcome=inspection_failed}` |
| same event with outcome `exception` | error | `project_document_inspections_total{outcome=exception}` |

Allowlisted fields: `event_version`, `outcome`, `duration_ms`, and a bounded `size_bucket` if approved. Exact byte size is not required and should be omitted initially. No scanner output is recorded.

Derived indicators:

- inspection count and outcome ratio;
- unavailable/failure rate over 5- and 15-minute windows;
- inspection latency percentiles from `duration_ms`;
- absence of clean completions while upload traffic is known to exist (requires external request-volume correlation and is not inferred by the app).

### Project-file operations

| Event/category | Level | Derived metric |
|---|---:|---|
| `project_files.operation.started` | info | runs started by bounded mode |
| `project_files.operation.completed` | info/warning/error by result | completed runs, duration, and result |
| `project_files.operation.options_rejected` | warning | invalid invocations |

Bounded mode values: `dry_run`, `execute`, `rollback`, `orphans`. Bounded result values: `success`, `unresolved`, `failed`. The completion context may contain the existing aggregate count keys and byte totals. It must not contain batch size input as raw text, row IDs, paths, checksums, or exception text.

Derived counters include runs, failed/unresolved runs, migrated files, rolled-back files, orphan counts/bytes, missing records, metadata/content mismatches, failures, batches, and elapsed time. A started event with no completion within an operator-defined maximum duration is the interruption signal. Restart safety remains provided by WP2 behavior, not by telemetry.

### Health

| Event/category | Level | Derived metric |
|---|---:|---|
| `operations.health.completed` | info/error | executions by overall status |
| per-component structured status in the same event | bounded | component health (0/1) and duration |

Avoid separate high-volume component log lines unless the downstream collector cannot parse a bounded component map. The console exit code remains the primary deployment/scheduler signal.

### Initial alert recommendations

Alerting configuration is infrastructure work and cannot be committed without a selected log collector. The implementation report should nevertheless document these starting thresholds:

- immediate critical alert on any `malware_detected` event for incident review, without exposing the file or user;
- warning when scanner unavailable or inspection failure occurs at least once; critical when failures persist for 5 minutes or exceed 5% of inspections in 5 minutes;
- warning on any WP2 `unresolved` completion; critical on `failed` or an uncompleted run beyond the approved maintenance window;
- warning on any content/metadata mismatch or missing-both count greater than zero;
- critical when database, private storage, or scanner health is unhealthy on two consecutive checks;
- warning when cache is degraded on two consecutive checks.

Thresholds are proposals and must be tuned after staging baselines. The application must not send pages or emails directly in WP4.

## Log redaction and retention policy

### Allowed context

- fixed event name and schema version;
- fixed component, operation mode, result, outcome, and health-status values;
- numeric aggregate counts, aggregate bytes, elapsed milliseconds, and bounded latency/size buckets;
- application environment name only if the log shipper adds it outside request context.

### Prohibited context

- filenames, original client names, paths, storage roots, URLs, hashes/checksums, file content, MIME detector output;
- scanner response, malware signature/name, engine error message, raw protocol output, socket host/port;
- user, profile, project, file, session, request, IP, email, phone, token, cookie, authorization header, or database identifiers;
- exception messages, stack traces, command arguments, environment values, connection strings, secrets, credentials, or private message content;
- arbitrary context arrays supplied by callers.

Unexpected exceptions should be represented in the operational event only as outcome `exception` and a stable internal error category. The existing Laravel exception reporter may separately record an uncaught exception according to global policy, but the telemetry decorator must not duplicate raw exception content. Metric labels must never contain generated run IDs or any other unbounded value.

The proposed operational log retains 14 daily files by default. Production shipping, access control, encryption, deletion, legal retention, and backup policy require an explicit infrastructure approval before deployment. Operations logs should be readable only by the application/runtime operators and must not be exposed from the web root.

## Implementation phases

### Phase 1 â€” vocabulary and safe sink

Create the telemetry contract, typed event vocabulary/context validation, Laravel-log implementation, dedicated channel, binding, and fake. Add tests proving allowed fields survive and prohibited/arbitrary fields cannot be emitted.

### Phase 2 â€” WP3 inspection instrumentation

Add and bind the inspector decorator. Verify one event per result, duration presence, exception rethrow, unchanged generic validation response, unchanged fail-closed behavior, and absence of sensitive values.

### Phase 3 â€” WP2 operational summaries

Instrument command start/completion and option rejection through one finalization path. Preserve all modes, output tables, exit codes, counters, idempotence, and restart behavior. Test interrupted-start observability at the event boundary without using OS-level destructive termination.

### Phase 4 â€” health command

Implement isolated bounded checks and the non-public console command. Add deterministic fakes/tests for healthy, degraded, unhealthy, timeout, and multiple simultaneous failure cases. Document scheduler/deployment invocation but do not deploy it.

### Phase 5 â€” verification and handoff

Run targeted suites, full tests, lint/format checks, and `git diff --check`. Create the WP4 implementation report with event examples containing synthetic aggregate values only. Record collector/alert deployment as an approval-gated operations task.

Phases should be implemented in one approved WP4 change set, but each phase must remain independently reviewable and reversible.

## Expected files to change

Exact names may be adjusted during implementation only when the framework structure requires it. The expected minimal set is:

### New production files

- `app/Contracts/OperationalTelemetry.php`
- `app/Services/OperationalTelemetry/LaravelOperationalTelemetry.php`
- `app/Services/ProjectDocuments/TelemetryProjectDocumentInspector.php`
- `app/ValueObjects/OperationalEvent.php` or a narrowly scoped event-name enum
- `app/Console/Commands/OperationalHealthCheck.php`
- one small health service/contract and typed result under `app/Contracts`, `app/Services/OperationalTelemetry`, and/or `app/ValueObjects`

### Existing production files

- `config/logging.php` â€” dedicated bounded-retention operational channel only.
- `app/Providers/AppServiceProvider.php` â€” telemetry and decorated inspector bindings.
- `app/Console/Commands/MigrateProjectFilesToPrivateStorage.php` â€” terminal aggregate telemetry only.
- `app/Console/Kernel.php` only if command discovery/scheduling in this repository requires explicit registration. No schedule should be enabled without approval.

### Tests and reports

- `tests/Fakes/FakeOperationalTelemetry.php`
- `tests/Unit/OperationalTelemetryTest.php` (or an equivalent focused feature test)
- `tests/Feature/Uploads/ProjectDocumentInspectionTelemetryTest.php`
- `tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php`
- `tests/Feature/Commands/OperationalHealthCheckTest.php`
- `docs/reports/SPRINT-2-WP4-IMPLEMENTATION-REPORT.md`

The existing `InspectedProjectDocument`, `ClamAvProjectDocumentInspector`, and upload tests should need no production modification if the decorator boundary is used correctly. If implementation evidence proves otherwise, any change must be minimal and called out for approval before expanding scope.

## Files and areas that must remain untouched

- all `database/migrations/**` files and database schema;
- `composer.json`, `composer.lock`, `package.json`, and lockfiles;
- `.env` and deployment secrets/configuration;
- `app/Actions/Employer/CreateProjectAction.php` and all upload persistence/storage behavior;
- `app/Http/Requests/Employer/StoreProjectRequest.php` validation order and contract;
- `app/Rules/AllowedProjectDocument.php` and the generic Persian validation messages;
- project-file download controllers, policies, routes, response headers, and authorization tests;
- WP1 user-skill controller/action/request/migration and Sprint 1A authorization foundation;
- WP2 copy, checksum, metadata-update, deletion, rollback, orphan, and restart algorithms except the isolated telemetry calls;
- unrelated controllers, models, views, policies, routes, and frontend assets;
- production monitoring, log-shipping, alerting, scanner, scheduler, and deployment systems.

No public health endpoint should be added in this work package.

## Required automated tests

### Telemetry core

- emits fixed event names and allowlisted scalar fields to the operations channel;
- rejects/discards unknown keys, nested values, and free-form messages;
- telemetry sink failure does not alter business outcomes;
- no prohibited filename/path/hash/signature/scanner output/identity/exception text appears in captured logs;
- numeric counters and durations retain numeric types.

### Inspection

- clean, malware, scanner unavailable, inspection failed, and thrown exception each produce exactly one correct terminal event;
- unexpected exception remains fail closed and preserves the generic `422` response;
- multiple files produce one outcome per attempted inspection and `bail` behavior remains unchanged;
- rejected uploads still create no project/database/file rows and make no storage writes;
- fake inspector and fake telemetry bindings remain deterministic;
- logging failure does not permit unsafe content or reject a clean result;
- existing upload and download regression suites remain green.

### WP2 command

- dry-run, execute, rollback, and orphan modes produce one bounded completion summary;
- invalid batch and mutually exclusive modes produce safe option-rejected telemetry;
- success, unresolved, and failure exit classifications match existing exit codes;
- aggregate values match the console report without paths or record identifiers;
- simulated interruption leaves a start without completion; rerun remains restart-safe and completes;
- telemetry failure does not alter filesystem/database safety semantics;
- the full approved WP2 state matrix remains green.

### Health command

- all healthy returns success;
- scanner/database/private-storage failure returns failure and generic component status;
- cache degradation follows the approved criticality rule;
- public-storage failure is represented distinctly for operations;
- one failed check does not suppress remaining checks;
- timeouts are bounded and reveal no connection/path/error detail;
- output and logged context contain only component/status/duration aggregates.

## Verification steps

After approved implementation, run in this order:

```text
php artisan test --filter=ProjectDocumentInspectionTelemetryTest
php artisan test --filter=OperationalHealthCheckTest
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
php artisan test --filter=ProjectDocumentInspectionTest
php artisan test --filter=ProjectUploadValidationTest
php artisan test --filter=ProjectFileDownloadAuthorizationTest
php artisan test
php -l <every changed PHP file>
vendor/bin/pint --test <every changed PHP file>
git diff --check
```

Additionally:

1. Capture logs with Laravel's logging fake/mock and assert exact keys and levels.
2. Seed synthetic sensitive markers in fake names, paths, signatures, scanner responses, and exception messages; assert none appear in telemetry or command output.
3. Run `operations:health` against fakes in automation. Do not contact production services during tests.
4. Run the WP2 command against fake storage/database states and compare emitted totals to its table.
5. Confirm `route:list` contains no new public telemetry or health route.
6. Confirm `git diff --name-only` contains only the approved WP4 files plus pre-existing earlier-work changes.
7. Confirm no migration or dependency file changed.

## Operational risks and controls

| Risk | Impact | Control |
|---|---|---|
| Sensitive data enters logs | Confidentiality breach | Strict context allowlist; synthetic leakage tests; no arbitrary messages/context |
| Clean-upload event volume | Disk/collector pressure | One compact event per inspection; daily rotation; measure in staging; later sampling requires separate approval because it affects counters |
| Telemetry sink failure affects requests | Availability regression | Catch sink failures inside implementation; never change inspection result or command safety behavior |
| Logging malware creates an oracle | Enumeration/incident leakage | Aggregate outcome only; no identity, path, signature, or response detail |
| Health endpoint exposes internals | Reconnaissance and load | Console-only command; fixed component/status output |
| Health probe mutates or damages storage | Data risk | Read-only accessibility checks initially; synthetic writes require separate prefix and approval |
| Scanner probe competes with scans | Upload latency/capacity | Short PING with strict timeout; execute on operator schedule, not every request |
| Logs mistaken for durable metrics | Missed alerts/data loss | Document dependency on external collection; never claim delivery guarantees |
| Incomplete command has no completion event | Ambiguous interruption | Start/completion pairing and external maximum-duration alert; retain WP2 restart safety |
| Duplicate exception reporting | Noise and cost | Operational events contain outcome/category only; do not call raw exception reporter for handled expected states |
| Over-instrumenting authorization failures | Privacy and cardinality risk | Exclude download denials from phase one; revisit with explicit audit requirements |
| Health thresholds cause false alarms | Alert fatigue | Stage baseline and require two consecutive failures where appropriate |

## Rollback strategy

WP4 requires no database rollback and no data transformation.

1. Disable external collection/alerts first if they were configured outside the repository.
2. Revert the inspector binding from the telemetry decorator to the existing ClamAV inspector. The validation rule and fail-closed behavior remain unchanged.
3. Remove command telemetry calls while retaining all WP2 console reporting and algorithms.
4. Unregister/remove the health command and health services.
5. Remove telemetry bindings, implementation, event vocabulary, fake, and the dedicated logging channel.
6. Retain or securely expire already-written operational logs under the approved retention policy; do not bulk delete evidence during an incident.
7. Run WP2, WP3, authorization, and full regression suites.

Because telemetry is side-effect-only and has no schema, rollback does not require a migration, file movement, or restoration of user data. If disabling the sink is urgently required, pointing the telemetry binding to a no-op implementation is the lowest-risk temporary rollback while preserving instrumentation call sites.

## Approval decisions required

Human approval is required before implementation for the following choices:

1. **Architecture:** approve a Laravel-log-backed telemetry contract and inspector decorator rather than a new metrics/APM dependency.
2. **Scope:** approve instrumentation limited to WP2 operations, WP3 inspection, and a health command; exclude general request tracing, business audits, and download-denial logging.
3. **Health exposure:** approve console-only `operations:health`; no HTTP route.
4. **Health criticality:** confirm database, private storage, and scanner as required/unhealthy; confirm cache as degraded by default; confirm public storage as operation-specific.
5. **Storage checks:** approve read-only/accessibility checks and defer synthetic storage write probes.
6. **Event volume:** approve one compact event for every inspection, including clean outcomes, to keep denominators accurate.
7. **Retention:** approve a dedicated daily operations log with 14-day local retention pending infrastructure policy.
8. **Alerts:** approve the proposed initial thresholds as documentation only; collector-specific alert deployment remains separate.
9. **Command interruption:** approve start/completion pairing as the observable signal; OS crash simulation and process supervision remain infrastructure responsibilities.
10. **Privacy:** approve the strict allowlist and prohibition on all document, identity, scanner-output, exception-message, and unbounded label fields.

## Recommended final WP4 scope

Proceed only after approval with the five phases above. The recommended deliverable is a small, dependency-free telemetry seam, privacy-safe structured inspection outcomes, one durable aggregate summary per project-file operation, and a non-public health command with deterministic tests. It must not alter HTTP responses, validation order, storage behavior, authorization, migrations, dependencies, or deployment systems.

**Final analysis status: READY FOR HUMAN APPROVAL â€” NOT IMPLEMENTED.**
