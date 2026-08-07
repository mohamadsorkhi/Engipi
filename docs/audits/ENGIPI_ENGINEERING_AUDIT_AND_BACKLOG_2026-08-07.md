# Engipi Engineering Audit & Technical Backlog

**Date:** 2026-08-07  
**Repository baseline:** `main` at `4217fe1`  
**Scope:** Core stability only — no new feature or UI work

## 1. Executive decision

Engipi is not ready for new feature development. The application can bootstrap, migrate, route, test, and build locally, but it has confirmed security debt, one reproducible core regression, data-integrity risks, taxonomy ambiguity, and performance/concurrency risks that must be addressed first.

The remediation backlog is finite and contains **28 findings**:

| Severity | Count |
|---|---:|
| P0 | 0 |
| P1 | 7 |
| P2 | 15 |
| P3 | 6 |
| P4 | 0 |
| **Total** | **28** |

### Report reconciliation correction

The first static audit's executive summary stated 20 findings, but its detailed section actually enumerated **23 distinct IDs**. The correct static-audit distribution is 5 P1, 13 P2, and 5 P3. Five locally verified runtime/build findings were then added, bringing the reconciled total to 28.

The Cloud runtime-verification report reconstructed the original IDs from a stale `project-audit-report.md`; that reconstructed ID mapping is invalid and is not used here. Only its environment observations that do not depend on ID mapping were retained.

## 2. Verification baseline

| Verification | Result | Evidence |
|---|---|---|
| Repository alignment | PASS | Local `main` and `origin/main` at `4217fe1`; clean working tree |
| PHP runtime | PASS | PHP 8.2.12 |
| Composer install | PASS with warning | Lockfile install succeeded; duplicate Flysystem class-resolution warnings observed |
| Laravel bootstrap | PASS | Laravel 11.47.0; `artisan about` succeeded |
| Route generation | PASS | 109 active routes; no duplicate names; no duplicate method/URI pairs |
| Route auth screen | PASS with note | No sensitive project/admin/message/ticket mutation route was observed without auth; Ignition mutation routes require production-hardening verification |
| Test suite | FAIL | 146 passed, 1 failed, 572 assertions |
| Failed test reproducibility | CONFIRMED | `deleted_active_profile_is_safely_recovered` fails independently |
| Clean migration | PASS | SQLite disposable DB: `migrate:fresh` succeeded |
| Rollback | PASS | Complete rollback succeeded |
| Re-migration | PASS | Complete re-migration succeeded |
| PHP dependency audit | FAIL | 38 advisories across 14 packages: 9 high, 24 medium, 4 low, 1 unspecified |
| Frontend production audit | FAIL | 38 advisories: 1 critical, 2 high, 35 moderate |
| Frontend build | PASS with warnings | Vite build completed in 1m 8s; Sass deprecations and unresolved font reference |
| Post-verification tree | PASS | `git status --short` remained clean |

## 3. Evidence levels

- **Runtime confirmed:** reproduced by a command, test, audit, migration, or build.
- **Static confirmed:** directly traceable deterministic behavior in current source.
- **Characterization required:** credible source-level defect/risk whose operational impact or race/load threshold still needs a targeted test.

## 4. Reconciled finding register

### P1 — Critical remediation queue

| ID | Finding | Evidence | Dependencies |
|---|---|---|---|
| ENG-AUTH-001 | Production migration provisions a known administrator account and default password | Static confirmed | None; deployed-environment credential containment is immediate |
| ENG-AUTH-002 | Deactivated users can authenticate and existing sessions are not universally rejected | Static confirmed; executable path not yet characterized | Coordinate with ENG-AUTH-003 and ENG-RUNTIME-005 |
| ENG-DATA-001 | Project deletion removes physical files before the database transaction commits | Static confirmed | Shared lifecycle with ENG-DATA-002 |
| ENG-DATA-002 | User deletion duplicates project cleanup and deletes files inside a DB transaction | Static confirmed | ENG-DATA-001; combine with ENG-DATA-003 |
| ENG-MATCH-001 | Matching relies on mutable display-name equality instead of canonical IDs | Static confirmed | Blocked by ENG-TAX-001 and production backfill analysis |
| ENG-RUNTIME-006 | PHP lockfile contains 38 known advisories across 14 packages | Runtime confirmed | Compatibility/update plan and full regression suite |
| ENG-RUNTIME-007 | Production frontend graph contains 1 critical, 2 high, and 35 moderate advisories | Runtime confirmed | Breaking-upgrade analysis for Swiper, Quill, Moment, CKEditor |

### P2 — High-priority core correctness

