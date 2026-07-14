# Engineering Marketplace

## Master Development Plan

> **Document purpose:** This document is the single source of truth for the product vision, engineering direction, delivery roadmap, known risks, and release readiness of the Engineering Marketplace. It must be updated whenever scope, architecture, priorities, or release status changes.

---

# Project Overview

The Engineering Marketplace is a specialized freelancing platform that connects employers with engineers and technical specialists. Employers can publish engineering projects, define required domains, processes, and skills, review collaboration requests, communicate with specialists, and manage project-related support. Engineers can create specialist profiles, register their skills and experience, discover matching projects, submit collaboration requests, and communicate with employers.

The product is designed for the Iranian market, with Persian-language workflows, Jalali date support, local user expectations, and domain-specific engineering classification. Its strategic goal is to become the best and most trusted engineering project marketplace in Iran: a platform where organizations can find qualified engineering talent efficiently and engineers can build credible careers through real project outcomes.

Success depends on four foundations:

1. Trust, security, and reliable project delivery.
2. High-quality matching between project requirements and engineering expertise.
3. A clear, modern, and accessible Persian user experience.
4. Sustainable marketplace economics supported by secure payments and transparent reputation.

---

# Vision

The long-term vision is to build Iran's most complete digital marketplace for engineering work. The platform should support the full lifecycle of an engineering engagement: discovery, qualification, matching, agreement, communication, delivery, payment, reputation, and repeat collaboration.

The platform should eventually provide:

- **Employer Dashboard** for projects, applicants, contracts, payments, and hiring analytics.
- **Engineer Dashboard** for opportunities, proposals, active work, earnings, and professional growth.
- **Smart Project Matching** based on domains, skills, processes, experience, availability, location, and past success.
- **Portfolio** pages that demonstrate verified engineering capabilities and completed work.
- **Messaging** with project context, attachments, moderation, and conversation controls.
- **Notifications** across in-app, email, SMS, and optional push channels.
- **Wallet** functionality for balances, transactions, withdrawals, refunds, and account statements.
- **Secure Payments** with local payment gateways, escrow-style controls, milestones, and dispute handling.
- **Ratings & Reviews** based on completed and verified engagements.
- **Ticket System** for customer support and operational issue resolution.
- **Admin Panel** for marketplace operations, moderation, finance, reporting, and configuration.
- **Search Engine** for projects, engineers, skills, companies, and engineering domains.
- **Mobile API** supporting first-party Android, iOS, or cross-platform applications.
- **AI-powered recommendations** for project matching, candidate ranking, profile improvement, and marketplace insights.

The platform should evolve incrementally. Security, authorization, data integrity, and operational reliability must be established before financial and high-scale marketplace functionality is introduced.

---

# Current Tech Stack

## Backend

| Technology | Current state |
|---|---|
| Laravel | Laravel Framework 11.47.0; project constraint `^11.0` |
| PHP | PHP `^8.2`; local audited runtime 8.2.12 |
| Database | Relational database through Eloquent and Laravel migrations; MySQL is the configured default, with SQLite, PostgreSQL, and SQL Server connections available in Laravel configuration |
| Authentication | Laravel UI session authentication; Laravel Sanctum installed but not applied to active API routes |
| Authorization | Custom middleware and FormRequest authorization; Spatie Permission installed; no application policy classes currently registered |
| Dates/localization | Morilog Jalali plus Persian and multilingual translation resources |

## Frontend

| Technology | Current state |
|---|---|
| Rendering | Server-rendered Blade templates |
| Theme | Velzon 4.3.0 administration/UI theme |
| CSS framework | Bootstrap 5.3.3 with RTL build support |
| Asset pipeline | Vite 5 with `laravel-vite-plugin`, Sass, PostCSS, and RTL CSS tooling |
| JavaScript | ES modules plus page-level and inline scripts |
| UI libraries | CKEditor, Quill, Choices, Flatpickr, SweetAlert2, FilePond, Dropzone, charts, maps, tables, calendars, sliders, and other Velzon dependencies |

## Composer Packages

