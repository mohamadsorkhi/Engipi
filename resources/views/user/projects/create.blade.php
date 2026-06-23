@extends('layouts.master')

@section('title', 'ثبت پروژه مهندسی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="ri-briefcase-line text-primary me-2"></i>ثبت پروژه مهندسی جدید</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        مشخصات پروژه مهندسی خود را وارد کنید تا متخصصان فنی مناسب بتوانند همکاری کنند.
                    </p>

                    <form id="projectForm" action="{{ route('user.projects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Info -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-file-text-line me-2"></i>اطلاعات پایه
                                </h6>
                            </div>
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

                        <!-- Work Type -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-map-pin-line me-2"></i>نوع اجرای پروژه <span class="text-danger">*</span>
                                </h6>
                            </div>
                            <div class="col-12">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="form-check card-radio">
                                            <input class="form-check-input" type="radio" name="work_type" 
                                                id="work_type_remote" value="remote" required>
                                            <label class="form-check-label w-100" for="work_type_remote">
                                                <div class="d-flex align-items-center p-3 border rounded cursor-pointer work-type-card">
                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                        <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                                            <i class="ri-global-line fs-4"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">دورکاری</h6>
                                                        <small class="text-muted">کار از راه دور</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check card-radio">
                                            <input class="form-check-input" type="radio" name="work_type" 
                                                id="work_type_onsite" value="onsite">
                                            <label class="form-check-label w-100" for="work_type_onsite">
                                                <div class="d-flex align-items-center p-3 border rounded cursor-pointer work-type-card">
                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                            <i class="ri-building-line fs-4"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">حضوری</h6>
                                                        <small class="text-muted">حضور در محل کار</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check card-radio">
                                            <input class="form-check-input" type="radio" name="work_type" 
                                                id="work_type_hybrid" value="hybrid">
                                            <label class="form-check-label w-100" for="work_type_hybrid">
                                                <div class="d-flex align-items-center p-3 border rounded cursor-pointer work-type-card">
                                                    <div class="avatar-sm flex-shrink-0 me-3">
                                                        <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                                            <i class="ri-git-merge-line fs-4"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">ترکیبی</h6>
                                                        <small class="text-muted">هم حضوری هم دورکاری</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="work-type-error"></div>
                            </div>
                        </div>

                        <!-- Domain & Processes -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-stack-line me-2"></i>حوزه‌های تخصصی و پردازش‌ها
                                </h6>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">حوزه‌های تخصصی <span class="text-danger">*</span>
                                    <small class="text-muted">(حداقل ۱ و حداکثر ۳ حوزه انتخاب کنید)</small>
                                </label>
                                <div class="row g-3" id="domains-list">
                                    @foreach($domains as $domain)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card border domain-card" data-domain-id="{{ $domain->id }}">
                                            <div class="card-body">
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
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback d-block" id="domains-error"><span></span></div>
                            </div>
                            <div class="col-12 mb-3">
                                <div id="processes-container" style="display: none;">
                                    <label for="processes" class="form-label d-flex align-items-center justify-content-between">
                                        <span>
                                            مهارت‌های پردازشی <span class="text-danger">*</span>
                                            <small class="text-muted">(حداقل ۱ پردازش انتخاب کنید)</small>
                                        </span>
                                        <small class="text-muted fw-medium" id="processes-counter">۰ از ۳</small>
                                    </label>
                                    <select class="form-select" id="processes" multiple></select>
                                    <div class="alert alert-info small mb-3 mt-2">
                                        <i class="ri-information-line me-1"></i>
                                        برای هر پردازش انتخاب شده، سطح مهارت مورد نیاز را مشخص کنید.
                                    </div>
                                    <div id="processes-cards" class="row g-3"></div>
                                    <div class="invalid-feedback d-block" id="processes-error"><span></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills (Optional) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-tools-line me-2"></i>مهارت‌های میدانی (اختیاری)
                                </h6>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="skills" class="form-label">مهارت‌ها</label>
                                <select class="form-select" id="skills" multiple>
                                    @foreach($skills as $skill)
                                        <option value="{{ $skill->id }}" data-skill-type="{{ $skill->skill_type }}">{{ $skill->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">مهارت‌های خاص مورد نیاز پروژه را انتخاب کنید</div>
                                <div class="invalid-feedback d-block" id="skills-error"><span></span></div>
                                <div id="skills-cards" class="row g-3 mt-3"></div>
                            </div>
                        </div>

                        <!-- Timeline & Budget -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-time-line me-2"></i>زمان‌بندی و بودجه
                                </h6>
                            </div>
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

                        <!-- File Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-attachment-line me-2"></i>فایل‌های پیوست (اختیاری)
                                </h6>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="files" class="form-label">بارگذاری فایل</label>
                                <input type="file" class="form-control" id="files" name="files[]" multiple>
                                <div class="form-text">حداکثر حجم هر فایل: ۱۰ مگابایت</div>
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2 ep-form-actions">
                            <a href="{{ route('user.projects.index') }}" class="btn btn-light">انصراف</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                ثبت پروژه
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
const allSkillsData = @json($skills->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'skill_type' => $s->skill_type]));

document.addEventListener('DOMContentLoaded', function () {
    const form              = document.getElementById('projectForm');
    const submitBtn         = document.getElementById('submitBtn');
    const spinner           = submitBtn.querySelector('.spinner-border');
    const domainCheckboxes  = document.querySelectorAll('.domain-checkbox');
    const processesContainer= document.getElementById('processes-container');
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
            options.forEach(function (opt) {
                var el = document.createElement('option');
                el.value = opt.id;
                el.textContent = opt.name;
                if (opt.dataset) {
                    Object.keys(opt.dataset).forEach(function (k) { el.dataset[k] = opt.dataset[k]; });
                }
                if (selected.indexOf(opt.id) !== -1) { el.selected = true; }
                selectEl.appendChild(el);
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

        const html = '<div class="col-md-6 col-lg-4" data-skill-card-id="' + skillId + '">' +
            '<div class="card skill-card" data-skill-id="' + skillId + '">' +
            '<div class="card-body">' +
            '<div class="d-flex justify-content-between align-items-start mb-2">' +
            '<span class="fw-medium">' + skillName + '</span>' +
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
            visible.map(function (s) { return { id: s.id, name: s.name, dataset: { skillType: s.skill_type } }; }),
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

        const html = '<div class="col-md-6 col-lg-4" data-process-card-id="' + processId + '">' +
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
            document.querySelectorAll('.work-type-card').forEach(function (card) {
                card.classList.remove('border-primary', 'bg-primary-subtle');
            });
            if (this.checked) {
                this.closest('.form-check').querySelector('.work-type-card')
                    .classList.add('border-primary', 'bg-primary-subtle');
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
            const card         = this.closest('.domain-card');
            const checkedCount = document.querySelectorAll('.domain-checkbox:checked').length;

            if (this.checked) {
                if (checkedCount > 3) {
                    this.checked = false;
                    alert('حداکثر ۳ حوزه می‌توانید انتخاب کنید.');
                    return;
                }
                card.classList.add('border-primary', 'bg-primary-subtle');
            } else {
                card.classList.remove('border-primary', 'bg-primary-subtle');
            }

            allProcessesMap.clear();
            document.querySelectorAll('.domain-checkbox:checked').forEach(function (cb) {
                try {
                    JSON.parse(cb.dataset.processes || '[]').forEach(function (p) {
                        if (!allProcessesMap.has(p.id)) allProcessesMap.set(p.id, p);
                    });
                } catch (_) {}
            });

            processesContainer.style.display = allProcessesMap.size > 0 ? 'block' : 'none';
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
.work-type-card, .domain-card {
    cursor: pointer;
    transition: all 0.2s ease;
}
.work-type-card:hover, .domain-card:hover {
    border-color: var(--vz-primary) !important;
}
.card-radio .form-check-input {
    display: none;
}
.card.process-card {
    background-color: rgba(var(--vz-primary-rgb), .07) !important;
    border-left: 3px solid var(--vz-primary) !important;
}
.card.skill-card {
    background-color: rgba(var(--vz-success-rgb), .07) !important;
    border-left: 3px solid var(--vz-success) !important;
}
</style>
@endsection
