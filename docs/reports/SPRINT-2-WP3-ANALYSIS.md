# Sprint 2 WP3 Analysis and Implementation Plan

Date: 2026-07-17
Status: Analysis complete; implementation not started
Work package: Sprint 2 WP3 â€” project attachment malware and content inspection

## 1. Scope Decision

`docs/reports/AI_PROJECT_MEMORY.md` defines the ordered Sprint 2 priorities as:

1. user-skill unique constraint;
2. migration rehearsal;
3. malware/content inspection;
4. operational telemetry; and
5. legacy URL retirement.

WP1 and WP2 are complete at the implementation level, so WP3 is the malware/content-inspection control deferred by Sprint 1B.

The exact recommended scope is **new project document attachments accepted by the active full project-create endpoint**. This is the file domain hardened in Sprint 1B and referenced by the Sprint 2 priority. WP3 does not silently expand into user avatars, administrative setting images/files, future message attachments, ticket attachments, or legacy-file retroactive scanning. Those surfaces have different storage, ownership, and compatibility risks and require separate work packages.

The repository also contains a public avatar upload in `HomeController` and generic public setting helpers in `Setting`; they are security findings, but changing them in WP3 would violate the approved incremental project-attachment scope.

## 2. Documents and Evidence Reviewed

- `docs/PROJECT_MASTER_PLAN.md`
- `docs/AI_PROJECT_RULES.md`
- `docs/reports/AI_PROJECT_MEMORY.md`
- `docs/reports/SPRINT-1B-IMPLEMENTATION-PLAN.md`
- `docs/reports/SPRINT-1B-WP3-REPORT.md`
- `docs/reports/SPRINT-1B-WP4-REPORT.md`
- `docs/reports/SPRINT-1B-FINAL-REPORT.md`
- `docs/reports/SPRINT-2-WP2-ANALYSIS.md`
- `docs/reports/SPRINT-2-WP2-IMPLEMENTATION-REPORT.md`
- active upload requests, controllers, actions, validation rules, filesystem configuration, download code, migration command, and upload/delivery tests

Sprint 1B explicitly deferred malware scanning, archive inspection, document parsing, and quarantine. Its reports identify the remaining risks: accepted ZIP contents are not inspected, Office/PDF files can contain harmful embedded content, legacy OLE detection varies, and allowlisting is not malware scanning.

## 3. Current Upload Pipeline

### 3.1 Active project attachment entry point

The active route is the `store` member of `Route::resource('projects', ProjectController::class)` inside the authenticated `user` route group and `active_role:employer` middleware.

The flow is:

```text
HTTP multipart request
  -> authentication + active employer role
  -> StoreProjectRequest validation
  -> ProjectController::store()
  -> CreateProjectAction::execute()
  -> database transaction creates project/relationships
  -> each UploadedFile is stored on private local disk
  -> ProjectFile metadata row is created
```

Only the full project-create path accepts `files[]`. `SimpleStoreProjectRequest`, `UpdateProjectRequest`, and `UpdateProjectAction` do not accept or persist attachments. No active project-file replacement/upload endpoint was found.

### 3.2 Current validation

`StoreProjectRequest` applies to each file:

```text
file
max:10240
AllowedProjectDocument
```

This provides:

- Laravel uploaded-file validity checking;
- a 10 MiB maximum per file; and
- exact client-extension to server-detected-MIME pairing.

`AllowedProjectDocument` lowercases the client extension and detected MIME, then checks an explicit map. It does not trust only the filename or browser-supplied content type.

Mixed requests fail validation before controller/action execution, so one invalid file prevents project, project-file, and storage writes.

### 3.3 Supported project document types

| Extension | Accepted detected MIME values | Format/security notes |
|---|---|---|
| `pdf` | `application/pdf` | Container may include JavaScript, launch actions, embedded files, links, malformed structures, or exploit payloads. |
| `txt` | `text/plain` | Low structural complexity, but can contain control/NUL data or deceptive content. |
| `csv` | `text/csv`, `text/plain`, `application/csv` | Can carry spreadsheet-formula injection payloads when opened in desktop software. |
| `doc` | `application/msword`, `application/x-ole-storage`, `application/cdfv2` | Legacy OLE container; can contain VBA macros and embedded OLE objects even though `.docm` is denied. |
| `docx` | OOXML Word MIME or `application/zip` | ZIP package; can contain external relationships, embedded objects, malformed package data, or disguised archive content. |
| `xls` | `application/vnd.ms-excel`, `application/x-ole-storage`, `application/cdfv2` | Legacy OLE container; can contain macros, formulas, links, and embedded objects even though `.xlsm` is denied. |
| `xlsx` | OOXML Excel MIME or `application/zip` | ZIP package; formulas, external links, embedded objects, malformed package data, and decompression attacks remain possible. |
| `zip` | `application/zip`, `application/x-zip-compressed` | Arbitrary nested content, executables, encrypted entries, traversal paths, nested archives, and zip bombs are possible. |

