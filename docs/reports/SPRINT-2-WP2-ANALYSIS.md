# Sprint 2 WP2 Analysis and Implementation Plan

Date: 2026-07-17
Status: Analysis complete; implementation not started
Work package: Sprint 2 WP2 â€” legacy project-file migration rehearsal

## 1. Scope Decision and Sources

The repository does not contain a standalone Sprint 2 implementation plan that assigns work-package numbers. The work-package mapping is derived from the ordered priorities in `docs/reports/AI_PROJECT_MEMORY.md`:

1. user-skill unique constraint (completed as Sprint 2 WP1);
2. migration rehearsal (this WP2);
3. malware/content inspection;
4. operational telemetry; and
5. legacy URL retirement.

Accordingly, WP2 means rehearsal and operational hardening of the existing legacy public-to-private project-file migration. It does not mean the broader conversation-query optimization listed in the older Sprint 2 performance roadmap. That performance work remains future scope unless the maintainer explicitly changes the WP mapping.

Documents reviewed:

- `docs/PROJECT_MASTER_PLAN.md`
- `docs/AI_PROJECT_RULES.md`
- `docs/reports/AI_PROJECT_MEMORY.md`
- `docs/reports/SPRINT-1B-IMPLEMENTATION-PLAN.md`
- `docs/reports/SPRINT-1B-WP4-REPORT.md`
- `docs/reports/SPRINT-1B-FINAL-REPORT.md`
- `docs/reports/SPRINT-2-WP1-FINAL-REPORT.md`

The Sprint 1B plan requires a dry run, bounded/restartable processing, classification of mixed storage states, per-file copy verification, safe interruption, aggregate-only reporting, a sanitized production-like rehearsal, storage-headroom and backup evidence, and a verified copy-back rollback procedure before any production execution.

## 2. Current Implementation

### 2.1 Storage transition model

`project_files.storage_disk` is nullable:

- `null` and `public` identify legacy public files;
- `local` identifies private files;
- new uploads are written to `local`; and
- `ProjectFile::storageDisk()` preserves null-to-public compatibility.

Downloads for either generation use the authenticated `user.project-files.download` route and `ProjectFilePolicy`. Deletion actions select the disk recorded by each row. Existing public copies can still be served through historical `/storage/...` URLs until they are migrated and removed.

### 2.2 Existing migration command

`app/Console/Commands/MigrateProjectFilesToPrivateStorage.php` registers:

```text
project-files:migrate-private
    --execute
    --batch=100
```

Without `--execute`, it performs a dry-run inventory. It processes database rows in primary-key order with `chunkById`, validates a batch range of 1â€“1000, and reports aggregate counts and byte totals without printing paths or original filenames.

For legacy public rows, execute mode:

1. streams a public file to the private disk when needed;
2. verifies size and SHA-256 equality;
3. changes that row's `storage_disk` to `local`; and
4. deletes the public copy only after verification and metadata update.

It can resume two important interrupted states:

- a legacy row where matching public and private copies already exist; and
- a private-metadata row where matching public and private copies both exist.

It fails closed for missing files, mismatched content, unsupported disk metadata, legacy metadata with only a private copy, and private metadata with only a public copy.

### 2.3 Existing test coverage

`tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php` currently covers eight cases:

- non-mutating default dry run;
- successful copy/verify/update/public-delete flow;
- resume from matching dual copies under legacy metadata;
- cleanup of a matching public copy after metadata is already private;
- content mismatch without mutation/deletion;
- neither copy present;
- private metadata with only a public copy; and
- bounded batches, multi-batch processing, and invalid maximum batch input.

The broader `ProjectFileDownloadAuthorizationTest` covers delivery and deletion behavior for both storage generations. The configured database has already run the additive `storage_disk` migration, but no production-like legacy file rehearsal is documented.

## 3. Problems WP2 Must Solve

### 3.1 Missing rehearsal evidence

The command is implemented and unit/feature tested with fake disks, but there is no recorded rehearsal against an approved sanitized MySQL-compatible database and realistic filesystem copy. There are no measured values for runtime, throughput, peak disk growth, storage headroom, batch behavior, or restart duration.

