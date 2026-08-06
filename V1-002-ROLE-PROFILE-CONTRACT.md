# V1-002 — Canonical role and profile contract

Status: **accepted specification for V1-003**
Scope: contract and migration plan only; this task intentionally makes no runtime or schema changes.

## 1. Terminology and normative rules

The words **MUST**, **MUST NOT**, **SHOULD**, and **MAY** below are normative.

| Concept | Canonical source of truth | Contract |
| --- | --- | --- |
| Account identity | The authenticated `users` row (`Auth::user()` / user UUID) | A user is the login identity and owner of zero or more profiles. Authentication MUST continue to use the user, not a profile. `active` remains an account-status concern, not a role. |
| Available profiles | Owned `user_profiles` rows | A user has employer capability iff they own an `employer` profile and specialist capability iff they own a `specialist` profile. Profile types are exactly `employer` and `specialist`. At most one row of each type per user is supported by the existing unique key. |
| Active profile | Session `active_profile_id`, resolved as an owned `user_profiles.id` | Browser context is a profile identity, not a free-standing role string. A value is valid only when the row still exists and its `user_id` equals the authenticated user's ID. No value is valid for another user. The active context type is derived from the resolved profile's `type`; it is never independently authoritative. API/bearer-token authorization MUST NOT depend on browser session context. |
| Administrator status | `users.is_admin` (boolean) | `true` is the sole administrator grant. `users.role = admin`, a profile, a session value, or a Spatie role MUST NOT grant administrator access. Administrators authenticate as users, do not need a profile, and bypass profile-context selection only where the current admin contract explicitly permits it. |
| Employer context | Resolved active profile with `type = employer` | This selects employer UI/workflow context. Employer capability is ownership of an employer profile. Resource authorization still MUST verify durable ownership/relationships (for example `projects.employer_id` and, where populated, `employer_profile_id`); merely selecting the context is not ownership. |
| Specialist context | Resolved active profile with `type = specialist` | This selects specialist UI/workflow context. Specialist capability is ownership of a specialist profile. Resource authorization still MUST verify durable eligibility/relationships; merely selecting the context is not authorization to another user's data. |

In compact form, for a non-admin browser request:

```text
identity          := authenticated User
availableProfiles := identity.profiles
activeProfile     := availableProfiles.find(session.active_profile_id)
activeContext     := activeProfile.type
isAdministrator   := identity.is_admin === true
```

`activeContext` MUST be computed, never accepted from the request or stored as a second authority. Having a profile answers “may this account act in this capacity?”; selecting it answers “which capacity is this browser session currently using?”; policies answer “may it perform this operation on this resource?”. These are distinct questions.

## 2. Current implementation inventory

### 2.1 Durable database/model state

1. `users.id` is the UUID account identity; Laravel's web guard authenticates a `User` by email or mobile plus password.
2. `users.role` is a legacy enum (`worker`, `employer`, `admin`) defaulting to `worker`. It is mass assignable, emitted by the factory, read by `RoleMiddleware`, `HomeController`, admin dashboard statistics, the public project view, and some tests. Registration normally leaves it at the database default. Its term `worker` conflicts with profile type `specialist`.
3. `users.is_admin` is a boolean defaulting to false. It is read by admin middleware, dashboards, login flow, requests, policies, actions, layouts, and controllers. The initial admin is seeded with both `role = admin` and `is_admin = true`.
4. `user_profiles` contains UUID profile identities owned through `user_id`; `type` is the employer/specialist capability. The schema unique constraint and `AddProfileRequest` both enforce one profile per type per user. `User::profiles()` and `UserProfile::user()` expose ownership.
5. Durable domain relations also imply operational context but do not define role: projects have `employer_id` and nullable `employer_profile_id`; skills are currently user-owned; requests, messages, tickets, and policy relationships attach primarily to the user. They remain resource-authorization evidence.

### 2.2 Session state

There are two competing keys:

