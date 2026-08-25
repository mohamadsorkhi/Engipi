# SEC-FE-001 â€” Frontend Dependency Security Remediation

Date: 2026-08-25
Status: Implemented and locally verified; pending commit, push, PR review, and merge.

## Scope

This change remediates production frontend dependency vulnerabilities tracked under `ENG-RUNTIME-007`.

Changed files:

- `package.json`
- `package-lock.json`
- `package-copy-config.json`
- `docs/audits/SEC-FE-001.md`

No application route, controller, migration, Blade view, or user-facing feature is changed.

## Baseline

The production dependency audit initially reported:

- 38 vulnerabilities
- 1 critical
- 2 high
- 35 moderate

Affected direct dependencies included:

- `swiper`
- `moment`
- `@ckeditor/ckeditor5-build-classic`
- `echarts`
- `quill`

## Remediation

The following unused vulnerable production packages were removed:

- `swiper`
- `@ckeditor/ckeditor5-build-classic`
- `echarts`
- `quill`

Their corresponding entries were also removed from `package-copy-config.json`.

`moment` was upgraded from `2.24.0` to the patched `2.30.1` release.

The remaining vulnerable `lodash-es` dependency chain was updated using the non-breaking command:

```console
npm audit fix
