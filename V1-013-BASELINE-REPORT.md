# V1-013 Regression Baseline Report

- **Date:** 2026-08-06
- **Branch:** `feature/v1-013-regression-tests`
- **Starting revision:** `1d99d00`

## Scope

V1-013 adds an executable regression baseline without changing application code or production behavior. The baseline focuses on stable, high-value HTTP boundaries: route names, URIs, methods, access middleware, guest access to the project form, and authentication redirects for protected pages.

The requested `ENGIPI_V1_ROADMAP.md` and `PROJECT_STATE.md` files were not present in the working tree, repository history, or the surrounding workspace at implementation time. This report therefore records that constraint rather than inventing roadmap or state content.

## Added Coverage

`tests/Feature/RegressionBaselineTest.php` adds 15 executable regression cases:

- nine route-contract cases spanning the landing page, guest project intake, public projects, profiles, the user dashboard, protected downloads, employer and specialist workflows, and the admin dashboard;
- five anonymous-user redirect cases for authenticated application areas; and
- one guest project-form availability case.

The route checks deliberately assert public contracts and middleware boundaries rather than controller internals. This makes route removal, renaming, method changes, URI drift, or accidental middleware removal immediately visible while leaving production implementation untouched.

## Repository Test Inventory

After adding the V1-013 baseline, the repository contains:

| Measure | Count |
| --- | ---: |
| PHPUnit test files | 16 |
| Declared test methods | 115 |
| V1-013 executable cases | 15 |

## Verification Results

| Command | Result |
| --- | --- |
| `php -l tests/Feature/RegressionBaselineTest.php` | **Passed.** No syntax errors detected. |
| `php artisan test` | **Environment-blocked.** The full suite was invoked, but `vendor/autoload.php` was absent. |
| `composer install --no-interaction --prefer-dist` | **Environment-blocked.** Composer attempted all 118 locked dependency installs, but the environment's GitHub CONNECT tunnel returned HTTP 403 for package downloads; no usable autoloader was produced. |

The full suite has therefore been run as far as the supplied environment permits, but no PHPUnit pass/fail baseline can be claimed. The first verification step in an environment with dependencies available must be:

```bash
composer install --no-interaction --prefer-dist
php artisan test
```

## Production Impact

None. V1-013 changes only the test suite and this report. No routes, controllers, models, migrations, configuration, views, or other runtime files were modified.

## Baseline Status

The regression contract is implemented and syntax-valid. Full-suite confirmation remains pending solely because locked Composer dependencies could not be downloaded in this environment.
