# Engineering Marketplace

## AI Project Rules

> **Status:** Permanent operating manual  
> **Applies to:** Every human-assisted and AI-assisted development session  
> **Authority:** These rules govern implementation unless an explicit, task-specific human instruction supersedes them  
> **Related document:** [`PROJECT_MASTER_PLAN.md`](PROJECT_MASTER_PLAN.md)

---

# 1. Project Philosophy

The Engineering Marketplace is a production platform serving employers, engineers, specialists, administrators, and future financial workflows. Every change must strengthen trust in the platform.

Development decisions must protect five priorities:

1. **Production continuity:** Existing working behavior must remain reliable.
2. **User trust:** Private data, project information, communications, and future financial records must be protected.
3. **Correctness:** Business rules and ownership boundaries must be explicit and testable.
4. **Maintainability:** The codebase must become easier to understand with each change.
5. **Product quality:** Performance, accessibility, Persian/RTL usability, and clear workflows are engineering responsibilities.

The preferred path is incremental improvement. Large rewrites, speculative abstractions, and unrelated cleanup create unnecessary production risk and are not acceptable without explicit approval.

---

# 2. Development Principles

| Principle | Required behavior |
|---|---|
| Analyze first | Inspect routes, call paths, dependencies, tests, data ownership, and side effects before modifying files. |
| Respect scope | Never change code outside the requested scope. Unrelated defects may be reported but not silently fixed. |
| Preserve compatibility | Always preserve backward compatibility unless explicitly instructed otherwise. |
| Make small changes | Prefer focused, reversible patches over broad changes. |
| Refactor, do not rewrite | Prefer controlled refactoring over replacement of working systems. |
| Keep behavior explicit | Business rules, authorization, validation, and error behavior must be visible and testable. |
| Avoid duplication | Shared business logic must have one authoritative implementation. |
| Follow SOLID | Apply SOLID principles pragmatically without introducing unnecessary layers. |
| Use framework conventions | Prefer Laravel conventions and supported framework features over custom mechanisms. |
| Design rollback first | Every production-affecting change must have a practical rollback path. |
| Verify proportionally | Testing depth must reflect the security, data, and operational risk of the change. |
| Human authority | Final product, architectural, deployment, and Git decisions belong to a human maintainer. |

**Always analyze before modifying.**

## Scope Control

- Read-only investigation may extend far enough to understand the requested change safely.
- Writes must remain strictly within the approved task scope.
- Do not rename, move, format, or reorganize unrelated files.
- Do not fix incidental style issues in untouched code.
- Do not introduce new dependencies unless the task requires them and a human approves the tradeoff.
- If safe implementation requires broader work, stop and request approval before expanding scope.

## Backward Compatibility

Compatibility includes:

- Existing routes and route names
- Request and response formats
- Authentication and session behavior
- Authorization behavior for legitimate users
- Database schema and existing records
- Events, jobs, notifications, and integrations
- Blade variables and component interfaces
- Public APIs and frontend contracts
- Deployment and environment expectations

Breaking changes require explicit instruction, a migration strategy, tests, release notes, and rollback steps.

---

# 3. Coding Standards

## PHP Standards

- Follow **PSR-12**.
- Follow the repository's Laravel Pint rules when available.
- Use clear, domain-relevant names.
- Prefer strict comparisons where type coercion is not intended.
- Use type declarations and return types when they improve correctness and match surrounding code.
- Avoid hidden side effects and mutable global state.
- Keep methods focused on one responsibility.
- Prefer early returns over deeply nested conditionals.
- Do not suppress errors unless the failure mode is explicitly handled and documented.
- Do not leave `dd()`, `dump()`, `var_dump()`, temporary logs, commented debug code, or test credentials.
- Comments must explain intent or constraints, not restate obvious code.

## General Design