### Production

- `guzzlehttp/guzzle` `^7.2`
- `laravel/framework` `^11.0`
- `laravel/sanctum` `^4.0`
- `laravel/tinker` `^2.9`
- `laravel/ui` `^4.2`
- `morilog/jalali` `3.*`
- `spatie/laravel-permission` `^6.23`

### Development

- `fakerphp/faker` `^1.23`
- `laravel/pint` `^1.13`
- `laravel/sail` `^1.26`
- `mockery/mockery` `^1.6`
- `nunomaduro/collision` `^8.0`
- `phpunit/phpunit` `^10.5`
- `spatie/laravel-ignition` `^2.4`

## npm Packages

### Runtime

`@ckeditor/ckeditor5-build-classic`, `@openrouter/sdk`, `@simonwep/pickr`, `@tarekraafat/autocomplete.js`, `aos`, `apexcharts`, `bootstrap`, `card`, `chart.js`, `choices.js`, `cleave.js`, `dom-autoscroller`, `dragula`, `dropzone`, `echarts`, `feather-icons`, `fg-emoji-picker`, `filepond`, FilePond plugins, `flatpickr`, `fullcalendar`, `glightbox`, `gmaps`, `gridjs`, `isotope-layout`, `jsvectormap`, `leaflet`, `list.js`, `list.pagination.js`, `masonry-layout`, `moment`, `multi.js`, `node-waves`, `nouislider`, `particles.js`, `prismjs`, `quill`, `rater-js`, `shepherd.js`, `simplebar`, `sortablejs`, `sweetalert2`, `swiper`, `toastify-js`, and `wnumb`.

### Development

`@popperjs/core`, `axios`, `fs`, `fs-extra`, `laravel-vite-plugin`, `lodash`, `popper.js`, `postcss`, `postcss-import`, `resolve-url-loader`, `rimraf`, `rtlcss`, `sass`, `sass-loader`, and `vite`.

---

# Current Project Status

The project has a functional marketplace foundation. It includes authentication, employer and specialist profiles, project management, skill classification, project matching, collaboration requests, direct messaging, support tickets, and a custom administration panel. The audited application exposes 94 active routes and contains a substantial Blade/Velzon frontend.

The codebase is usable but not yet ready for aggressive feature expansion. Security boundaries, test coverage, deployment reproducibility, dependency health, and performance hot spots must be addressed first.

## Strengths

- Clear employer and specialist marketplace concepts.
- Functional project, matching, collaboration request, message, and ticket workflows.
- Laravel 11 and PHP 8.2 foundation.
- Action classes separate many business mutations from controllers.
- FormRequests provide structured validation and some ownership authorization.
- Eloquent relationships model the domain comprehensively.
- UUID identifiers are used throughout core marketplace entities.
- Database schema includes foreign keys, cascades, unique constraints, and several useful indexes.
- Multi-table project writes use transactions.
- Web routes retain CSRF protection.
- Admin routes are protected by authentication and custom admin middleware.
- Project update, employer request management, profile update, and ticket operations contain ownership checks.
- Persian localization and Jalali date support are established.
- Vite, Sass, Bootstrap, and RTL tooling are available.

## Weaknesses

- No visible automated feature or authorization test suite.
- No centralized model policies; authorization logic is scattered.
- A matched-project detail endpoint does not verify that the project matches the specialist.
- Messaging permits arbitrary recipients and unrelated project associations.
- Project attachments are stored on the public disk and linked directly.
- Upload validation restricts size but not approved MIME types.
- The user-skill API route is broken and lacks a safe authentication design.
- Sanctum is installed but not used by active API routes.
- Composer audit reported 24 advisories affecting 14 locked packages at audit time.
- Deployment over FTPS is non-atomic and does not reproducibly build dependencies or Vite assets.
- `public/build` is excluded while the deployment workflow does not build it.
- There is no documented health check or rollback mechanism.
- Public landing statistics and some lookup data are not efficiently cached.
- Conversation listing loads and groups all participating messages in application memory.

## Technical Debt