* `active_role` is the live application key. `ProfileSelectController` writes a submitted type after checking the user has that type; `EnsureActiveRole` reads it, auto-writes it for a single-profile user, and uses it for route gating. Layouts read it directly. The selection page clears it on every GET. It contains a type, not a profile ID.
* `active_profile_id` is implemented by `SwitchProfileAction`. It accepts only an owned profile and can resolve it, but no current controller, route, middleware, or view invokes this action. Its fallback to the user's first profile is implicit and does not persist a selection. It is therefore presently dormant, not the effective source of browser context.

Neither key is persisted in the database. Both are scoped to a browser session and must be treated as disposable preferences, never as account capability or durable authorization evidence.

### 2.3 Creation, authentication, selection, and switching

* The current `RegisterUserAction` creates a non-admin user without a profile and relies on the `users.role` default. Laravel UI's `RegisterController` is also present and can create an employer profile for the guest-project flow. This creates two registration paths.
* Login authenticates the account regardless of profile presence. Non-admin users without profiles are redirected to selection/creation. A user with any specialist profile and no skills is redirected to skill selection, even if employer context may be intended. Admins skip those checks.
* Profile creation validates `employer|specialist` and per-user type uniqueness. Creation does not establish `active_role` or `active_profile_id`; redirects imply the next workflow.
* The visible picker activates by submitted profile **type**, checks type ownership, and writes `active_role`. It cannot distinguish duplicate same-type rows if legacy data violates the unique invariant.
* `SwitchProfileAction` switches by owned profile **ID**, but is disconnected from the visible flow.
* `EnsureActiveRole` requires profiles for non-admins and auto-selects a single profile. It does not revalidate an existing `active_role` against current ownership. Admins bypass it.

### 2.4 Authorization and presentation consumers

Current decisions are distributed across:

* `admin` middleware and numerous inline/FormRequest/policy checks of `is_admin`;
* `active_role[:type]` route middleware for the unified user, skill, and employer flows;
* `profile` middleware, which checks durable profile-type ownership independent of selection;
* legacy `role` middleware and direct comparisons of `users.role`;
* resource policies and controller/request ownership checks;
* sidebar/topbar role labels and quick actions, which read `active_role` directly;
* Spatie Permission configuration and `CheckRoleOrPermission`, although `User` does not use Spatie's `HasRoles` trait and the application's active admin routes do not use this as their authority.

This inventory covers all *kinds* of current role/profile state: user identity/status columns, profile rows/types, two session keys, resource relationships, and the installed-but-not-integrated permission system. A repository-wide V1-003 replacement search must use the affected-file checklist in section 7 so no direct consumer becomes a new authority accidentally.

## 3. Conflicts and duplicated state

| Conflict | Consequence |
| --- | --- |
| `users.role` versus `user_profiles.type` | A `role=employer` user may lack an employer profile; a default `role=worker` user may own employer and specialist profiles. `worker` and `specialist` describe the same broad capacity with different vocabulary. |
| `users.role=admin` versus `users.is_admin` | The two values can disagree, producing different results in admin middleware and legacy views/controllers. |
| `active_role` versus `active_profile_id` | Two session values can disagree. The used key stores an unowned/unvalidated string after the initial write; the safer ID-based action is unused. |
| Capability versus selected context | Some code checks profile ownership, some checks the session type, and some checks neither. A selected type is sometimes mistaken for authorization. |
| Admin bypass versus context middleware | `EnsureActiveRole` lets admins through employer/specialist route groups without an employer/specialist profile. That is existing compatibility behavior, not evidence that an admin owns those capabilities. Policies must remain decisive. |
| User-scoped versus profile-scoped domain data | Projects have both an employer user and optional employer profile; skills and several collaborations remain user-scoped. Context cannot safely replace existing ownership keys without a separate data-model task. |
| Profile selection side effects | Visiting the picker clears `active_role`; profile creation does not set either session key; login's specialist-skill redirect is based on availability rather than active context. |
| Spatie versus custom authorization | Package concepts appear in configuration/middleware but are not wired into `User`; treating them as canonical would silently alter access. |

## 4. Canonical behavior and testable invariants

V1-003 MUST make the following assertions executable without changing the product design:

1. **Login safety:** every valid, active legacy user can authenticate whether it has zero, one, or two profiles and regardless of `users.role`; missing context may redirect after login but MUST NOT cause authentication failure or a 403 loop.
2. **Admin truth table:** `is_admin=true` grants existing admin access even when `role!=admin`; `role=admin` with `is_admin=false` does not grant it. Existing admin profile bypass remains supported.
3. **Availability:** employer/specialist availability equals existence of the corresponding owned profile, never `users.role`, `active_role`, or a permission-package role.
4. **Selection ownership:** activating profile ID P succeeds only when `P.user_id` equals the authenticated user. Foreign, deleted, malformed, and unknown IDs do not become active and produce the existing safe picker redirect (or 403 JSON where that endpoint contract calls for JSON).
5. **Derived context:** after selecting an owned profile, context type always equals that row's type. No request can choose an ID and a contradictory type.
6. **Stale-session recovery:** a missing/deleted/foreign active profile is cleared and resolved using the compatibility rules below; it never grants access and never blocks later login.
7. **Dual profile:** with two supported profiles and no valid selection, the user is sent to the picker; no arbitrary first row is selected.
8. **Single profile:** with exactly one supported profile and no valid selection, that profile is selected transparently and both transition keys are synchronized.
9. **No profile:** a non-admin with no supported profile reaches profile creation; an admin retains admin access.
10. **Route context:** an employer-only route accepts employer context and rejects specialist context; the inverse holds for specialist-only routes. Capability/selection does not replace resource policies.
11. **Session isolation:** logout/session invalidation removes effective context. A profile ID left from a previous authenticated user is rejected by ownership resolution.
12. **API independence:** bearer-token eligibility checks durable profile ownership and resource policies, not either browser session key.

## 5. Compatibility and data-preservation rules

These rules apply during V1-003 and its transition window:

1. **No destructive migration.** Do not drop, rename, narrow, rewrite, or make non-null `users.role`, `users.is_admin`, or existing profile/resource columns. Do not bulk-create/delete profiles. V1-003 requires no data backfill to permit login.
2. **Legacy fields remain readable for compatibility only.** `users.role` stays populated and may continue to feed legacy reporting/presentation until each listed consumer is migrated, but MUST NOT decide administrator status, available profiles, or active context. New code MUST NOT write it to represent profile changes.
3. **Admin compatibility is asymmetric.** Continue honoring every `is_admin=true` row regardless of `role`. Do not promote `role=admin,is_admin=false`; flag that inconsistency for audit. This prevents privilege escalation while retaining existing administrators.
4. **Session read precedence.** Resolve an owned `active_profile_id` first. If absent/invalid, accept legacy `active_role` only when it is exactly `employer` or `specialist` and the user owns that profile type; translate it to that profile ID. Otherwise clear both values.
5. **Session dual-write.** During transition, every successful selection or automatic single-profile resolution writes `active_profile_id` and the selected profile's type to `active_role`. The ID is authoritative; the string is a derived compatibility mirror. Logout continues to invalidate the entire session.
6. **Deterministic malformed-data handling.** For unexpected duplicate same-type profiles, never delete or merge data. A valid owned `active_profile_id` wins; legacy type translation chooses the oldest row by `created_at`, then `id`, and logs/records an audit warning. Unsupported profile types are ignored for context but retained.
7. **Safe selection defaults.** Zero supported profiles means creation; one means auto-select; more than one means explicit selection. `SwitchProfileAction`'s current arbitrary `first()` fallback must not survive.
8. **Admin context.** Admin routes require only `is_admin`. Admins have no implied active profile. If an administrator explicitly uses a non-admin workflow, existing bypass behavior is retained in V1-003 unless a resource policy denies it; changing that is a later authorization-design decision.
9. **Resource ownership remains stable.** Continue using existing user/profile foreign keys and policies. Do not reinterpret historical project, request, message, ticket, or skill rows based on the selected profile.
10. **Deployment order.** Ship tolerant readers and dual writers before changing any consumer; keep the legacy column/key through at least one deployed release and audit telemetry before considering removal in a later roadmap task.

## 6. Exact implementation plan for V1-003

V1-003 is an implementation task and should be limited to the following sequence.

