# V1-003 — Canonical active profile implementation report

## Canonical runtime flow

`App\Support\Auth\ProfileContext` is the single runtime resolver. It loads only
supported profiles owned by the authenticated `User`, validates
`session.active_profile_id` through that ownership relation, and derives the
employer or specialist context exclusively from `user_profiles.type`.

When no context is available, zero-profile users are sent to profile creation,
single-profile users are selected transparently, and users with both supported
profile types are sent to the picker. Selecting context does not alter any
resource ownership relationship or policy.

## Changed files

The implementation adds `app/Support/Auth/ProfileContext.php`, focuses contract
coverage in `tests/Feature/Auth/ProfileContextTest.php`, and updates the existing
profile action, profile controllers, login flow, context middleware, legacy role
middleware, dashboard statistics, and role-dependent Blade presentation. The
profile picker now submits an owned profile UUID rather than a role string.

No migration, schema, seed, resource relationship, or unrelated business flow
was changed.

## Removed or deprecated `active_role` usage

Application consumers no longer read `active_role` directly. The existing
`active_role` middleware alias remains so route names do not need a broad
redesign, but the middleware now resolves `active_profile_id` and compares only
the selected row's derived type. `SwitchProfileAction` remains as a deprecated
adapter to the canonical resolver.

During the transition release, `active_role` is written only as a derived mirror
after a profile ID has been ownership-validated. It is not authority.

## Legacy-session compatibility

An owned, supported `active_profile_id` always wins. If it is missing, stale, or
foreign, the resolver clears the ID and translates `active_role` only when its
value is exactly `employer` or `specialist` and a matching owned profile exists.
Translation selects deterministically by `created_at`, then ID. Unexpected
duplicate same-type rows are retained and logged. Malformed, unsupported, or
unowned legacy state clears both keys and falls back safely.

## Administrator behavior

Admin middleware and flows continue to use `users.is_admin`. Administrators do
not need an active profile. `users.role=admin`, profile data, request values,
session values, and route names cannot promote a non-admin. The legacy
`users.role` column remains in place for production compatibility but no longer
drives the active authorization or profile presentation updated by this task.

## Production-data safety

V1-003 has no migration and performs no backfill, reassignment, deletion, or ID
rewrite. Existing users with zero, one, or two profiles remain valid. Incomplete
profiles and missing specialist skills do not block authentication. Existing
resource ownership foreign keys and policies are unchanged.

Suggested read-only pre-deploy audit queries (adapt table/schema syntax for the
production database):

```sql
SELECT id, role, is_admin FROM users
WHERE (role = 'admin' AND is_admin = 0)
   OR (role <> 'admin' AND is_admin = 1);

SELECT user_id, type, COUNT(*) AS profile_count
FROM user_profiles GROUP BY user_id, type HAVING COUNT(*) > 1;

SELECT id, user_id, type FROM user_profiles
WHERE type NOT IN ('employer', 'specialist');

SELECT p.id, p.employer_id, p.employer_profile_id
FROM projects p LEFT JOIN user_profiles up ON up.id = p.employer_profile_id
WHERE p.employer_profile_id IS NOT NULL
  AND (up.id IS NULL OR up.user_id <> p.employer_id);
```

## Tests and results

Focused tests cover profile-free login, one profile of either type, dual-profile
selection, owned switching, foreign/stale/deleted IDs, missing context, legacy
translation and invalid legacy state, non-blocking incomplete login, admin flag
truth, and route gates. Existing authorization characterization tests continue
to verify that resource ownership remains independent from selected context.

PHP syntax checks and `git diff --check` pass. The PHP test suite and route list
could not be executed in this workspace because `vendor/autoload.php` is absent;
this is an environment limitation rather than a test failure.

## Deployment considerations

Deploy tolerant readers and the compatibility dual writer together. Clear
Laravel configuration, route, and view caches after release. Do not flush the
session store: old sessions are upgraded lazily and safely. Keep `active_role`
and `users.role` for at least this transition release and review duplicate-profile
warnings and the read-only audit before considering later removal.

## Known remaining risks

Malformed historical duplicate profile types cannot normally be created under
the current unique constraint, but the resolver handles them deterministically
without repair. Legacy columns and the middleware alias remain intentionally;
their eventual schema removal is outside V1-003. Resource-level authorization
continues to reflect the existing policies and user/profile foreign keys and
must not be inferred from profile selection in future work. V1-004 has not been
started.