### 3.2 Incomplete operational gates

The command does not itself enforce or record:

- verified database and storage backup readiness;
- required free-space/headroom thresholds;
- an execution identifier and start/end operational summary;
- a maintenance/concurrency decision for uploads or deletion during migration; or
- explicit human approval between dry run and execute mode.

These may be implemented as an operator runbook and approval checklist rather than application code, but they cannot remain implicit.

### 3.3 Incomplete state and failure coverage

Tests do not separately lock all reported classifications or important failure boundaries:

- legacy `storage_disk = public` as distinct from null;
- legacy metadata with only a private file;
- already-private with only a private file;
- unknown/non-supported `storage_disk` value;
- public/private byte totals;
- write-stream, read-stream, size, checksum, metadata-save, and public-delete failures;
- interruption after private copy but before metadata update;
- interruption after metadata update but before public deletion;
- rerunning a completely migrated dataset;
- minimum, zero, non-integer, and negative batch inputs; and
- assurance that exception output never exposes path, filename, project/user identifiers, or content.

### 3.4 Orphan-file inventory is absent

The command iterates database rows, so it detects missing physical files for known rows but cannot detect physical public/private files that have no `project_files` row. The Sprint 1B plan explicitly called for orphaned-disk-file rehearsal. Any implementation must avoid an unbounded in-memory listing and must report aggregates only.

### 3.5 Rollback is procedural, not yet rehearsed

There is no automated rollback mode. The documented rollback principle is:

1. copy a verified private file back to public;
2. verify size/checksum;
3. change that row's metadata back to `public`/legacy; and
4. retain the private source until restoration is verified.

WP2 must decide whether to add a narrowly scoped, idempotent CLI rollback mode or use a reviewed rehearsal-only copy-back procedure. Adding rollback behavior is preferable only if it can share the command's verification rules without making normal migration less safe. Bulk deletion of private files is not acceptable.

### 3.6 Filesystem/database operations are not atomic

A process or database failure can leave a valid private copy with legacy metadata; a delete failure can leave matching public and private files with private metadata. The existing command resumes both of those states, but this recovery must be deliberately interruption-tested. Concurrent upload/deletion operations could also change rows or physical files during a pass, so the runbook needs a clear concurrency window or quiescence rule.

### 3.7 Error visibility is intentionally minimal

The command catches `Throwable` and increments `failed`, which protects sensitive metadata but provides little diagnostic differentiation. WP2 needs actionable aggregate error categories or a redacted per-run incident mechanism without exposing paths, UUIDs, filenames, or user data.

## 4. Dependencies

### Runtime dependencies

- `project_files` table and its nullable `storage_disk` column.
- `ProjectFile` model and null-to-public compatibility rule.
- Laravel `public` and `local` filesystem disks.
- Read/write/delete/stream permissions for both storage roots.
- Stable file paths across both disks.
- Sufficient temporary capacity to hold both copies during verification.
- SHA-256 stream support in PHP.
- A MySQL-compatible sanitized database snapshot paired with a corresponding sanitized storage copy.

### Application dependencies

- New uploads must continue writing `storage_disk = local`.
- The protected download controller must continue resolving the recorded disk.
- Project/user deletion actions must remain aware of both storage generations.
- The storage metadata migration must be applied before rehearsal.
- Backup and restore tooling must be independently verified by an operator.

### Human/operational dependencies

- Approval of sanitized rehearsal data and storage.
- Confirmation that no external backup, report, CDN, web-server, or integration depends on legacy public paths.
- Defined maintenance/concurrency policy.
- Human review of dry-run aggregates, unresolved states, storage headroom, backup evidence, and rollback rehearsal before execute mode.

No new Composer/npm dependency is justified for WP2.

## 5. Risks and Mitigations