### Step 1 — Introduce one resolver contract

Create an application service (suggested `App\Support\Auth\ProfileContext` or an action plus immutable result) with constants for `active_profile_id`, legacy `active_role`, and supported types. It MUST expose:

* `availableProfiles(User): Collection` (supported owned profiles, deterministic order);
* `activeProfile(Request|Session, User): ?UserProfile` (ownership-validated, compatibility translation);
* `activate(User, UserProfile|string): UserProfile` (owned ID only, dual-write);
* `clear(): void`; and
* `activeType(...): ?string` derived solely from the resolved row.

Do not cache a profile across authenticated users. Use an ownership-constrained query (`$user->profiles()->whereKey(...)`), not `UserProfile::find(...)`. Consolidate or replace `SwitchProfileAction`; there must be only one resolution algorithm.

### Step 2 — Characterize before replacing

Add focused feature/unit tests for all twelve invariants in section 4. Include the four admin mismatch combinations, stale/foreign session IDs, valid/invalid legacy role translation, zero/one/two profiles, duplicate fixtures inserted only if the test database can bypass the unique key safely, logout isolation, and route-type gates. Retain existing authorization characterization tests unchanged unless their setup must dual-write the transition session.

### Step 3 — Move the picker to profile identity

Change `ProfileSelectController::activate` and the picker forms to submit `profile_id`, not type. Validate UUID/string input, resolve it through the authenticated user's relation, call the resolver, and preserve current redirects/messages. The cards may still be labeled by type. GET `/profile/select` SHOULD display the current selection and MUST NOT clear a valid selection merely because the page was visited.

After profile creation, activate the created profile through the same resolver before redirecting. For specialist creation, preserve the skill-selection redirect.

### Step 4 — Replace context middleware reads

Refactor `EnsureActiveRole` to use the resolver. Keep the middleware alias and route parameters to avoid route redesign. For non-admins apply zero/one/many behavior, verify required types against the derived profile type, and safely recover stale state. Preserve the present admin bypass. Either retire `ProfileMiddleware` where redundant or make it call the same availability helper; do not add a third algorithm.

### Step 5 — Replace presentation and flow consumers

Provide the resolved profile/type to the sidebar and topbar (view composer, shared service call, or controller data) and remove their direct authoritative reads of `session('active_role')`. Update dashboard/login/skill onboarding decisions so availability comes from profiles and context-dependent redirects use the resolver. A dual-profile employer must not be forced into specialist onboarding merely because a specialist profile exists.

### Step 6 — Quarantine legacy authorities

Replace active authorization/presentation comparisons of `users.role` with `is_admin`, profile availability, derived context, or resource policy as appropriate. At minimum address `RoleMiddleware`, `HomeController`, `Admin\DashboardController`, and `resources/views/projects/show.blade.php`. Keep the database field and, if a report still needs it temporarily, label that use explicitly legacy. Do not adopt Spatie Permission or remove its package in V1-003; mark `CheckRoleOrPermission` unused/deprecated unless route discovery proves it active.

### Step 7 — Verify deployment compatibility

Run the full suite and route listing. Add a read-only audit command/query or documented pre-deploy SQL for contradictory admin flags, missing profiles, duplicate/unsupported profile types, and projects whose optional employer profile is not owned by `employer_id`. It MUST report only. Verify an old session containing only `active_role` upgrades in place and a new dual-written session remains usable while any old application instance is still serving traffic.

### V1-003 non-goals

Do not drop `users.role` or `active_role`; redesign authorization; migrate user-owned skills/messages/requests to profiles; change resource ownership; introduce multiple profiles of the same type; adopt Spatie roles; revoke the admin middleware bypass; or destructively “repair” production rows.

## 7. Affected-file checklist for V1-003

The implementer MUST repeat a repository-wide search, but the current known surface is:

### Core model, schema, factories, and actions

* `app/Models/User.php`, `app/Models/UserProfile.php`
* `app/Actions/Auth/RegisterUserAction.php`, `AddProfileAction.php`, `SwitchProfileAction.php`
* `database/migrations/2014_10_12_000000_create_users_table.php`
* `database/migrations/2024_05_23_100000_create_user_profiles_table.php`
* `database/factories/UserFactory.php` and profile/user seeders

