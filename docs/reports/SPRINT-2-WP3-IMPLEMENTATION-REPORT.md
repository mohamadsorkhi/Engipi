# Sprint 2 WP3 Implementation Report

Date: 2026-07-17
Work package: Sprint 2 WP3 â€” synchronous project-document inspection

## Final Status

**APPLICATION IMPLEMENTATION COMPLETED â€” live scanner rollout remains approval-gated.**

New project document uploads now pass through a synchronous, fail-closed inspection contract after the existing cheap validation checks and before `CreateProjectAction` executes. Deterministic tests cover clean, malware, unavailable, failure, exception, and multi-file rejection outcomes without contacting a real scanner.

No ClamAV daemon was installed, configured, contacted, or deployed during this work package. A reachable, healthy daemon with current definitions is required before enabling uploads in a deployed environment; the default application behavior is intentionally fail closed when it is unavailable.

## Files Changed

### New application files

- `app/Contracts/ProjectDocumentInspector.php`
- `app/ValueObjects/ProjectDocumentInspectionStatus.php`
- `app/ValueObjects/ProjectDocumentInspectionResult.php`
- `app/Services/ProjectDocuments/ClamAvProjectDocumentInspector.php`
- `app/Rules/InspectedProjectDocument.php`
- `config/project_documents.php`

### Modified application files

- `app/Http/Requests/Employer/StoreProjectRequest.php`
- `app/Providers/AppServiceProvider.php`

### Test files

- `tests/Fakes/FakeProjectDocumentInspector.php`
- `tests/Feature/Uploads/ProjectUploadValidationTest.php`
- `tests/Feature/Uploads/ProjectDocumentInspectionTest.php`

### Documentation

- `docs/reports/SPRINT-2-WP3-IMPLEMENTATION-REPORT.md`

`CreateProjectAction`, upload storage, `ProjectFile`, downloads, routes, policies, WP2 migration command, database migrations, dependency manifests, and unrelated upload modules were not changed.

## Architecture

The implementation separates policy integration from scanner transport:

```text
StoreProjectRequest
  -> existing cheap validation
  -> InspectedProjectDocument rule
  -> ProjectDocumentInspector contract
  -> ClamAvProjectDocumentInspector
  -> typed ProjectDocumentInspectionResult
```

`AppServiceProvider` binds `ProjectDocumentInspector` as a singleton backed by the ClamAV streaming adapter. Tests replace this binding with a deterministic in-memory fake.

This keeps scanner/network mechanics out of validation and makes provider behavior replaceable without changing the request contract.

## Inspector Contract

`ProjectDocumentInspector` accepts the temporary Laravel `UploadedFile` and returns a typed `ProjectDocumentInspectionResult`.

The typed status enum supports:

- `clean`;
- `malware_detected`;
- `scanner_unavailable`; and
- `inspection_failed`.

Only `clean` permits validation to continue. Every other status fails closed.

The ClamAV adapter:

- connects using configured TCP host/port;
- uses the ClamAV `INSTREAM` protocol;
- streams bytes rather than sending an application/server file path;
- enforces the existing 10 MiB maximum independently;
- uses bounded connection and read timeouts;
- handles partial socket writes;
- maps exact clean/found response suffixes to typed results;
- treats connection failure/timeout as unavailable;
- treats invalid uploads, stream failures, write failures, and unknown protocol responses as inspection failures; and
- closes file and socket resources on every result path.

No shell command is built or executed, and no Composer package was added.

## Configuration

`config/project_documents.php` defines non-secret defaults:

| Setting | Default |
|---|---:|
| `CLAMAV_HOST` | `127.0.0.1` |
| `CLAMAV_PORT` | `3310` |
| `CLAMAV_CONNECT_TIMEOUT` | `2` seconds |
| `CLAMAV_READ_TIMEOUT` | `15` seconds |
| stream chunk | 8192 bytes |
| maximum stream | 10 MiB |

No `.env` file was read, printed, or modified. Deployment-specific configuration remains an operational responsibility.

## Validation Flow

`StoreProjectRequest` now applies this exact per-file order:

```text
bail
file
max:10240
AllowedProjectDocument
InspectedProjectDocument
```

Consequences:

1. invalid upload, oversize, and extension/MIME failures stop before scanner invocation;
2. approved outer types are inspected while still in PHP temporary upload storage;
3. every file in a multi-upload request must be clean;
4. controller/action execution begins only after all validation succeeds; and
5. rejected or unscannable requests create no project, project-file row, public file, or private file.

`CreateProjectAction` remains unchanged. Clean uploads retain the existing private `local` storage path, generated filename, metadata, success response, and download behavior.

## Failure Policy

| Inspection outcome | HTTP behavior | Persistence |
|---|---|---|
| Clean | Existing request flow continues | Existing private write after validation |
| Malware detected | Generic `422` validation error on `files.N` | None for entire request |
| Scanner unavailable | Generic `422` validation error on `files.N` | None for entire request |
| Inspection failure | Generic `422` validation error on `files.N` | None for entire request |
| Inspector throws | Exception is caught; same generic `422` error | None for entire request |

The `422` choice preserves the existing upload validation contract. Infrastructure failure is not exposed as a scanner/network error to the caller.

