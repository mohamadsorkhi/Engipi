# Sprint 2 WP2 Implementation Report

Date: 2026-07-17
Work package: Sprint 2 WP2 â€” legacy project-file migration rehearsal hardening

## Final Status

**IMPLEMENTATION COMPLETED â€” production-like rehearsal remains approval-gated.**

The command implementation, automated state-matrix coverage, restart verification, read-only orphan inventory, verified rollback mode, aggregate reporting, and regression verification are complete. No production or sanitized external dataset was supplied or authorized, so no real-storage execute/rollback rehearsal was performed. Production execution remains prohibited pending the operational gates documented below.

## Files Changed

- `app/Console/Commands/MigrateProjectFilesToPrivateStorage.php`
- `tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php`
- `docs/reports/SPRINT-2-WP2-IMPLEMENTATION-REPORT.md`

The approved analysis remains at `docs/reports/SPRINT-2-WP2-ANALYSIS.md` and was not modified during implementation.

No database migration, model, upload action/request, download controller/policy/route, Blade view, filesystem configuration, dependency manifest, environment file, or unrelated module was modified.

## Implementation Summary

The existing command name and default behavior are preserved:

```text
project-files:migrate-private
```

Available modes are now:

```text
default       aggregate dry-run inventory; no writes or deletes
--execute     verified public-to-private migration
--rollback    verified private-to-public copy-back
--orphans     read-only orphan inventory on public and local disks
```

`--execute`, `--rollback`, and `--orphans` are mutually exclusive. Invalid combinations fail before database or filesystem mutation. `--batch` retains its existing default of 100 and allowed range of 1â€“1000.

The command continues to process database-backed migration/rollback rows with `chunkById` in primary-key order. The report now includes aggregate operational counters for:

- total rows and batches processed;
- public/private bytes detected;
- every storage-state classification;
- migrated files and removed verified public copies;
- rolled-back/already-public rows;
- scanned/orphan file counts and bytes per disk;
- unresolved/failure counts; and
- elapsed milliseconds.

Output contains counters and mode/status messages only. It does not print paths, original filenames, project/user/file UUIDs, contents, or caught exception messages.

## Forward Migration Behavior

Existing `--execute` behavior remains intact:

1. locate the public and private copies using the row path;
2. stream public content to private storage only when the private copy is absent;
3. verify both size and SHA-256 checksum;
4. update that row to `storage_disk = local`;
5. remove the public copy only after verification and metadata update; and
6. retain failure/problem states without automatic repair.

Null and explicit `public` metadata are treated as legacy. Unsupported metadata, missing copies, single-private-copy legacy states, private-metadata/public-only states, and content mismatches remain fail-closed.

Dry run remains the default and never copies, deletes, or changes metadata.

## Orphan Inventory Behavior

`--orphans` is strictly read-only:

```text
php artisan project-files:migrate-private --orphans
```

Behavior:

- scans the `project-files` namespace on both `public` and `local` disks;
- consumes Flysystem's recursive listing as an iterable instead of first collecting all paths in application memory;
- checks whether each file path has a corresponding `project_files` database row;
- reports aggregate scanned-file, orphan-file, and orphan-byte counts per disk;
- never creates, copies, overwrites, repairs, renames, or deletes a file;
- never updates a database row; and
- never prints an orphan path or identifier.

The inventory intentionally treats physical files outside the `project-files` namespace as out of scope.

## Rollback Behavior

Rollback is invoked with:

```text
php artisan project-files:migrate-private --rollback
```

For a private row, rollback:

1. requires the private source to exist;
2. refuses to overwrite an existing public file when size or SHA-256 differs;
3. streams the private source to public only when the public copy is absent;
4. verifies size and SHA-256 after copying;
5. updates metadata to explicit `public` only after verification; and
6. always retains the private source.

When matching public/private copies already exist, rollback resumes by verifying them and updating metadata without recopying. A subsequent rollback recognizes the explicit-public state and succeeds without mutation. This makes rollback restart-safe and idempotent.

Rollback never bulk-deletes private files and exposes no option that deletes private sources.

Legacy metadata with a public copy is already rolled back and remains unchanged. Missing, mismatched, or unsupported states fail without automatic repair.

## Restart and Interruption Behavior

The automated suite verifies the two important forward interruption states already supported by the command:

- copy completed but metadata still legacy: matching public/private copies are verified, metadata is advanced, and only then is public removed;
- metadata already private but verified public copy remains: the matching public copy is safely cleaned on rerun.

Rollback verification covers its corresponding restart state:

- public copy already restored but metadata still private: matching copies are verified, metadata changes to public, and the private source remains.

A second rollback run succeeds without additional writes or deletes. Content mismatches in either direction stop processing for that row and preserve both copies and metadata.