Explicitly denied today include HTML, SVG, JavaScript, PHP, executables, shell scripts, `.docm`, `.xlsm`, and extension/MIME mismatches. Denying macro-enabled extensions does not prove that legacy `.doc`/`.xls` files contain no macros.

### 3.4 Current storage and delivery

`CreateProjectAction::storeProjectFile()` uses Laravel-generated filenames under:

```text
local disk: project-files/{project UUID}/{generated filename}
```

The `local` disk root is `storage/app`, not the public storage symlink. `ProjectFile` records `storage_disk = local`, original name, detected MIME, and size.

Downloads are authenticated and policy-authorized before filesystem access. Files are served as attachments with `nosniff`, private/no-store caching, sanitized filename handling, and a safe MIME fallback. This reduces browser exposure but does not neutralize a malicious file after the user downloads and opens it.

The WP2 migration command handles legacy public/private movement and rollback. It does not inspect content and must remain outside WP3.

## 4. Missing Functionality

The current system has no:

- anti-malware engine or service integration;
- known-signature scan before persistence;
- scanner timeout, health, or fail-closed behavior;
- archive entry/expansion/path/encryption/nesting limits;
- OOXML package validation;
- legacy OLE macro/embedded-object policy enforcement;
- PDF active-content or structural inspection;
- content-inspection result abstraction;
- scanner test double or deterministic malware fixture contract;
- quarantine state or quarantine storage;
- scan audit/telemetry (scheduled for Sprint 2 WP4);
- retroactive scan of already-stored project attachments; or
- operational rollout gate proving scanner definitions, connectivity, latency, and resource limits.

The allowlist answers â€œdoes this outer file type match an approved format?â€ It does not answer â€œis the content safe?â€

## 5. Correct Inspection Boundary

Inspection should occur **after basic upload, size, extension, and detected-MIME validation, but before `CreateProjectAction` starts its transaction or writes any file to durable storage**.

Recommended request rule order:

```text
bail
file
max:10240
AllowedProjectDocument
InspectedProjectDocument
```

Reasons:

- `bail` avoids scanning files that already fail cheap validation.
- PHP's request temporary file is available for streaming without first making it an application attachment.
- all files in a multi-upload request are validated before controller execution;
- an infected, structurally unsafe, or unscannable file produces no project row, project-file row, private attachment, or public file;
- `CreateProjectAction`, upload storage flow, and download behavior remain unchanged; and
- synchronous pre-persistence inspection provides a clear fail-closed guarantee without adding schema/quarantine lifecycle state.

The inspection rule should delegate to a dedicated inspector contract; it must not contain socket/process/archive parsing logic itself.

### Why asynchronous post-storage scanning is not recommended for this WP

An asynchronous design would require quarantine storage, persistent scan states, download denial for pending/failed files, jobs/queues, retries, schema fields, cleanup, and operational monitoring. The task forbids migrations and dependency changes, and current production queue defaults may be synchronous/file-based. Implementing an incomplete â€œstore now, scan laterâ€ flow risks serving unscanned files.

For WP3, scan synchronously while the file is still temporary and reject before persistence. A future high-volume quarantine architecture can be designed separately if measured latency requires it.

## 6. Proposed Inspection Architecture

### 6.1 Inspector contract and result

Introduce a narrow project-document inspector contract accepting an `UploadedFile` or readable temporary path and returning a typed result/status such as:

- clean;
- malware detected;
- content policy rejected; or
- scanner unavailable/error.

User-facing validation must remain generic and Persian-compatible. Scanner signatures, raw engine responses, temporary paths, original filenames, file hashes, UUIDs, and document content must not appear in HTTP responses or ordinary logs.

### 6.2 Malware engine adapter

No malware engine is currently installed or configured in the repository. Implementation requires an explicit operational decision before code is approved for deployment.

Preferred initial provider: a locally controlled ClamAV daemon using its streaming protocol, with:

- no shell command construction from user input;
- bounded connection and read timeouts;
- maximum stream size aligned with the 10 MiB request limit;
- exact interpretation of clean/found/error responses;
- fail-closed behavior when unavailable or ambiguous; and
- no file path submission to the daemon.

A TCP/Unix-socket streaming client can be implemented with PHP stream primitives, avoiding a Composer dependency change. Symfony Process is transitively installed, but invoking a shell/binary is less portable and creates command/process-policy concerns. An ICAP or managed scanner could replace the adapter later behind the same contract, but sending confidential documents to a third party requires privacy, residency, retention, authentication, and legal approval.

Deployment must not enable the rule until the selected engine is installed, definitions are updating, health checks pass, and failure behavior is verified. â€œScanner disabled means allowâ€ is not acceptable in production.

### 6.3 Bounded content-policy inspection

Malware signatures alone are insufficient. Add format-aware, bounded checks using already available PHP extensions (`fileinfo`, `zip`, `xml`, hashing/streams); no new package is required for the first implementation.

#### ZIP and OOXML containers

For `.zip`, `.docx`, and `.xlsx`:

- limit entry count;
- limit total declared and streamed uncompressed bytes;
- limit per-entry size and compression ratio;
- reject absolute paths, drive-prefixed paths, `..` traversal, NUL names, and duplicate/conflicting paths;
- reject encrypted entries because content cannot be inspected;
- reject or strictly limit nested archives;
- reject executable/script/active extension families inside general ZIP files;
- scan the outer stream and, where the engine does not reliably unpack, scan bounded extracted entry streams;
- require valid OOXML package markers and the expected Word/Excel main document part;
- reject `vbaProject.bin`, macro-enabled content types, embedded OLE/package objects, and disallowed external relationships under an approved policy; and
- never extract into a web-accessible or durable application directory.

Archive limits must be configuration values with safe defaults and tests, not magic numbers distributed across rules.

#### PDF

At minimum, verify a plausible PDF header/EOF structure and apply conservative checks for active actions such as JavaScript, Launch, embedded files, and automatic open actions. Simple token searching is bypassable and must not be represented as full PDF sanitization. The malware engine remains the primary exploit/signature control; a robust parser/sanitizer would require a separately reviewed dependency or external service.

#### TXT and CSV

Reject NUL-heavy/binary content masquerading as text and enforce bounded reads. Decide explicitly whether spreadsheet-formula prefixes in CSV (`=`, `+`, `-`, `@`) are in WP3 policy; rejecting them improves safety when opened in spreadsheet tools but may block legitimate engineering formulas. This requires product approval rather than an incidental rule.

#### Legacy DOC/XLS

Native PHP does not provide robust OLE/VBA inspection. Options requiring approval:

1. preserve `.doc`/`.xls` compatibility and require both malware scanning and an approved OLE inspection service/tool;
2. temporarily reject legacy Office formats until reliable inspection exists; or
3. accept the residual macro/embedded-object risk explicitly.

Option 1 provides the best compatibility/security balance but introduces an operational tool/service dependency. Option 2 is safest technically but is a breaking allowlist change. WP3 must not pretend that extension denial or ClamAV alone guarantees macro-free legacy Office files.

## 7. Failure and Response Policy

Recommended policy:

| Condition | Request outcome | Persistence |
|---|---|---|
| Clean and content-policy compliant | Existing success contract | Existing private write and metadata flow |
| Malware detected | Generic validation error on the affected `files.N` | None for entire request |
| Unsafe archive/document content | Generic validation error on the affected `files.N` | None for entire request |
| Scanner unavailable, timeout, malformed response, or inspection exception | Fail closed; preferably service-unavailable semantics rather than claiming the file is invalid | None for entire request |
| File already fails size/type validation | Existing validation error; scanner not called | None |

Laravel validation naturally provides the existing `422` contract for rejected content. Scanner infrastructure failure is operationally different; returning `503` is more accurate but requires a focused exception/response test and frontend compatibility confirmation. The approval decision must lock `422` versus `503` before implementation.

No raw scanner details should reach users. WP4 operational telemetry should later add safe counters/health signals without document metadata.

## 8. Dependencies

### Existing code/runtime dependencies

- `StoreProjectRequest` and `AllowedProjectDocument`.
- `ProjectController::store()` and `CreateProjectAction` ordering.
- PHP request temporary-file lifecycle.
- PHP `fileinfo`, `zip`, `xml`, stream, and hash extensions (present in the audited local runtime).
- private `local` storage and the existing protected download flow.
- existing fake-disk and `UploadedFile::fake()` test foundation.