The generic Persian message is:

```text
ÙØ§ÛŒÙ„ Ø¢Ù¾Ù„ÙˆØ¯ Ø´Ø¯Ù‡ Ù‚Ø§Ø¨Ù„ Ø¨Ø±Ø±Ø³ÛŒ Ù†ÛŒØ³Øª.
```

The same message is used for every non-clean outcome so callers cannot infer malware signatures or scanner state.

## Security Considerations

- Inspection is synchronous and pre-persistence.
- Scanner unavailability never permits an upload.
- No path is sent to ClamAV; content is streamed.
- No filename, path, hash, signature, or raw scanner response is added to validation output.
- Caught exception messages are discarded.
- Tests deliberately inject a sensitive filename, scanner signature, path, and hash into an exception and verify none appears in the response.
- Multi-file rejection is atomic at the request-validation boundary.
- Existing private storage and policy-protected attachment delivery remain unchanged.
- The scanner connection uses configuration values, not request input.
- No real malware was added to test fixtures.

## Deterministic Test Design

`FakeProjectDocumentInspector` implements the production contract and accepts an ordered queue of typed results or exceptions. It counts calls so tests can prove:

- clean allowed files are inspected;
- the container resolves the fake binding;
- no-file requests do not invoke inspection;
- mixed files are inspected in order; and
- tests never depend on a live scanner, network, definitions, or timing.

The existing upload suite binds a clean fake to preserve and verify the prior successful upload behavior.

## Tests Executed

### Existing upload validation regression

```text
php artisan test --filter=ProjectUploadValidationTest
```

Final result: **PASS â€” 22 tests, 48 assertions, 3.33 seconds.**

Coverage retains all approved extension/MIME, active/executable/macro denial, size, mixed validation, private storage, and no-file behavior.

### New inspection suite

```text
php artisan test --filter=ProjectDocumentInspectionTest
```

Final result: **PASS â€” 7 tests, 54 assertions, 4.04 seconds.**

Coverage includes clean upload, malware, scanner unavailable, typed inspection failure, thrown inspection exception, sensitive-output redaction, one infected file in a multiple upload, zero persistence/storage writes after rejection, fake binding, and no-file behavior.

### Protected download/deletion regression

```text
php artisan test --filter=ProjectFileDownloadAuthorizationTest
```

Final result: **PASS â€” 12 tests, 36 assertions, 3.99 seconds.**

### WP2 migration/restart regression

```text
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
```

Final result: **PASS â€” 17 tests, 89 assertions, 4.49 seconds.**

### Complete suite

```text
php artisan test
```

Final result: **PASS â€” 94 tests, 349 assertions, 10.53 seconds.**

This includes the unchanged Sprint 1A authorization foundation, Sprint 2 WP1 integrity contract, WP2 migration behavior, uploads, downloads, and WP3 inspection.

### Syntax, style, and diff verification

- `php -l` on all 11 changed/new PHP files: PASS.
- `vendor/bin/pint --test` on all 11 changed/new PHP files: PASS after scoped mechanical formatting.
- `git diff --check`: PASS; no whitespace errors. Git emitted only informational LF-to-CRLF warnings for pre-existing tracked files.

## Remaining Limitations

- No live ClamAV provider integration test was run because no isolated daemon was supplied or authorized. Exact daemon-version/protocol behavior, signature detection, definition freshness, latency, and concurrency remain staging gates.
- The application now supplies known-signature malware scanning, but the bounded format-aware ZIP/OOXML/PDF/OLE/CSV policy described in the broader analysis is not implemented in this minimal scanner stage. ZIP bombs, encrypted/nested archives, OOXML external/embedded content, legacy OLE macros, PDF active content, and CSV formula risks remain subject to scanner capability and existing download defenses.
- ClamAV and signatures are operational dependencies even though no application package dependency was added.
- Scanner failure currently returns the same `422` contract as unsafe content. Operational telemetry cannot distinguish failures from HTTP output and remains Sprint 2 WP4 scope.
- Synchronous scanning adds request latency and consumes scanner/PHP worker capacity. Ten-MiB worst-case and concurrent-load measurements remain required before deployment.
- The default host/port will fail closed when no daemon is running. Application deployment must be coordinated with scanner readiness.
- Existing stored project files are not retroactively scanned.
- Avatar and administrative setting uploads remain outside WP3.
- Malware scanning reduces risk but cannot guarantee detection of zero-day or evasive content.

## Rollback

Application rollback removes the inspection rule from `StoreProjectRequest`, the inspector binding/config/services/contract/result classes, and focused tests. No schema or data rollback is required.

Clean files accepted while WP3 is active remain legitimate user data and must not be deleted. Rejected files were never persisted. Scanner infrastructure must remain reachable until application code is rolled back; disabling it first would intentionally block uploads under fail-closed behavior.

## Scope Confirmation

- No migration was created or modified.
- No dependency manifest or lockfile was changed.
- `CreateProjectAction` was unchanged.
- Upload storage and download behavior were unchanged.
- No unrelated module was refactored.
- No live scanner, production data, production storage, or external service was contacted.
- No commit, push, deployment, or production configuration change was performed.

Current status: **waiting for human review and scanner-integration approval**.
