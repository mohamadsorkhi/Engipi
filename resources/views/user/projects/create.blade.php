@extends('layouts.master')

@section('title', 'ثبت پروژه مهندسی')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="bp-form-head mb-4">
                <h4 class="mb-1"><i class="ri-briefcase-line text-primary me-2"></i>ثبت پروژه مهندسی جدید</h4>
                <p class="text-muted mb-0">
                    مشخصات پروژه مهندسی خود را وارد کنید تا متخصصان فنی مناسب بتوانند همکاری کنند.
                </p>
            </div>

            <form id="projectForm" action="{{ route('user.projects.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Basic Info -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-file-text-line"></i></div>
                        <h5>اطلاعات پایه</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">عنوان پروژه مهندسی <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title"
                                    placeholder="مثال: طراحی و پیاده‌سازی سیستم کنترل صنعتی" required minlength="5" maxlength="255">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">توضیحات فنی پروژه <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="شرح فنی پروژه، الزامات، استانداردها و خروجی‌های مورد انتظار..."
                                    required minlength="20"></textarea>
                                <div class="form-text d-flex justify-content-between">
                                    <span>حداقل ۲۰ کاراکتر</span>
                                    <span id="description-counter">۰ / ۲۰</span>
                                </div>
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Work Type -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-map-pin-line"></i></div>
                        <h5>نوع اجرای پروژه <span class="text-danger">*</span></h5>
                    </div>
                    <div class="bp-fb">
                        <div class="bp-wt-grid">
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="work_type"
                                    id="work_type_remote" value="remote" required>
                                <label class="form-check-label w-100" for="work_type_remote">
                                    <div class="bp-wt">
                                        <i class="ri-check-line bp-wt-check"></i>
                                        <div class="bp-wt-ic"><i class="ri-home-wifi-line"></i></div>
                                        <div>
                                            <div class="bp-wt-t">دورکاری</div>
                                            <div class="bp-wt-s">کار از راه دور</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="work_type"
                                    id="work_type_onsite" value="onsite">
                                <label class="form-check-label w-100" for="work_type_onsite">
                                    <div class="bp-wt">
                                        <i class="ri-check-line bp-wt-check"></i>
                                        <div class="bp-wt-ic"><i class="ri-building-line"></i></div>
                                        <div>
                                            <div class="bp-wt-t">حضوری</div>
                                            <div class="bp-wt-s">حضور در محل کار</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check card-radio">
                                <input class="form-check-input" type="radio" name="work_type"
                                    id="work_type_hybrid" value="hybrid">
                                <label class="form-check-label w-100" for="work_type_hybrid">
                                    <div class="bp-wt">
                                        <i class="ri-check-line bp-wt-check"></i>
                                        <div class="bp-wt-ic"><i class="ri-git-merge-line"></i></div>
                                        <div>
                                            <div class="bp-wt-t">ترکیبی</div>
                                            <div class="bp-wt-s">هم حضوری هم دورکاری</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="invalid-feedback" id="work-type-error"></div>
                    </div>
                </div>

                <!-- Domain & Processes -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-stack-line"></i></div>
                        <h5>حوزه‌های تخصصی و پردازش‌ها</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="mb-3">
                            <label class="form-label">حوزه‌های تخصصی <span class="text-danger">*</span>
                                <small class="text-muted">(حداقل ۱ و حداکثر ۳ حوزه انتخاب کنید)</small>
                            </label>
                            <div class="bp-search-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" id="domain-search-input" class="bp-search-input" placeholder="جستجو در حوزه‌ها...">
                            </div>
                            <div class="bp-domain-grid" id="domains-list">
                                @foreach($domains as $domain)
                                <div class="bp-domain" data-domain-id="{{ $domain->id }}">
                                    <div class="form-check">
                                        <input class="form-check-input domain-checkbox" type="checkbox"
                                            id="domain_{{ $domain->id }}"
                                            value="{{ $domain->id }}"
                                            data-processes='@json($domain->processes)'>
                                        <label class="form-check-label fw-medium" for="domain_{{ $domain->id }}">
                                            {{ $domain->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="invalid-feedback d-block" id="domains-error"><span></span></div>
                        </div>
                        <div class="mb-0">
                            <div id="processes-container">
                                <label for="processes" class="form-label d-flex align-items-center justify-content-between">
                                    <span>
                                        مهارت‌های پردازشی <span class="text-danger">*</span>
                                        <small class="text-muted">(حداقل ۱ پردازش انتخاب کنید)</small>
                                    </span>
                                    <small class="text-muted fw-medium" id="processes-counter">۰ از ۳</small>
                                </label>
                                <p id="processes-placeholder" class="bp-processes-hint">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    ابتدا یک حوزه تخصصی انتخاب کنید تا مهارت‌های پردازشی مرتبط نمایش داده شوند.
                                </p>
                                <div id="processes-inner" style="display: none;">
                                    <select class="form-select" id="processes" multiple></select>
                                    <div class="alert alert-info small mb-3 mt-2">
                                        <i class="ri-information-line me-1"></i>
                                        برای هر پردازش انتخاب شده، سطح مهارت مورد نیاز را مشخص کنید.
                                    </div>
                                    <div id="processes-cards" class="row g-3"></div>
                                </div>
                                <div class="invalid-feedback d-block" id="processes-error"><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skills (Optional) -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-tools-line"></i></div>
                        <h5>مهارت‌های میدانی (اختیاری)</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="mb-0">
                            <label for="skills" class="form-label">مهارت‌ها</label>
                            <div class="bp-search-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" id="skills-search-input" class="bp-search-input" placeholder="جستجو در مهارت‌ها...">
                            </div>
                            <select class="form-select" id="skills" multiple>
                                @php $skillsByGroup = $skills->groupBy(fn($s) => ($s->subdomain?->domain?->name ?? 'سایر') . '|' . ($s->subdomain?->name ?? '')); @endphp
                                @foreach($skillsByGroup as $groupKey => $groupSkills)
                                    @php
                                        $groupParts = explode('|', $groupKey, 2);
                                        $domainName = $groupParts[0] ?? '';
                                        $subdomainName = $groupParts[1] ?? '';
                                    @endphp
                                    <optgroup label="{{ $subdomainName ? $domainName.' › '.$subdomainName : $domainName }}">
                                        @foreach($groupSkills as $skill)
                                            <option value="{{ $skill->id }}" data-skill-type="{{ $skill->skill_type }}">{{ $skill->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="form-text">مهارت‌های خاص مورد نیاز پروژه را انتخاب کنید</div>
                            <div class="invalid-feedback d-block" id="skills-error"><span></span></div>
                            <div id="skills-cards" class="row g-3 mt-3"></div>
                        </div>
                    </div>
                </div>

                <!-- Timeline & Budget -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-time-line"></i></div>
                        <h5>زمان‌بندی و بودجه</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="duration_days" class="form-label">مدت زمان (روز)</label>
                                <input type="number" class="form-control" id="duration_days" name="duration_days"
                                    min="1" placeholder="مثال: 30">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget_min" class="form-label">حداقل بودجه (تومان)</label>
                                <input type="text" class="form-control" id="budget_min" inputmode="numeric" autocomplete="off"
                                    placeholder="مثال: 5,000,000">
                                <input type="hidden" id="budget_min_value" name="budget_min">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget_max" class="form-label">حداکثر بودجه (تومان)</label>
                                <input type="text" class="form-control" id="budget_max" inputmode="numeric" autocomplete="off"
                                    placeholder="مثال: 10,000,000">
                                <input type="hidden" id="budget_max_value" name="budget_max">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Upload -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-attachment-line"></i></div>
                        <h5>فایل‌های پیوست (اختیاری)</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="mb-0">
                            <label for="files" class="form-label">بارگذاری فایل</label>
                            <input type="file" class="form-control" id="files" name="files[]" multiple>
                            <div class="form-text">حداکثر حجم هر فایل: ۱۰ مگابایت</div>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                    </div>
                </div>

                <div class="bp-form-foot ep-form-actions">
                    <span class="bp-form-note"><i class="ri-shield-check-line"></i>اطلاعات پروژه شما محفوظ و امن نگه‌داری می‌شود.</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('user.projects.index') }}" class="btn btn-light">انصراف</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            ثبت پروژه
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@php
    $allSkillsArray = $skills->map(function ($s) {
        return [
            'id' => $s->id,
            'name' => $s->name,
            'skill_type' => $s->skill_type,
            'domain' => $s->subdomain?->domain?->name ?? 'سایر',
            'subdomain' => $s->subdomain?->name ?? '',
        ];
    });
@endphp
@section('script')
<script>
const allSkillsData = @json($allSkillsArray);

document.addEventListener('DOMContentLoaded', function () {
    const form              = document.getElementById('projectForm');
    const submitBtn         = document.getElementById('submitBtn');
    const spinner           = submitBtn.querySelector('.spinner-border');
    const domainCheckboxes  = document.querySelectorAll('.domain-checkbox');
    const processesContainer   = document.getElementById('processes-container');
    const processesInner       = document.getElementById('processes-inner');
    const processesPlaceholder = document.getElementById('processes-placeholder');
    const processesCards    = document.getElementById('processes-cards');
    const skillsCards       = document.getElementById('skills-cards');
    const workTypeRadios    = document.querySelectorAll('input[name="work_type"]');
    const budgetMin         = document.getElementById('budget_min');
    const budgetMax         = document.getElementById('budget_max');
    const budgetMinHidden   = document.getElementById('budget_min_value');
    const budgetMaxHidden   = document.getElementById('budget_max_value');
    const descriptionInput   = document.getElementById('description');
    const descriptionCounter = document.getElementById('description-counter');
    const processesCounterEl = document.getElementById('processes-counter');

    let allProcessesMap        = new Map();
    let selectedProcessesState = {};
    let selectedSkillsState    = {};
    const SKILL_LEVELS = ['مبتدی', 'متوسط', 'حرفه ای'];

    function toPersianDigits(n) {
        const map = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        return String(n).replace(/[0-9]/g, function (d) { return map[d]; });
    }

    function scrollToError(el) {
        if (!el) return;
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function updateProcessesCounter() {
        if (!processesCounterEl) return;
        processesCounterEl.textContent = toPersianDigits(Object.keys(selectedProcessesState).length) + ' از ' + toPersianDigits(3);
    }

    // ── Shared chip-selector + card factory used by both the processes and
    // skills sections, so they behave identically. Listeners are bound once
    // to the underlying <select> element and survive option rebuilds, since
    // setOptions() only replaces the element's children, not the element.
    function createChipCardSelector(selectEl, onAdd, onRemove) {
        var instance = null;

        selectEl.addEventListener('addItem', function (e) {
            onAdd(e.detail.value, e.detail.label);
        });
        selectEl.addEventListener('removeItem', function (e) {
            onRemove(e.detail.value);
        });

        function buildInstance() {
            if (instance) { instance.destroy(); instance = null; }
            if (typeof Choices === 'undefined') return;
            instance = new Choices(selectEl, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'انتخاب کنید...',
                noResultsText: 'نتیجه‌ای یافت نشد',
                itemSelectText: 'انتخاب',
            });
        }

        function setOptions(options, selectedIds) {
            var selected = selectedIds || [];
            if (instance) { instance.destroy(); instance = null; }
            selectEl.innerHTML = '';

            var groupEls = {};
            options.forEach(function (opt) {
                var container = selectEl;
                if (opt.group) {
                    if (!groupEls[opt.group]) {
                        var og = document.createElement('optgroup');
                        og.label = opt.group;
                        selectEl.appendChild(og);
                        groupEls[opt.group] = og;
                    }
                    container = groupEls[opt.group];
                }
                var el = document.createElement('option');
                el.value = opt.id;
                el.textContent = opt.name;
                if (opt.dataset) {
                    Object.keys(opt.dataset).forEach(function (k) { el.dataset[k] = opt.dataset[k]; });
                }
                if (selected.indexOf(opt.id) !== -1) { el.selected = true; }
                container.appendChild(el);
            });
            buildInstance();
        }

        function getSelectedIds() {
            if (!instance) return [];
            return instance.getValue(true).map(function (v) { return typeof v === 'object' ? v.value : v; });
        }

        function removeByValue(id) {
            if (instance) instance.removeActiveItemsByValue(id);
        }

        return { init: buildInstance, setOptions: setOptions, getSelectedIds: getSelectedIds, removeByValue: removeByValue };
    }

    // ── "مهارت‌های میدانی" (skills) chip selector + cards ──────────────────
    function renderSkillCard(skillId, skillName) {
        if (skillsCards.querySelector('[data-skill-card-id="' + skillId + '"]')) return;
        if (!selectedSkillsState[skillId]) selectedSkillsState[skillId] = { level: SKILL_LEVELS[1], years: '' };
        const saved = selectedSkillsState[skillId];
        const skillInfo = allSkillsData.find(function (s) { return s.id === skillId; });
        const groupLabel = skillInfo
            ? (skillInfo.subdomain ? (skillInfo.domain + ' › ' + skillInfo.subdomain) : skillInfo.domain)
            : '';

        const html = '<div class="col-md-6 col-lg-4" data-skill-card-id="' + skillId + '">' +
            '<div class="card skill-card" data-skill-id="' + skillId + '">' +
            '<div class="card-body">' +
            '<div class="d-flex justify-content-between align-items-start mb-2">' +
            '<div><span class="fw-medium">' + skillName + '</span>' +
            (groupLabel ? '<div class="small text-muted">' + groupLabel + '</div>' : '') +
            '</div>' +
            '<button type="button" class="btn btn-sm btn-link text-danger p-0 remove-skill-card" data-skill-id="' + skillId + '"><i class="ri-close-line"></i></button>' +
            '</div>' +
            '<div class="row g-2">' +
            '<div class="col-6">' +
            '<label class="form-label small text-muted mb-1">سطح مهارت</label>' +
            '<select class="form-select form-select-sm skill-level" data-skill-id="' + skillId + '">' +
            SKILL_LEVELS.map(function (lvl) {
                return '<option value="' + lvl + '" ' + (saved.level === lvl ? 'selected' : '') + '>' + lvl + '</option>';
            }).join('') +
            '</select>' +
            '</div>' +
            '<div class="col-6">' +
            '<label class="form-label small text-muted mb-1">سال‌های تجربه</label>' +
            '<input type="number" class="form-control form-control-sm skill-years" data-skill-id="' + skillId + '" min="0" max="50" value="' + saved.years + '">' +
            '</div>' +
            '</div></div></div></div>';

        skillsCards.insertAdjacentHTML('beforeend', html);

        const cardEl = skillsCards.querySelector('[data-skill-card-id="' + skillId + '"]');
        cardEl.querySelector('.remove-skill-card').addEventListener('click', function () {
            skillsSelector.removeByValue(skillId);
        });
        cardEl.querySelector('.skill-level').addEventListener('change', function () {
            selectedSkillsState[skillId].level = this.value;
        });
        cardEl.querySelector('.skill-years').addEventListener('input', function () {
            selectedSkillsState[skillId].years = this.value;
        });
    }

    function removeSkillCard(skillId) {
        const card = skillsCards.querySelector('[data-skill-card-id="' + skillId + '"]');
        if (card) card.remove();
        delete selectedSkillsState[skillId];
    }

    const skillsSelector = createChipCardSelector(document.getElementById('skills'), renderSkillCard, removeSkillCard);
    skillsSelector.init();

    // ── Domain search filter ──────────────────────────────────────────────
    var domainSearchInput = document.getElementById('domain-search-input');
    if (domainSearchInput) {
        domainSearchInput.addEventListener('input', function () {
            var q = this.value.trim().toLowerCase();
            document.querySelectorAll('#domains-list .bp-domain').forEach(function (card) {
                var label = card.querySelector('.form-check-label');
                var text  = label ? label.textContent.trim().toLowerCase() : '';
                card.style.display = (!q || text.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    }

    // ── Skills search filter ──────────────────────────────────────────────
    var skillsSearchInput = document.getElementById('skills-search-input');
    if (skillsSearchInput) {
        skillsSearchInput.addEventListener('input', function () {
            var q           = this.value.trim().toLowerCase();
            var selectedIds = skillsSelector.getSelectedIds();
            var selectedSet = new Set(selectedIds);
            // Respect whatever work-type filter is currently active
            var activeWorkType = null;
            workTypeRadios.forEach(function (r) { if (r.checked) activeWorkType = r.value; });
            var base = activeWorkType === 'onsite'
                ? allSkillsData.filter(function (s) { return s.skill_type === 'field'; })
                : activeWorkType === 'remote'
                    ? allSkillsData.filter(function (s) { return s.skill_type === 'software'; })
                    : allSkillsData;
            var filtered = q
                ? base.filter(function (s) {
                    return selectedSet.has(s.id) || s.name.toLowerCase().indexOf(q) !== -1;
                  })
                : base;
            skillsSelector.setOptions(
                filtered.map(function (s) {
                    return {
                        id: s.id, name: s.name,
                        dataset: { skillType: s.skill_type },
                        group: s.subdomain ? (s.domain + ' › ' + s.subdomain) : s.domain,
                    };
                }),
                selectedIds
            );
        });
    }

    function filterSkillsByWorkType(workType) {
        const selected = skillsSelector.getSelectedIds();

        let visible;
        if (workType === 'onsite') {
            visible = allSkillsData.filter(function (s) { return s.skill_type === 'field'; });
        } else if (workType === 'remote') {
            visible = allSkillsData.filter(function (s) { return s.skill_type === 'software'; });
        } else {
            visible = allSkillsData; // hybrid: show all
        }

        const visibleIds   = new Set(visible.map(function (s) { return s.id; }));
        const stillValid   = selected.filter(function (id) { return visibleIds.has(id); });
        const removedCount = selected.length - stillValid.length;

        selected.forEach(function (id) {
            if (stillValid.indexOf(id) === -1) removeSkillCard(id);
        });

        skillsSelector.setOptions(
            visible.map(function (s) {
                return {
                    id: s.id,
                    name: s.name,
                    dataset: { skillType: s.skill_type },
                    group: s.subdomain ? (s.domain + ' › ' + s.subdomain) : s.domain,
                };
            }),
            stillValid
        );

        if (removedCount > 0 && typeof window.showToast === 'function') {
            window.showToast(removedCount + ' مهارت انتخاب‌شده با نوع همکاری جدید سازگار نبود و حذف شد.', 'warning');
        }
    }

    // ── "مهارت‌های پردازشی" (processes) chip selector + cards ──────────────
    function renderProcessCard(processId, processName) {
        if (processesCards.querySelector('[data-process-card-id="' + processId + '"]')) return;
        if (Object.keys(selectedProcessesState).length >= 3) {
            alert('حداکثر ۳ پردازش می‌توانید انتخاب کنید.');
            processesSelector.removeByValue(processId);
            return;
        }
        if (!selectedProcessesState[processId]) selectedProcessesState[processId] = ['practical'];
        const savedLevels = selectedProcessesState[processId];
        const labels = { practical: 'عملی', proficient: 'مسلط', advanced: 'پیشرفته' };

        const html = '<div class="col-6 col-md-4 col-lg-2" data-process-card-id="' + processId + '">' +
            '<div class="card process-card" data-process-id="' + processId + '">' +
            '<div class="card-body">' +
            '<div class="d-flex justify-content-between align-items-start mb-2">' +
            '<span class="fw-medium">' + processName + '</span>' +
            '<button type="button" class="btn btn-sm btn-link text-danger p-0 remove-process-card" data-process-id="' + processId + '"><i class="ri-close-line"></i></button>' +
            '</div>' +
            '<div class="level-select">' +
            '<label class="form-label small text-muted mb-2">سطوح مهارت مورد نیاز:</label>' +
            ['practical', 'proficient', 'advanced'].map(function (lvl) {
                return '<div class="form-check"><input class="form-check-input level-checkbox" type="checkbox" value="' + lvl + '" id="level_' + processId + '_' + lvl + '" data-process-id="' + processId + '" ' + (savedLevels.includes(lvl) ? 'checked' : '') + '><label class="form-check-label small" for="level_' + processId + '_' + lvl + '">' + labels[lvl] + '</label></div>';
            }).join('') +
            '</div></div></div></div>';

        processesCards.insertAdjacentHTML('beforeend', html);

        const cardEl = processesCards.querySelector('[data-process-card-id="' + processId + '"]');
        cardEl.querySelector('.remove-process-card').addEventListener('click', function () {
            processesSelector.removeByValue(processId);
        });
        cardEl.querySelectorAll('.level-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () {
                const pid = this.dataset.processId;
                const checked = Array.from(
                    processesCards.querySelectorAll('.level-checkbox[data-process-id="' + pid + '"]:checked')
                ).map(function (el) { return el.value; });

                if (checked.length > 0) {
                    selectedProcessesState[pid] = checked;
                } else {
                    this.checked = true;
                    selectedProcessesState[pid] = [this.value];
                }
            });
        });

        updateProcessesCounter();
    }

    function removeProcessCard(processId) {
        const card = processesCards.querySelector('[data-process-card-id="' + processId + '"]');
        if (card) card.remove();
        delete selectedProcessesState[processId];
        updateProcessesCounter();
    }

    const processesSelector = createChipCardSelector(document.getElementById('processes'), renderProcessCard, removeProcessCard);
    processesSelector.init();

    // Rebuild the available processes whenever the selected domains change,
    // preserving cards/state for processes that remain selectable.
    function updateProcessesOptions() {
        const available   = Array.from(allProcessesMap.values());
        const availableIds = new Set(available.map(function (p) { return p.id; }));

        Object.keys(selectedProcessesState).forEach(function (pid) {
            if (!availableIds.has(pid)) removeProcessCard(pid);
        });

        const stillSelected = Object.keys(selectedProcessesState).filter(function (pid) { return availableIds.has(pid); });

        processesSelector.setOptions(
            available.map(function (p) { return { id: p.id, name: p.name }; }),
            stillSelected
        );
    }

    // ── Work-type card styling + skill filtering ──────────────────────────
    workTypeRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.bp-wt').forEach(function (card) {
                card.classList.remove('sel');
            });
            if (this.checked) {
                this.closest('.form-check').querySelector('.bp-wt').classList.add('sel');
                filterSkillsByWorkType(this.value);
            }
        });
    });

    // ── Description live character counter ─────────────────────────────────
    if (descriptionInput && descriptionCounter) {
        descriptionInput.addEventListener('input', function () {
            const len = this.value.length;
            descriptionCounter.textContent = toPersianDigits(len) + ' / ' + toPersianDigits(20);
            descriptionCounter.classList.toggle('text-success', len >= 20);
            descriptionCounter.classList.toggle('text-danger', len > 0 && len < 20);
        });
    }

    // ── Budget thousands-separator formatting + max ≥ min validity ─────────
    function checkBudgetValidity() {
        const min = parseFloat(budgetMinHidden.value) || 0;
        const max = parseFloat(budgetMaxHidden.value) || 0;
        budgetMax.setCustomValidity(max > 0 && max < min ? 'حداکثر بودجه باید بزرگتر از حداقل بودجه باشد' : '');
    }

    function formatBudgetInput(displayEl, hiddenEl) {
        displayEl.addEventListener('input', function () {
            const raw = this.value.replace(/[^0-9]/g, '');
            hiddenEl.value = raw;
            this.value = raw ? Number(raw).toLocaleString('en-US') : '';
            checkBudgetValidity();
        });
    }

    formatBudgetInput(budgetMin, budgetMinHidden);
    formatBudgetInput(budgetMax, budgetMaxHidden);

    // ── Domain checkbox change ────────────────────────────────────────────
    domainCheckboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const card         = this.closest('.bp-domain');
            const checkedCount = document.querySelectorAll('.domain-checkbox:checked').length;

            if (this.checked) {
                if (checkedCount > 3) {
                    this.checked = false;
                    alert('حداکثر ۳ حوزه می‌توانید انتخاب کنید.');
                    return;
                }
                card.classList.add('sel');
            } else {
                card.classList.remove('sel');
            }

            allProcessesMap.clear();
            document.querySelectorAll('.domain-checkbox:checked').forEach(function (cb) {
                try {
                    JSON.parse(cb.dataset.processes || '[]').forEach(function (p) {
                        if (!allProcessesMap.has(p.id)) allProcessesMap.set(p.id, p);
                    });
                } catch (_) {}
            });

            processesContainer.style.display  = allProcessesMap.size > 0 ? 'block' : 'none';
            processesInner.style.display       = allProcessesMap.size > 0 ? 'block' : 'none';
            processesPlaceholder.style.display = allProcessesMap.size > 0 ? 'none'  : 'block';
            updateProcessesOptions();
        });
    });

    // ── Build domain + process hidden inputs; returns true if valid ───────
    function buildHiddenInputs() {
        form.querySelectorAll('input[name^="processes"], input[name^="domains"], input[name^="skills"]').forEach(function (el) { el.remove(); });

        const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');
        if (checkedDomains.length < 1 || checkedDomains.length > 3) {
            const el = document.getElementById('domains-error');
            if (el) { el.querySelector('span').textContent = 'حداقل ۱ و حداکثر ۳ حوزه انتخاب کنید'; el.style.display = 'block'; }
            scrollToError(el);
            return false;
        }
        checkedDomains.forEach(function (cb, i) {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'domains[' + i + ']'; inp.value = cb.value;
            form.appendChild(inp);
        });
        const domainsErr = document.getElementById('domains-error');
        if (domainsErr) domainsErr.style.display = 'none';

        // Processes are optional — clear any previous error and build inputs only for selected cards
        const processCardEls = processesCards.querySelectorAll('.process-card');
        const processesErr2 = document.getElementById('processes-error');
        if (processesErr2) processesErr2.style.display = 'none';

        let idx = 0;
        let ok  = true;
        processCardEls.forEach(function (card) {
            const pid    = card.dataset.processId;
            const levels = card.querySelectorAll('.level-checkbox:checked');
            if (!levels || levels.length === 0) {
                alert('لطفاً حداقل یک سطح مهارت برای «' + card.querySelector('.fw-medium').textContent.trim() + '» انتخاب کنید.');
                ok = false;
                return;
            }
            levels.forEach(function (lvCb) {
                const idInp  = document.createElement('input'); idInp.type = 'hidden'; idInp.name = 'processes[' + idx + '][id]';    idInp.value = pid;        form.appendChild(idInp);
                const lvInp  = document.createElement('input'); lvInp.type = 'hidden'; lvInp.name = 'processes[' + idx + '][level]'; lvInp.value = lvCb.value; form.appendChild(lvInp);
                idx++;
            });
        });
        if (!ok) return false;

        const processesErr = document.getElementById('processes-error');
        if (processesErr) processesErr.style.display = 'none';

        // Skills are optional — clear any previous error and build inputs only for selected cards
        const skillCardEls = skillsCards.querySelectorAll('.skill-card');
        const skillsErr = document.getElementById('skills-error');
        if (skillsErr) skillsErr.style.display = 'none';

        let sIdx = 0;
        skillCardEls.forEach(function (card) {
            const sid   = card.dataset.skillId;
            const level = card.querySelector('.skill-level').value;
            const years = card.querySelector('.skill-years').value;
            if (!level || years === '' || isNaN(parseInt(years, 10)) || parseInt(years, 10) < 0) {
                alert('لطفاً سطح و سال‌های تجربه را برای «' + card.querySelector('.fw-medium').textContent.trim() + '» مشخص کنید.');
                ok = false;
                return;
            }
            const idInp  = document.createElement('input'); idInp.type = 'hidden'; idInp.name = 'skills[' + sIdx + '][id]';                  idInp.value = sid;   form.appendChild(idInp);
            const lvInp  = document.createElement('input'); lvInp.type = 'hidden'; lvInp.name = 'skills[' + sIdx + '][level]';               lvInp.value = level; form.appendChild(lvInp);
            const yrInp  = document.createElement('input'); yrInp.type = 'hidden'; yrInp.name = 'skills[' + sIdx + '][years_of_experience]'; yrInp.value = years; form.appendChild(yrInp);
            sIdx++;
        });
        if (!ok) return false;

        return true;
    }

    // ── Show server-side validation errors ────────────────────────────────
    function showErrors(errors) {
        let firstErrorEl = null;

        Object.keys(errors).forEach(function (key) {
            const msg = errors[key][0];
            let el = null;

            if (key === 'domains' || key === 'domains.0') {
                el = document.getElementById('domains-error');
                if (el) { el.querySelector('span').textContent = msg; el.style.display = 'block'; }
            } else if (key === 'processes' || key.startsWith('processes.')) {
                el = document.getElementById('processes-error');
                if (el) { el.querySelector('span').textContent = msg; el.style.display = 'block'; }
            } else if (key === 'skills' || key.startsWith('skills.')) {
                el = document.getElementById('skills-error');
                if (el) { el.querySelector('span').textContent = msg; el.style.display = 'block'; }
            } else {
                // Map dot-notation key to field name
                const fieldName = key.replace(/\.(\w+)/g, '[$1]');
                const field     = form.querySelector('[name="' + fieldName + '"]')
                               || form.querySelector('[name="' + fieldName + '[]"]');
                if (field) {
                    field.classList.add('is-invalid');
                    if (field.type === 'hidden') {
                        const visibleField = document.getElementById(field.id.replace('_value', ''));
                        if (visibleField) visibleField.classList.add('is-invalid');
                    }
                    const fb = field.parentElement.querySelector('.invalid-feedback')
                            || field.closest('.mb-3, .col-md-12, .col-md-4, .col-12')?.querySelector('.invalid-feedback');
                    if (fb) { fb.querySelector('span').textContent = msg; fb.style.display = 'block'; }
                    el = field;
                }
            }

            if (el && !firstErrorEl) firstErrorEl = el;
        });

        scrollToError(firstErrorEl);
    }

    // ── Form submit → validate → AJAX ────────────────────────────────────
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!buildHiddenInputs()) return;

        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, status: response.status, data: data };
            });
        })
        .then(function (result) {
            if (result.ok) {
                window.showToast(result.data.message || 'پروژه با موفقیت ثبت شد.', 'success');
                var redirectUrl = result.data.redirect;
                setTimeout(function () { window.location.assign(redirectUrl); }, 1200);
            } else if (result.status === 422 && result.data.errors) {
                showErrors(result.data.errors);
                window.showToast('لطفاً خطاها را برطرف کنید.', 'error');
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            } else {
                window.showToast(result.data.message || 'خطایی رخ داد. دوباره تلاش کنید.', 'error');
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        })
        .catch(function (err) {
            console.error('Project submit error:', err);
            window.showToast('خطا در ارتباط با سرور. دوباره تلاش کنید.', 'error');
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
});
</script>

<style>
/* ── Form head ────────────────────────────────────────────────────────── */
.bp-form-head h4 { font-weight: 800; }

/* ── Section card, matching .fcard on the landing/blueprint reference ──── */
.bp-fcard {
    background: #fff;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    overflow: hidden;
}
.bp-fh {
    padding: 16px 24px;
    border-bottom: 1px solid var(--bp-hair);
    display: flex;
    align-items: center;
    gap: 10px;
}
.bp-fh-icon {
    width: 34px; height: 34px;
    border-radius: var(--bp-r);
    background: var(--bp-tint-blue);
    color: var(--bp-blue);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex: none;
}
.bp-fh h5 { font-size: 1rem; font-weight: 700; margin: 0; }
.bp-fb { padding: 24px; }

/* ── Work-type cards, matching .wt on the blueprint reference ───────────── */
.bp-wt-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
@media (max-width: 640px) { .bp-wt-grid { grid-template-columns: 1fr; } }
.card-radio .form-check-input { display: none; }
.bp-wt {
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    padding: 16px;
    cursor: pointer;
    transition: all .2s var(--bp-ease);
    display: flex;
    gap: 11px;
    align-items: flex-start;
    position: relative;
}
.bp-wt:hover { border-color: var(--bp-blue); }
.bp-wt.sel { border-color: var(--bp-blue); background: var(--bp-tint-blue); box-shadow: 0 0 0 1px var(--bp-blue) inset; }
.bp-wt-ic {
    width: 38px; height: 38px;
    border-radius: var(--bp-r);
    background: var(--bp-surface);
    color: var(--bp-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    flex: none;
    transition: all .2s var(--bp-ease);
}
.bp-wt.sel .bp-wt-ic { background: var(--bp-blue); color: #fff; }
.bp-wt-t { font-weight: 700; font-size: .92rem; color: var(--bp-ink); }
.bp-wt-s { font-size: .76rem; color: var(--bp-muted); }
.bp-wt-check {
    position: absolute; top: 12px; inset-inline-end: 12px;
    color: var(--bp-blue); font-size: 1.1rem;
    opacity: 0; transition: opacity .2s;
}
.bp-wt.sel .bp-wt-check { opacity: 1; }

/* ── Domain checkbox cards ──────────────────────────────────────────────── */
.bp-domain-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 12px; }
@media (max-width: 860px) { .bp-domain-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 560px) { .bp-domain-grid { grid-template-columns: repeat(2, 1fr); } }
.bp-domain {
    background: #fff;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r-lg);
    padding: 14px 16px;
    cursor: pointer;
    transition: all .2s var(--bp-ease);
}
.bp-domain:hover { border-color: var(--bp-blue); transform: translateY(-2px); box-shadow: var(--bp-sh-sm); }
.bp-domain.sel { border-color: var(--bp-blue); background: var(--bp-tint-blue); }

/* ── Process / skill chip-cards ─────────────────────────────────────────── */
.card.process-card {
    background-color: var(--bp-tint-blue) !important;
    border-left: 3px solid var(--bp-blue) !important;
}
.card.skill-card {
    background-color: var(--bp-tint-teal) !important;
    border-left: 3px solid var(--bp-teal) !important;
}

/* ── Form footer ─────────────────────────────────────────────────────────── */
.bp-form-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.bp-form-note {
    font-size: .82rem;
    color: var(--bp-muted);
    display: flex;
    align-items: center;
    gap: 6px;
}
.bp-form-note i { color: var(--bp-teal); }

/* ── Search/filter inputs ────────────────────────────────────────────── */
.bp-search-wrap { position: relative; max-width: 320px; margin-bottom: 12px; }
.bp-search-wrap i { position: absolute; top: 50%; right: 12px; transform: translateY(-50%); color: var(--bp-muted); font-size: 1rem; pointer-events: none; }
.bp-search-input { width: 100%; border: 1px solid var(--bp-border); border-radius: var(--bp-r); padding: 7px 38px 7px 12px; font-size: .85rem; color: var(--bp-text, #1a1a2e); background: #fff; font-family: inherit; transition: border-color .15s, box-shadow .15s; }
.bp-search-input:focus { outline: none; border-color: var(--bp-blue); box-shadow: 0 0 0 3px var(--bp-tint-blue); }

/* ── Choices.js domain ›  subdomain group headings on the skills selector ── */
.choices__list--dropdown .choices__heading {
    color: var(--bp-muted) !important;
    font-size: .68rem !important;
    font-weight: 700 !important;
    letter-spacing: .06em !important;
    text-transform: uppercase !important;
    background: var(--bp-surface) !important;
    border-bottom: 1px solid var(--bp-hair) !important;
    padding: 6px 10px !important;
}
</style>
@endsection