### New operational dependency requiring approval

- A malware scanning engine/service, signature-update process, health check, timeout policy, and resource allocation.

No Composer or npm dependency change is planned. The scanner itself is an infrastructure dependency, not something that can be supplied safely by application code alone.

### Deployment dependencies

- scanner connectivity from every application node;
- current malware definitions with monitored update failures;
- timeouts and upload/request-worker capacity;
- consistent configuration across web and test environments;
- a deployment gate preventing fail-open configuration; and
- privacy approval if content leaves the host/network.

## 9. Risks and Mitigations

| Risk | Consequence | Mitigation |
|---|---|---|
| Scanner unavailable and application fails open | Malware is persisted | Production fail closed; deployment health gate; explicit unavailable tests. |
| Scanner unavailable and application fails closed | Legitimate uploads unavailable | Short bounded timeout, clear generic error, health telemetry in WP4, operational runbook. |
| Synchronous scan latency | Request timeout/worker exhaustion | Stream once where possible, 10 MiB cap, bounded archive work, measured performance budget, concurrency/load test. |
| ZIP bomb or huge entry graph | CPU/memory/disk exhaustion | Entry/count/ratio/expanded-byte/nesting limits before extraction; bounded streams; no public extraction. |
| Encrypted archive | Content cannot be assessed | Reject by policy. |
| Nested executable in accepted ZIP | User downloads dangerous payload | Archive entry policy plus malware scan; reject unsafe nested types. |
| OOXML disguised as generic ZIP | Policy bypass | Require extension-specific package structure and content types. |
| Legacy DOC/XLS macros | Active content survives extension allowlist | Approve OLE inspection, remove formats, or explicitly accept residual risk. |
| PDF parser/token bypass | False assurance | Treat lightweight checks as defense-in-depth; use AV and document limitations; approve robust parser/service separately if required. |
| False positive | Legitimate business file blocked | Generic user response, safe operational correlation, controlled signature/policy review; never bypass per user ad hoc. |
| False negative/zero-day | Malicious file accepted | Layer allowlist, structure policy, AV, private storage, authorized attachment download, `nosniff`, endpoint monitoring. |
| Logging leaks content or identity | Confidentiality breach | Aggregate event categories only; never log path/name/hash/content/raw engine output. |
| Scanner test calls real service | Flaky or unsafe tests | Bind deterministic fake inspector in automated tests; reserve EICAR/provider checks for isolated integration environment. |
| Multi-file partial persistence | Project/files partially created | Inspect every upload during request validation before controller/action execution. |
| Existing attachments remain unscanned | Historical risk persists | Explicitly out of WP3; design read-only inventory/scan and remediation policy separately before any deletion/quarantine. |

## 10. Detailed Implementation Plan

### Phase 1 â€” Lock contracts and fixtures

1. Preserve current successful upload response, 10 MiB limit, allowed outer types, private storage path, metadata, and no-file project creation.
2. Add deterministic synthetic fixtures for clean, infected-marker, scanner-error, archive traversal, encrypted archive, excessive expansion, nested archive/executable, malformed OOXML, macro-bearing OOXML, embedded object/external relationship, suspicious PDF, and binary-disguised text cases.
3. Do not commit real malware. Use the standard harmless EICAR test string only in isolated scanner integration tests if repository policy explicitly approves it; otherwise mock provider results.

### Phase 2 â€” Add an inspection contract and configuration

1. Add a narrow project-file inspector interface and immutable/typed result.
2. Add configuration for provider, fail-closed mode, timeouts, stream limit, archive entry/expanded-byte/ratio/nesting limits, and content-policy toggles.
3. Bind the configured implementation through the application service provider.
4. Provide a deterministic fake for tests; never make feature tests depend on a live scanner.
5. Validate configuration at startup/use and reject unsafe production fail-open settings.

### Phase 3 â€” Implement malware adapter

1. Implement a streaming ClamAV daemon adapter or the separately approved provider.
2. Avoid shell interpolation and avoid sharing server filesystem paths.
3. Bound connection/read/write duration and bytes.
4. Map clean, infected, unavailable, timeout, and protocol-error results exactly.
5. Redact provider responses from user-visible and ordinary log output.

### Phase 4 â€” Implement bounded content inspection