- Overlapping authorization concepts: `is_admin`, `role`, user profiles, `active_role`, and Spatie Permission.
- Legacy Worker controllers and inactive worker/employer/specialist route files overlap active unified routes.
- Duplicate `lang` and `resources/lang` structures.
- Large amounts of Velzon demo JavaScript, JSON, fonts, images, and potentially unused npm packages.
- Large inline CSS and JavaScript blocks in Blade templates.
- Broad Laravel Pint formatting differences.
- Inconsistent method types, return types, imports, and controller conventions.
- No registered policy classes.
- Empty or insufficient project-level setup and operational documentation.
- Public cache-clearing code remains in an inactive route file.
- Subdomain lookup cache is not visibly invalidated after administrative changes.
- Potential mismatch in project-skill pivot synchronization during updates.
- Queue, broadcasting, events, and scheduling infrastructure are largely unused.
- Deployment and export artifact directories are insufficiently documented.

---

# Development Principles

The following rules apply to every change unless an approved architectural decision explicitly supersedes them:

1. **Never break production.** Preserve current behavior unless a change is explicitly approved and covered by tests.
2. **Every change must be reversible.** Code, configuration, deployment, and schema changes require a practical rollback procedure.
3. **Prefer small, focused changes.** Avoid combining security, refactoring, dependency, schema, and product changes in one pull request.
4. **Use small commits with clear intent.** Each commit should be reviewable, testable, and safe to revert independently.
5. **Test before merge.** New behavior, bug fixes, and authorization boundaries require automated tests.
6. **Protect production data.** Never edit production data manually; use reviewed, idempotent, observable application processes.
7. **Keep migrations backward-compatible.** Prefer additive changes and phased rollouts. Destructive schema changes require a separate deprecation process.
8. **Never expose secrets.** Environment values, credentials, tokens, personal data, and private files must not enter source control, logs, documentation, or test fixtures.
9. **No duplicated business logic.** Shared rules belong in policies, actions, services, validation rules, scopes, or other appropriate domain abstractions.
10. **Prefer Laravel best practices.** Use FormRequests, policies, resource classes, jobs, events, Eloquent relationships, transactions, and framework security controls where appropriate.
11. **Security first.** Authentication, authorization, validation, data isolation, rate limiting, and safe file handling are release requirements.
12. **Performance second.** Measure before optimizing, preserve correctness, and validate improvements with realistic data.
13. **UX always matters.** Error states, accessibility, Persian/RTL presentation, mobile behavior, and perceived performance are part of engineering quality.
14. **Make ownership explicit.** Every endpoint that reads or mutates a bound model must demonstrate why the current user may do so.
15. **Use least privilege.** Administrative, user, API, filesystem, and deployment access should be no broader than required.
16. **Observe before and after.** Important changes require logs, metrics, health checks, or other safe verification signals.
17. **Document decisions.** Significant tradeoffs and architectural choices belong in this document or a linked Architecture Decision Record.
18. **Do not hide failures.** Errors must be actionable for developers without leaking sensitive details to users.
19. **Review generated and third-party assets.** Keep only dependencies and theme components that the product actually uses.
20. **Definition of done includes rollback.** A task is incomplete until its tests, documentation, operational impact, and rollback steps are known.

---

# Sprint Roadmap

## Sprint 1 — Security & Stability

### Objective

Establish reliable authorization boundaries, safe file/API behavior, and a regression-test foundation without disrupting active production workflows.

### Scope

- Add characterization and authorization tests.
- Protect matched-project details from IDOR access.
- Restrict collaboration requests to accessible projects.
- Define and enforce messaging participant/project rules.
- Correct and authenticate the user-skill API.
- Introduce policies incrementally while retaining proven checks.
- Design private project attachment delivery and restrict upload types.
- Harden active-role and admin authorization.
- Add focused rate limits to abuse-prone endpoints.

### Expected Deliverables

- Repeatable feature/authorization test suite.
- Documented access-control matrix.
- Correctly authenticated state-changing API route.
- Project, request, message, ticket, and profile policy foundation.
- Protected attachment download design and implementation plan.
- No known critical cross-user access path in audited features.

