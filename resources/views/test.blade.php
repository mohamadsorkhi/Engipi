@extends('layouts.master')

@section('title')
    انتخاب مهارت
@endsection

@section('content')

<div class="row bp-skill-wizard-page">
    <div class="col-12">
        <div class="card bp-wizard-card">

            <div class="card-header">
                <h4 class="card-title">انتخاب مهارت</h4>
            </div>

            <div class="card-body" id="skillWizardRoot">

                <div class="bp-wizard__progress" aria-label="مراحل ثبت مهارت">
                    <div class="bp-wizard__mobile-progress">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span id="wizardMobileCount">مرحله ۱ از ۶</span>
                            <strong id="wizardMobileTitle">انتخاب حوزه</strong>
                        </div>
                        <div class="progress" aria-hidden="false">
                            <div class="progress-bar" id="wizardProgressBar" role="progressbar" style="width: 16.67%" aria-valuenow="16.67" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <ol class="bp-stepper">
                        <li class="bp-stepper__item is-active" data-step-indicator="1" aria-current="step"><span>۱</span><strong>حوزه</strong></li>
                        <li class="bp-stepper__item" data-step-indicator="2"><span>۲</span><strong>گرایش</strong></li>
                        <li class="bp-stepper__item" data-step-indicator="3"><span>۳</span><strong>مهارت‌های پردازشی</strong></li>
                        <li class="bp-stepper__item" data-step-indicator="4"><span>۴</span><strong>مهارت‌های میدانی</strong></li>
                        <li class="bp-stepper__item" data-step-indicator="5"><span>۵</span><strong>سطح و سابقه</strong></li>
                        <li class="bp-stepper__item" data-step-indicator="6"><span>۶</span><strong>بازبینی</strong></li>
                    </ol>
                </div>

                <aside class="bp-wizard-summary" id="wizardStickySummary" aria-label="خلاصه انتخاب‌ها">
                    <strong>خلاصه انتخاب‌ها</strong>
                    <div class="bp-wizard-summary__stats">
                        <span><b id="summaryDomainCount">۰</b> حوزه</span>
                        <span><b id="summarySubdomainCount">۰</b> گرایش</span>
                        <span><b id="summarySkillCount">۰</b> مهارت</span>
                        <span><b id="summarySoftwareCount">۰</b> پردازشی</span>
                        <span><b id="summaryFieldCount">۰</b> میدانی</span>
                    </div>
                </aside>

                <div class="alert alert-warning d-none" id="wizardValidationAlert" role="alert"></div>

                <section class="bp-wizard__panel is-active" data-wizard-step="1" aria-labelledby="wizardStep1Title">
                    <div class="bp-wizard__heading">
                        <span>مرحله اول</span>
                        <h5 id="wizardStep1Title">حوزه‌های تخصصی خود را انتخاب کنید</h5>
                        <p>برای ادامه حداقل یک و حداکثر دو حوزه انتخاب کنید.</p>
                    </div>

                {{-- DOMAIN --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">حوزه (حداکثر ۲)</label>
                    <div class="ep-select bp-wizard-control" id="dd-wrap">
                        <div class="ep-select__trigger" id="dd-trigger" role="combobox" tabindex="0" aria-haspopup="listbox" aria-expanded="false" aria-controls="dd-menu">
                            <span id="dd-label">انتخاب حوزه</span>
                            <i class="ri-arrow-down-s-line ep-select__chevron"></i>
                        </div>
                        <ul class="ep-select__menu" id="dd-menu" role="listbox"></ul>
                    </div>
                </div>

                <div id="selected-domains" class="mb-2 d-flex flex-wrap"></div>

                </section>

                <section class="bp-wizard__panel" data-wizard-step="2" aria-labelledby="wizardStep2Title" hidden>
                    <div class="bp-wizard__heading">
                        <span>مرحله دوم</span>
                        <h5 id="wizardStep2Title">گرایش‌های مرتبط را انتخاب کنید</h5>
                        <p>گرایش‌ها بر اساس حوزه‌های انتخاب‌شده نمایش داده می‌شوند.</p>
                    </div>

                {{-- SUBDOMAIN — custom dropdown (native <select> cannot be styled cross-browser) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">زیرشاخه</label>
                    <div class="ep-select ep-select--disabled bp-wizard-control" id="sd-wrap">
                        <div class="ep-select__trigger" id="sd-trigger" role="combobox" tabindex="0" aria-haspopup="listbox" aria-expanded="false" aria-disabled="true" aria-controls="sd-menu">
                            <span id="sd-label">اول حوزه را انتخاب کنید</span>
                            <i class="ri-arrow-down-s-line ep-select__chevron"></i>
                        </div>
                        <ul class="ep-select__menu" id="sd-menu" role="listbox"></ul>
                    </div>
                </div>

                {{-- SELECTED SUBDOMAINS --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">گرایش‌های انتخاب شده (حداکثر ۲)</label>
                    <div id="selected-subdomains" class="d-flex flex-wrap gap-2"></div>
                </div>

                </section>

                <section class="bp-wizard__panel" data-wizard-step="3" aria-labelledby="wizardStep3Title" hidden>
                    <div class="bp-wizard__heading">
                        <span>مرحله سوم</span>
                        <h5 id="wizardStep3Title">مهارت‌های پردازشی خود را انتخاب کنید</h5>
                        <p>نرم‌افزارها و مهارت‌های پردازشی مرتبط با تجربه تخصصی خود را انتخاب کنید.</p>
                    </div>
                    <div class="mb-4 bp-skillsec bp-skillsec--blue">
                        <label class="bp-skillsec-label" for="skillSearchSoftware">
                            <i class="ri-code-box-line" aria-hidden="true"></i>مهارت‌های پردازشی / نرم‌افزاری
                        </label>
                        <div class="bp-skillsec-search">
                            <i class="ri-search-line" aria-hidden="true"></i>
                            <input type="search" id="skillSearchSoftware" placeholder="جست‌وجوی مهارت پردازشی..." aria-label="جست‌وجوی مهارت‌های پردازشی">
                        </div>
                        <div id="skillsContainerSoftware" class="row g-3 bp-software-grid"></div>
                        <div class="bp-skillsec-empty d-none" id="skillsEmptySoftware" role="status"><i class="ri-code-box-line" aria-hidden="true"></i><strong>برای گرایش‌های انتخاب‌شده مهارت پردازشی ثبت نشده است.</strong><span>انتخاب مهارت پردازشی اختیاری است و می‌توانید ادامه دهید.</span></div>
                    </div>
                </section>

                <section class="bp-wizard__panel" data-wizard-step="4" aria-labelledby="wizardStep4Title" hidden>
                    <div class="bp-wizard__heading">
                        <span>مرحله چهارم</span>
                        <h5 id="wizardStep4Title">مهارت‌های میدانی خود را انتخاب کنید</h5>
                        <p>مهارت‌هایی را انتخاب کنید که تجربه عملی یا اجرایی در آن‌ها دارید.</p>
                    </div>
                    <div class="mb-4 bp-skillsec bp-skillsec--teal">
                        <label class="bp-skillsec-label" for="skillSearchField">
                            <i class="ri-tools-line" aria-hidden="true"></i>مهارت‌های میدانی
                        </label>
                        <div class="bp-skillsec-search">
                            <i class="ri-search-line" aria-hidden="true"></i>
                            <input type="search" id="skillSearchField" placeholder="جست‌وجوی مهارت میدانی..." aria-label="جست‌وجوی مهارت‌های میدانی">
                        </div>
                        <div id="skillsContainerField" class="row g-3 bp-field-grid"></div>
                        <div class="bp-skillsec-empty d-none" id="skillsEmptyField" role="status"><i class="ri-tools-line" aria-hidden="true"></i><strong>برای گرایش‌های انتخاب‌شده مهارت میدانی ثبت نشده است.</strong><span>اگر مهارت پردازشی انتخاب کرده‌اید، می‌توانید ادامه دهید.</span></div>
                    </div>
                    <div class="bp-suggest-skill mb-4">
                        <div>
                            <strong>مهارت موردنظر شما در فهرست نیست؟</strong>
                            <span>آن را برای بررسی و اضافه‌شدن به مهارت‌های میدانی پیشنهاد دهید.</span>
                        </div>
                        <button type="button" class="btn btn-outline-success bp-success-action" data-bs-toggle="modal" data-bs-target="#skillSuggestionModal">
                            <i class="ri-lightbulb-flash-line me-1" aria-hidden="true"></i>پیشنهاد مهارت جدید
                        </button>
                    </div>
                </section>
                <div class="modal fade" id="skillSuggestionModal" tabindex="-1" aria-labelledby="skillSuggestionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form id="skillSuggestionForm" action="{{ route('skill-suggestions.store') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <div>
                                        <h5 class="modal-title" id="skillSuggestionModalLabel">پیشنهاد مهارت میدانی جدید</h5>
                                        <p class="text-muted small mb-0 mt-1">پیشنهاد شما پس از بررسی مدیر به لیست مهارت‌ها اضافه می‌شود.</p>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="skillSuggestionAlert" class="alert d-none" role="alert"></div>
                                    <div class="mb-3">
                                        <label for="suggestedSkillName" class="form-label">نام مهارت پیشنهادی <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="suggestedSkillName" name="skill_name" maxlength="255" required autocomplete="off">
                                        <div class="invalid-feedback" data-error-for="skill_name"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="suggestedSkillSubdomain" class="form-label">حوزه / زیرشاخه مرتبط <span class="text-danger">*</span></label>
                                        <select class="form-select" id="suggestedSkillSubdomain" name="subdomain_id" required>
                                            <option value="">انتخاب کنید</option>
                                            @foreach($domains as $domain)
                                                <optgroup label="{{ $domain->name }}">
                                                    @foreach($domain->subdomains as $subdomain)
                                                        <option value="{{ $subdomain->id }}">{{ $subdomain->name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" data-error-for="subdomain_id"></div>
                                    </div>
                                    <div>
                                        <label for="suggestedSkillDescription" class="form-label">توضیح کوتاه <span class="text-muted small">(اختیاری)</span></label>
                                        <textarea class="form-control" id="suggestedSkillDescription" name="description" rows="3" maxlength="1000" placeholder="کاربرد یا دلیل نیاز به این مهارت را کوتاه توضیح دهید."></textarea>
                                        <div class="invalid-feedback" data-error-for="description"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                                    <button type="submit" class="btn btn-success" id="skillSuggestionSubmit">
                                        <span class="spinner-border spinner-border-sm me-1 d-none" aria-hidden="true"></span>
                                        ارسال پیشنهاد
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <section class="bp-wizard__panel" data-wizard-step="5" aria-labelledby="wizardStep5Title" hidden>
                    <div class="bp-wizard__heading">
                        <span>مرحله پنجم</span>
                        <h5 id="wizardStep5Title">سطح و سابقه هر مهارت را تکمیل کنید</h5>
                        <p>برای همه مهارت‌های انتخاب‌شده سطح و تعداد سال تجربه را مشخص کنید.</p>
                    </div>

                {{-- SELECTED SKILLS --}}
                <div class="alert alert-danger d-none bp-step5-validation-alert" id="wizardStep5ValidationAlert" role="alert">برای ادامه، سطح مهارت و سابقه فعالیت همه مهارت‌های انتخاب‌شده را مشخص کنید.</div>
                <div class="mb-4 bp-selected-section">
                    <label class="form-label fw-bold">مهارت‌های انتخاب شده (حداکثر ۵)</label>
                    <div id="selected-skills"></div>
                </div>

                </section>

                <section class="bp-wizard__panel" data-wizard-step="6" aria-labelledby="wizardStep6Title" hidden>
                    <div class="bp-wizard__heading">
                        <span>مرحله ششم</span>
                        <h5 id="wizardStep6Title">بازبینی و ثبت</h5>
                        <p>پیش از ثبت نهایی، انتخاب‌های خود را بررسی کنید.</p>
                    </div>
                    <div class="bp-preview-stats" aria-label="آمار انتخاب‌ها">
                        <div><b id="previewDomainCount">۰</b><span>حوزه</span></div>
                        <div><b id="previewSubdomainCount">۰</b><span>گرایش</span></div>
                        <div><b id="previewSkillCount">۰</b><span>مهارت</span></div>
                        <div><b id="previewSoftwareCount">۰</b><span>پردازشی</span></div>
                        <div><b id="previewFieldCount">۰</b><span>میدانی</span></div>
                    </div>
                    <div class="bp-preview" aria-label="پیش‌نمایش انتخاب‌ها">
                        <section class="bp-preview__section">
                            <div class="bp-preview__section-head">
                                <h6>حوزه‌ها</h6>
                                <button type="button" class="btn btn-sm btn-link" data-preview-edit-step="1">ویرایش</button>
                            </div>
                            <div class="bp-preview__chips" id="previewDomains"></div>
                        </section>
                        <section class="bp-preview__section">
                            <div class="bp-preview__section-head">
                                <h6>گرایش‌ها</h6>
                                <button type="button" class="btn btn-sm btn-link" data-preview-edit-step="2">ویرایش</button>
                            </div>
                            <div class="bp-preview__chips" id="previewSubdomains"></div>
                        </section>
                        <section class="bp-preview__section">
                            <div class="bp-preview__section-head">
                                <h6>مهارت‌ها، سطح و سابقه</h6>
                                <div>
                                    <button type="button" class="btn btn-sm btn-link" data-preview-edit-step="3">ویرایش مهارت‌های پردازشی</button>
                                    <button type="button" class="btn btn-sm btn-link" data-preview-edit-step="4">ویرایش مهارت‌های میدانی</button>
                                    <button type="button" class="btn btn-sm btn-link" data-preview-edit-step="5">ویرایش سطح و سابقه</button>
                                </div>
                            </div>
                            <div class="bp-preview__skills" id="previewSkills"></div>
                        </section>
                    </div>
                    <div class="alert d-none mt-3" id="wizardSubmitAlert" role="alert" aria-live="polite"></div>
                </section>

                <div class="bp-wizard__actions">
                    <button type="button" class="btn btn-light" id="wizardBackBtn" hidden>
                        <i class="ri-arrow-right-line me-1"></i>بازگشت
                    </button>
                    <button type="button" class="btn btn-primary ms-auto" id="wizardNextBtn" disabled>
                        ادامه<i class="ri-arrow-left-line ms-1"></i>
                    </button>
                    {{-- The existing final submit button and its handler are preserved. --}}
                    <button type="button" class="btn btn-primary ms-auto" id="saveBtn" disabled hidden>
                        <span class="spinner-border spinner-border-sm me-1 d-none" id="saveBtnSpinner" aria-hidden="true"></span>
                        <span id="saveBtnLabel">ثبت مهارت‌ها</span>
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

{{-- Subdomain lookup keyed by domain UUID --}}
<script>
const domainSubdomainsMap = @json(
    $domains->mapWithKeys(fn($d) => [
        $d->id => $d->subdomains->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()
    ])
);
</script>

@push('styles')
<style>
/* ── Custom subdomain dropdown ───────────────────────────────────────── */
.ep-select {
    position: relative;
    user-select: none;
}
.ep-select__trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
    padding: 0.475rem 0.9rem;
    border: 1px solid var(--ep-border-2, #dee2e6);
    border-radius: 8px;
    background: #ffffff;
    color: var(--ep-text, #1e293b);
    cursor: pointer;
    font-size: 1rem;
    line-height: 1.5;
    transition: border-color .15s, box-shadow .15s;
}
.ep-select--disabled .ep-select__trigger {
    opacity: .55;
    cursor: not-allowed;
    pointer-events: none;
}
.ep-select__trigger:hover {
    border-color: var(--ep-accent, #1F6FEB);
}
.ep-select--open .ep-select__trigger {
    border-color: var(--ep-accent, #1F6FEB);
    box-shadow: 0 0 0 3px rgba(31,111,235,.12);
}
.ep-select__chevron {
    flex-shrink: 0;
    transition: transform .2s;
    font-size: 1.1rem;
}
.ep-select--open .ep-select__chevron {
    transform: rotate(180deg);
}
.ep-select__menu {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    z-index: 1000;
    list-style: none;
    margin: 0;
    padding: .25rem 0;
    background: #ffffff;
    border: 1px solid var(--ep-border-2, #dee2e6);
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,.10);
    max-height: min(320px, 50vh);
    overflow-x: hidden;
    overflow-y: auto;
    overscroll-behavior: contain;
    -webkit-overflow-scrolling: touch;
}
.ep-select--open .ep-select__menu {
    display: block;
}
.ep-select__option {
    padding: .5rem .9rem;
    cursor: pointer;
    color: var(--ep-text, #1e293b);
    transition: background .12s;
    font-size: .95rem;
}
.ep-select__option:hover {
    background: rgba(31,111,235,.10);
    color: var(--ep-accent, #1F6FEB);
}

/* ── Skill-type section headers (پردازش‌ها / مهارت‌های میدانی) ──────────── */
.bp-skillsec-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1.05rem;
    font-weight: 700;
    margin-bottom: 1.1rem;
}
.bp-skillsec-label i { font-size: 1.3rem; }
.bp-skillsec--blue .bp-skillsec-label { color: var(--bp-blue); }
.bp-skillsec--teal .bp-skillsec-label { color: var(--bp-teal); }
.bp-skillsec--teal { padding-bottom: 4rem; }
.bp-skillsec-divider {
    height: 1px;
    background: var(--bp-hair);
    margin: 20px 0 16px;
}
.bp-skillsec-search {
    position: relative;
    margin-bottom: 14px;
    max-width: 320px;
}
.bp-skillsec-search i {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    color: var(--bp-muted);
    font-size: 1rem;
    pointer-events: none;
}
.bp-skillsec-search input {
    width: 100%;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r);
    padding: 7px 38px 7px 12px;
    font-size: .85rem;
    color: var(--bp-text);
    background: #fff;
    transition: border-color .15s, box-shadow .15s;
}
.bp-skillsec-search input:focus { outline: none; }
.bp-skillsec--blue .bp-skillsec-search input:focus { border-color: var(--bp-blue); box-shadow: 0 0 0 3px var(--bp-tint-blue); }
.bp-skillsec--teal .bp-skillsec-search input:focus { border-color: var(--bp-teal); box-shadow: 0 0 0 3px var(--bp-tint-teal); }
.bp-skillsec-empty {
    padding: 14px;
    text-align: center;
    color: var(--bp-muted);
    font-size: .85rem;
    margin: 0;
}

/* ── Available-skill cards: depth + hover-lift (mirrors .bp-card / .feature on landing) ── */
.bp-skill-card {
    cursor: pointer;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    width: 100%;
    min-height: 84px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--bp-bg);
    box-shadow: var(--bp-sh-sm);
    transition: transform .2s var(--bp-ease), box-shadow .2s var(--bp-ease), border-color .2s;
}
.bp-skill-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--bp-sh-md);
}

/* ── Skill card expandable panel ─────────────────────────────────────── */
.bp-skill-card__face {
    position: relative;
    padding: 8px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
}
.bp-skill-card__controls {
    display: none;
    width: 100%;
    padding: 8px 10px 10px;
    border-top: 1px solid var(--bp-border);
    direction: rtl;
}
.bp-skill-card--expanded { justify-content: flex-start; }
.bp-skill-card--expanded .bp-skill-card__controls { display: block; }
.bp-skill-card--added:hover { transform: none !important; box-shadow: var(--bp-sh-sm) !important; }
.bp-card-chips { display: flex; gap: 3px; flex-wrap: wrap; margin-bottom: 6px; justify-content: center; }
.bp-card-years { margin-bottom: 8px; direction: rtl; }
.bp-card-years span {
    display: block;
    font-size: .7rem;
    color: var(--bp-muted);
    margin-bottom: 3px;
}
.bp-card-years select {
    width: 100%;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r);
    padding: 4px 6px;
    font-size: .78rem;
    color: var(--bp-text);
    background: #fff;
    direction: rtl;
    cursor: pointer;
}
.bp-card-years select:focus { outline: none; border-color: var(--bp-blue); }
.bp-card-add-btn {
    width: 100%;
    padding: 5px 0;
    border: none;
    border-radius: var(--bp-r);
    font-size: .74rem;
    font-weight: 700;
    cursor: pointer;
    transition: opacity .15s;
    color: #fff;
}
.bp-card-add-btn:hover { opacity: .85; }
.bp-card-level-sel {
    width: 100%;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r);
    padding: 4px 6px;
    font-size: .78rem;
    color: var(--bp-text);
    background: #fff;
    margin-bottom: 6px;
    direction: rtl;
    cursor: pointer;
}
.bp-card-level-sel:focus { outline: none; border-color: var(--bp-blue); }
.bp-skill-card--teal .bp-level-chip--active { background: var(--bp-teal); border-color: var(--bp-teal); }
.bp-skill-card--teal .bp-level-chip:hover   { border-color: var(--bp-teal); color: var(--bp-teal); }

/* ── Selected-skills section card ───────────────────────────────────── */
.bp-selected-section {
    background: #f8f9fb;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    padding: 1.25rem;
}

/* ── Selected-skill ID cards ────────────────────────────────────────── */
#selected-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    direction: rtl;
}
.bp-sid-card {
    width: 250px;
    aspect-ratio: 1.6 / 1;
    border: 2px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    background: var(--bp-bg);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 12px 12px 8px;
    text-align: center;
    box-shadow: var(--bp-sh-sm);
    overflow: hidden;
}
.bp-sid-card__body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    flex: 1;
    min-height: 0;
    width: 100%;
}
.bp-sid-card__icon {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bp-sid-card__icon i { font-size: 0.8rem; color: #fff; }
.bp-sid-card__name {
    font-weight: 700;
    font-size: .8rem;
    line-height: 1.25;
    color: var(--bp-text);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-break: break-word;
    width: 100%;
}
.bp-sid-card__meta {
    display: flex;
    align-items: center;
    gap: 5px;
    flex-shrink: 0;
}
.bp-sid-card__level {
    padding: 1px 7px;
    border-radius: 999px;
    font-size: .67rem;
    font-weight: 700;
    white-space: nowrap;
}
.bp-sid-card__years { font-size: .7rem; color: var(--bp-muted); white-space: nowrap; }
.bp-sid-card__del {
    width: 100%;
    padding: 4px 0;
    border: 1px solid #dc3545;
    border-radius: var(--bp-r);
    background: transparent;
    font-size: .72rem;
    font-weight: 600;
    color: #dc3545;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .15s, color .15s;
}
.bp-sid-card__del:hover { background: #dc3545; color: #fff; }

/* ── Selected-skill ID cards — mobile ───────────────────────────────── */
@media (max-width: 576px) {
    #selected-skills { gap: 10px; }
    .bp-sid-card {
        width: 100%;                /* one per row; aspect-ratio still enforces 1.6:1 height */
        padding: 10px 10px 7px;
    }
    .bp-sid-card__icon   { width: 24px; height: 24px; }
    .bp-sid-card__icon i { font-size: .68rem; }
    .bp-sid-card__name   { font-size: .74rem; }
    .bp-sid-card__level  { font-size: .61rem; }
    .bp-sid-card__years  { font-size: .63rem; }
    .bp-sid-card__del    { font-size: .67rem; padding: 3px 0; }
}

/* ── Responsive ──────────────────────────────────────────────────────── */
.bp-wizard__progress {
    margin-bottom: 28px;
    padding: 20px;
    border: 1px solid var(--bp-hair);
    border-radius: var(--bp-r-lg);
    background: #fff;
}
.bp-wizard__mobile-progress { display: none; }
.bp-stepper {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 0;
    margin: 0;
    list-style: none;
}
.bp-stepper__item {
    position: relative;
    z-index: 1;
    display: flex;
    flex: 1;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    color: var(--bp-muted);
    text-align: center;
}
.bp-stepper__item:not(:last-child)::after {
    position: absolute;
    z-index: -1;
    top: 17px;
    right: 50%;
    width: 100%;
    height: 2px;
    background: var(--bp-hair);
    content: '';
}
.bp-stepper__item span {
    display: inline-flex;
    width: 36px;
    height: 36px;
    align-items: center;
    justify-content: center;
    border: 2px solid var(--bp-hair);
    border-radius: 50%;
    background: #fff;
    font-weight: 800;
}
.bp-stepper__item strong { font-size: .78rem; }
.bp-stepper__item.is-active { color: var(--bp-blue); }
.bp-stepper__item.is-active span {
    border-color: var(--bp-blue);
    background: var(--bp-blue);
    color: #fff;
}
.bp-stepper__item.is-complete { color: var(--bp-teal); }
.bp-stepper__item.is-complete span {
    border-color: var(--bp-teal);
    background: var(--bp-teal);
    color: #fff;
}
.bp-stepper__item.is-complete:not(:last-child)::after { background: var(--bp-teal); }
.bp-wizard__panel[hidden] { display: none !important; }
.bp-wizard__panel.is-entering { animation: bpWizardPanelIn 250ms ease both; }
@keyframes bpWizardPanelIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.bp-wizard__heading {
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--bp-hair);
}
.bp-wizard__heading > span {
    display: block;
    margin-bottom: 5px;
    color: var(--bp-blue);
    font-size: .75rem;
    font-weight: 800;
}
.bp-wizard__heading h5 { margin-bottom: 7px; color: var(--bp-ink); }
.bp-wizard__heading p { margin: 0; color: var(--bp-muted); font-size: .88rem; }
.bp-wizard__actions {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 28px;
    padding-top: 20px;
    border-top: 1px solid var(--bp-hair);
}
.bp-wizard-summary {
    position: sticky;
    z-index: 15;
    top: 76px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 22px;
    padding: 13px 16px;
    border: 1px solid rgba(59,130,246,.18);
    border-radius: var(--bp-r-lg);
    background: rgba(255,255,255,.96);
    box-shadow: 0 8px 22px rgba(15,23,42,.06);
    backdrop-filter: blur(10px);
}
.bp-wizard-summary > strong { color: var(--bp-ink); font-size: .82rem; white-space: nowrap; }
.bp-wizard-summary__stats { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.bp-wizard-summary__stats span {
    padding: 5px 9px;
    border-radius: 999px;
    background: #f1f5f9;
    color: var(--bp-muted);
    font-size: .72rem;
}
.bp-wizard-summary__stats b { color: var(--bp-ink); }
.bp-preview-stats {
    display: grid;
    grid-template-columns: repeat(5, minmax(0, 1fr));
    gap: 10px;
    margin-bottom: 18px;
}
.bp-preview-stats > div {
    padding: 14px 8px;
    border: 1px solid var(--bp-hair);
    border-radius: var(--bp-r-lg);
    background: #fff;
    text-align: center;
}
.bp-preview-stats b { display: block; color: var(--bp-blue); font-size: 1.25rem; }
.bp-preview-stats span { color: var(--bp-muted); font-size: .72rem; }
.bp-preview { display: grid; gap: 14px; }
.bp-preview__section {
    padding: 18px;
    border: 1px solid var(--bp-hair);
    border-radius: var(--bp-r-lg);
    background: #fff;
}
.bp-preview__section-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
}
.bp-preview__section-head h6 { margin: 0; color: var(--bp-ink); }
.bp-preview__section-head .btn { padding: 2px 5px; text-decoration: none; }
.bp-preview__chips { display: flex; flex-wrap: wrap; gap: 8px; }
.bp-preview__chip {
    padding: 6px 10px;
    border-radius: 999px;
    background: #eef4ff;
    color: var(--bp-blue);
    font-size: .78rem;
    font-weight: 700;
}
.bp-preview__skills { display: grid; gap: 9px; }
.bp-preview-skill {
    display: grid;
    grid-template-columns: minmax(150px, 1.5fr) repeat(4, minmax(90px, 1fr));
    align-items: center;
    gap: 10px;
    padding: 12px;
    border: 1px solid #edf0f4;
    border-radius: 10px;
    background: #fafbfc;
}
.bp-preview-skill strong { color: var(--bp-ink); }
.bp-preview-skill span { color: var(--bp-muted); font-size: .78rem; }
.bp-preview-skill__type {
    display: inline-flex;
    width: fit-content;
    padding: 4px 8px;
    border-radius: 999px;
    background: #eef4ff;
    color: var(--bp-blue) !important;
    font-weight: 700;
}
.bp-preview-skill__type.is-field { background: var(--bp-tint-teal); color: var(--bp-teal) !important; }
#wizardSubmitAlert { white-space: pre-line; }
.bp-preview-skeleton {
    display: grid;
    gap: 14px;
}
.bp-preview-skeleton > div {
    padding: 18px;
    border: 1px solid var(--bp-hair);
    border-radius: var(--bp-r-lg);
    background: #fff;
}
.bp-preview-skeleton span {
    display: block;
    margin-bottom: 14px;
    color: var(--bp-ink);
    font-weight: 800;
}
.bp-preview-skeleton i {
    display: block;
    width: 100%;
    height: 12px;
    margin-top: 9px;
    border-radius: 999px;
    background: #eef1f5;
}
.bp-preview-skeleton i.is-short { width: 58%; }
.bp-sid-card__fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
    width: 100%;
    padding: 0 12px 12px;
}
.bp-sid-card__field label {
    display: block;
    margin-bottom: 5px;
    color: var(--bp-muted);
    font-size: .72rem;
    font-weight: 700;
}
.bp-sid-card__field select {
    width: 100%;
    min-height: 38px;
    border: 1px solid var(--bp-hair);
    border-radius: 8px;
    background: #fff;
}

@media (max-width: 767.98px) {
    #saveBtn { width: 100%; }
    #domainContainer .btn { font-size: 0.8rem; padding: 0.35rem 0.75rem; }
    .bp-stepper { display: none; }
    .bp-wizard__progress { padding: 16px; }
    .bp-wizard__mobile-progress { display: block; }
    .bp-wizard__mobile-progress .progress { height: 6px; }
    .bp-wizard__mobile-progress span { color: var(--bp-muted); font-size: .75rem; }
    .bp-wizard__mobile-progress strong { color: var(--bp-ink); font-size: .82rem; }
    .bp-wizard-summary { display: none; }
    .bp-preview-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .bp-preview-stats > div:last-child { grid-column: span 2; }
    .bp-preview__section-head { align-items: flex-start; }
    .bp-preview__section-head > div { display: flex; flex-direction: column; align-items: flex-end; }
    .bp-preview-skill { grid-template-columns: 1fr 1fr; }
    .bp-preview-skill strong { grid-column: span 2; }
    .bp-wizard__actions {
        position: sticky;
        z-index: 20;
        bottom: 0;
        margin-right: -16px;
        margin-left: -16px;
        padding: 12px 16px;
        background: rgba(255,255,255,.96);
        box-shadow: 0 -8px 24px rgba(15,23,42,.07);
        backdrop-filter: blur(8px);
    }
    .bp-wizard__actions .btn { min-height: 44px; flex: 1; }
    .bp-sid-card__fields { grid-template-columns: 1fr; }
}
/* SKILL-WIZARD-UI-POLISH: scoped EngiPi design system */
.bp-skill-wizard-page {
    --bp-blue: #2563EB; --bp-blue-hover: #1D4ED8; --bp-tint-blue: #EFF6FF;
    --bp-teal: #14B8A6; --bp-teal-hover: #0F9F92; --bp-tint-teal: #F0FDFA;
    --bp-ink: #1E293B; --bp-text: #1E293B; --bp-muted: #64748B;
    --bp-muted-light: #94A3B8; --bp-border: #E2E8F0; --bp-hair: #E2E8F0;
    --bp-surface: #F8FAFC; --bp-bg: #FFFFFF; --bp-danger: #DC2626;
    max-width: 1180px; margin-inline: auto;
}
.bp-wizard-card { border: 1px solid #E2E8F0; background: #fff; box-shadow: 0 10px 30px rgba(15,23,42,.06); }
.bp-wizard-card > .card-header { background: #fff; border-bottom-color: #E2E8F0; padding: 18px 22px; }
.bp-wizard-card > .card-body { padding: 22px; background: #fff; }
.bp-wizard-control { width: min(76%, 680px); }
.ep-select__trigger { min-height: 46px; border-color: #E2E8F0; color: #1E293B; }
.ep-select__trigger:hover, .ep-select--open .ep-select__trigger { border-color: #2563EB; }
.ep-select__trigger:focus-visible, .ep-select__option:focus-visible { outline: 3px solid rgba(37,99,235,.24); outline-offset: 2px; }
.ep-select__option:hover, .ep-select__option:focus { background: #EFF6FF; color: #1D4ED8; }
.bp-wizard__progress { max-width: 980px; margin: 0 auto 20px; padding: 18px 24px; background: #F8FAFC; }
.bp-stepper { max-width: 880px; margin-inline: auto; }
.bp-stepper__item { gap: 9px; color: #94A3B8; }
.bp-stepper__item:not(:last-child)::after { top: 18px; height: 3px; background: #E2E8F0; }
.bp-stepper__item span { width: 38px; height: 38px; border-color: #E2E8F0; }
.bp-stepper__item strong { font-size: .8rem; font-weight: 650; }
.bp-stepper__item.is-active strong { font-weight: 850; }
.bp-stepper__item.is-active span { border-color: #2563EB; background: #2563EB; box-shadow: 0 0 0 5px #EFF6FF; }
.bp-stepper__item.is-complete span { border-color: #14B8A6; background: #14B8A6; }
.bp-stepper__item.is-complete:not(:last-child)::after { background: #14B8A6; }
.bp-wizard-summary { top: 76px; margin: 0 0 20px; border-color: #E2E8F0; padding: 12px 14px; }
.bp-wizard-summary__stats span { display: inline-flex; align-items: baseline; gap: 4px; padding: 6px 10px; background: #F8FAFC; border: 1px solid #E2E8F0; }
.bp-wizard-summary__stats b { color: #2563EB; font-size: 1rem; font-weight: 850; }
.bp-wizard__panel { min-height: 0; padding: 2px 2px 0; }
.bp-wizard__heading { margin-bottom: 18px; padding-bottom: 14px; }
.bp-wizard__heading > span { margin-bottom: 7px; color: #2563EB; font-size: .77rem; letter-spacing: .01em; }
.bp-wizard__heading h5 { margin-bottom: 8px; color: #1E293B; font-size: clamp(1.15rem,2vw,1.42rem); font-weight: 850; }
.bp-wizard__heading p { color: #64748B; font-size: .9rem; }
.bp-wizard__actions { margin-top: 22px; padding-top: 18px; }
.bp-wizard__actions .btn { min-height: 46px; padding-inline: 24px; border-radius: 9px; font-weight: 750; }
.bp-wizard__actions .btn-primary { border-color: #2563EB; background: #2563EB; }
.bp-wizard__actions .btn-primary:hover { border-color: #1D4ED8; background: #1D4ED8; }
.bp-wizard__actions .btn-primary:active { transform: translateY(1px); }
.bp-wizard__actions .btn-primary:focus-visible { outline: 3px solid rgba(37,99,235,.3); outline-offset: 2px; }
.bp-wizard__actions .btn-primary:disabled { border-color: #CBD5E1; background: #CBD5E1; color: #64748B; opacity: 1; }
.bp-wizard__actions .btn-light { border: 1px solid #E2E8F0; background: #fff; color: #64748B; }
#saveBtn { min-width: 180px; box-shadow: 0 8px 18px rgba(37,99,235,.2); }
#selected-domains .btn, #selected-subdomains .btn { min-height: 34px; margin: 3px !important; padding: 5px 10px; border: 1px solid #BFDBFE; border-radius: 999px; background: #EFF6FF; color: #1D4ED8; font-size: .78rem; transition: background .16s,border-color .16s,transform .16s; }
#selected-subdomains .btn { border-color: #99F6E4; background: #F0FDFA; color: #0F766E; }
#selected-domains .btn:hover, #selected-subdomains .btn:hover { transform: translateY(-1px); filter: saturate(1.1); }
#selected-domains .btn:focus-visible, #selected-subdomains .btn:focus-visible, .bp-sid-card__del:focus-visible { outline: 3px solid rgba(37,99,235,.25); outline-offset: 2px; }
.bp-skillsec { padding: 16px; border: 1px solid #E2E8F0; border-radius: 14px; }
.bp-skillsec--blue { border-inline-start: 3px solid #2563EB; background: linear-gradient(135deg,#EFF6FF 0,#fff 26%); }
.bp-skillsec--teal { padding-bottom: 16px; border-inline-start: 3px solid #14B8A6; background: linear-gradient(135deg,#F0FDFA 0,#fff 26%); }
.bp-skillsec-divider { margin: 18px 0; }
.bp-skill-card { min-height: 104px; border-color: #E2E8F0; background: #fff; box-shadow: 0 3px 10px rgba(15,23,42,.05); }
.bp-skill-card:hover { border-color: #93C5FD; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37,99,235,.1); }
.bp-skill-card--teal:hover { border-color: #5EEAD4; box-shadow: 0 10px 20px rgba(20,184,166,.1); }
.bp-skill-card--added { background: #EFF6FF; }
.bp-skill-card--teal.bp-skill-card--added { background: #F0FDFA; }
.bp-skillsec-empty { margin: 8px 0 0; padding: 24px 16px; border: 1px dashed #CBD5E1; border-radius: 12px; background: #F8FAFC; text-align: center; }
.bp-skillsec-empty i, .bp-skillsec-empty strong, .bp-skillsec-empty span { display: block; }
.bp-skillsec-empty i { margin-bottom: 6px; color: #94A3B8; font-size: 1.5rem; }
.bp-skillsec-empty strong { color: #1E293B; font-size: .88rem; }
.bp-skillsec-empty span { margin-top: 3px; color: #64748B; font-size: .78rem; }
.bp-skill-loading { min-height: 104px; overflow: hidden; border: 1px solid #E2E8F0; border-radius: 12px; background: #fff; }
.bp-skill-loading::before { display: block; width: 100%; height: 100%; min-height: 104px; background: linear-gradient(90deg,#F1F5F9 25%,#E2E8F0 38%,#F1F5F9 63%); background-size: 400% 100%; animation: bpSkeleton 1.2s ease infinite; content: ''; }
@keyframes bpSkeleton { to { background-position: -100% 0; } }
.bp-selected-section { background: #F8FAFC; border-color: #E2E8F0; }
.bp-sid-card { width: min(100%,290px); aspect-ratio: auto; min-height: 220px; border-width: 1px; }
.bp-sid-card__del { border-color: #DC2626; color: #DC2626; }
.bp-sid-card__del:hover { background: #DC2626; }
.bp-preview-stats > div { background: #F8FAFC; }
.bp-preview-stats b { color: #2563EB; font-size: 1.4rem; font-weight: 850; }
.bp-preview__chip { background: #EFF6FF; color: #1D4ED8; }
.bp-success-action { border-color: #14B8A6 !important; color: #0F766E !important; }
.bp-success-action:hover { background: #14B8A6 !important; color: #fff !important; }
#wizardValidationAlert:focus, #wizardSubmitAlert:focus { outline: 3px solid rgba(220,38,38,.18); }
@media (max-width: 991.98px) { .bp-wizard-control { width: 90%; } .bp-skill-card { min-height: 112px; } }
@media (max-width: 767.98px) {
    .bp-skill-wizard-page { margin-inline: -6px; overflow-x: clip; }
    .bp-wizard-card > .card-body { padding: 16px; }
    .bp-wizard-control { width: 100%; }
    .bp-wizard__progress { margin-bottom: 16px; padding: 14px; }
    .bp-wizard__mobile-progress .progress { height: 8px; background: #E2E8F0; }
    .bp-wizard__mobile-progress .progress-bar { background: #2563EB; }
    .bp-skillsec { padding: 12px; }
    .bp-wizard__actions { gap: 8px; }
    .bp-wizard__actions .btn { padding-inline: 12px; }
    #selected-skills { display: grid; grid-template-columns: 1fr; }
    .bp-sid-card { min-height: 0; }
}
/* Mobile dropdown layering: menu > open select > sticky navigation */
.bp-wizard-card,
#skillWizardRoot,
.bp-wizard__panel { overflow: visible; }
.ep-select--open { z-index: 110; }
.ep-select--open-up .ep-select__menu {
    top: auto;
    bottom: calc(100% + 4px);
}
@media (max-width: 767.98px) {
    .bp-skill-wizard-page { overflow-x: clip; overflow-y: visible; }
    .bp-wizard__actions { z-index: 100; }
    .ep-select__menu {
        z-index: 1000;
        max-height: min(320px, 50vh);
        touch-action: pan-y;
    }
}
/* Step 5 inline validation feedback */
.bp-step5-validation-alert {
    margin-bottom: 16px;
    border: 1px solid #FECACA;
    border-inline-start: 4px solid #DC2626;
    border-radius: 10px;
    background: #FEF2F2;
    color: #991B1B;
    font-size: .88rem;
    font-weight: 700;
    line-height: 1.7;
}
.bp-sid-card--validation-error {
    border-color: #FCA5A5 !important;
    background: linear-gradient(180deg, #FFFFFF 0%, #FEF2F2 100%);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .08);
}
.bp-sid-card__field select.is-invalid {
    border-color: #DC2626;
    background-color: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .09);
}
.bp-sid-card__field select.is-invalid:focus {
    border-color: #DC2626;
    outline: none;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, .16);
}
.bp-step5-field-error {
    margin-top: 5px;
    color: #DC2626;
    font-size: .7rem;
    font-weight: 700;
    line-height: 1.45;
    text-align: start;
}
.bp-step5-field-error[hidden] { display: none; }
@media (max-width: 767.98px) {
    .bp-step5-validation-alert { padding: 12px; font-size: .82rem; }
    .bp-step5-field-error { font-size: .74rem; }
}
/* SKILL-WIZARD-SPLIT-SKILLS: independent responsive grids */
.bp-software-grid,
.bp-field-grid {
    display: grid;
    gap: 14px;
    margin: 0;
}
.bp-software-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
.bp-field-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
.bp-skill-grid__item { min-width: 0; width: 100%; padding: 0; }
.bp-field-grid .bp-skill-card { min-height: 132px; }
.bp-field-grid .bp-skill-card__face { padding: 14px 12px; }
.bp-field-grid .bp-skill-card__face p {
    display: -webkit-box;
    overflow: hidden;
    line-height: 1.55 !important;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}
.bp-stepper__item strong { max-width: 120px; line-height: 1.35; }
@media (max-width: 1199.98px) {
    .bp-software-grid { grid-template-columns: repeat(4, minmax(0, 1fr)); }
}
@media (max-width: 991.98px) {
    .bp-software-grid,
    .bp-field-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .bp-stepper__item strong { max-width: 92px; font-size: .7rem; }
}
@media (max-width: 575.98px) {
    .bp-software-grid,
    .bp-field-grid { grid-template-columns: minmax(0, 1fr); }
    .bp-field-grid .bp-skill-card { min-height: 112px; }
}
@media (prefers-reduced-motion: reduce) {
    .bp-skill-loading::before { animation: none; }
    .bp-wizard__panel.is-entering { animation: none; }
    .bp-wizard__progress .progress-bar { transition: none; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const skillsContainerSoftware     = document.getElementById('skillsContainerSoftware');
    const skillsContainerField        = document.getElementById('skillsContainerField');
    const skillContainers             = [skillsContainerSoftware, skillsContainerField];
    const skillsEmptySoftware         = document.getElementById('skillsEmptySoftware');
    const skillsEmptyField            = document.getElementById('skillsEmptyField');
    const skillSearchSoftware         = document.getElementById('skillSearchSoftware');
    const skillSearchField            = document.getElementById('skillSearchField');
    const selectedSkillsContainer     = document.getElementById('selected-skills');
    const selectedSubdomainsContainer = document.getElementById('selected-subdomains');
    const saveBtn                     = document.getElementById('saveBtn');
    const wizardPanels                = Array.from(document.querySelectorAll('[data-wizard-step]'));
    const wizardIndicators            = Array.from(document.querySelectorAll('[data-step-indicator]'));
    wizardPanels.forEach(function (panel) {
        panel.addEventListener('animationend', function () { panel.classList.remove('is-entering'); });
    });
    const wizardBackBtn               = document.getElementById('wizardBackBtn');
    const wizardNextBtn               = document.getElementById('wizardNextBtn');
    const wizardActions               = document.querySelector('.bp-wizard__actions');
    const wizardProgressBar           = document.getElementById('wizardProgressBar');
    const wizardMobileCount           = document.getElementById('wizardMobileCount');
    const wizardMobileTitle           = document.getElementById('wizardMobileTitle');
    const wizardValidationAlert       = document.getElementById('wizardValidationAlert');
    const wizardStep5ValidationAlert  = document.getElementById('wizardStep5ValidationAlert');
    const wizardSubmitAlert           = document.getElementById('wizardSubmitAlert');
    const saveBtnSpinner              = document.getElementById('saveBtnSpinner');
    const previewDomains              = document.getElementById('previewDomains');
    const previewSubdomains           = document.getElementById('previewSubdomains');
    const previewSkills               = document.getElementById('previewSkills');
    // ─── CUSTOM DOMAIN DROPDOWN ─────────────────────────────────────────
    const ddWrap    = document.getElementById('dd-wrap');
    const ddTrigger = document.getElementById('dd-trigger');
    const ddLabel   = document.getElementById('dd-label');
    const ddMenu    = document.getElementById('dd-menu');

    function resetDropdownPlacement(wrap, menu) {
        wrap.classList.remove('ep-select--open-up');
        menu.style.removeProperty('max-height');
    }

    function placeDropdown(wrap, trigger, menu) {
        resetDropdownPlacement(wrap, menu);
        const triggerRect = trigger.getBoundingClientRect();
        const mobile = window.matchMedia('(max-width: 767.98px)').matches;
        const actionsRect = wizardActions.getBoundingClientRect();
        const navigationTop = mobile && actionsRect.top > 0 && actionsRect.top < window.innerHeight
            ? actionsRect.top - 8
            : window.innerHeight - 8;
        const availableBelow = Math.max(0, navigationTop - triggerRect.bottom - 4);
        const availableAbove = Math.max(0, triggerRect.top - 8);
        const openUp = availableBelow < 180 && availableAbove > availableBelow;
        const available = openUp ? availableAbove : availableBelow;
        const viewportLimit = window.innerHeight * 0.5;
        const maxHeight = Math.max(96, Math.min(320, viewportLimit, available - 4));
        wrap.classList.toggle('ep-select--open-up', openUp);
        menu.style.maxHeight = maxHeight + 'px';
    }
    function ddClose()            { ddWrap.classList.remove('ep-select--open'); resetDropdownPlacement(ddWrap, ddMenu); ddTrigger.setAttribute('aria-expanded', 'false'); }
    function ddReset(placeholder) { ddMenu.innerHTML = ''; ddLabel.textContent = placeholder; }
    function ddPopulate(items) {
        ddMenu.innerHTML = '';
        items.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'ep-select__option';
            li.setAttribute('role', 'option');
            li.tabIndex = 0;
            li.textContent = item.name;
            li.addEventListener('click', function () { onDomainPick(item.id, item.name); });
            ddMenu.appendChild(li);
        });
    }

    ddTrigger.addEventListener('click', function () {
        sdClose();
        ddWrap.classList.toggle('ep-select--open');
        const isOpen = ddWrap.classList.contains('ep-select--open');
        ddTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        if (isOpen) placeDropdown(ddWrap, ddTrigger, ddMenu);
        else resetDropdownPlacement(ddWrap, ddMenu);
    });
    document.addEventListener('click', function (e) { if (!ddWrap.contains(e.target)) ddClose(); });

    // ─── CUSTOM SUBDOMAIN DROPDOWN ──────────────────────────────────────
    const sdWrap    = document.getElementById('sd-wrap');
    const sdTrigger = document.getElementById('sd-trigger');
    const sdLabel   = document.getElementById('sd-label');
    const sdMenu    = document.getElementById('sd-menu');

    function sdEnable()  { sdWrap.classList.remove('ep-select--disabled'); sdTrigger.setAttribute('aria-disabled', 'false'); }
    function sdDisable() { sdWrap.classList.remove('ep-select--open'); sdWrap.classList.add('ep-select--disabled'); resetDropdownPlacement(sdWrap, sdMenu); sdTrigger.setAttribute('aria-disabled', 'true'); sdTrigger.setAttribute('aria-expanded', 'false'); }
    function sdClose()   { sdWrap.classList.remove('ep-select--open'); resetDropdownPlacement(sdWrap, sdMenu); sdTrigger.setAttribute('aria-expanded', 'false'); }

    function sdReset(placeholder) {
        sdMenu.innerHTML = '';
        sdLabel.textContent = placeholder;
    }

    function sdPopulate(items) {
        sdMenu.innerHTML = '';
        items.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'ep-select__option';
            li.setAttribute('role', 'option');
            li.tabIndex = 0;
            li.textContent = item.name;
            li.addEventListener('click', function () {
                onSubdomainPick(item.id, item.name);
            });
            sdMenu.appendChild(li);
        });
    }

    // Toggle open / close on trigger click
    sdTrigger.addEventListener('click', function () {
        if (sdWrap.classList.contains('ep-select--disabled')) return;
        ddClose();
        sdWrap.classList.toggle('ep-select--open');
        const isOpen = sdWrap.classList.contains('ep-select--open');
        sdTrigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        if (isOpen) placeDropdown(sdWrap, sdTrigger, sdMenu);
        else resetDropdownPlacement(sdWrap, sdMenu);
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!sdWrap.contains(e.target)) sdClose();
    });
    [ddTrigger, sdTrigger].forEach(function (trigger) {
        trigger.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); trigger.click(); }
            if (event.key === 'Escape') { ddClose(); sdClose(); }
        });
    });
    [ddMenu, sdMenu].forEach(function (menu) {
        menu.addEventListener('keydown', function (event) {
            if ((event.key === 'Enter' || event.key === ' ') && event.target.matches('[role="option"]')) {
                event.preventDefault(); event.target.click();
            }
        });
    });

    window.addEventListener('resize', function () {
        if (ddWrap.classList.contains('ep-select--open')) placeDropdown(ddWrap, ddTrigger, ddMenu);
        if (sdWrap.classList.contains('ep-select--open')) placeDropdown(sdWrap, sdTrigger, sdMenu);
    });
    // ─── STATE ──────────────────────────────────────────────────────────
    let selectedDomains          = [];
    let loadedSubdomainsByDomain = {};
    let selectedSubdomains       = [];
    let selectedSkills           = [];
    let currentWizardStep        = 1;
    let isSubmittingSkills       = false;
    let hasAttemptedStep5Validation = false;

    saveBtn.disabled = true;

    const wizardStepTitles = [
        'انتخاب حوزه',
        'انتخاب گرایش',
        'مهارت‌های پردازشی',
        'مهارت‌های میدانی',
        'سطح و سابقه',
        'بازبینی و ثبت',
    ];

    function isWizardStepValid(step) {
        if (step === 1) return selectedDomains.length > 0;
        if (step === 2) return selectedSubdomains.length > 0;
        if (step === 3) return true;
        if (step === 4) return selectedSkills.length > 0;
        if (step === 5) {
            return selectedSkills.length > 0 && selectedSkills.every(function (skill) {
                return Boolean(skill.level) && Number.isInteger(skill.years) && skill.years >= 1;
            });
        }
        return true;
    }

    function wizardValidationMessage(step) {
        if (step === 1) return 'برای ادامه حداقل یک حوزه انتخاب کنید.';
        if (step === 2) return 'برای ادامه حداقل یک گرایش انتخاب کنید.';
        if (step === 4) return 'برای ادامه حداقل یک مهارت پردازشی یا میدانی انتخاب کنید.';
        if (step === 5) return 'سطح و سابقه همه مهارت‌ها را تکمیل کنید.';
        return '';
    }

    function skillCounts() {
        return {
            domains: selectedDomains.length,
            subdomains: selectedSubdomains.length,
            skills: selectedSkills.length,
            software: selectedSkills.filter(function (skill) { return skill.skillType !== 'field'; }).length,
            field: selectedSkills.filter(function (skill) { return skill.skillType === 'field'; }).length,
        };
    }

    function setCount(id, value) {
        const element = document.getElementById(id);
        if (element) element.textContent = value.toLocaleString('fa-IR');
    }

    function renderWizardSummary() {
        const counts = skillCounts();
        setCount('summaryDomainCount', counts.domains);
        setCount('summarySubdomainCount', counts.subdomains);
        setCount('summarySkillCount', counts.skills);
        setCount('summarySoftwareCount', counts.software);
        setCount('summaryFieldCount', counts.field);
        setCount('previewDomainCount', counts.domains);
        setCount('previewSubdomainCount', counts.subdomains);
        setCount('previewSkillCount', counts.skills);
        setCount('previewSoftwareCount', counts.software);
        setCount('previewFieldCount', counts.field);
    }

    function appendPreviewChip(container, text) {
        const chip = document.createElement('span');
        chip.className = 'bp-preview__chip';
        chip.textContent = text;
        container.appendChild(chip);
    }

    function subdomainName(skill) {
        const subdomain = selectedSubdomains.find(function (item) { return item.id === skill.subdomainId; });
        return subdomain ? subdomain.name : '—';
    }

    function renderWizardPreview() {
        previewDomains.innerHTML = '';
        previewSubdomains.innerHTML = '';
        previewSkills.innerHTML = '';

        selectedDomains.forEach(function (domainId) {
            const domain = domainsData.find(function (item) { return item.id === domainId; });
            appendPreviewChip(previewDomains, domain ? domain.name : domainId);
        });

        selectedSubdomains.forEach(function (subdomain) {
            appendPreviewChip(previewSubdomains, subdomain.name);
        });

        selectedSkills.forEach(function (skill) {
            const row = document.createElement('article');
            row.className = 'bp-preview-skill';

            const name = document.createElement('strong');
            name.textContent = skill.name;

            const type = document.createElement('span');
            type.className = 'bp-preview-skill__type' + (skill.skillType === 'field' ? ' is-field' : '');
            type.textContent = skill.skillType === 'field' ? 'میدانی' : 'نرم‌افزاری';

            const subdomain = document.createElement('span');
            subdomain.textContent = 'گرایش: ' + subdomainName(skill);

            const level = document.createElement('span');
            level.textContent = 'سطح: ' + skill.level;

            const years = document.createElement('span');
            years.textContent = 'سابقه: ' + skill.years.toLocaleString('fa-IR') + ' سال';

            row.appendChild(name);
            row.appendChild(type);
            row.appendChild(subdomain);
            row.appendChild(level);
            row.appendChild(years);
            previewSkills.appendChild(row);
        });

        renderWizardSummary();
    }

    function showSubmitFeedback(message, type) {
        wizardSubmitAlert.className = 'alert mt-3 alert-' + type;
        wizardSubmitAlert.textContent = message;
        wizardSubmitAlert.tabIndex = -1;
        wizardSubmitAlert.focus({ preventScroll: true });
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        wizardSubmitAlert.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
    }

    function clearSubmitFeedback() {
        wizardSubmitAlert.className = 'alert d-none mt-3';
        wizardSubmitAlert.textContent = '';
    }

    function updateWizardControls() {
        const valid = isWizardStepValid(currentWizardStep);
        const progress = (currentWizardStep / 6) * 100;

        wizardPanels.forEach(function (panel) {
            const active = Number(panel.dataset.wizardStep) === currentWizardStep;
            panel.hidden = !active;
            panel.classList.toggle('is-active', active);
        });

        wizardIndicators.forEach(function (indicator) {
            const step = Number(indicator.dataset.stepIndicator);
            indicator.classList.toggle('is-active', step === currentWizardStep);
            const complete = step < currentWizardStep && isWizardStepValid(step);
            indicator.classList.toggle('is-complete', complete);
            const marker = indicator.querySelector('span');
            if (marker) { marker.textContent = complete ? '✓' : step.toLocaleString('fa-IR'); marker.setAttribute('aria-hidden', 'true'); }
            if (step === currentWizardStep) indicator.setAttribute('aria-current', 'step');
            else indicator.removeAttribute('aria-current');
        });

        wizardBackBtn.hidden = currentWizardStep === 1;
        wizardNextBtn.hidden = currentWizardStep === 6;
        saveBtn.hidden = currentWizardStep !== 6;
        wizardNextBtn.disabled = currentWizardStep === 5 ? false : !valid;
        saveBtn.disabled = isSubmittingSkills || currentWizardStep !== 6 || !isWizardStepValid(5);
        wizardProgressBar.style.width = progress + '%';
        wizardProgressBar.setAttribute('aria-valuenow', progress);
        wizardMobileCount.textContent = 'مرحله ' + currentWizardStep.toLocaleString('fa-IR') + ' از ۶';
        wizardMobileTitle.textContent = wizardStepTitles[currentWizardStep - 1];
        renderWizardSummary();
        if (currentWizardStep === 6) renderWizardPreview();
    }

    function showWizardStep(step) {
        if (step < 1 || step > 6) return;
        currentWizardStep = step;
        wizardValidationAlert.classList.add('d-none');
        wizardValidationAlert.textContent = '';
        updateWizardControls();
        const panel = document.querySelector('[data-wizard-step="' + step + '"]');
        if (panel) {
            panel.classList.remove('is-entering');
            void panel.offsetWidth;
            panel.classList.add('is-entering');
        }
        const heading = panel ? panel.querySelector('.bp-wizard__heading') : null;
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (heading) heading.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
    }

    wizardNextBtn.addEventListener('click', function () {
        if (currentWizardStep === 5 && !isWizardStepValid(5)) {
            hasAttemptedStep5Validation = true;
            applyStep5Validation(true);
            return;
        }
        if (!isWizardStepValid(currentWizardStep)) {
            wizardValidationAlert.textContent = wizardValidationMessage(currentWizardStep);
            wizardValidationAlert.classList.remove('d-none');
            return;
        }
        showWizardStep(currentWizardStep + 1);
    });

    wizardBackBtn.addEventListener('click', function () {
        showWizardStep(currentWizardStep - 1);
    });

    document.querySelectorAll('[data-preview-edit-step]').forEach(function (button) {
        button.addEventListener('click', function () {
            showWizardStep(Number(this.dataset.previewEditStep));
        });
    });

    updateWizardControls();

    // ─── DOMAIN SELECTION ───────────────────────────────────────────────
    const domainsData = @json($domains->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values());
    ddPopulate(domainsData);

    function deselectDomain(domainId) {
        const dependentSubdomains = (loadedSubdomainsByDomain[domainId] || []).filter(function (sub) { return selectedSubdomains.some(function (selected) { return selected.id === sub.id; }); });
        const hasDependentSkills = selectedSkills.some(function (skill) { return dependentSubdomains.some(function (sub) { return sub.id === skill.subdomainId; }); });
        if ((dependentSubdomains.length || hasDependentSkills) && !window.confirm('با حذف این حوزه، گرایش‌ها و مهارت‌های وابسته نیز از انتخاب شما حذف می‌شوند. ادامه می‌دهید؟')) return;
        selectedDomains = selectedDomains.filter(function (id) { return id !== domainId; });
        const removedIds = new Set(
            (loadedSubdomainsByDomain[domainId] || []).map(function (s) { return s.id; })
        );
        delete loadedSubdomainsByDomain[domainId];
        selectedSubdomains = selectedSubdomains.filter(function (s) { return !removedIds.has(s.id); });
        renderSelectedSubdomains();
        const remaining = Object.values(loadedSubdomainsByDomain).flat();
        if (remaining.length === 0) {
            sdDisable();
            sdReset('اول حوزه را انتخاب کنید');
        } else {
            sdPopulate(remaining);
        }
        Array.from(removedIds).forEach(function (subId) { removeSkillsBySubdomain(subId); });
        renderSelectedDomains();
        updateWizardControls();
    }

    function onDomainPick(domainId, domainName) {
        ddClose();
        if (selectedDomains.includes(domainId)) {
            alert('این حوزه قبلا انتخاب شده');
            return;
        }
        if (selectedDomains.length >= 2) {
            alert('حداکثر دو حوزه قابل انتخاب است');
            return;
        }
        selectedDomains.push(domainId);
        renderSelectedDomains();
        const subdomains = domainSubdomainsMap[domainId] || [];
        loadedSubdomainsByDomain[domainId] = subdomains;
        const allFlat = Object.values(loadedSubdomainsByDomain).flat();
        if (allFlat.length > 0) {
            sdReset('انتخاب زیررشته');
            sdPopulate(allFlat);
            sdEnable();
        } else {
            sdDisable();
            sdReset('زیرشاخه‌ای برای این حوزه تعریف نشده');
        }
        clearSkillSelection();
        updateWizardControls();
    }

    // ─── SUBDOMAIN PICK (replaces native <select> change handler) ───────
    async function onSubdomainPick(subdomainID, subdomainName) {
        sdClose();

        if (selectedSubdomains.some(function (x) { return x.id === subdomainID; })) {
            alert('این گرایش قبلا انتخاب شده');
            return;
        }

        if (selectedSubdomains.length >= 2) {
            alert('حداکثر دو گرایش قابل انتخاب است');
            return;
        }

        // Show chosen name in trigger, then reset label for next pick
        sdLabel.textContent = subdomainName;
        setTimeout(function () { sdLabel.textContent = 'انتخاب زیررشته'; }, 800);

        const allSubs = Object.values(loadedSubdomainsByDomain).flat();
        const selectedItem = allSubs.find(function (x) { return x.id === subdomainID; });
        if (selectedItem) {
            selectedSubdomains.push(selectedItem);
            renderSelectedSubdomains();
            updateWizardControls();
        }

        showSkillSkeletons();
        let skills = [];
        try {
            const response = await fetch('/api/skills/' + subdomainID);
            if (!response.ok) throw new Error('skills-load-failed');
            skills = await response.json();
        } catch (error) {
            skillsEmptySoftware.classList.remove('d-none');
            skillsEmptySoftware.querySelector('strong').textContent = 'بارگذاری مهارت‌ها انجام نشد';
            skillsEmptySoftware.querySelector('span').textContent = 'لطفاً دوباره تلاش کنید.';
        } finally {
            clearSkillSkeletons();
        }

        skills.forEach(function (skill) {
            const theme = getSkillTypeTheme(skill.skill_type);

            const col = document.createElement('div');
            col.className = 'bp-skill-grid__item';
            col.dataset.subdomainId = subdomainID;
            col.dataset.skillName   = skill.name;

            const card = document.createElement('div');
            card.className = 'mb-0 bp-skill-card' + (skill.skill_type === 'field' ? ' bp-skill-card--teal' : '');
            card.setAttribute('role', 'button');
            card.setAttribute('tabindex', '0');
            card.setAttribute('aria-pressed', 'false');
            card.setAttribute('aria-label', 'انتخاب مهارت ' + skill.name);

            // ── face (always visible) ──────────────────────────────────
            const face = document.createElement('div');
            face.className = 'bp-skill-card__face';
            face.innerHTML =
                '<span class="bp-skill-check" style="position:absolute;top:6px;left:6px;width:20px;height:20px;border-radius:50%;background:#7c3aed;display:none;align-items:center;justify-content:center;z-index:2;">' +
                    '<i class="ri ri-check-line" style="font-size:13px;color:#fff;"></i>' +
                '</span>' +
                '<div class="skill-icon" style="width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:' + theme.tint + ';color:' + theme.color + ';flex-shrink:0;">' +
                    '<i class="' + getSkillIcon(skill) + '" style="font-size:0.7rem;"></i>' +
                '</div>' +
                '<p style="margin:5px 0 0;font-weight:700;font-size:0.78rem;line-height:1.25;word-break:break-word;">' + skill.name + '</p>';

            // ── controls (revealed on expand) ──────────────────────────
            const controls = document.createElement('div');
            controls.className = 'bp-skill-card__controls';
            controls.addEventListener('click', function (e) { e.stopPropagation(); });

            const addBtn = document.createElement('button');
            addBtn.type = 'button';
            addBtn.className = 'bp-card-add-btn';
            addBtn.textContent = 'افزودن به مهارت‌ها';
            addBtn.style.background = theme.color;
            addBtn.addEventListener('click', function () {
                selectedSkills.push({
                    id:          skill.id,
                    name:        skill.name,
                    subdomainId: subdomainID,
                    skillType:   skill.skill_type,
                    level:       '',
                    years:       0,
                    cardRef:     card,
                });

                card.classList.remove('bp-skill-card--expanded');
                card.classList.add('bp-skill-card--added');
                card.setAttribute('aria-pressed', 'true');
                card.style.border    = '2.5px solid ' + theme.color;
                card.style.boxShadow = '0 0 0 3px ' + theme.ring;
                const iconDiv = card.querySelector('.skill-icon');
                if (iconDiv) { iconDiv.style.background = theme.color; iconDiv.style.color = '#fff'; }
                const checkEl = card.querySelector('.bp-skill-check');
                if (checkEl) { checkEl.style.display = 'flex'; }

                renderSelectedSkills();
                updateWizardControls();
            });

            controls.appendChild(addBtn);

            card.appendChild(face);
            card.appendChild(controls);

            // ── card click: expand / collapse face ─────────────────────
            card.addEventListener('click', function () {
                if (card.classList.contains('bp-skill-card--added')) return;

                if (card.classList.contains('bp-skill-card--expanded')) {
                    card.classList.remove('bp-skill-card--expanded');
                    return;
                }
                if (selectedSkills.length >= 5) {
                    alert('حداکثر ۵ مهارت قابل انتخاب است');
                    return;
                }
                skillContainers.forEach(function (c) {
                    c.querySelectorAll('.bp-skill-card--expanded').forEach(function (other) {
                        if (other !== card) other.classList.remove('bp-skill-card--expanded');
                    });
                });
                card.classList.add('bp-skill-card--expanded');
            });

            card.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); card.click(); }
            });
            col.appendChild(card);
            getSkillsContainer(skill.skill_type).appendChild(col);
        });

        refreshSkillFilters();
    }

    // ─── SELECTED SUBDOMAINS ─────────────────────────────────────────────
    function renderSelectedSubdomains() {
        selectedSubdomainsContainer.innerHTML = '';
        selectedSubdomains.forEach(function (item, index) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary m-1';
            btn.setAttribute('aria-label', 'حذف گرایش ' + item.name);
            btn.textContent = item.name + ' ×';
            btn.addEventListener('click', function () {
                const hasSkills = selectedSkills.some(function (skill) { return skill.subdomainId === item.id; });
                if (hasSkills && !window.confirm('با حذف این گرایش، مهارت‌های وابسته نیز از انتخاب شما حذف می‌شوند. ادامه می‌دهید؟')) return;
                removeSkillsBySubdomain(item.id);
                selectedSubdomains.splice(index, 1);
                renderSelectedSubdomains();
                updateWizardControls();
            });
            selectedSubdomainsContainer.appendChild(btn);
        });
    }

    // ─── SELECTED DOMAINS ───────────────────────────────────────────────────
    const selectedDomainsContainer = document.getElementById('selected-domains');
    function renderSelectedDomains() {
        selectedDomainsContainer.innerHTML = '';
        selectedDomains.forEach(function (domainId) {
            const domainName = (domainsData.find(function (d) { return d.id === domainId; }) || {}).name || domainId;
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'btn btn-primary m-1';
            chip.setAttribute('aria-label', 'حذف حوزه ' + domainName);
            chip.textContent = domainName + ' ×';
            chip.addEventListener('click', function () {
                deselectDomain(domainId);
            });
            selectedDomainsContainer.appendChild(chip);
        });
    }

    // ─── SKILL SELECTION HELPERS ─────────────────────────────────────────
    const SKILL_TYPE_THEME = {
        software: { icon: 'ri-code-box-line', color: '#1F6FEB', tint: 'rgba(31,111,235,.10)', ring: 'rgba(31,111,235,.15)' },
        field:    { icon: 'ri-tools-line',     color: '#00B8A9', tint: 'rgba(0,184,169,.10)',  ring: 'rgba(0,184,169,.15)' },
    };

    function getSkillTypeTheme(skillType) {
        return SKILL_TYPE_THEME[skillType] || SKILL_TYPE_THEME.software;
    }

    const SKILL_ICON_MAP = [
        [["شبیه‌سازی","ANSYS","COMSOL","Abaqus","Fluent"], "ri-flow-chart"],
        [["CAD","SolidWorks","AutoCAD","طراحی"], "ri-pencil-ruler-2-line"],
        [["DICOM","ImageJ","Slicer","تصویربرداری","MRI"], "ri-image-line"],
        [["MATLAB","Python","برنامه‌نویسی","کدنویسی","پایتون"], "ri-terminal-box-line"],
        [["SCADA","LabVIEW","اسکادا","لب‌ویو","HMI"], "ri-dashboard-line"],
        [["بافت","هیستولوژی","سلول","سلولی","کشت سلول","بیوراکتور","ایمپلنت","زیست‌سازگاری","زنده‌مانی"], "ri-microscope-line"],
        [["جوش","جوشکاری"], "ri-fire-line"],
        [["خوردگی","زنگ"], "ri-shield-flash-line"],
        [["آب‌بند","آب‌بندی","آبیاری","هیدرو","رودخانه","سیلاب"], "ri-drop-line"],
        [["خاک","ژئوتکنیک","حفاری","گمانه"], "ri-landscape-line"],
        [["بتن","سیمان"], "ri-building-2-line"],
        [["لوله","مخزن"], "ri-test-tube-line"],
        [["برق","الکتر","کابل","ترانسفورماتور","ولتاژ","جریان"], "ri-flashlight-line"],
        [["پلیمر","پلاستیک","لاستیک"], "ri-flask-line"],
        [["فلز","فولاد","ریخته","آلیاژ"], "ri-box-3-line"],
        [["نقشه‌بردار","ژئودز","توتال","نقشه‌برداری"], "ri-map-pin-line"],
        [["هواپیما","پرواز","ایرفویل"], "ri-flight-takeoff-line"],
        [["کشتی","شناور","دریا","لنگر"], "ri-ship-line"],
        [["جاده","آسفالت","روسازی"], "ri-route-line"],
        [["موتور","توربین","پمپ"], "ri-settings-3-line"],
        [["خورشید","فتوولتائیک","پنل"], "ri-sun-line"],
        [["نیروگاه","ژنراتور"], "ri-building-4-line"],
        [["بدن","خون","سلول","عضله","قلب","عصب"], "ri-heart-pulse-line"],
        [["گیاه","کشاورزی","محصول","کشت"], "ri-plant-line"],
        [["معدن","سنگ","کانی"], "ri-hammer-line"],
        [["لرزه","زلزله","ژئوفیزیک"], "ri-pulse-line"],
        [["دما","حرارت","گرمایش","سرمایش"], "ri-temp-hot-line"],
        [["صدا","آکوستیک","نویز"], "ri-volume-up-line"],
        [["نور","لیزر","نوری"], "ri-lightbulb-line"],
        [["دوربین","تصویر","عکس"], "ri-camera-line"],
        [["سنسور","حسگر","رادار"], "ri-radar-line"],
        [["باتری","شارژ"], "ri-battery-2-charge-line"],
        [["بازرسی","بررسی"], "ri-search-eye-line"],
        [["نصب","راه‌اندازی","مونتاژ","سیم‌کشی"], "ri-tools-line"],
        [["اجرای","انجام"], "ri-play-circle-line"],
        [["اندازه‌گیری","کالیبر","کالیبراسیون"], "ri-ruler-line"],
        [["نظارت","پایش"], "ri-eye-line"],
        [["ارزیابی","تحلیل","آنالیز"], "ri-line-chart-line"],
        [["کنترل"], "ri-equalizer-line"],
        [["آزمون","تست","آزمایش"], "ri-test-tube-line"],
        [["نمونه","برداشت","جمع‌آوری"], "ri-flask-line"],
        [["مستند","ثبت","گزارش"], "ri-file-text-line"],
        [["عیب‌یابی","تعمیر","نگهداری"], "ri-wrench-line"],
        [["آموزش","تدریس"], "ri-graduation-cap-line"],
    ];

    function getSkillIcon(skill) {
        for (var i = 0; i < SKILL_ICON_MAP.length; i++) {
            var kws = SKILL_ICON_MAP[i][0];
            for (var j = 0; j < kws.length; j++) {
                if (skill.name && skill.name.indexOf(kws[j]) !== -1) return SKILL_ICON_MAP[i][1];
            }
        }
        return skill.skill_type === 'software' ? 'ri-code-s-slash-line' : 'ri-briefcase-line';
    }

    function getSkillsContainer(skillType) {
        return skillType === 'field' ? skillsContainerField : skillsContainerSoftware;
    }

    // ─── SEARCH / FILTER (per section, visual only — never touches selection state) ──
    function filterSkillsContainer(container, emptyEl, query) {
        const q = query.trim().toLowerCase();
        let anyCard    = false;
        let anyVisible = false;

        Array.prototype.forEach.call(container.children, function (col) {
            anyCard = true;
            const name  = (col.dataset.skillName || '').toLowerCase();
            const match = !q || name.indexOf(q) !== -1;
            col.style.display = match ? '' : 'none';
            if (match) anyVisible = true;
        });

        emptyEl.classList.toggle('d-none', anyVisible);
    }

    function refreshSkillFilters() {
        filterSkillsContainer(skillsContainerSoftware, skillsEmptySoftware, skillSearchSoftware.value);
        filterSkillsContainer(skillsContainerField, skillsEmptyField, skillSearchField.value);
    }

    skillSearchSoftware.addEventListener('input', function () {
        filterSkillsContainer(skillsContainerSoftware, skillsEmptySoftware, this.value);
    });
    skillSearchField.addEventListener('input', function () {
        filterSkillsContainer(skillsContainerField, skillsEmptyField, this.value);
    });

    function showSkillSkeletons() {
        clearSkillSkeletons();
        for (var index = 0; index < 6; index++) {
            const col = document.createElement('div');
            col.className = 'bp-skill-grid__item bp-skill-skeleton';
            col.setAttribute('aria-hidden', 'true');
            const skeleton = document.createElement('div');
            skeleton.className = 'bp-skill-loading';
            col.appendChild(skeleton);
            (index % 2 === 0 ? skillsContainerSoftware : skillsContainerField).appendChild(col);
        }
    }

    function clearSkillSkeletons() {
        skillContainers.forEach(function (container) {
            container.querySelectorAll('.bp-skill-skeleton').forEach(function (item) { item.remove(); });
        });
    }
    function clearSkillSelection() {
        selectedSkills = [];
        renderSelectedSkills();
        skillContainers.forEach(function (c) { c.innerHTML = ''; });
        refreshSkillFilters();
        saveBtn.disabled = true;
        updateWizardControls();
    }

    function removeSkillsBySubdomain(subdomainId) {
        selectedSkills = selectedSkills.filter(function (skill) { return skill.subdomainId !== subdomainId; });
        skillContainers.forEach(function (c) {
            c.querySelectorAll('[data-subdomain-id="' + subdomainId + '"]').forEach(function (el) { el.remove(); });
        });
        renderSelectedSkills();
        saveBtn.disabled = selectedSkills.length === 0;
        if (selectedSubdomains.length === 0) skillContainers.forEach(function (c) { c.innerHTML = ''; });
        refreshSkillFilters();
        updateWizardControls();
    }

    function applyStep5Validation(shouldFocus) {
        let firstInvalidCard = null;
        let firstInvalidSelect = null;
        selectedSkillsContainer.querySelectorAll('.bp-sid-card[data-skill-index]').forEach(function (card) {
            const index = Number(card.dataset.skillIndex);
            const skill = selectedSkills[index];
            if (!skill) return;
            const levelSelect = card.querySelector('[data-step5-field="level"]');
            const yearsSelect = card.querySelector('[data-step5-field="years"]');
            const levelError = card.querySelector('[data-step5-error="level"]');
            const yearsError = card.querySelector('[data-step5-error="years"]');
            const levelMissing = !skill.level;
            const yearsMissing = !Number.isInteger(skill.years) || skill.years < 1;
            card.classList.toggle('bp-sid-card--validation-error', levelMissing || yearsMissing);
            [[levelSelect, levelError, levelMissing], [yearsSelect, yearsError, yearsMissing]].forEach(function (field) {
                const select = field[0];
                const error = field[1];
                const missing = field[2];
                select.classList.toggle('is-invalid', missing);
                select.setAttribute('aria-invalid', missing ? 'true' : 'false');
                if (missing) select.setAttribute('aria-describedby', error.id);
                else select.removeAttribute('aria-describedby');
                error.hidden = !missing;
            });
            if ((levelMissing || yearsMissing) && !firstInvalidCard) {
                firstInvalidCard = card;
                firstInvalidSelect = levelMissing ? levelSelect : yearsSelect;
            }
        });
        const complete = !firstInvalidCard && isWizardStepValid(5);
        wizardStep5ValidationAlert.classList.toggle('d-none', complete || !hasAttemptedStep5Validation);
        if (complete) hasAttemptedStep5Validation = false;
        if (shouldFocus && firstInvalidCard) {
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            firstInvalidCard.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
            window.setTimeout(function () { firstInvalidSelect.focus({ preventScroll: true }); }, reduceMotion ? 0 : 250);
        }
        return complete;
    }
    // ─── SELECTED SKILLS RENDERING ───────────────────────────────────────
    function renderSelectedSkills() {
        selectedSkillsContainer.innerHTML = '';

        selectedSkills.forEach(function (skill, index) {
            const theme = getSkillTypeTheme(skill.skillType);

            const card = document.createElement('div');
            card.className = 'bp-sid-card';
            card.dataset.skillIndex = index;
            card.style.borderColor = theme.color;

            // ── body: icon + name + meta ────────────────────────────────
            const body = document.createElement('div');
            body.className = 'bp-sid-card__body';

            const iconEl = document.createElement('div');
            iconEl.className = 'bp-sid-card__icon';
            iconEl.style.background = theme.color;
            iconEl.innerHTML = '<i class="' + getSkillIcon({ name: skill.name, skill_type: skill.skillType }) + '"></i>';

            const nameEl = document.createElement('div');
            nameEl.className = 'bp-sid-card__name';
            nameEl.textContent = skill.name;

            const metaEl = document.createElement('div');
            metaEl.className = 'bp-sid-card__meta';

            const typeSpan = document.createElement('span');
            typeSpan.className = 'bp-sid-card__level';
            typeSpan.style.background = theme.tint;
            typeSpan.style.color = theme.color;
            typeSpan.textContent = skill.skillType === 'field' ? 'میدانی' : 'نرم‌افزاری';
            metaEl.appendChild(typeSpan);
            body.appendChild(iconEl);
            body.appendChild(nameEl);
            body.appendChild(metaEl);

            const fields = document.createElement('div');
            fields.className = 'bp-sid-card__fields';

            const levelField = document.createElement('div');
            levelField.className = 'bp-sid-card__field';
            const levelLabel = document.createElement('label');
            levelLabel.textContent = 'سطح مهارت';
            const levelSelect = document.createElement('select');
            const levelError = document.createElement('div');
            levelSelect.id = 'skill-level-' + index;
            levelSelect.dataset.step5Field = 'level';
            levelLabel.htmlFor = levelSelect.id;
            levelError.id = 'skill-level-error-' + index;
            levelError.className = 'bp-step5-field-error';
            levelError.dataset.step5Error = 'level';
            levelError.textContent = 'سطح مهارت را انتخاب کنید.';
            levelError.hidden = true;
            levelSelect.innerHTML = '<option value="">انتخاب سطح</option>';
            ['مبتدی', 'متوسط', 'حرفه‌ای'].forEach(function (level) {
                const option = document.createElement('option');
                option.value = level;
                option.textContent = level;
                option.selected = skill.level === level;
                levelSelect.appendChild(option);
            });
            levelSelect.addEventListener('change', function () {
                skill.level = this.value;
                if (hasAttemptedStep5Validation) applyStep5Validation(false);
                updateWizardControls();
            });
            levelField.appendChild(levelLabel);
            levelField.appendChild(levelSelect);
            levelField.appendChild(levelError);

            const yearsField = document.createElement('div');
            yearsField.className = 'bp-sid-card__field';
            const yearsLabel = document.createElement('label');
            yearsLabel.textContent = 'سال تجربه';
            const yearsSelect = document.createElement('select');
            const yearsError = document.createElement('div');
            yearsSelect.id = 'skill-years-' + index;
            yearsSelect.dataset.step5Field = 'years';
            yearsLabel.htmlFor = yearsSelect.id;
            yearsError.id = 'skill-years-error-' + index;
            yearsError.className = 'bp-step5-field-error';
            yearsError.dataset.step5Error = 'years';
            yearsError.textContent = 'سابقه فعالیت را انتخاب کنید.';
            yearsError.hidden = true;
            yearsSelect.innerHTML = '<option value="">انتخاب سابقه</option>';
            for (var year = 1; year <= 30; year++) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year + ' سال';
                option.selected = skill.years === year;
                yearsSelect.appendChild(option);
            }
            yearsSelect.addEventListener('change', function () {
                skill.years = parseInt(this.value, 10) || 0;
                if (hasAttemptedStep5Validation) applyStep5Validation(false);
                updateWizardControls();
            });
            yearsField.appendChild(yearsLabel);
            yearsField.appendChild(yearsSelect);
            yearsField.appendChild(yearsError);

            fields.appendChild(levelField);
            fields.appendChild(yearsField);

            // ── delete button ───────────────────────────────────────────
            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'bp-sid-card__del';
            delBtn.textContent = 'حذف مهارت';
            delBtn.setAttribute('aria-label', 'حذف مهارت ' + skill.name);
            delBtn.addEventListener('click', function () {
                if (skill.cardRef) {
                    const t = getSkillTypeTheme(skill.skillType);
                    skill.cardRef.classList.remove('bp-skill-card--added', 'bp-skill-card--expanded');
                    skill.cardRef.setAttribute('aria-pressed', 'false');
                    skill.cardRef.style.border    = '';
                    skill.cardRef.style.boxShadow = '';
                    const iconDiv = skill.cardRef.querySelector('.skill-icon');
                    if (iconDiv) { iconDiv.style.background = t.tint; iconDiv.style.color = t.color; }
                    const checkEl = skill.cardRef.querySelector('.bp-skill-check');
                    if (checkEl) { checkEl.style.display = 'none'; }
                }
                selectedSkills.splice(index, 1);
                renderSelectedSkills();
                updateWizardControls();
            });

            card.appendChild(body);
            card.appendChild(fields);
            card.appendChild(delBtn);
            selectedSkillsContainer.appendChild(card);
        });

        if (hasAttemptedStep5Validation) applyStep5Validation(false);
        updateWizardControls();
    }

    // ─── SAVE ────────────────────────────────────────────────────────────
    saveBtn.addEventListener('click', async function () {
        if (isSubmittingSkills) return;
        isSubmittingSkills = true;
        clearSubmitFeedback();
        saveBtn.disabled = true;
        saveBtnSpinner.classList.remove('d-none');

        const dataToSave = selectedSkills.map(function (skill) {
            return { skill_id: skill.id, level: skill.level, years: skill.years };
        });

        try {
            const response = await fetch('/save-user-skills', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ skills: dataToSave, domains: selectedDomains }),
            });

            const result = await response.json();

            if (response.ok && result.success) {
                showSubmitFeedback(result.message || 'مهارت‌ها با موفقیت ثبت شدند.', 'success');
                if (result.redirect) setTimeout(function () { window.location.assign(result.redirect); }, 350);
            } else {
                const errors = result.errors
                    ? Object.values(result.errors).flat().join('\n')
                    : result.message || 'خطا در ذخیره';
                showSubmitFeedback(errors, 'danger');
            }
        } catch (e) {
            showSubmitFeedback('خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.', 'danger');
        } finally {
            isSubmittingSkills = false;
            saveBtnSpinner.classList.add('d-none');
            saveBtn.disabled = selectedSkills.length === 0 || !isWizardStepValid(5);
        }
    });

});
</script>
@endpush

@push('styles')
<style>
.bp-suggest-skill {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border: 1px dashed rgba(0,184,169,.55);
    border-radius: var(--bp-r-lg);
    background: var(--bp-tint-teal);
}
.bp-suggest-skill strong { display: block; margin-bottom: 3px; color: var(--bp-ink); }
.bp-suggest-skill span { display: block; color: var(--bp-muted); font-size: .84rem; }
#skillSuggestionModal .modal-content { border: 0; border-radius: var(--bp-r-xl); box-shadow: var(--bp-sh-lg); }
#skillSuggestionModal .modal-header { align-items: flex-start; border-bottom-color: var(--bp-hair); }
#skillSuggestionModal .modal-footer { border-top-color: var(--bp-hair); }
@media (max-width: 576px) {
    .bp-suggest-skill { align-items: stretch; flex-direction: column; padding: 16px; }
    .bp-suggest-skill .btn { width: 100%; }
    #skillSuggestionModal .modal-dialog { margin: 12px; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('skillSuggestionForm');
    if (!form) return;

    const submit = document.getElementById('skillSuggestionSubmit');
    const spinner = submit.querySelector('.spinner-border');
    const alertBox = document.getElementById('skillSuggestionAlert');

    function clearSuggestionFeedback() {
        alertBox.className = 'alert d-none';
        alertBox.textContent = '';
        form.querySelectorAll('.is-invalid').forEach(function (field) { field.classList.remove('is-invalid'); });
        form.querySelectorAll('[data-error-for]').forEach(function (el) { el.textContent = ''; });
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        clearSuggestionFeedback();
        submit.disabled = true;
        spinner.classList.remove('d-none');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
                body: new FormData(form),
            });
            const result = await response.json();

            if (response.ok && result.success) {
                alertBox.className = 'alert alert-success';
                alertBox.textContent = result.message;
                form.reset();
                return;
            }

            if (result.errors) {
                Object.keys(result.errors).forEach(function (name) {
                    const field = form.querySelector('[name="' + name + '"]');
                    const feedback = form.querySelector('[data-error-for="' + name + '"]');
                    if (field) field.classList.add('is-invalid');
                    if (feedback) feedback.textContent = result.errors[name][0];
                });
            } else {
                alertBox.className = 'alert alert-danger';
                alertBox.textContent = result.message || 'ثبت پیشنهاد انجام نشد. لطفاً دوباره تلاش کنید.';
            }
        } catch (error) {
            alertBox.className = 'alert alert-danger';
            alertBox.textContent = 'ارتباط با سرور برقرار نشد. لطفاً دوباره تلاش کنید.';
        } finally {
            submit.disabled = false;
            spinner.classList.add('d-none');
        }
    });
});
</script>
@endpush
