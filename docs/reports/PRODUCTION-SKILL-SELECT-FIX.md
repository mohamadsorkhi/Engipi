# Production `/skill-select` Fix

Date: 2026-07-21

## Summary

The skill data and API were not the source of the empty UI. `/skill-select` renders `resources/views/test.blade.php`, whose page initializer populates the domain dropdown and installs the handlers that later request `/api/skills/{subdomain}`. The shared `public/build/js/app.js` executes before that page script.

Feather Icons had been removed as a globally loaded dependency, while the shared application script still contained calls to `feather.replace()`. In the production artifact reported by the browser, one of those calls was unguarded. The resulting `ReferenceError` aborted JavaScript execution and prevented the independent skill-selection initializer from completing. An HTTP 200 for the JavaScript file only confirmed delivery; it did not confirm successful execution or that the optional Feather global existed.

## Fix

`resources/js/app.js` now routes all Feather rendering through one defensive `replaceFeatherIcons()` boundary. It reads the optional library from `window.feather` and calls `replace()` only when both the object and method exist. There are no direct `feather.replace()` calls remaining.

The production build was regenerated, which copied the corrected source to `public/build/js/app.js`, the file included by `resources/views/layouts/vendor-scripts.blade.php`.

This is deliberately an availability fix, not a business-logic change:

- Domain/subdomain selection limits are unchanged.
- `/api/skills/{subdomain}` and its database query are unchanged.
- Skill selection, validation, and persistence are unchanged.
- No schema or migration changes were made.

## Why skill loading works again

With Feather absent, the shared application initializer now continues normally instead of throwing. The page-specific `DOMContentLoaded` handler can populate domains, register the subdomain handler, fetch `/api/skills/{subdomain}`, and render each returned skill card. If Feather is present, icons are still replaced as before.

## Verification

- `npm.cmd run build` completed successfully and synchronized the served asset.
- Both source and built assets contain the safe `window.feather` boundary.
- Repository search found no direct Feather references outside that boundary in application JavaScript.
- The skill API controller remains `App\\Http\\Controllers\\Api\\SkillController::index`, returning `id`, `name`, and `skill_type` for the selected `subdomain_id`.

Deployment must include the regenerated `public/build/js/app.js`. After deployment, invalidate any CDN/browser cache for that asset (or otherwise use the deployment's normal cache-busting process), then smoke-test `/skill-select` with Feather intentionally unavailable and select a domain and subdomain. The skill cards should populate without a `feather is not defined` exception.

## Files changed

- `resources/js/app.js`
- `public/build/js/app.js` (generated deployment artifact; ignored by Git in this workspace)
- `docs/reports/PRODUCTION-SKILL-SELECT-FIX.md`
