# Sprint 2 WP1 Final Report

Date: 2026-07-17

## Final Status

**COMPLETED**

Application-level duplicate protection remains intact, and the database now enforces uniqueness for every `user_skills (user_id, skill_id)` pair.

## Files Changed for WP1

- `app/Actions/Api/AddUserSkillAction.php`
- `app/Http/Controllers/Api/UserSkillController.php`
- `app/Http/Requests/Api/StoreUserSkillRequest.php`
- `database/migrations/2026_07_17_000000_add_composite_unique_index_to_user_skills_table.php`
- `routes/api.php`
- `tests/Feature/Api/UserSkillApiContractTest.php`
- `docs/reports/SPRINT-2-WP1-FINAL-REPORT.md`

The old empty migration, `database/migrations/2024_07_24_100000_add_unique_index_to_user_skills_table.php`, was inspected but was not modified.

## Migration Details

- Migration filename: `2026_07_17_000000_add_composite_unique_index_to_user_skills_table.php`
- Table: `user_skills`
- Indexed columns, in order: `user_id`, `skill_id`
- Index name: `user_skills_user_id_skill_id_unique_v2`
- Constraint type: composite unique index

Before adding the index, `up()` groups the table by `user_id` and `skill_id` and checks for counts greater than one. If any duplicate group exists, it throws an exception containing the offending groups before schema alteration. It never deletes or merges data.

## Duplicate Data Check

A read-only duplicate query was executed against the configured database before the migration file was created.

- Result: `[]`
- Duplicate groups found: 0
- Automatic deletion or merge performed: no

The migration repeats this check at execution time to protect against data changing between preflight and migration.

## Migration Output

Initial `php artisan migrate` execution did not alter the schema because Laravel treated a PHP global-class import warning as an error. The migration namespace reference was corrected without changing its behavior.

Successful required command output:

```text
INFO  Running migrations.

2026_07_16_000000_add_storage_disk_to_project_files_table ........ DONE
2026_07_17_000000_add_composite_unique_index_to_user_skills_table  DONE
```

The project-file migration was already pending and was applied by the required unscoped `php artisan migrate`; it was not created or modified as part of WP1.

After rollback verification, only the WP1 migration was re-applied:

```text
INFO  Running migrations.

2026_07_17_000000_add_composite_unique_index_to_user_skills_table  18.05ms DONE
```

Final `php artisan migrate:status` reports the WP1 migration as `[3] Ran`. A final `SHOW INDEX FROM user_skills` inspection reports the named unique index with `user_id` at sequence 1 and `skill_id` at sequence 2.

## Database Rejection Verification

`UserSkillApiContractTest` now directly inserts an already-existing `(user_id, skill_id)` pair through the query builder and expects `Illuminate\Database\QueryException`.

- Result: PASS
- This verifies rejection by the database constraint independently of `AddUserSkillAction`.
- The existing HTTP duplicate contract still returns HTTP 409 without adding another row.

## Rollback Verification

Rollback was executed with:

```text
php artisan migrate:rollback --path=database/migrations/2026_07_17_000000_add_composite_unique_index_to_user_skills_table.php
```

The first rollback attempt revealed that MySQL had removed its redundant automatic `user_id` index and was using the new composite index to support the existing foreign key. MySQL therefore refused to drop the composite index. No rollback was recorded by that failed attempt and the unique index remained present.

The final `down()` safely restores the pre-existing foreign-key-supporting `user_id` index only when MySQL has removed it, then drops only `user_skills_user_id_skill_id_unique_v2`. It does not drop columns, foreign keys, data, or unrelated indexes.

Successful rollback output:

```text
INFO  Rolling back migrations.

2026_07_17_000000_add_composite_unique_index_to_user_skills_table  60.57ms DONE
```

The migration was then successfully re-applied, leaving the configured database in the intended migrated state.

Rollback verification: PASS.

## Test Results

Focused API contract verification:

```text
php artisan test --filter=UserSkillApiContractTest
Tests: 8 passed (28 assertions)
Duration: 3.00s
```

Complete suite:

```text
php artisan test
Tests: 78 passed (240 assertions)
Duration: 7.73s
```

The focused suite confirms that authentication, specialist authorization, authenticated ownership, validation, sequential duplicate behavior, and database-enforced uniqueness all pass.

`git diff --check` also completed with exit code 0 and no whitespace errors. Git emitted only informational LF-to-CRLF warnings for pre-existing tracked modifications.

## Remaining Risks

- The migration intentionally aborts when existing duplicates are found. A future environment containing duplicates will require an explicit, reviewed remediation decision before migration can proceed.
- MySQL may use a composite index to support the leading-column foreign key. The rollback accounts for this by restoring the missing supporting index before removing the unique index.
- The required unscoped migration command also applied the already-pending project-file migration in this configured environment. This is recorded here for operational clarity.

## Scope Confirmation

- Sprint 1A WP1 authorization foundation files were not modified or reimplemented.
- No application refactoring was performed.
- No unrelated source file was modified for WP1.
- Pre-existing unrelated worktree changes were preserved.
- No commit, push, deployment, or production migration was performed.
