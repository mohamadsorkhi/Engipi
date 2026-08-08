# V1-001 Security Report

## Scope

This change implements V1-001 only. No V1-002 work is included. The requested
`ENGIPI_V1_ROADMAP.md` and `PROJECT_STATE.md` files were not present in this
repository checkout, so the implementation is based on the V1-001 requirements
provided with the task.

## Changes

- Removed application-model imports and the hard-coded administrator insert from
  the users-table migration. A fresh database now starts with no users or known
  administrator credentials.
- Added the `admin:provision` Artisan command for deliberate administrator
  provisioning.
- New administrator passwords are entered through a hidden prompt or read from a
  caller-selected environment variable (default: `ENGIPI_ADMIN_PASSWORD`), so a
  password does not need to appear in shell history or the process list.
- New administrator passwords must contain at least 12 characters. Names and the
  email address are validated before the account is created.
- Existing accounts are never overwritten. The command fails closed when an email
  is already registered unless `--promote-existing` is explicitly supplied.
- Explicit promotion changes only `role` and `is_admin`; the existing user's ID,
  password, profile fields, activation state, and related production data remain
  intact. Re-running the command for an administrator is a no-op.

## Operations

Interactive provisioning:

```bash
php artisan admin:provision admin@example.com \
  --first-name=System \
  --last-name=Administrator
```

Non-interactive provisioning without exposing the password as a command argument:

```bash
export ENGIPI_ADMIN_PASSWORD='replace-with-a-strong-secret'
php artisan admin:provision admin@example.com \
  --first-name=System \
  --last-name=Administrator
unset ENGIPI_ADMIN_PASSWORD
```

To deliberately promote an existing account while preserving its data:

```bash
php artisan admin:provision existing@example.com --promote-existing
```

## Focused test coverage

`ProvisionAdministratorTest` verifies that:

1. Fresh migrations do not create a user.
2. Environment-secret provisioning creates an active, verified administrator and
   hashes the password.
3. An existing user is unchanged without explicit promotion.
4. Explicit promotion preserves password, name, activation state, and row identity.

## Verification

- `git diff --check` passed.
- PHP syntax checks passed for every changed PHP file.
- The PHPUnit suite could not be executed in this environment because dependencies
  were absent and Composer downloads were rejected by the network proxy with HTTP
  403 responses. The attempted command was
  `php artisan test --filter=ProvisionAdministratorTest`.