### Risks

- Legitimate legacy conversations or project workflows may be blocked by stricter rules.
- Session and Sanctum authentication behavior may differ between browser and API clients.
- Existing public attachments require a backward-compatible transition.

### Estimated Complexity

**High** — security changes are narrow in code size but require comprehensive tests and careful compatibility decisions.

## Sprint 2 — Performance Optimization

### Objective

Remove known query and memory hot spots while preserving existing response formats and business behavior.

### Scope

- Optimize conversation listing in the database.
- Cache landing statistics and stable lookup data.
- Add explicit cache invalidation after admin mutations.
- Profile project matching and replace memory-heavy ID collection where safe.
- Review eager loading and N+1 behavior.
- Verify and add indexes through backward-compatible migrations only where measurements justify them.
- Establish basic performance budgets.

### Expected Deliverables

- Query-count and response-time baselines.
- Optimized conversation and matching queries.
- Documented cache keys and invalidation behavior.
- Index plan with staging `EXPLAIN` evidence.
- Reduced memory use on message and project pages.

### Risks

- Query rewrites may subtly change ordering or matching results.
- New indexes can increase write cost and may lock large tables during creation.
- Cached information may become stale if invalidation is incomplete.

### Estimated Complexity

**Medium–High** — improvements must be measured against production-like data.

## Sprint 3 — Architecture Improvements

### Objective

Create a maintainable domain structure and remove ambiguity without a large rewrite.

### Scope

- Consolidate authorization around policies and documented domain rules.
- Clarify roles, profiles, admin privileges, and Spatie Permission responsibilities.
- Consolidate active employer/specialist implementations.
- Deprecate legacy Worker and inactive route paths safely.
- Move reusable controller logic into actions/services where justified.
- Standardize FormRequests, response formats, model scopes, and type declarations.
- Establish coding-style and static-analysis checks.
- Record architectural decisions.

### Expected Deliverables

- Clear module and ownership boundaries.
- Reduced duplicate business logic.
- Deprecated legacy paths with removal criteria.
- CI checks for tests and style.
- Initial Architecture Decision Records.

### Risks

- Hidden references to legacy controllers, views, translations, or theme assets.
- Over-abstraction could increase complexity.
- Role-model changes can cause authorization regressions.

### Estimated Complexity

**High** — must be delivered as multiple small, reversible changes.

## Sprint 4 — UI / UX Modernization

### Objective

Create a consistent, accessible, fast, mobile-friendly Persian marketplace experience.

### Scope

- Audit navigation and major user journeys.
- Establish reusable UI components and design tokens.
- Improve RTL, mobile responsiveness, accessibility, loading, empty, error, and success states.
- Reduce inline CSS/JavaScript and unused Velzon assets.
- Modernize employer, specialist, project, message, ticket, and admin screens.
- Improve form usability and validation feedback.

### Expected Deliverables

- Documented design system foundation.
- Updated primary workflows.
- Accessibility and responsive-layout checklist.
- Smaller frontend build and fewer unused dependencies.
- Consistent interaction and feedback patterns.

### Risks

- Visual regressions across the large theme surface.
- Removing apparently unused assets may break dynamic pages.
- UI changes can unintentionally alter workflow behavior.

### Estimated Complexity

**High** — broad product surface, but work should be split by journey.

## Sprint 5 — Marketplace Features

### Objective

Strengthen marketplace discovery, trust, and engagement after the platform foundation is stable.

### Scope

- Engineer portfolios and verified experience.
- Employer/company profiles.
- Favorites and saved projects.
- Saved searches and alerts.
- Ratings and reviews linked to completed engagements.
- Improved proposal/collaboration workflows.
- Notification center.
- Search and filtering improvements.
- Marketplace moderation tools.

### Expected Deliverables

- Portfolio and reputation MVP.
- Saved/favorite discovery workflows.
- Search improvements.
- In-app notification foundation.
- Moderation and abuse-reporting controls.

### Risks