- Follow SOLID principles pragmatically.
- Prefer composition over inheritance when behavior does not represent a true subtype.
- Do not introduce an abstraction until it removes real duplication, isolates an external system, or clarifies a stable business concept.
- Avoid duplicated logic across controllers, actions, jobs, commands, and views.
- Keep domain rules out of Blade templates.
- Use constants, enums, or validated value objects for stable finite states where appropriate.
- Preserve the codebase's established naming and directory conventions unless an approved architecture decision changes them.

## JavaScript and CSS

- Use the existing Vite pipeline.
- Avoid adding global variables.
- Prefer reusable modules over duplicated inline scripts.
- Preserve RTL behavior.
- Do not add a frontend library when existing dependencies or native browser capabilities are sufficient.
- Keep selectors and component styles scoped to prevent regressions.
- Treat browser-side validation as UX enhancement, never as a replacement for server validation.

---

# 4. Laravel Best Practices

## Controllers

- Keep controllers thin.
- Controllers should coordinate requests and responses, not contain complex business logic.
- Use FormRequests for non-trivial validation and request authorization.
- Use policies for model-level authorization.
- Use Services or Actions where appropriate for reusable or multi-step business operations.
- Do not create service classes that merely wrap a single obvious model call.
- Do not trust route model binding as authorization.
- Authorize before loading sensitive relationships or performing side effects.

## Models and Queries

- Define and use Eloquent relationships consistently.
- Use scopes for reusable query constraints.
- Prevent mass assignment by maintaining deliberate `$fillable` or `$guarded` rules.
- Eager-load known relationships to prevent N+1 queries.
- Avoid retrieving full collections when a count, existence check, cursor, or pagination is sufficient.
- Keep expensive query logic measurable and covered by tests.
- Use transactions for operations that must succeed or fail as one unit.
- Do not place request-specific or presentation-specific behavior in models.

## Services and Actions

Use a Service or Action when one or more of the following apply:

- A workflow spans multiple models.
- The logic is reused by multiple entry points.
- An external provider must be isolated.
- The operation requires a transaction.
- The operation has meaningful domain semantics.
- The operation needs focused unit testing.

Services and Actions must not bypass policies, validation, transaction boundaries, or audit requirements.

## Framework Features

- Use Laravel authentication and hashing facilities.
- Use policies and Gates for authorization.
- Use FormRequests and validation rules for input validation.
- Use API Resources for stable API serialization where appropriate.
- Use jobs for slow, retryable, or asynchronous work.
- Use events when multiple independent reactions are required; do not use events to hide essential control flow.
- Use notifications for multi-channel user communication.
- Use cache with explicit keys, TTLs, and invalidation rules.
- Use Laravel filesystem abstractions; select public or private disks deliberately.
- Use configuration files and environment variables appropriately. Never call `env()` outside configuration files.

---

# 5. UI/UX Standards

## Core Standards

- Preserve Persian and RTL presentation throughout all affected workflows.
- Design mobile-first and verify common viewport sizes.
- Maintain consistent navigation, spacing, typography, colors, buttons, forms, and feedback states.
- Every asynchronous action must have loading, success, validation-error, authorization-error, and failure behavior.
- Forms must preserve user input after recoverable errors.
- Destructive actions require clear confirmation and must communicate consequences.
- Empty states must explain what happened and the next useful action.
- Error messages must be understandable without exposing internal details.
- Avoid layout shifts and unnecessary blocking assets.

## Accessibility

- Use semantic HTML.
- Associate labels with inputs.
- Ensure keyboard access and visible focus states.
- Provide accessible names for icon-only controls.
- Maintain sufficient color contrast.
- Do not use color as the only indicator of state.
- Use ARIA only where native semantics are insufficient.
- Respect reduced-motion preferences where animation is introduced.

## User Safety

- Do not reveal whether protected resources exist when a `404` is safer than a `403`.
- Do not expose internal IDs unless required by the user workflow.
- Do not display secrets, private contact information, or administrative fields unintentionally.
- Escape output by default. Render sanitized HTML only when the feature explicitly requires rich text.