## Automated Coverage Added

The focused command suite increased from 8 tests/34 assertions to 17 tests/89 assertions. Added coverage includes:

- explicit-public legacy metadata;
- remaining legacy/private/unsupported metadata states;
- aggregate batch, byte, and elapsed reporting;
- negative assertions preventing sensitive output;
- read-only public/local orphan inventory;
- private-to-public verified rollback;
- rollback restart and idempotency;
- rollback recovery from legacy metadata with only a verified private copy;
- mismatched-public no-overwrite behavior; and
- mutually exclusive modes failing before mutation.

Existing tests continue covering default dry run, successful execution, both forward interruption states, mismatches, missing files, unsafe metadata state refusal, bounded batch size, and multi-batch processing.

## Test and Verification Results

### Command suite

```text
php artisan test --filter=MigrateProjectFilesToPrivateStorageTest
```

Final result: **PASS â€” 17 tests, 89 assertions, 3.78 seconds.**

### Protected download and deletion regression

```text
php artisan test --filter=ProjectFileDownloadAuthorizationTest
```

Final result: **PASS â€” 12 tests, 36 assertions, 3.61 seconds.**

### Upload regression

```text
php artisan test --filter=ProjectUploadValidationTest
```

Final result: **PASS â€” 22 tests, 48 assertions, 3.55 seconds.**

### Complete suite

```text
php artisan test
```

Final result: **PASS â€” 87 tests, 295 assertions, 10.39 seconds.**

The full suite includes the unchanged Sprint 1A authorization characterization foundation and Sprint 2 WP1 database-integrity contract.

### Additional checks

- `git diff --check`: PASS; exit code 0, no whitespace errors. Git emitted informational LF-to-CRLF warnings for pre-existing tracked modifications.
- `php -l app/Console/Commands/MigrateProjectFilesToPrivateStorage.php`: PASS.
- `php -l tests/Feature/Commands/MigrateProjectFilesToPrivateStorageTest.php`: PASS.
- scoped `vendor/bin/pint --test ...`: PASS for both WP2 PHP files.
- command help/discovery: PASS; displays `--execute`, `--rollback`, `--orphans`, and `--batch`.

## Database, Migration, Upload, and Download Impact

- Database schema: no migration created or modified.
- Dependencies: none added or changed.
- Default dry run: no database or filesystem mutation.
- Orphan mode: no database or filesystem mutation.
- Execute/rollback: only explicitly selected command modes can update `project_files.storage_disk` and copy/delete according to their documented rules.
- Upload behavior: unchanged; new files continue to be written privately.
- Download/authorization behavior: unchanged.
- Routes and rendered attachment links: unchanged.

## Remaining Risks

- No approved sanitized MySQL-compatible database and matching realistic storage copy was available, so measured runtime, throughput, storage headroom, interruption timing, and backup restore evidence remain outstanding.
- Orphan scanning is memory-bounded at the filesystem-listing layer, but performs a database existence query per physical file. A very large storage tree may be slow; measure it during sanitized rehearsal before production use.
- Filesystem and database changes are not atomic. The command is restart-safe for known intermediate states, but operators must still use a controlled concurrency/maintenance policy.
- A source file changing during copy/checksum can produce a mismatch and unresolved row. Upload/deletion writes should be quiesced during execution.
- Aggregate elapsed time is process-wall-clock evidence, not a performance guarantee.
- The local/private disk must be authoritative and shared appropriately for the deployment topology. Multi-node installations cannot assume one node's local storage represents all files.
- Orphan inventory does not inspect paths outside `project-files` and deliberately performs no repair or deletion.
- Rollback retains private copies by design; later private cleanup requires a separate, reviewed operation and must never be inferred from successful rollback.
- Legacy static public URLs remain available until files are actually migrated and the separately scoped legacy URL retirement work is approved.

## Operational Approval Gate

Before any non-test execute or rollback run:

1. confirm the environment is not production unless production execution is explicitly authorized;
2. use a corresponding sanitized database/storage snapshot for rehearsal;
3. verify database and storage backup restoration;
4. measure destination and backup headroom against dry-run aggregate bytes;
5. define a write-quiescence/maintenance policy;
6. run dry run and orphan inventory;
7. stop on any unresolved/problem counter or unexpected orphan result;
8. obtain human approval of aggregate evidence before `--execute`;
9. rehearse interruption/restart and `--rollback`; and
10. record post-run aggregate verification without sensitive identifiers.

## Scope Confirmation

- No database migration was created or modified.
- No unrelated source file was changed.
- Upload/download behavior was not changed.
- No production or real external storage migration was run.
- No commit, push, deployment, or external-system action was performed.

Current status: **waiting for human review and approval**.
