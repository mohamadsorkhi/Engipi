# V1-005 — Taxonomy audit

## Scope

This task audits the existing skill taxonomy only. It does not implement any V1-006 work and does not mutate taxonomy data.

## Audit findings

The taxonomy uses `skill_domains` and `subdomains` as its hierarchy, while `skills.subdomain_id` is the legacy primary relationship and `skill_subdomain` is the many-to-many representation. A software skill may also reference a `process`, whose domain must agree with its primary subdomain.

The audit identified five consistency risks that database uniqueness and foreign-key constraints do not fully cover:

1. skills with no primary subdomain;
2. legacy primary subdomain IDs that no longer resolve;
3. primary skill/subdomain relationships missing from the pivot table;
4. skills whose process and primary subdomain belong to different domains; and
5. skill type values outside the supported `software` and `field` vocabulary.

Duplicate natural keys remain covered by `skills:check-duplicates` and the taxonomy unique-constraint migration, so that check is intentionally not duplicated.

## Implementation

`php artisan skills:audit-taxonomy` performs a read-only audit, prints per-category counts and affected skill IDs, and exits unsuccessfully when inconsistencies exist so it can act as a CI or deployment gate. `--json` provides a stable machine-readable report with `status`, `issue_count`, and categorized issue IDs.

## Verification

Feature tests cover both a clean empty taxonomy and representative inconsistent records. The inconsistent fixture verifies missing pivots, an unassigned skill, a cross-domain process, and an unsupported skill type in one audit run.

## Operational use

Run the duplicate and consistency audits before and after taxonomy imports or maintenance:

```bash
php artisan skills:check-duplicates
php artisan skills:audit-taxonomy
```

Use the existing `skills:backfill-subdomains --dry-run` command to assess missing pivot repairs. Review unassigned, invalid, and cross-domain records manually before changing production data.