---

# 6. Database Rules

## General Rules

- Never modify production data directly.
- Never run destructive database commands without explicit human approval.
- Never infer permission to run migrations in production.
- Every schema change must use a reviewed migration.
- Database changes must be backward-compatible by default.
- Prefer additive changes: nullable columns, new tables, new indexes, or dual-read/dual-write transitions.
- Separate data backfills from schema migrations when volume or runtime is uncertain.
- Backfills must be restartable, idempotent, observable, and safely throttled.
- Do not delete columns or tables until all code paths have stopped using them and the deprecation period is complete.

## Migration Requirements

Every proposed migration must document:

| Requirement | Description |
|---|---|
| Forward behavior | What the migration changes and how old code behaves during rollout |
| Backward compatibility | Why the previous application version remains safe during deployment |
| Locking risk | Expected table locks, duration, and production-volume considerations |
| Data impact | Whether existing rows are read, rewritten, backfilled, or left unchanged |
| Rollback | What `down()` can safely reverse and what requires an operational procedure |
| Verification | Queries or application checks that confirm success without exposing data |

## Data Integrity

- Use foreign keys where operationally appropriate.
- Use unique constraints to enforce true invariants.
- Use indexes based on measured query patterns.
- Do not rely solely on application checks for concurrency-sensitive uniqueness.
- Use transactions and row locking only when required and with known contention behavior.
- Store money using precise decimal or integer minor-unit representations; never use floating point.
- Future wallet functionality must use an auditable ledger, not a mutable balance as the sole source of truth.

---

# 7. Security Rules

## Authorization and Ownership

- Every endpoint that accepts a model or identifier must answer: **Why may this user access this resource?**
- Authentication is not authorization.
- Route model binding is not authorization.
- Validate ownership before reading sensitive relationships, generating downloads, or performing mutations.
- Use policies for model-level rules and middleware for route-level concerns.
- Apply least privilege to users, administrators, APIs, jobs, storage, and deployments.
- Administrative access must be explicit and auditable.
- Impersonation, if introduced, must be deliberate, visible, time-limited, and logged.

## Input and Output

- Validate all untrusted input on the server.
- Use allowlists for finite states, MIME types, sortable columns, filters, and external URLs.
- Escape Blade output by default.
- Sanitize rich HTML using an approved server-side sanitizer.
- Never construct SQL, shell commands, filesystem paths, or HTML from unchecked input.
- Protect against mass assignment.
- Return only fields required by the client.
- Avoid user and resource enumeration where possible.

## Authentication and Sessions

- Use Laravel's authentication, password hashing, session regeneration, signed URLs, and rate limiting.
- Require appropriate authentication middleware for all state-changing APIs.
- Revalidate role/profile state when authorization depends on session values.
- Protect sensitive actions with password confirmation or stronger verification when appropriate.
- Do not weaken CSRF protection without a documented, tested reason.

## Files

- Store private files on private storage.
- Deliver protected files through authorized application endpoints.
- Use server-generated filenames.
- Validate size, extension, MIME type, and business purpose.
- Add safe response headers such as `X-Content-Type-Options: nosniff`.
- Prefer attachment disposition unless inline rendering is explicitly approved.
- Consider malware scanning for untrusted documents.

## Secrets and Personal Data

- Never expose `.env` values.
- Never commit credentials, tokens, API keys, private keys, production identifiers, or personal data.
- Never print secrets in commands, logs, reports, screenshots, tests, or error messages.
- Redact sensitive values from diagnostic output.
- Use fake, synthetic data in tests and documentation.
- Collect, retain, and display only the personal data required for the feature.

## Dependency and External-Service Security

- Review dependency advisories before release.
- Do not update dependencies as an incidental part of another task.
- Pin and review CI actions and external integrations.
- Verify webhook signatures and implement idempotency.
- Apply timeouts, retry policies, and failure handling to external calls.
- Never trust AI, payment, email, storage, or search provider responses without validation.