| Risk | Consequence | Planned mitigation |
|---|---|---|
| Public copy is deleted before private verification | Attachment loss | Preserve copy â†’ stream/size/SHA-256 verify â†’ metadata update â†’ public delete order; test every boundary. |
| Database update fails after copy | Dual copy with legacy metadata | Fail visibly; rerun must verify and resume without recopying or deleting early. |
| Public delete fails after metadata update | Legacy URL remains reachable | Count failure; rerun cleans only a matching public copy. |
| Source changes during hashing/copying | Inconsistent destination | Use a maintenance/quiescence policy; verify source/destination after copy; abort mismatches. |
| Insufficient private-disk capacity | Partial migration or service impact | Dry-run byte totals plus independent free-space/headroom check and conservative margin. |
| Sanitized snapshot does not resemble production | False confidence | Record dataset/file counts, size distribution, storage-state distribution, engine, filesystem topology, and limitations without personal/path data. |
| Logs expose attachment metadata | Confidentiality breach | Aggregate-only output; explicit negative tests for paths, names, UUIDs, and exception details. |
| Orphan scan consumes excessive memory/time | Rehearsal or production disruption | Use bounded iteration where the filesystem driver permits it; separate orphan inventory from row migration; time-box and document unsupported drivers. |
| Rollback overwrites a changed public file | Data corruption | Never overwrite mismatched content; verify before metadata changes; retain private source. |
| Rerun deletes the wrong generation | Data loss | Explicit metadata/state matrix and checksum equality requirement before any cleanup. |
| Existing public URLs remain reachable | Continued legacy exposure | WP2 only rehearses migration; URL retirement remains Sprint 2 WP5 and requires separate approval. |
| Multi-node local storage differs | Missing files per node | Rehearse on actual deployment topology or stop until shared/authoritative storage is identified. |

## 6. Proposed Implementation Plan

### Phase 1 â€” Lock the state machine in tests

Extend the command test suite before changing behavior. Add table-driven or clearly named tests for every metadata/physical-copy state, all batch validation edges, rerun idempotency, aggregate-only output, and the two interruption recovery points. Preserve existing command name, default dry-run behavior, output safety, and execute semantics.

Acceptance gate: focused tests describe the complete approved state matrix; any intentionally missing behavior is represented by a failing test only within the active implementation session and is green before handoff.

### Phase 2 â€” Add rehearsal safety and observability

Make the smallest changes to the existing command needed to:

- distinguish operational failure categories through aggregate counters;
- preserve redaction under thrown filesystem/database exceptions;
- expose enough aggregate byte/count evidence for capacity planning;
- support deterministic interruption/restart testing through small internal seams only if necessary; and
- ensure execute mode remains idempotent for all recoverable intermediate states.

Do not introduce a service/repository abstraction unless tests prove the command cannot be safely exercised without one. Do not change upload, download, authorization, or deletion behavior.

### Phase 3 â€” Define orphan inventory

Add a bounded, read-only orphan inventory path only after confirming the deployed filesystem driver's listing behavior and scale. It must compare physical project-file namespace entries to database paths without loading an unbounded full set, must not delete or repair anything, and must emit only aggregate counts/bytes.

If the current local filesystem API cannot provide a genuinely bounded traversal, document orphan enumeration as an external rehearsal operation and do not disguise an unbounded scan as production-safe.

### Phase 4 â€” Implement or document rollback execution

Choose one of these at approval time:

- Preferred when safely testable: extend the same CLI with a mutually exclusive rollback/copy-back mode that copies private to public, verifies, changes metadata only after verification, never overwrites mismatches, and does not delete private sources automatically.
- Minimal operational alternative: create a reviewed rehearsal runbook using a sanitized snapshot and backup restore/copy-back procedure, without adding production rollback code.

The implementation must not add an automatic bulk-delete-private option.

### Phase 5 â€” Run isolated rehearsal

On an explicitly approved sanitized production-like environment:

1. record database engine/version, filesystem topology, row counts, state counts, aggregate bytes, and free space without sensitive identifiers;
2. verify database and both storage backups can be restored;
3. run default dry run with a conservative batch size;
4. stop if unresolved states, mismatches, missing files, unsupported metadata, insufficient headroom, or unexpected external consumers exist;
5. after separate approval, run execute mode;
6. interrupt safely at planned boundaries and rerun;
7. confirm all expected rows are `local`, private copies match, and public copies are absent only for migrated rows;
8. verify authorized downloads and deletion behavior on sampled synthetic/sanitized records;
9. rehearse copy-back/restore rollback; and
10. re-run dry run and record final aggregate state and runtime.

