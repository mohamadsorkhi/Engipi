# Sprint 1B WP-3 â€” Project Upload Allowlist and Characterization Report

> **Report date:** 2026-07-16
> **Work package:** WP-3 â€” Upload allowlist and characterization
> **Status:** Implementation and local verification complete; waiting for human approval
> **Storage/schema/route/dependency changes:** None
> **Commit, push, or deployment:** Not performed

## Objective

Restrict new full project-creation uploads to the approved document formats by validating both the client filename extension and detected MIME type, while retaining the existing 10 MiB per-file limit, transactional project creation, no-file behavior, public-storage behavior pending WP-4, and all unrelated routes and workflows.

## Scope

Completed:

- Applied the two approved wording clarifications to the WP-2 report before WP-3.
- Added one reusable project-document allowlist rule.
- Applied the rule only to `StoreProjectRequest`'s existing `files.*` validation path.
- Added allowed, blocked, mismatched, oversize, mixed-upload, accepted-upload, and no-file tests.
- Verified invalid mixed uploads create no project row, project-file row, or stored file.
- Verified an approved PDF retains the current successful project/file creation behavior.
- Verified project creation without files remains unchanged.

Excluded:

- No private storage, download controller, download policy, attachment route, Blade link, disk metadata, migration, deletion-path change, or legacy-file operation was started.
- No storage disk or filesystem configuration changed. Existing successful uploads still use the public disk until WP-4 is approved.
- No project update/upload flow was added; the current update request has no file upload field.
- No malware scanner, archive inspection, document parser, or dependency was introduced.
- No route, schema, production data, external system, commit, push, or deployment operation occurred.

## Files Changed

### WP-3 files

| File | Purpose |
|---|---|
| `app/Http/Requests/Employer/StoreProjectRequest.php` | Adds `AllowedProjectDocument` to the existing `files.*` rules while preserving `file` and the 10 MiB limit. |
| `app/Rules/AllowedProjectDocument.php` | Provides the authoritative extension-to-detected-MIME allowlist. |
| `tests/Feature/Uploads/ProjectUploadValidationTest.php` | Covers the validation matrix and project-create side effects with isolated SQLite and fake public storage. |
| `docs/reports/SPRINT-1B-WP3-REPORT.md` | Records decisions, evidence, risks, rollback, and WP-4 boundary. |

### Approved documentation-only correction before WP-3

| File | Change |
|---|---|
| `docs/reports/SPRINT-1B-WP2-REPORT.md` | Clarifies that user-row locking protects only writers using `AddUserSkillAction` and is not a unique-constraint substitute; clarifies that no schema rollback is required and valid WP-2 application data remains. |

Previously approved WP-1/WP-2 changes remain in the working tree. The pre-existing untracked `.claude/` directory was not modified.

## Validation Design

The active full project-create request retains:

```text
files: nullable array
files.*: valid file, maximum 10240 KiB, allowed extension/detected-MIME pair
```

`AllowedProjectDocument`:

1. requires an `UploadedFile` instance;
2. lowercases the client original filename extension;
3. obtains the server-detected MIME type through `UploadedFile::getMimeType()`;
4. lowercases the detected MIME type;
5. looks up the extension in one explicit map; and
6. rejects the file unless the detected MIME appears in that extension's allowed list.

The original filename is not used as a storage path. `CreateProjectAction` continues to use Laravel-generated stored filenames. Storage and download security remain WP-4.

## Validation Matrix

### Allowed file types

| Extension | Allowed detected MIME types | Intended use |
|---|---|---|
| `pdf` | `application/pdf` | Project briefs, specifications, drawings exported to PDF. |
| `txt` | `text/plain` | Plain-text notes and requirements. |
| `csv` | `text/csv`, `text/plain`, `application/csv` | Tabular text exchange across common MIME-detection variants. |
| `doc` | `application/msword`, `application/x-ole-storage`, `application/CDFV2` (compared lowercase) | Legacy Microsoft Word documents. |
| `docx` | `application/vnd.openxmlformats-officedocument.wordprocessingml.document`, `application/zip` | Microsoft Word Open XML documents; ZIP MIME accounts for common server detection of OOXML containers. |
| `xls` | `application/vnd.ms-excel`, `application/x-ole-storage`, `application/CDFV2` (compared lowercase) | Legacy Microsoft Excel workbooks. |
| `xlsx` | `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet`, `application/zip` | Microsoft Excel Open XML workbooks; ZIP MIME accounts for common server detection of OOXML containers. |
| `zip` | `application/zip`, `application/x-zip-compressed` | Bundled project documents/drawings. |