---

# 8. Performance Rules

- Measure before optimizing.
- Preserve correctness and authorization while improving speed.
- Establish a baseline for query count, response time, memory, and payload size.
- Use pagination for potentially unbounded collections.
- Prefer `exists()`, `count()`, selected columns, chunking, cursors, and database aggregation over loading unnecessary models.
- Prevent N+1 queries through deliberate eager loading.
- Cache only when ownership, invalidation, TTL, and failure behavior are understood.
- Never cache private data under keys shared across users.
- Invalidate affected keys after writes; do not use broad cache flushes.
- Queue slow, retryable work such as email, notifications, exports, and external synchronization.
- Keep queue jobs idempotent when retries are possible.
- Add indexes only after examining real query patterns and migration risk.
- Avoid loading large theme assets or JavaScript libraries on pages that do not use them.
- Performance improvements must include before/after evidence in the task report.

---

# 9. Refactoring Rules

- Prefer refactoring over rewriting.
- Refactoring must preserve externally observable behavior unless the task explicitly changes behavior.
- Add characterization tests before changing poorly understood code.
- Refactor one concern at a time.
- Do not combine refactoring with dependency upgrades, schema changes, or unrelated feature work.
- Do not rename public routes, API fields, events, configuration keys, or database columns incidentally.
- Remove duplication only after identifying the canonical behavior.
- Do not create speculative Services, repositories, interfaces, traits, or base classes.
- Preserve Git history through focused moves and minimal formatting noise.
- Deprecated code must have documented consumers, replacement, warning period, removal condition, and rollback plan.
- Delete code only after confirming it is inactive through route, reference, build, runtime, and stakeholder checks as appropriate.

---

# 10. Git Workflow

## Branch and Commit Rules

- Use a dedicated branch for each task or tightly related task group.
- Keep commits small, cohesive, and reversible.
- Write commit messages that describe the intent and affected area.
- Do not mix formatting-only changes with functional changes.
- Review `git diff` and `git status` before proposing completion.
- Preserve existing user changes in a dirty working tree.
- Never use destructive commands such as `git reset --hard` or forced checkout without explicit approval.

## Human Approval Rules

- **Never commit automatically.**
- **Never push automatically.**
- **Never open or merge a pull request automatically unless explicitly instructed.**
- **Always wait for final human approval before generating git commands.**
- When approval is given, generate only the minimum commands required for the approved operation.
- Never force-push unless a human explicitly authorizes that exact operation and understands its impact.
- Do not alter published history by default.

## Review Expectations

Before requesting approval, provide:

- A concise outcome summary
- Files changed
- Tests performed and their results
- Known limitations or untested areas
- Security and database impact
- Rollback instructions
- The path to the task report

---

# 11. Sprint Workflow

Every task should follow this sequence:

| Stage | Required activity |
|---|---|
| 1. Intake | Confirm task ID, objective, boundaries, constraints, and acceptance criteria. |
| 2. Investigation | Inspect relevant code, routes, tests, schema, dependencies, and documentation without modifying files. |
| 3. Risk analysis | Identify ownership, security, compatibility, database, performance, UX, and deployment risks. |
| 4. Plan | Define small implementation steps, tests, dependencies, and rollback. |
| 5. Approval | Obtain human approval when scope, behavior, data, dependencies, or architecture would materially change. |
| 6. Implementation | Make the smallest safe change inside scope. |
| 7. Verification | Run targeted tests first, then broader checks proportional to risk. |
| 8. Review | Inspect the final diff for scope creep, secrets, debug artifacts, and unintended formatting. |
| 9. Report | Create the required Markdown task report in `docs/reports/`. |
| 10. Handoff | Summarize the outcome and wait for human approval. Do not commit or push. |

## Sprint Discipline