This phase must never target production without a new explicit instruction.

### Phase 6 â€” Regression and handoff

Run focused command tests, project-file authorization/delivery tests, the complete suite, syntax/style checks for scoped files, command discovery, route verification, secret/debug scans, and `git diff --check`. Create a WP2 completion report containing before/after aggregate evidence, exact commands, timings, interruption outcomes, rollback evidence, limitations, and the production approval gate.

## 7. Files Involved

### Files expected to be modified

The minimal recommended implementation scope is:

- `app/Console/Commands/MigrateProjectFilesToPrivateStorage.php` â€” safety, state reporting, restart behavior, and an approved rollback/orphan option if required.
- `tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php` â€” full state matrix, failure, redaction, interruption, rerun, orphan, and rollback coverage.
- `docs/reports/SPRINT-2-WP2-IMPLEMENTATION-REPORT.md` â€” final evidence and approval gate (to be created during implementation).
- A focused operational runbook under `docs/` or `docs/reports/` if rehearsal commands, backup/headroom gates, and rollback steps cannot be expressed clearly in the implementation report alone; exact filename should be approved before creation.

This analysis file, `docs/reports/SPRINT-2-WP2-ANALYSIS.md`, is the only file created during analysis.

### Files involved as dependencies and verification surfaces, but not expected to change

- `app/Models/ProjectFile.php`
- `database/migrations/2026_07_16_000000_add_storage_disk_to_project_files_table.php`
- `config/filesystems.php`
- `app/Actions/Employer/CreateProjectAction.php`
- `app/Actions/Employer/DeleteProjectAction.php`
- `app/Actions/Admin/DeleteProjectAction.php`
- `app/Actions/Admin/DeleteUserAction.php`
- `app/Http/Controllers/User/ProjectFileController.php`
- `app/Policies/ProjectFilePolicy.php`
- `app/Providers/AuthServiceProvider.php`
- `routes/user.php`
- `resources/views/admin/projects/show.blade.php`
- `resources/views/specialist/matched-projects/show.blade.php`
- `resources/views/user/matched-projects/show.blade.php`
- `resources/views/user/projects/show.blade.php`
- `tests/Feature/Authorization/ProjectFileDownloadAuthorizationTest.php`
- `tests/Feature/Uploads/ProjectUploadValidationTest.php`

These files define or verify the storage contract and should be read during implementation, but a required change to any of them is a scope-expansion signal requiring review.

### Files that must remain untouched

- The completed WP1 migration and API integrity files, especially `database/migrations/2026_07_17_000000_add_composite_unique_index_to_user_skills_table.php` and `tests/Feature/Api/UserSkillApiContractTest.php`.
- The historical empty migration `database/migrations/2024_07_24_100000_add_unique_index_to_user_skills_table.php`.
- Sprint 1A authorization foundation and characterization tests except running them unchanged for regression verification.
- Dependency manifests and locks: `composer.json`, `composer.lock`, `package.json`, and lockfiles.
- `.env`, production configuration, deployment workflows, web-server configuration, and filesystem credentials.
- Database schema migrations: WP2 requires no new or modified migration.
- Active upload validation, attachment authorization policy, protected download route/controller, Blade links, project creation, and deletion actions unless a separately approved finding proves a correctness blocker.
- Unrelated Sprint 2 performance areas: messages/conversations, landing caches, admin cache invalidation, project matching, eager loading, and performance indexes.

## 8. Required Tests

### Focused automated tests

At minimum, the command suite must verify:

1. dry run is the default and never changes files or metadata;
2. null and explicit-public legacy metadata behave identically;
3. every public/private presence combination for legacy and private metadata;
4. unknown disk metadata fails without repair;
5. matching files require both size and SHA-256 equality;
6. content mismatch preserves both copies and metadata;
7. successful execute order and final state;
8. failure at copy/read/write/verify/save/delete boundaries is safe;
9. restart after copy-before-save and save-before-delete;
10. fully migrated rerun is idempotent;
11. batch bounds and multiple batches;
12. aggregate byte/state counts are correct;
13. command output and errors contain no paths, filenames, UUIDs, user/project data, or contents;
14. orphan inventory is read-only and bounded, if implemented;
15. rollback/copy-back never overwrites mismatches, updates metadata only after verification, and retains private source, if implemented; and
16. invalid/mutually exclusive operational options fail before mutation.