- Ratings and reviews can be abused without verified engagement rules.
- Notifications can create spam and operational cost.
- Search quality depends on normalized marketplace data.

### Estimated Complexity

**High** — introduces multiple interconnected product capabilities.

## Sprint 6 — Payment & Wallet

### Objective

Introduce secure, auditable financial workflows suitable for the Iranian marketplace.

### Scope

- Payment provider evaluation and gateway abstraction.
- Wallet ledger design.
- Deposits, withdrawals, refunds, fees, and transaction history.
- Project milestones and controlled release of funds.
- Payment webhooks, idempotency, reconciliation, and dispute workflows.
- Financial administration and reports.
- Compliance, identity, and operational review.

### Expected Deliverables

- Approved payment architecture and threat model.
- Immutable double-entry or equivalent auditable ledger design.
- Gateway sandbox integration.
- Idempotent webhook processing.
- Reconciliation and operational runbooks.
- End-to-end financial tests before production activation.

### Risks

- Financial loss, double charging, fraud, inconsistent balances, and regulatory obligations.
- Gateway outages and delayed callbacks.
- Irreversible design errors if balances are stored without a ledger.

### Estimated Complexity

**Very High** — requires separate security, legal, financial, and operational approval.

## Sprint 7 — SEO & Marketing

### Objective

Build sustainable organic acquisition and measurable marketplace growth.

### Scope

- SEO-friendly public project, domain, skill, company, and engineer pages.
- Metadata, canonical URLs, structured data, sitemap, and robots controls.
- Content and landing-page framework.
- Performance and Core Web Vitals improvements.
- Analytics, conversion events, campaign attribution, and privacy controls.
- Referral and lifecycle communication foundations.

### Expected Deliverables

- Technical SEO baseline.
- Search-indexable marketplace pages with privacy safeguards.
- Sitemap and structured data.
- Analytics event dictionary and dashboards.
- Content publishing workflow.

### Risks

- Accidental indexing of private or low-quality content.
- Duplicate pages and uncontrolled crawl space.
- Analytics may collect excessive personal data if not governed.

### Estimated Complexity

**Medium–High** — combines product, content, performance, and privacy work.

## Sprint 8 — Release Candidate

### Objective

Validate the platform as a stable, secure, observable, and operable release candidate.

### Scope

- Full regression, security, performance, accessibility, and browser testing.
- Dependency and configuration review.
- Reproducible and rollback-capable deployment.
- Database backup and migration rehearsal.
- Monitoring, alerting, health checks, and incident procedures.
- Data-retention and privacy review.
- Final content, localization, and operational acceptance.

### Expected Deliverables

- Approved release candidate build.
- Completed release checklist.
- Verified rollback rehearsal.
- Security and performance sign-off.
- Production monitoring and incident runbooks.
- Go/no-go decision record.

### Risks

- Late discovery of authorization, data, deployment, or browser-specific regressions.
- Incomplete operational ownership.
- Production data volume may reveal issues absent from staging.

### Estimated Complexity

**High** — primarily integration, verification, and operational readiness work.

---

# Product Backlog

| ID | Priority | Title | Status | Sprint | Notes |
|---|---|---|---|---|---|

---

# Known Issues

## Security and Authorization

1. `POST /api/user-skill` targets a nonexistent controller method.
2. The intended user-skill controller relies on authenticated identity, but the API route has no authentication middleware.
3. `GET /user/matched-projects/{project}` does not confirm that the project belongs to the specialist's matched result set.
4. Unauthorized matched-project access also increments another project's view counter.
5. Any authenticated user can initiate a conversation with any known user UUID.
6. Messages can reference any existing project without verifying participant relationships.
7. The conversation route can be used to confirm another account UUID and display its name.
8. Collaboration requests can target any existing project rather than only projects visible to the specialist.
9. Project attachments are stored on the public disk and bypass Laravel authorization when downloaded directly.
10. Project uploads enforce file size but do not use a MIME/extension allowlist.
11. No centralized model policies are registered.
12. Several admin FormRequests authorize any authenticated user and rely entirely on route middleware for admin enforcement.
13. Active-role session state is not always revalidated against currently owned profiles.
14. Administrators bypass active-role checks broadly rather than through an explicit impersonation workflow.
15. TrustHosts middleware is disabled.
16. No Content Security Policy was identified.
17. An inactive test route file contains a public cache-clearing endpoint that would be dangerous if loaded.