- Each task must map to an approved sprint objective or an explicitly approved urgent issue.
- New findings go into the backlog unless they block safe completion.
- A blocker must be reported with evidence and a clear decision request.
- Scope changes require explicit approval.
- Security regressions, data-loss risks, and production-breaking failures stop the task immediately.
- Incomplete work must not be presented as complete.

---

# 12. Definition of Done

A task is complete only when every applicable item below is satisfied:

## Scope and Behavior

- [ ] The approved objective and acceptance criteria are met.
- [ ] No unrelated code was changed.
- [ ] Backward compatibility is preserved or the approved breaking-change plan is complete.
- [ ] Error and edge-case behavior is defined.

## Code Quality

- [ ] Code follows PSR-12, Laravel conventions, and applicable repository style.
- [ ] Controllers remain thin.
- [ ] Business logic is placed appropriately.
- [ ] No duplicated logic or speculative abstraction was introduced.
- [ ] No debug artifacts, temporary code, or secrets remain.

## Security and Data

- [ ] Authentication, authorization, ownership, validation, and output handling were reviewed.
- [ ] Database impact is understood and backward-compatible.
- [ ] No production data was edited directly.
- [ ] Private data and files remain protected.

## Verification

- [ ] New or changed behavior has automated tests where feasible.
- [ ] Existing relevant tests pass.
- [ ] Static analysis, syntax, style, build, and route checks were run as applicable.
- [ ] Manual verification was performed where automation is insufficient.
- [ ] Untested areas are disclosed.

## Operations and Documentation

- [ ] Deployment impact is understood.
- [ ] Rollback steps are practical and documented.
- [ ] Relevant documentation is updated.
- [ ] A Markdown report exists in `docs/reports/` with all required sections.
- [ ] Final diff and working-tree status were reviewed.
- [ ] Human approval is pending or recorded; no automatic commit or push occurred.

---

# 13. Reporting Rules

## Mandatory Task Report

After every completed task, create a Markdown report inside:

```text
docs/reports/
```

Recommended filename format:

```text
TASK-001-ai-project-rules.md
```

Use the exact task ID when one exists. Filenames must be lowercase after the task ID, use hyphens, and avoid spaces.

Every report must include these sections:

1. **Objective**
2. **Scope**
3. **Files Changed**
4. **Technical Decisions**
5. **Risks**
6. **Tests Performed**
7. **Future Recommendations**

## Report Quality

- State facts, not assumptions.
- Distinguish completed work from recommendations.
- Include commands or verification methods without exposing secrets.
- Report failed, skipped, unavailable, and successful tests accurately.
- Document database, deployment, security, and compatibility impact even when the impact is “none.”
- Link to relevant files using repository-relative paths.
- Do not copy sensitive logs or environment values into reports.
- Do not claim completion while required work remains.

## Suggested Report Template

```markdown
# TASK-000 — Task Title

## Objective

## Scope

## Files Changed

## Technical Decisions

## Risks

## Tests Performed

## Future Recommendations
```

---

# 14. Documentation Rules

- Documentation is part of the product and must be reviewed like code.
- Keep `docs/PROJECT_MASTER_PLAN.md` as the product and engineering source of truth.
- Keep this document as the permanent operating manual for development sessions.
- Update documentation in the same task when behavior, architecture, operations, APIs, or workflows change.
- Use professional Markdown with clear headings, tables, lists, and code blocks.
- Use repository-relative links where possible.
- Record durable architectural decisions as ADRs or in the Architecture Notes section of the master plan.
- Keep setup, test, deployment, rollback, and incident instructions executable and current.
- Do not document secret values, private infrastructure details, production personal data, or credentials.
- Do not silently rewrite historical task reports. Add corrections with context when necessary.
- Mark planned, experimental, deprecated, and production behavior clearly.
- Avoid duplicated documentation; link to the authoritative source instead.

---

# 15. AI Assistant Rules

## Before Work