| ID | Finding | Evidence | Dependencies |
|---|---|---|---|
| ENG-AUTH-003 | Sanctum tokens lack an application expiration/revocation policy for disabled or reset accounts | Static confirmed; token characterization required | ENG-AUTH-002 |
| ENG-ADMIN-001 | Administrator name edits use `name` while persistence uses `first_name`/`last_name` | Static confirmed | None |
| ENG-DATA-003 | User deletion removes profiles before projects, causing ownership nulling/intermediate mutation | Static confirmed | ENG-DATA-002 |
| ENG-PROJECT-001 | Guest registration bypasses canonical project creation and can create incomplete projects | Static confirmed | Agree project draft invariant |
| ENG-PROJECT-002 | Project creation chooses the first employer profile instead of the active owned profile | Static confirmed | Coordinate with active-profile repair |
| ENG-PROJECT-003 | Project writes do not consistently enforce domain/subdomain/skill consistency | Static confirmed | ENG-TAX-001 |
| ENG-PROJECT-004 | Create and update use incompatible project-skill payload contracts and pivot semantics | Static confirmed | ENG-PROJECT-003 |
| ENG-TAX-001 | Skill taxonomy has competing singular and many-to-many subdomain authorities | Static confirmed | Foundational decision and production divergence audit |
| ENG-MATCH-002 | Matching materializes candidate IDs in PHP and returns a large `WHERE IN` | Characterization/load test required | Final implementation depends on ENG-MATCH-001 |
| ENG-REQ-001 | Collaboration-request transitions use race-prone check-then-update logic | Static confirmed; concurrency test required | None |
| ENG-MSG-001 | Conversation listing loads and groups complete message history in PHP | Static confirmed; representative-load measurement required | Coordinate with ENG-MSG-002 |
| ENG-MSG-002 | Message schema lacks composite indexes for conversation/unread access paths | Static confirmed; query-plan verification required | ENG-MSG-001 query design |
| ENG-TICKET-001 | Ticket and initial message are not created atomically | Static confirmed | None |
| ENG-RUNTIME-005 | Deleted active profile remains in session; existing regression test fails | Runtime confirmed | Coordinate with ENG-AUTH-002 and ENG-PROJECT-002 |
| ENG-RUNTIME-009 | Vite cannot resolve `hkgrotesk-bold.eot` at build time | Runtime/build confirmed; browser 404 check required | Asset-path correction |

### P3 — Scheduled debt reduction

| ID | Finding | Evidence | Dependencies |
|---|---|---|---|
| ENG-TAX-002 | Skill-suggestion approval loads and normalizes all skills of a type inside a lock | Static confirmed; scale test required | ENG-TAX-001 uniqueness key |
| ENG-TICKET-002 | Ticket message/close/reopen checks are race-prone | Static confirmed; concurrency test required | Can follow ENG-TICKET-001 |
| ENG-PERF-001 | Project creation loads and sorts duplicated complete taxonomy collections in PHP | Static confirmed; query/memory budget required | ENG-TAX-001 |
| ENG-DEAD-001 | Inactive legacy routes/controllers, previews, and deprecated profile code remain | Static confirmed | Remove only after generated-route/reference checks |
| ENG-TEST-001 | Regression coverage omits several core workflows and performance budgets | Confirmed by inventory; suite exists but is incomplete | Continuous across all fixes |
| ENG-RUNTIME-008 | Frontend build emits hundreds of Sass/Bootstrap deprecation warnings | Runtime/build confirmed | Frontend dependency modernization |

## 5. Negative findings and passed gates

These items are not backlog defects at the current baseline:

- The application does have a substantial automated test suite; a stale claim that no tests exist is rejected.
- The user-skill API points to an existing controller action and is protected by Sanctum; the stale broken-method claim is rejected.
- No route-name duplication was observed.
- No method/URI route duplication was observed.
- SQLite clean migration, full rollback, and re-migration completed successfully.
- The production frontend build completes successfully.

## 6. Prioritized remediation backlog

### Wave 0 — Immediate containment and characterization

1. **SEC-ADM-001 — Remove known bootstrap administrator risk**  
   Findings: ENG-AUTH-001.  
   Deliverable: corrective migration/provisioning command, deployment runbook, regression test, and explicit deployed-environment rotation/removal checklist.  
   Gate: no known bootstrap credential in source or any deployed environment.

2. **SEC-PHP-001 — Upgrade vulnerable PHP dependency graph**  
   Findings: ENG-RUNTIME-006.  
   Deliverable: smallest compatible dependency updates, advisory delta, test/migration/route verification.  
   Gate: zero high advisories; remaining advisories explicitly risk-accepted or removed.

3. **SEC-FE-001 — Upgrade vulnerable production frontend graph**  
   Findings: ENG-RUNTIME-007 and ENG-RUNTIME-008.  
   Deliverable: staged breaking-upgrade plan and verified replacements/upgrades for Swiper, Moment, Quill, CKEditor/lodash-es.  
   Gate: zero critical/high production advisories and successful Vite build/smoke test.

4. **AUTH-CORE-001 — Establish one enabled-account and active-profile invariant**  
   Findings: ENG-AUTH-002, ENG-AUTH-003, ENG-RUNTIME-005, ENG-PROJECT-002.  
   Deliverable: inactive login denial, request-time denial, token revocation, session invalidation, stale-profile cleanup, active-owned-profile project binding, tests.  
   Gate: full suite green; inactive web/Sanctum access rejected; deleted profile ID removed from session.

5. **DATA-LIFECYCLE-001 — Canonical deletion lifecycle**  
   Findings: ENG-DATA-001, ENG-DATA-002, ENG-DATA-003.  
   Deliverable: one idempotent deletion service; DB commit before retryable after-commit storage cleanup; correct ordering and failure tests.  
   Gate: injected SQL/storage failures cannot produce unrecoverable row/file divergence.