## Stability and Correctness

1. Project-skill update synchronization may not preserve expected pivot data shape.
2. Repeated project-detail requests can inflate view counts.
3. Cache invalidation is not visible after domain/subdomain/skill administrative mutations.
4. Both `lang` and `resources/lang` exist, creating localization ambiguity.
5. A seeder contains diagnostic `dump()` output.
6. No automated test suite was found during the audit.
7. Broad Pint formatting differences exist across active PHP code.
8. Persian text displayed as mojibake in some command-line inspection output, indicating encoding consistency should be verified.

## Dependencies

1. Composer audit reported 24 security advisories affecting 14 locked packages at audit time.
2. The frontend contains old or redundant packages, including Moment 2.24, Quill 1.x, both Popper generations, and a placeholder `fs` package.
3. Dropzone is pinned to a beta release.
4. The active need for many Velzon libraries and `@openrouter/sdk` has not been demonstrated.

## Performance

1. Conversation listing loads every participating message and groups it in PHP.
2. Public landing statistics execute multiple database counts on uncached requests.
3. Project creation/editing loads large skill and relationship datasets.
4. Project matching collects multiple ID sets into application memory.
5. Skills lookup is not cached consistently with subdomain lookup.
6. Production defaults fall back to synchronous queues and file-based cache/session behavior unless overridden by environment configuration.

## Deployment and Operations

1. Deployment uses non-atomic FTPS uploads.
2. CI does not install production dependencies reproducibly.
3. CI does not run `npm ci` or `npm run build`.
4. `public/build` is ignored/excluded and not built during deployment.
5. Migrations, Laravel optimization caches, health checks, and rollback are not part of the workflow.
6. Local `vendor` contents may be uploaded across operating systems.
7. Fresh deployment depends on pre-existing `public/index.php` and `.htaccess` behavior.
8. Deployment and export artifact directories are insufficiently documented.

## Architecture and Maintainability

1. Worker and Specialist controllers duplicate marketplace responsibilities.
2. Inactive worker, employer, and specialist route files overlap active unified user routes.
3. Multiple authentication controller sets coexist.
4. Role and permission concepts overlap without a single authoritative model.
5. Large inline Blade scripts/styles and extensive unused theme assets increase maintenance cost.
6. The existing project README does not adequately document setup, architecture, deployment, or operations.

---

# Future Features

## Intelligence and Discovery

- **AI Matching:** Rank projects and engineers using verified skills, experience, availability, location, outcomes, and behavioral signals.
- **AI Profile Assistant:** Suggest profile improvements, missing skills, portfolio structure, and clearer professional summaries.
- **AI Project Assistant:** Help employers write complete project briefs, define milestones, and identify relevant engineering disciplines.
- **AI Risk Signals:** Detect suspicious project descriptions, spam, duplicate posts, unrealistic budgets, and unsafe communications.
- **Smart Search:** Full-text and faceted search across projects, engineers, skills, companies, domains, and locations.
- **Semantic Search:** Understand engineering intent and terminology beyond exact keyword matches.
- **Saved Searches:** Let users store filters and receive controlled alerts for new matches.
- **Favorites:** Save projects, engineers, companies, and searches for later review.
- **Recommendation Explanations:** Explain why a project or engineer was recommended.

## Engineer Career Tools

- **Resume Builder:** Create Persian and English engineering resumes from verified profile data.
- **Portfolio Builder:** Publish case studies, images, documents, responsibilities, technologies, and measurable outcomes.
- **Verified Credentials:** Validate education, licenses, certifications, memberships, and work experience.
- **Skill Assessments:** Offer domain-specific assessments and verified badges.
- **Availability Calendar:** Show working capacity, lead time, and project availability.
- **Career Analytics:** Track profile views, search appearances, proposal conversion, ratings, and earnings.
- **Team Profiles:** Allow engineering teams and firms to collaborate under shared profiles.