The 10 MiB limit applies independently to every allowed file.

### Blocked file types

The automated matrix explicitly blocks:

| Category | Tested examples | Reason |
|---|---|---|
| Active web content | `.html`, `.svg`, `.js` | Browser-executable or active rendering content is not an approved project document. |
| Server/script content | `.php`, `.sh` | Executable/script content is not permitted. |
| Native executable | `.exe` | Executable binary content is not permitted. |
| Macro-enabled Office | `.docm`, `.xlsm` | Macro-capable formats are denied by default. |
| Renamed executable | `.pdf` with executable MIME | Extension alone cannot authorize content. |
| Approved MIME under blocked extension | `.exe` with PDF MIME | MIME alone cannot authorize content. |
| Oversize file | 10,241 KiB PDF | Exceeds the retained 10 MiB per-file limit. |

All extensions absent from the allowlist are denied, including images, archives other than ZIP, CAD/native engineering formats, presentations, and media unless a future reviewed business decision expands the matrix.

## Test Evidence

### Targeted WP-3 suite

```text
php vendor/bin/phpunit tests/Feature/Uploads/ProjectUploadValidationTest.php --colors=never
```

Result: **passed â€” 22 tests, 46 assertions, 2.926 seconds**.

Coverage:

- eight approved extension/MIME families;
- ten active, executable, macro, and mismatch cases;
- oversize denial;
- mixed valid/invalid request returns `422` for `files.1`;
- mixed invalid request creates zero projects, zero project-file rows, and zero public-disk files;
- approved PDF creates one project, one project-file row, and one file through the unchanged current public-storage behavior; and
- no-file request creates one project, zero project-file rows, and zero files.

### Complete Feature suite

```text
php artisan test --testsuite=Feature
```

Result: **passed â€” 57 tests, 167 assertions, 6.16 seconds**.

This includes WP-1/WP-2 API coverage, Sprint 1A authorization coverage, and all WP-3 upload tests.

### Route verification

```text
php artisan route:list --except-vendor -v
```

Result: **passed â€” 94 active routes**. No WP-3 route was added, removed, renamed, or modified.

### Syntax and style

| Verification | Result |
|---|---|
| `php -l app/Rules/AllowedProjectDocument.php` | Passed. |
| `php -l app/Http/Requests/Employer/StoreProjectRequest.php` | Passed. |
| `php -l tests/Feature/Uploads/ProjectUploadValidationTest.php` | Passed. |
| Scoped Pint on the new rule and test | Passed. |
| Scoped Pint on `StoreProjectRequest` | Reported pre-existing whole-file line-ending and formatting differences. Not auto-fixed because that would create unrelated formatting churn; the WP-3 diff in this legacy file is exactly one import and one rule addition. |
| `git diff --check` | Passed. |
| Scoped debug scan | No `dd`, `dump`, `var_dump`, `ray`, `console.log`, or `debugger` matches. |
| Dependency/migration/configuration check | No changes. |

The Pint limitation is disclosed accurately; functional, syntax, new-file style, and diff-integrity checks are green.

## Backward Compatibility

- The project-create route, controller, response, form fields, size limit, transaction, and storage path are unchanged.
- Requests without files behave as before.
- Requests with approved files behave as before after validation.
- Existing stored attachments and existing database rows are not revalidated or changed.
- The intended security change is that previously accepted unapproved formats now return validation `422` before project creation.
- Simple employer project creation remains unchanged because it has no file-upload input.

## Remaining Risks