### Regression tests

- `php artisan test --filter=MigrateProjectFilesToPrivateStorageTest`
- `php artisan test --filter=ProjectFileDownloadAuthorizationTest`
- `php artisan test --filter=ProjectUploadValidationTest`
- `php artisan test`

The existing Sprint 1A authorization and WP1 data-integrity suites must pass unchanged as part of the full run.

### Rehearsal assertions

Automated fake-disk tests are necessary but insufficient. The sanitized rehearsal must confirm aggregate pre/post row states, file counts and bytes, checksum agreement, no unexpected public copies after execute, no missing private copies, successful authorized downloads, restartability, and verified rollback/copy-back.

## 9. Verification Steps

Before implementation:

```text
git status --short
php artisan project-files:migrate-private --help
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
```

After implementation, in the development/test environment:

```text
php -l app/Console/Commands/MigrateProjectFilesToPrivateStorage.php
vendor/bin/pint --test app/Console/Commands/MigrateProjectFilesToPrivateStorage.php tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php
php artisan list | findstr project-files:migrate-private
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
php artisan test --filter=ProjectFileDownloadAuthorizationTest
php artisan test --filter=ProjectUploadValidationTest
php artisan test
php artisan route:list --except-vendor -v
git diff --check
git status --short
```

Before any approved sanitized execute rehearsal:

- confirm environment identity is non-production;
- confirm database and storage snapshots correspond to each other;
- prove restore/copy-back capability;
- measure free space on source, destination, and backup locations;
- run dry run and obtain human approval of aggregate results;
- stop on any unresolved/problem counter or insufficient headroom; and
- ensure no command output/report contains sensitive paths or identifiers.

No production migration command, schema migration, public-file deletion, deployment, commit, or push belongs to this implementation without separate explicit approval.

## 10. Migration, Dependency, Security, and Rollback Impact

### Database migrations

No migration is required or planned. WP2 operates on the already-added nullable `storage_disk` column and existing rows. Creating or modifying a migration would be outside scope.

### Dependencies

No package change is required. Laravel filesystem streams and PHP hashing already provide the needed primitives.

### Security

The rehearsal handles confidential project attachments. Reports and logs must remain aggregate-only. The protected download route remains the only application delivery path. WP2 reduces legacy public exposure only in an approved rehearsal dataset; production URL retirement is deferred to WP5.

### Rollback

Source-code rollback is a focused revert of command/tests/report changes. Data rollback after an execute rehearsal must restore verified private content to public before changing metadata and must retain the private source until verification succeeds. Snapshot restore is acceptable only when database and storage are restored to the same point. No bulk private deletion is allowed.

## 11. Approval Decisions Required Before Implementation

1. Confirm that Sprint 2 WP2 is the legacy project-file migration rehearsal defined by project memory.
2. Approve whether rollback should be an automated mutually exclusive command mode or a rehearsal-only operational procedure.
3. Approve whether bounded orphan inventory belongs in the command or in an external read-only rehearsal tool/procedure after filesystem-scale review.
4. Identify or authorize an isolated sanitized MySQL-compatible database and matching storage copy; without it, implementation can harden tests/code but cannot complete the rehearsal objective.
5. Define the minimum free-space/headroom margin and the backup/restore evidence required before execute mode.
6. Define the concurrency policy during rehearsal (maintenance window, write pause, or another controlled approach).

## 12. Analysis Activity and Current Status

This task performed read-only inspection of planning documents, reports, current source, schema, routes, storage configuration, and tests. No source code, migration, dependency, configuration, database row, or storage object was changed. No test or migration command was required for this documentation-only analysis.

The only created file is:

- `docs/reports/SPRINT-2-WP2-ANALYSIS.md`

Current status: **waiting for human approval before WP2 implementation**.