## Marketplace Workflow

- Structured proposals and employer shortlists.
- Project invitations for selected engineers.
- Milestones, deliverables, approvals, and change requests.
- Project workspaces with controlled files and activity history.
- Contracts and reusable agreement templates.
- Dispute handling and evidence collection.
- Ratings & Reviews limited to verified completed engagements.
- Repeat-hire and preferred-engineer workflows.
- Company profiles and verified employer badges.
- Project moderation and abuse reporting.

## Communication

- **Notifications:** In-app, email, SMS, and push delivery with per-user preferences.
- **Chat Improvements:** Real-time updates, typing indicators, delivery/read states, message search, replies, reactions, and moderation.
- Secure conversation attachments.
- Project-linked threads and milestone discussions.
- User blocking, reporting, and spam controls.
- Notification digest and quiet-hour settings.
- Optional voice/video meeting integrations.

## Financial Platform

- **Wallet:** Available, pending, reserved, and withdrawable balances backed by an auditable ledger.
- **Payment Gateway:** Multiple Iranian gateway adapters with failover and reconciliation.
- Milestone funding and controlled payment release.
- Platform commissions, discounts, credits, and refunds.
- Withdrawal requests and payout verification.
- Transaction statements and invoices.
- Dispute reserves and administrative adjustments with audit trails.
- Financial analytics and reconciliation reports.

## Mobile and Integrations

- **Mobile App:** Android-first or cross-platform application for engineers and employers.
- **Mobile API:** Versioned, authenticated API supporting core marketplace workflows.
- **Public API:** Controlled partner access with scopes, quotas, audit logs, and documentation.
- Webhooks for approved integrations.
- Calendar, email, storage, accounting, and CRM integrations.
- Single sign-on for enterprise employers.

## Analytics and Administration

- **Analytics:** Marketplace liquidity, matching quality, conversion funnels, retention, revenue, and support metrics.
- **Admin Reports:** User growth, projects, proposals, payments, fraud, disputes, tickets, and operational SLA reports.
- Moderation queues and risk scoring.
- Configurable engineering taxonomies.
- Feature flags and controlled rollouts.
- Audit logs for sensitive administrative operations.
- Data export, retention, deletion, and privacy workflows.
- Marketplace health and geographic demand dashboards.

## Growth and SEO

- Public engineering domain and skill pages.
- Search-indexable public portfolios with privacy controls.
- Company and project landing pages.
- Educational content, salary/project guides, and engineering resources.
- Referral and invitation programs.
- Campaign tracking and conversion analytics.
- Personalized lifecycle communication.
- Multilingual public content where commercially valuable.

---

# Architecture Notes

This section is reserved for future architectural decisions and links to Architecture Decision Records (ADRs).

| ADR | Date | Decision | Status | Context / Link |
|---|---|---|---|---|

Topics expected to require explicit decisions include:

- Canonical role and permission model.
- Policy and authorization conventions.
- API authentication and versioning.
- Private file storage and download authorization.
- Queue, cache, session, and realtime infrastructure.
- Search technology.
- Notification architecture.
- Wallet ledger and payment-provider abstraction.
- Mobile application/API strategy.
- Deployment topology, atomic releases, and rollback.
- Observability, audit logging, and data retention.
- AI provider, privacy, evaluation, and fallback strategy.

---

# Changelog

| Date | Version / Sprint | Change | Author | Reference |
|---|---|---|---|---|

---

# Release Checklist

## Scope and Approval

- [ ]

## Code Quality

- [ ]

## Automated Tests

- [ ]

## Security and Privacy

- [ ]

## Database and Data Safety

- [ ]

## Dependencies and Build

- [ ]

## Deployment and Rollback

- [ ]

## Monitoring and Operations

- [ ]

## Product and UX Verification

- [ ]

## Post-release Validation

- [ ]