| Risk | Assessment / mitigation |
|---|---|
| MIME detection varies by operating system/libmagic database. | The map includes documented common variants, but representative real files must be exercised on the release/staging environment before deployment. Expand only through reviewed evidence. |
| Test fakes report controlled MIME values. | Tests prove the extension/MIME pairing and request behavior, but do not constitute a production libmagic corpus. Stage real sanitized samples for all eight formats. |
| OOXML may be detected as generic ZIP. | `application/zip` is allowed for `.docx`/`.xlsx` as an explicit compatibility decision. WP-3 does not inspect package internals, so a renamed ZIP can pass those pairs; ZIP itself is already approved. Re-evaluate this exception if production MIME detection becomes reliable enough to distinguish OOXML containers without accepting generic ZIP, and consider package-structure validation if business risk requires it. |
| ZIP contents are not inspected. | Executable or oversized-expanded content may exist inside an accepted ZIP. Malware scanning, archive traversal/expansion controls, and quarantine remain future defense-in-depth. Files must be forced as downloads in WP-4. |
| Legacy OLE MIME reporting varies. | `application/x-ole-storage` and `application/CDFV2` variants are accepted for `.doc`/`.xls`; real staging files should confirm expected detection. |
| Office/PDF documents can contain harmful embedded content. | Allowlisting reduces exposure but is not malware scanning. WP-4 must use attachment disposition and `nosniff`; later scanning remains recommended. |
| Current accepted uploads remain publicly reachable. | Deliberately unchanged in WP-3. This known risk is the central WP-4 scope and must not be considered resolved. |
| No file-count limit exists. | Existing behavior is preserved; many files at 10 MiB each can increase request/storage pressure. A reviewed count/request-size limit is a future hardening decision. |
| Existing public files are not retroactively validated. | Intentional compatibility boundary. Legacy inventory/migration belongs to WP-5 after protected delivery/private writes. |

## Database, Storage, Security, and Deployment Impact

- **Database schema/data:** No schema or existing-data change. Tests use isolated SQLite only.
- **Storage configuration/location:** No change. Accepted files still follow the existing public-disk action until WP-4.
- **Dependencies:** None.
- **Routes:** None.
- **Security:** New uploads are restricted by per-file size plus extension/detected-MIME pairs; active/executable/macro/mismatch cases are denied before mutation.
- **Deployment:** Not performed. Staging should validate real sanitized file samples and MIME detection before release.

## Rollback

WP-3 has no schema, data migration, storage migration, dependency, route, or configuration rollback.

To roll back WP-3:

1. remove `AllowedProjectDocument` from `StoreProjectRequest::rules()` and its import;
2. remove `app/Rules/AllowedProjectDocument.php`;
3. remove the WP-3 upload test class only if abandoning the security requirement; retaining it as a red specification is preferable during correction;
4. retain this report as historical evidence if WP-3 was deployed or reviewed; and
5. rerun the complete Feature suite and project-create workflow in a non-production environment.

Do not delete projects, project-file rows, or stored files during rollback. Files legitimately accepted while WP-3 was active remain valid application data and must not be removed solely because validation rules changed. No schema rollback or storage relocation is required.

## Remaining Work for WP-4

WP-4 has not started. After explicit approval it must:

1. perform the mandatory pre-change repository-wide enumeration of every project-attachment rendering/download location;
2. add explicit project-file download authorization for owner, canonically matched specialist, and administrator access;
3. add a named authenticated download route and safe streamed-download response headers;
4. obtain separate approval for the additive nullable disk-metadata migration before creating it;
5. write new files to private storage while dual-reading metadata-identified legacy public files;
6. replace every known direct project-attachment link with the protected route;
7. update all deletion paths for explicit disk handling;
8. repeat the repository-wide enumeration after implementation and prove no direct `Storage::url()` project-attachment links remain; and
9. test authorization, non-enumeration, headers, missing files, both storage generations, and rollback behavior.

Legacy public-file migration/removal remains WP-5 and must not be folded into WP-4 without its separate approval gate.

## Approval Status

WP-3 implementation and local verification are complete.

Current status: **waiting for human approval before WP-4**.

- Targeted and complete Feature suites are green.
- No WP-4 work was performed.
- No commit was created.
- No push was performed.
- No deployment was performed.
