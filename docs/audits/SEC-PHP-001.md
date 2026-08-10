# SEC-PHP-001 / ENG-RUNTIME-006 — Composer/PHP Dependency Remediation

Date: 2026-08-10
Status: Implemented and locally verified; pending commit, push, PR review, and merge.

## Scope

This change remediates Composer/PHP dependency vulnerabilities and stabilizes the supported runtime dependency set.

Tracked changes are intentionally limited to:

- `composer.json`
- `composer.lock`
- `docs/audits/SEC-PHP-001.md`

No feature, UI, npm dependency, application behavior, migration, route, or unrelated bug fix is included.

## Baseline audit

The initial command:

```console
composer audit --locked