Existing migrations are historical evidence and MUST NOT be edited for a production fix; add a forward migration only if implementation proves one necessary.

### Authentication, registration, and profile flows

* `app/Http/Controllers/Auth/RegisterController.php`, `RegisteredUserController.php`, `LoginController.php`, `AuthenticatedSessionController.php`, `ProfileController.php`
* `app/Http/Controllers/User/ProfileSelectController.php`, `DashboardController.php`
* `app/Http/Requests/Auth/RegisterRequest.php`, `AddProfileRequest.php`, `UpdateProfileRequest.php`
* `resources/views/auth/register.blade.php`, `resources/views/user/role-select.blade.php`, `profile-select.blade.php`, `dashboard.blade.php`

### Middleware, routes, policies, requests, and authorization consumers

* `app/Http/Kernel.php`
* `app/Http/Middleware/EnsureActiveRole.php`, `ProfileMiddleware.php`, `RoleMiddleware.php`, `AdminMiddleware.php`, `CheckRoleOrPermission.php`
* `routes/web.php`, `routes/user.php`, `routes/admin.php`, `routes/employer.php`, `routes/specialist.php`, `routes/worker.php`, `routes/api.php`
* `app/Policies/ProjectPolicy.php`, `ProjectFilePolicy.php`
* admin FormRequests and `app/Http/Requests/Specialist/StoreSkillSuggestionRequest.php`
* controllers/actions found by `rg "is_admin|->role|active_role|active_profile_id|profiles.*type" app routes resources tests`, notably `HomeController`, `Admin/DashboardController`, `Admin/UserController`, specialist/worker skill and matched-project controllers, `CreateProjectAction`, and `GetUsersAction`

### Presentation and tests

* `resources/views/layouts/sidebar.blade.php`, `topbar.blade.php`, and `resources/views/projects/show.blade.php`
* `tests/Feature/Authorization/*`, authentication/profile feature tests, `tests/Feature/SkillSuggestionWorkflowTest.php`, and route-specific tests that seed `active_role`
* New focused `tests/Feature/Auth/ProfileContextTest.php` and `tests/Feature/Middleware/EnsureActiveProfileTest.php` (suggested names)

## 8. Risks and mitigations

| Risk | Mitigation / acceptance check |
| --- | --- |
| Existing sessions lose context | Translate a validated `active_role` and dual-write; invalid state falls back to picker, not logout. |
| Account cannot log in because it has no profile or inconsistent legacy role | Authentication remains user-based; redirect only after successful login; test zero-profile and all role values. |
| Privilege escalation from `role=admin` | Only `is_admin` is authoritative; explicitly test mismatches. |
| Foreign/stale session profile leaks access | Always query through `$user->profiles()` on every resolution; clear invalid state; test user switching in one session. |
| Dual-profile user enters wrong onboarding flow | Base onboarding on resolved context, not mere existence of a specialist profile. |
| Rolling deployment sees incompatible session shapes | Keep `active_role` as a derived dual-written mirror for the transition release. |
| Duplicate or malformed production profiles break deterministic resolution | Never delete; prefer valid ID, otherwise deterministic oldest row; audit and log. |
| Admin bypass accidentally becomes resource ownership | Keep policies/ownership checks authoritative and test sensitive routes with an admin separately. |
| Legacy reports/counts change unexpectedly | Migrate authorization first; compare profile-based and legacy counts before changing reporting labels. |
| Scope expands into V1-003 or broader redesign | V1-002 changes documentation only; V1-003 follows section 6 and its explicit non-goals. |

## 9. V1-002 acceptance statement

This document is the V1-002 deliverable. It selects `User` for identity, owned `UserProfile` rows for available capacities, an ownership-validated `active_profile_id` for browser selection, the selected profile's `type` for derived employer/specialist context, and `users.is_admin` for administrator status. The compatibility rules deliberately preserve every existing row and allow every existing account to authenticate. Runtime consolidation, tests, and consumer migration belong to V1-003 exactly as specified above.