### Wave 1 — Foundational domain integrity

6. **TAX-AUTHORITY-001 — Choose and enforce canonical Skill/Subdomain authority**  
   Findings: ENG-TAX-001.  
   Deliverable: production divergence report, decision record, backfill, synchronized read/write model, constraints and tests.  
   Gate: one documented authoritative relationship with no divergent rows.

7. **REQUEST-SM-001 — Atomic collaboration-request state machine**  
   Findings: ENG-REQ-001.  
   Deliverable: centralized permitted transitions using row locks or compare-and-set; concurrency tests.

8. **TICKET-ATOMIC-001 — Atomic ticket lifecycle**  
   Findings: ENG-TICKET-001 and ENG-TICKET-002.  
   Deliverable: atomic ticket+first-message creation and serialized state transitions.

9. **ADMIN-CONTRACT-001 — Repair administrator name update contract**  
   Findings: ENG-ADMIN-001.  
   Deliverable: explicit first/last-name request and persistence contract with feature test.

### Wave 2 — Project and matching correctness

10. **PROJECT-CONTRACT-001 — Unify project creation/update invariants**  
    Findings: ENG-PROJECT-001, ENG-PROJECT-003, ENG-PROJECT-004.  
    Depends on: TAX-AUTHORITY-001 and AUTH-CORE-001.  
    Deliverable: shared canonical creation service, explicit draft state if required, reusable taxonomy validator, compatible skill-pivot contract.

11. **MATCH-CANONICAL-001 — Replace name-based matching with stable relations**  
    Findings: ENG-MATCH-001 and ENG-MATCH-002.  
    Depends on: TAX-AUTHORITY-001 and production backfill.  
    Deliverable: canonical-ID matching, SQL-side pagination/EXISTS strategy, correctness and query-budget tests.

### Wave 3 — Scale and cleanup

12. **MSG-SCALE-001 — SQL conversation summaries and indexes**  
    Findings: ENG-MSG-001 and ENG-MSG-002.

13. **TAX-APPROVAL-001 — Indexed normalized taxonomy approval**  
    Findings: ENG-TAX-002. Depends on TAX-AUTHORITY-001.

14. **PROJECT-PERF-001 — Bound project-form taxonomy loading**  
    Findings: ENG-PERF-001. Depends on TAX-AUTHORITY-001.

15. **ASSET-001 — Repair unresolved font build path**  
    Findings: ENG-RUNTIME-009.

16. **CLEANUP-001 — Remove confirmed legacy/dead surfaces**  
    Findings: ENG-DEAD-001. Requires route/reference verification before deletion.

17. **REGRESSION-GATES-001 — Close core coverage gaps**  
    Findings: ENG-TEST-001. Runs continuously across every work package, not as an end-only phase.

## 7. Multi-agent execution boundaries

Agents may run in parallel only when their write sets are disjoint. Every task uses its own branch/PR. Shared documentation and generic test helper files have a single owner per wave.

### Safe first parallel wave after backlog approval

| Agent package | Exclusive primary write ownership | Must not edit |
|---|---|---|
| SEC-ADM-001 | Corrective admin provisioning migration/command and dedicated tests | Auth middleware, package manifests, deletion actions |
| SEC-PHP-001 | `composer.json`, `composer.lock`, PHP dependency compatibility notes | `package*.json`, auth/profile source, deletion source |
| SEC-FE-001 | `package.json`, `package-lock.json`, dependency-specific frontend imports/styles | Composer files, auth/profile source, deletion source |
| DATA-LIFECYCLE-001 | Project/user deletion actions, new cleanup service/job, dedicated deletion tests | Package manifests, auth/profile source |

### Sequential package

`AUTH-CORE-001` must remain one coordinated package because inactive-account enforcement, token revocation, session cleanup, profile context, and active employer ownership overlap in authentication/profile files and tests.

`TAX-AUTHORITY-001` is a schema foundation. No project-contract, matching, taxonomy-performance, or taxonomy-approval agent may modify taxonomy models/migrations until it is merged.

## 8. Release gates before feature work

- No known bootstrap administrator credential remains.
- No critical/high production dependency advisory remains without explicit documented acceptance.
- Full test suite is green, including the deleted-active-profile regression.
- Inactive users are rejected consistently across web sessions and Sanctum.
- File deletion cannot diverge irreversibly from database state.
- One Skill/Subdomain authority is documented, backfilled, and constrained.
- Project create/update/guest paths enforce the same invariants.
- Matching uses canonical relations and meets query/memory budgets.
- Ticket and request transitions have atomic semantics.
- Conversation listing is paginated and database-aggregated.
- Frontend build completes without unresolved runtime asset references.
- Clean install, route generation, migration/rollback, tests, dependency audits, and frontend build run as CI gates.

## 9. Immediate next decision

Do not start feature/UI work. Approve the reconciled backlog, then begin Wave 0 with separate file ownership. Operational containment for the known administrator credential takes precedence over all code refactoring.
