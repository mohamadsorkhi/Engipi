# SEC-PHP-001 / ENG-RUNTIME-006 — Composer/PHP Dependency Remediation

Date: 2026-08-10
Status: Implemented and locally verified; pending PR review and merge.

## Scope

This change remediates Composer/PHP dependency vulnerabilities, stabilizes the supported runtime dependency set, and fixes the profile-context regression exposed by the upgraded test suite.

Tracked changes are intentionally limited to:

- `.github/workflows/deploy.yml`
- `app/Http/Middleware/EnsureActiveRole.php`
- `composer.json`
- `composer.lock`
- `docs/audits/SEC-PHP-001.md`

No feature, UI, npm dependency, migration, or route change is included.

## Dependency verification

The dependency remediation was verified with:

```console
composer validate --strict
composer audit --locked
```

Result:

```text
./composer.json is valid
No security vulnerability advisories found.
```

## Deployment integrity

The deployment workflow now sets up PHP 8.2 with Composer v2 and installs production dependencies from the committed lock file before the FTP upload:

```console
composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader
```

This prevents production from retaining stale or vulnerable packages when `vendor` is not tracked by Git and ensures the deployed dependency graph matches the audited `composer.lock`.

## Profile-context compatibility fix

The Laravel 12 verification run exposed a stale-session edge case in `EnsureActiveRole`.

When a user's active profile had been deleted and the user had no remaining profiles, the middleware redirected to profile selection before clearing `active_profile_id` and its legacy compatibility value. The middleware now clears the profile context before redirecting.

This is a narrowly scoped correctness fix with no new feature or route behavior.

Verification:

```console
php artisan test --filter=ProfileContextTest
php artisan test
```

Result:

```text
ProfileContextTest: 14 passed
Full suite: 164 passed (664 assertions)
```