1. Inspect by already-approved extension after `AllowedProjectDocument` passes.
2. Add bounded ZIP/OOXML structural and entry-policy checks.
3. Add conservative PDF and text/CSV checks with documented limitations.
4. Implement the approved legacy DOC/XLS decision; do not claim macro inspection unless it actually exists.
5. Ensure all temporary resources/streams are closed and temporary extraction, if any, is isolated and cleaned on every outcome.

### Phase 5 â€” Integrate before persistence

1. Add `bail` and the inspection rule after the existing cheap checks in `StoreProjectRequest`.
2. Ensure scanner rejection identifies only `files.N` with a generic localized message.
3. Define and test infrastructure-failure response (`503` recommended) without leaking details.
4. Leave `ProjectController`, `CreateProjectAction`, private storage, metadata shape, and download behavior unchanged unless implementation proves a minimal orchestration hook is unavoidable; any such expansion requires review.

### Phase 6 â€” Verification and operational handoff

1. Run focused unit/feature tests with fake provider.
2. Run an isolated provider integration check using harmless standard scanner test material.
3. Measure clean scan, archive scan, timeout, and concurrent-request latency at the 10 MiB boundary.
4. Verify scanner-definition freshness and unavailable behavior on each deployment node.
5. Run upload, download, WP2 migration-command, and complete regression suites.
6. Create `docs/reports/SPRINT-2-WP3-IMPLEMENTATION-REPORT.md` with exact provider/config decision, tests, latency, failure behavior, rollback, and remaining limitations.

## 11. Expected Files to Change

Exact class names may be refined at approval, but the minimal expected scope is:

- `app/Contracts/ProjectDocumentInspector.php` â€” scanner/content-inspection contract (new).
- `app/ValueObjects/ProjectDocumentInspectionResult.php` or a small equivalent enum/result class (new).
- `app/Services/ProjectDocuments/...` â€” composite inspector, selected malware adapter, and bounded format inspectors (new, focused classes only).
- `app/Rules/InspectedProjectDocument.php` â€” validation adapter and generic rejection behavior (new).
- `app/Http/Requests/Employer/StoreProjectRequest.php` â€” add `bail` and inspection after the current allowlist.
- `app/Providers/AppServiceProvider.php` â€” bind the configured inspector, unless an existing provider is more appropriate.
- `config/project_documents.php` â€” scanner endpoint/timeouts and bounded content-policy settings (new; no secrets committed).
- `tests/Feature/Uploads/ProjectUploadValidationTest.php` â€” preserve current contract and prove no persistence on inspection failure.
- `tests/Unit/ProjectDocuments/...` â€” provider/result and bounded format-inspection tests (new).
- `tests/Feature/Uploads/ProjectDocumentInspectionTest.php` â€” end-to-end request behavior with a fake inspector (new, if separation improves clarity).
- `docs/reports/SPRINT-2-WP3-IMPLEMENTATION-REPORT.md` â€” implementation evidence (new after approval).

No migration is required or planned. No dependency manifest should change.

## 12. Files That Must Remain Untouched

- All database migrations and schema, including WP1 and project-file storage migrations.
- `composer.json`, `composer.lock`, `package.json`, and frontend lockfiles.
- `.env` and committed environment files containing deployment-specific values.
- `app/Actions/Employer/CreateProjectAction.php`, unless a separately approved minimal hook is proven necessary; storage behavior must not change.
- `app/Http/Controllers/Employer/ProjectController.php`, unless the approved `503` infrastructure-error mapping requires a narrowly scoped handler.
- `app/Models/ProjectFile.php` and project-file metadata schema.
- `app/Http/Controllers/User/ProjectFileController.php`, `app/Policies/ProjectFilePolicy.php`, `routes/user.php`, and all attachment Blade links.
- Project-file deletion actions.
- `app/Console/Commands/MigrateProjectFilesToPrivateStorage.php` and its WP2 tests.
- `app/Rules/AllowedProjectDocument.php` and its approved type matrix, unless the human explicitly approves a legacy Office/ZIP policy change.
- `HomeController` avatar upload and `Setting` image/file helpers; report them separately, do not fold them into WP3.
- Sprint 1A authorization tests and Sprint 2 WP1 user-skill files.
- Message, ticket, portfolio, and any future attachment modules.
- Deployment workflows and production scanner installation/configuration; application planning may document requirements but must not deploy infrastructure.

## 13. Required Automated Tests

### Existing behavior

- every currently allowed extension/MIME pair still reaches inspection;
- currently blocked, mismatched, oversized, and malformed uploads fail before scanner invocation;
- clean single and multiple uploads retain the current success response and private storage metadata;
- project creation without files does not invoke the inspector;
- one rejected file in a mixed request creates no project, project-file row, or stored file.

