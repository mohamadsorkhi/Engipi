# SEC-PHP-001 / ENG-RUNTIME-006 — Composer/PHP Dependency Remediation

Date: 2026-08-10
Status: Implemented and locally verified; pending commit, push, PR review, and merge.

## Scope

This change remediates Composer/PHP dependency vulnerabilities and stabilizes the supported runtime dependency set.

Tracked changes are intentionally limited to:

- `.github/workflows/deploy.yml`
- `composer.json`
- `composer.lock`
- `docs/audits/SEC-PHP-001.md`

No feature, UI, npm dependency, application behavior, migration, route, or unrelated bug fix is included.

## Baseline audit

The initial command:

```console
composer audit --locked
```
## Deployment integrity

The deployment workflow now sets up PHP 8.2 with Composer v2 and installs production dependencies from the committed lock file before the FTP upload:

```console
composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader
```
This prevents production from retaining stale or vulnerable packages when `vendor` is not tracked by Git and ensures the deployed dependency graph matches the audited `composer.lock`.