- Read the user's full request and identify explicit prohibitions.
- Read applicable project rule files and relevant documentation.
- Inspect the current working tree before editing.
- Analyze relevant code paths before modifying anything.
- Confirm which files are in scope.
- Identify whether the request authorizes code, database, configuration, dependency, deployment, or external-system changes.
- Make reasonable, low-risk assumptions only when they do not expand scope.

## During Work

- Never change code outside the requested scope.
- Preserve current user changes and unrelated worktree modifications.
- Prefer small, reversible patches.
- Use existing patterns unless they are the subject of the task.
- Communicate material findings, assumptions, and blockers clearly.
- Do not expose `.env` values or other secrets.
- Do not modify production data or external systems without explicit authority.
- Do not install or update dependencies unless explicitly approved.
- Do not generate migrations unless explicitly requested and approved.
- Do not refactor unrelated code.
- Stop and request direction when safe completion requires a materially broader change.

## Verification and Handoff

- Run the narrowest relevant checks first.
- Expand verification based on risk.
- Review the final diff and confirm only intended files changed.
- Create the required report in `docs/reports/` after completed tasks.
- Explain what changed, what was tested, known risks, and rollback.
- Never commit automatically.
- Never push automatically.
- Always wait for final human approval before generating Git commands.
- If the user requests only analysis, do not implement fixes.
- If the user requests documentation only, do not modify application code, configuration, dependencies, or database files.

## Accuracy

- Do not invent files, routes, tests, package versions, test results, or production behavior.
- Clearly label inferences and unverified assumptions.
- Correct earlier findings when new evidence disproves them.
- Never hide tool failures or skipped verification.
- Do not state that a task is complete until all requested deliverables exist and have been reviewed.

---

# 16. Forbidden Actions

The following actions are forbidden unless a human gives explicit, task-specific approval and the action is legally, technically, and operationally safe:

| Category | Forbidden action |
|---|---|
| Scope | Changing unrelated code, formatting unrelated files, or fixing unrequested issues silently |
| Production | Breaking current production behavior or experimenting directly in production |
| Data | Editing production data manually, destructive data cleanup, or unreviewed backfills |
| Database | Dropping tables/columns, destructive migrations, or running production migrations automatically |
| Secrets | Reading out, printing, committing, documenting, or transmitting `.env` values and credentials |
| Dependencies | Installing, removing, or upgrading dependencies without approval |
| Configuration | Changing production configuration, secrets, domains, mail, storage, queue, cache, or payment settings without approval |
| Authorization | Removing or weakening ownership, authentication, CSRF, signature, rate-limit, or admin checks for convenience |
| Files | Publishing private uploads or accepting unrestricted executable/active file content |
| Git | Automatic commits, automatic pushes, force-pushes, destructive resets, or rewriting shared history |
| Deployment | Automatic production deployment, untested deployment changes, or deployment without rollback |
| Testing | Pointing automated tests at production services or production databases |
| Logging | Logging passwords, tokens, private message bodies, payment data, or sensitive personal data |
| Architecture | Large rewrites or broad refactors without an approved plan and characterization tests |
| Reporting | Claiming tests passed when they were not run, hiding failures, or reporting incomplete work as complete |
| External systems | Sending messages, creating tickets, charging payments, or mutating third-party systems without explicit authority |

When a forbidden action appears necessary, the AI assistant must stop, explain why, present safer alternatives, and wait for human approval.

---

# Governance

These rules are intentionally conservative because the project is a production marketplace. They may evolve through explicit human review. Changes to this manual must be documented, reviewed for consistency with `PROJECT_MASTER_PLAN.md`, and recorded in a task report.

When instructions conflict, apply this priority order:

1. Applicable law, security, privacy, and platform safety requirements
2. Explicit current human instructions
3. Approved project architecture and sprint scope
4. This operating manual
5. Existing local conventions

Uncertainty must be surfaced, not silently resolved through risky assumptions.