### Malware provider contract

- clean response;
- malware-found response;
- unavailable/refused connection;
- timeout;
- truncated/malformed/unknown response;
- stream/read failure;
- byte limit enforcement;
- no path/name/content/raw signature leakage in HTTP response or logs; and
- no shell invocation/user-controlled command construction.

### Archive/OOXML content policy

- normal bounded ZIP;
- excessive entry count;
- excessive total/per-entry expanded size and compression ratio;
- encrypted entry;
- traversal, absolute, drive-prefixed, NUL, and duplicate/conflicting entry names;
- executable/script and nested archive policy;
- valid DOCX/XLSX package markers;
- malformed or wrong-kind OOXML package;
- macro content types/`vbaProject.bin`;
- embedded OLE/package objects; and
- disallowed external relationships.

### PDF/text/legacy formats

- structurally plausible clean PDF;
- malformed PDF and approved active-content denial cases;
- clean bounded TXT/CSV and binary/NUL masquerade;
- approved CSV formula policy; and
- explicit tests for the approved DOC/XLS macro-inspection or compatibility decision.

### Failure atomicity and regression

- scanner failure/malware/policy denial causes zero database/storage writes for the entire request;
- temporary resources are cleaned;
- download authorization/headers are unchanged;
- WP2 migration and rollback command behavior is unchanged;
- all existing authorization and WP1 integrity tests remain green.

Automated tests must bind a fake inspector and must never contact production or a real external scanner. Provider integration tests must be separately marked/configured and skipped unless an isolated scanner endpoint is explicitly supplied.

## 14. Verification Steps

Recommended implementation verification:

```text
php -l <each changed PHP file>
vendor/bin/pint --test <scoped changed PHP files>
php artisan test --filter=ProjectUploadValidationTest
php artisan test --filter=ProjectDocumentInspectionTest
php artisan test --filter=ProjectFileDownloadAuthorizationTest
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
php artisan test
php artisan route:list --except-vendor -v
git diff --check
git status --short
```

Provider staging verification must additionally prove:

- clean standard fixtures pass;
- harmless standard anti-malware test content is rejected;
- engine unavailable and timeout paths fail closed;
- signature definitions are current;
- 10 MiB and archive worst-case latency remain within the approved request budget;
- concurrent scans do not exhaust PHP workers, scanner workers, memory, or temporary disk;
- no inspected content or sensitive metadata appears in application/scanner logs; and
- no file exists under application private/public storage and no project row exists after rejection.

## 15. Rollback Plan

Application rollback consists of removing the inspection validation rule/binding/services/config/tests as one focused change, restoring the existing size/type allowlist behavior. No schema rollback exists.

Files legitimately accepted while WP3 is active remain user data and must not be deleted automatically. Files rejected by WP3 were never persisted. Scanner infrastructure can be disabled/removed only after application code is rolled back; disabling the scanner first under fail-closed code would block uploads.

If a scanner outage occurs before rollback approval, fail closed and communicate temporary attachment-upload unavailability rather than bypassing inspection.

## 16. Approval Decisions Required Before Implementation

1. Confirm WP3 scope is new project document attachments only.
2. Select and approve the malware engine/provider and whether confidential content may leave the application host/network.
3. Approve fail-closed behavior and the scanner-infrastructure response contract (`503` recommended versus `422`).
4. Approve archive limits: entry count, expanded bytes, per-entry bytes, compression ratio, nesting depth, encrypted-entry policy, and nested executable/archive policy.
5. Approve OOXML external relationship and embedded-object policy.
6. Decide how legacy `.doc` and `.xls` macro/OLE inspection will be handled; preserving them without a real inspector requires explicit residual-risk acceptance.
7. Decide CSV formula-injection policy.
8. Approve synchronous pre-persistence inspection and its latency budget.
9. Approve the no-quarantine design for WP3; asynchronous quarantine would require a separate schema/queue architecture.
10. Provide an isolated scanner environment for provider integration and load verification before deployment approval.

## 17. Analysis Status

This task performed read-only inspection of planning documents, reports, active routes, requests, rules, controllers, actions, storage configuration, delivery code, installed PHP capabilities, and tests.

No source code, migration, dependency, configuration, database row, storage object, external scanner, Git history, or deployment state was changed.

The only created file is:

- `docs/reports/SPRINT-2-WP3-ANALYSIS.md`

Current status: **waiting for human approval before WP3 implementation**.
