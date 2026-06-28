@extends('layouts.master')

@section('title', 'ویرایش پروژه مهندسی')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="bp-form-head mb-4">
                <h4 class="mb-0">ویرایش پروژه مهندسی: {{ $project->title }}</h4>
            </div>

            <form id="projectForm" action="{{ route('user.projects.update', $project) }}" method="POST">
                @csrf
                @method('PUT')

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
                                    value="{{ $project->title }}" required minlength="5" maxlength="255">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">توضیحات فنی پروژه <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="شرح فنی پروژه، الزامات، استانداردها و خروجی‌های مورد انتظار..."
                                    required minlength="20">{{ $project->description }}</textarea>
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
                            @foreach(['remote' => ['دورکاری', 'ri-home-wifi-line'], 'onsite' => ['حضوری', 'ri-building-line'], 'hybrid' => ['ترکیبی', 'ri-git-merge-line']] as $value => $info)
                                <div class="form-check card-radio">
                                    <input class="form-check-input" type="radio" name="work_type"
                                        id="work_type_{{ $value }}" value="{{ $value }}"
                                        {{ $project->work_type === $value ? 'checked' : '' }} required>
                                    <label class="form-check-label w-100" for="work_type_{{ $value }}">
                                        <div class="bp-wt {{ $project->work_type === $value ? 'sel' : '' }}">
                                            <i class="ri-check-line bp-wt-check"></i>
                                            <div class="bp-wt-ic"><i class="{{ $info[1] }}"></i></div>
                                            <div><div class="bp-wt-t">{{ $info[0] }}</div></div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
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
                            @php
                                $selectedDomainIds = $project->domains->pluck('id')->toArray();
                            @endphp
                            <div class="bp-domain-grid" id="domains-list">
                                @foreach($domains as $domain)
                                @php $isSelected = in_array($domain->id, $selectedDomainIds); @endphp
                                <div class="bp-domain {{ $isSelected ? 'sel' : '' }}" data-domain-id="{{ $domain->id }}">
                                    <div class="form-check">
                                        <input class="form-check-input domain-checkbox" type="checkbox"
                                            id="domain_{{ $domain->id }}"
                                            value="{{ $domain->id }}"
                                            data-processes='@json($domain->processes)'
                                            {{ $isSelected ? 'checked' : '' }}>
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
                                <label class="form-label">
                                    پردازش‌های مورد نیاز <span class="text-danger">*</span>
                                    <small class="text-muted">(حداقل ۱ پردازش انتخاب کنید)</small>
                                </label>
                                <p id="processes-placeholder" class="bp-processes-hint">
                                    <i class="ri-arrow-up-line me-1"></i>
                                    ابتدا یک حوزه تخصصی انتخاب کنید تا مهارت‌های پردازشی مرتبط نمایش داده شوند.
                                </p>
                                <div id="processes-inner" style="display: none;">
                                    <div class="alert alert-info small mb-3">
                                        <i class="ri-information-line me-1"></i>
                                        برای هر پردازش انتخاب شده، سطح(های) مورد نیاز را مشخص کنید.
                                    </div>
                                    <div id="processes-list" class="row g-3"></div>
                                </div>
                                <div class="invalid-feedback d-block" id="processes-error"><span></span></div>
                            </div>
                            @php
                                $selectedProcesses = $project->processes
                                    ->mapWithKeys(function ($p) {
                                        $raw = $p->pivot?->desired_levels;
                                        $levels = [];
                                        if (is_string($raw)) {
                                            $decoded = json_decode($raw, true);
                                            $levels = is_array($decoded) ? $decoded : [];
                                        } elseif (is_array($raw)) {
                                            $levels = $raw;
                                        }
                                        return [$p->id => $levels];
                                    });
                            @endphp
                        </div>
                    </div>
                </div>

                <!-- Skills -->
                <div class="bp-fcard mb-4">
                    <div class="bp-fh">
                        <div class="bp-fh-icon"><i class="ri-tools-line"></i></div>
                        <h5>مهارت‌های مورد نیاز (اختیاری)</h5>
                    </div>
                    <div class="bp-fb">
                        <div class="mb-0">
                            <label for="skills" class="form-label">مهارت‌ها</label>
                            <div class="bp-search-wrap">
                                <i class="ri-search-line"></i>
                                <input type="text" id="skills-search-input" class="bp-search-input" placeholder="جستجو در مهارت‌ها...">
                            </div>
                            @php $selectedSkillIds = $project->skills->pluck('id')->toArray(); @endphp
                            <select class="form-select" id="skills" name="skills[]" multiple>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" {{ in_array($skill->id, $selectedSkillIds) ? 'selected' : '' }}>
                                        {{ $skill->name }}
                                    </option>
                                @endforeach
                            </select>
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
                                    min="1" value="{{ $project->duration_days }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget_min" class="form-label">حداقل بودجه (تومان)</label>
                                <input type="number" class="form-control" id="budget_min" name="budget_min"
                                    min="0" value="{{ $project->budget_min }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget_max" class="form-label">حداکثر بودجه (تومان)</label>
                                <input type="number" class="form-control" id="budget_max" name="budget_max"
                                    min="0" value="{{ $project->budget_max }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('user.projects.show', $project) }}" class="btn btn-light">انصراف</a>
                    <button type="submit" class="btn btn-primary ajax-submit" id="submitBtn">
                        <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                        ذخیره تغییرات
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const domainCheckboxes = document.querySelectorAll('.domain-checkbox');
    const processesContainer = document.getElementById('processes-container');
    const processesList = document.getElementById('processes-list');
    const submitBtn = document.getElementById('submitBtn');
    const workTypeRadios = document.querySelectorAll('input[name="work_type"]');

    const selectedProcesses = @json($selectedProcesses);
    let allProcessesMap = new Map();
    // Copy server data to state object for tracking user changes
    let selectedProcessesState = JSON.parse(JSON.stringify(selectedProcesses || {}));

    // Capture all skill options BEFORE Choices.js transforms the element
    var skillsSelectEl = document.getElementById('skills');
    var allSkillOptionsData = skillsSelectEl
        ? Array.from(skillsSelectEl.options).map(function (opt) {
            return { value: opt.value, label: opt.text };
          })
        : [];

    let skillsChoices;
    if (typeof Choices !== 'undefined') {
        skillsChoices = new Choices(document.getElementById('skills'), {
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'انتخاب کنید...',
        });
    }

    function renderProcesses(processes) {
        processesList.innerHTML = '';

        if (!processes || processes.length === 0) {
            processesList.innerHTML = '<div class="col-12"><p class="text-muted">پردازشی برای این حوزه تعریف نشده است.</p></div>';
            return;
        }

        processes.forEach((process) => {
            const selectedLevels = selectedProcessesState && selectedProcessesState[process.id] ? selectedProcessesState[process.id] : [];
            const isSelected = selectedLevels.length > 0;

            const processHtml = `
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card border ${isSelected ? 'border-primary' : ''} process-card" data-process-id="${process.id}">
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input process-checkbox" type="checkbox" 
                                    id="process_${process.id}" 
                                    data-process-id="${process.id}" ${isSelected ? 'checked' : ''}>
                                <label class="form-check-label fw-medium" for="process_${process.id}">
                                    ${process.name}
                                </label>
                            </div>
                            <div class="level-select ${isSelected ? '' : 'd-none'}">
                                <label class="form-label small text-muted mb-2">سطوح مورد نیاز:</label>
                                <div class="form-check">
                                    <input class="form-check-input level-checkbox" type="checkbox" value="practical"
                                        id="level_${process.id}_practical" data-process-id="${process.id}"
                                        ${selectedLevels.includes('practical') || (!isSelected) ? 'checked' : ''}>
                                    <label class="form-check-label small" for="level_${process.id}_practical">عملی</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input level-checkbox" type="checkbox" value="proficient"
                                        id="level_${process.id}_proficient" data-process-id="${process.id}"
                                        ${selectedLevels.includes('proficient') ? 'checked' : ''}>
                                    <label class="form-check-label small" for="level_${process.id}_proficient">مسلط</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input level-checkbox" type="checkbox" value="advanced"
                                        id="level_${process.id}_advanced" data-process-id="${process.id}"
                                        ${selectedLevels.includes('advanced') ? 'checked' : ''}>
                                    <label class="form-check-label small" for="level_${process.id}_advanced">پیشرفته</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            processesList.insertAdjacentHTML('beforeend', processHtml);
        });

        document.querySelectorAll('.process-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.process-card');
                const levelSelect = card.querySelector('.level-select');
                const processId = this.dataset.processId;

                if (this.checked) {
                    const checkedCount = document.querySelectorAll('.process-checkbox:checked').length;
                    if (checkedCount > 3) {
                        this.checked = false;
                        alert('حداکثر ۳ پردازش می‌توانید انتخاب کنید.');
                        return;
                    }
                    card.classList.add('border-primary');
                    levelSelect.classList.remove('d-none');
                    
                    // Save to state with default level if not already saved
                    if (!selectedProcessesState[processId]) {
                        selectedProcessesState[processId] = ['practical'];
                    }
                } else {
                    card.classList.remove('border-primary');
                    levelSelect.classList.add('d-none');
                    
                    // Remove from state
                    delete selectedProcessesState[processId];
                }
            });
        });
        
        // Add event listeners to level checkboxes
        document.querySelectorAll('.level-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const processId = this.dataset.processId;
                const level = this.value;
                
                // Get all checked levels for this process
                const checkedLevels = Array.from(
                    document.querySelectorAll(`.level-checkbox[data-process-id="${processId}"]:checked`)
                ).map(cb => cb.value);
                
                // Update state
                if (checkedLevels.length > 0) {
                    selectedProcessesState[processId] = checkedLevels;
                } else {
                    // At least one level must be selected, recheck this one
                    this.checked = true;
                    selectedProcessesState[processId] = [level];
                }
            });
        });
    }

    // Handle domain checkbox changes - aggregate processes from selected domains
    domainCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.bp-domain');
            const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');

            if (this.checked) {
                // Check max 3 limit
                if (checkedDomains.length > 3) {
                    this.checked = false;
                    alert('حداکثر ۳ حوزه می‌توانید انتخاب کنید.');
                    return;
                }
                card.classList.add('sel');
            } else {
                card.classList.remove('sel');
            }
            
            // Aggregate processes from all selected domains
            allProcessesMap.clear();
            checkedDomains.forEach(domainCheckbox => {
                const processesData = domainCheckbox.dataset.processes;
                if (processesData) {
                    const processes = JSON.parse(processesData);
                    processes.forEach(process => {
                        if (!allProcessesMap.has(process.id)) {
                            allProcessesMap.set(process.id, process);
                        }
                    });
                }
            });
            
            if (allProcessesMap.size > 0) {
                processesContainer.style.display = 'block';
                renderProcesses(Array.from(allProcessesMap.values()));
            } else {
                processesContainer.style.display = 'none';
                processesList.innerHTML = '';
            }
        });
    });

    // Initialize with selected domains on page load
    const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');
    if (checkedDomains.length > 0) {
        checkedDomains.forEach(domainCheckbox => {
            const processesData = domainCheckbox.dataset.processes;
            if (processesData) {
                const processes = JSON.parse(processesData);
                processes.forEach(process => {
                    if (!allProcessesMap.has(process.id)) {
                        allProcessesMap.set(process.id, process);
                    }
                });
            }
        });
        
        if (allProcessesMap.size > 0) {
            processesContainer.style.display = 'block';
            renderProcesses(Array.from(allProcessesMap.values()));
        }
    }

    function buildProcessesHiddenInputs(e) {
        if (!form) return true;

        form.querySelectorAll('input[name^="processes"]').forEach(el => el.remove());
        form.querySelectorAll('input[name^="domains"]').forEach(el => el.remove());

        // Validate and build domain hidden inputs
        const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');
        if (checkedDomains.length < 1 || checkedDomains.length > 3) {
            const errorEl = document.getElementById('domains-error');
            if (errorEl) {
                errorEl.querySelector('span').textContent = 'حداقل ۱ و حداکثر ۳ حوزه انتخاب کنید';
                errorEl.style.display = 'block';
            }
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            return false;
        }
        
        // Create hidden inputs for domains
        checkedDomains.forEach((checkbox, index) => {
            const domainInput = document.createElement('input');
            domainInput.type = 'hidden';
            domainInput.name = `domains[${index}]`;
            domainInput.value = checkbox.value;
            form.appendChild(domainInput);
        });
        
        const domainsErrorEl = document.getElementById('domains-error');
        if (domainsErrorEl) domainsErrorEl.style.display = 'none';

        // Validate processes

        const checkedProcesses = document.querySelectorAll('.process-checkbox:checked');
        if (checkedProcesses.length < 1 || checkedProcesses.length > 3) {
            const errorEl = document.getElementById('processes-error');
            if (errorEl) {
                errorEl.querySelector('span').textContent = 'حداقل ۱ و حداکثر ۳ پردازش انتخاب کنید';
                errorEl.style.display = 'block';
            }
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            return false;
        }

        let inputIndex = 0;
        let hasError = false;

        checkedProcesses.forEach((checkbox) => {
            const processId = checkbox.dataset.processId;
            const card = checkbox.closest('.process-card');
            const selectedLevels = card ? card.querySelectorAll('.level-checkbox:checked') : [];

            if (!selectedLevels || selectedLevels.length === 0) {
                alert('لطفا حداقل یک سطح مهارت برای هر پردازش انتخاب کنید.');
                hasError = true;
                return;
            }

            selectedLevels.forEach((levelCheckbox) => {
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = `processes[${inputIndex}][id]`;
                idInput.value = processId;
                form.appendChild(idInput);

                const levelInput = document.createElement('input');
                levelInput.type = 'hidden';
                levelInput.name = `processes[${inputIndex}][level]`;
                levelInput.value = levelCheckbox.value;
                form.appendChild(levelInput);

                inputIndex++;
            });
        });

        if (hasError) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            return false;
        }

        const errorEl = document.getElementById('processes-error');
        if (errorEl) errorEl.style.display = 'none';
        return true;
    }

    if (submitBtn) {
        submitBtn.addEventListener('click', function(e) {
            buildProcessesHiddenInputs(e);
        }, true);
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            buildProcessesHiddenInputs(e);
        }, true);
    }

    // Work type styling
    workTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.bp-wt').forEach(card => {
                card.classList.remove('sel');
            });
            if (this.checked) {
                this.closest('.form-check').querySelector('.bp-wt').classList.add('sel');
            }
        });
    });

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
    if (skillsSearchInput && skillsChoices) {
        skillsSearchInput.addEventListener('input', function () {
            var q = this.value.trim().toLowerCase();
            var selectedVals = skillsChoices.getValue(true).map(function (v) {
                return typeof v === 'object' ? v.value : v;
            });
            var selectedSet = new Set(selectedVals);
            var filtered = q
                ? allSkillOptionsData.filter(function (opt) {
                    return selectedSet.has(opt.value) || opt.label.toLowerCase().indexOf(q) !== -1;
                  })
                : allSkillOptionsData;
            skillsChoices.destroy();
            skillsSelectEl.innerHTML = '';
            filtered.forEach(function (opt) {
                var el = document.createElement('option');
                el.value = opt.value;
                el.textContent = opt.label;
                el.selected = selectedSet.has(opt.value);
                skillsSelectEl.appendChild(el);
            });
            skillsChoices = new Choices(skillsSelectEl, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'انتخاب کنید...',
            });
        });
    }
});
</script>

<style>
.bp-form-head h4 { font-weight: 800; }

.bp-fcard { background: #fff; border: 1px solid var(--bp-border); border-radius: var(--bp-r-lg); overflow: hidden; }
.bp-fh { padding: 16px 24px; border-bottom: 1px solid var(--bp-hair); display: flex; align-items: center; gap: 10px; }
.bp-fh-icon { width: 34px; height: 34px; border-radius: var(--bp-r); background: var(--bp-tint-blue); color: var(--bp-blue); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex: none; }
.bp-fh h5 { font-size: 1rem; font-weight: 700; margin: 0; }
.bp-fb { padding: 24px; }

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
.bp-wt-check {
    position: absolute; top: 12px; inset-inline-end: 12px;
    color: var(--bp-blue); font-size: 1.1rem;
    opacity: 0; transition: opacity .2s;
}
.bp-wt.sel .bp-wt-check { opacity: 1; }

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

.card.process-card {
    background-color: var(--bp-tint-blue) !important;
    border-left: 3px solid var(--bp-blue) !important;
}

/* ── Search/filter inputs ────────────────────────────────────────────── */
.bp-search-wrap { position: relative; max-width: 320px; margin-bottom: 12px; }
.bp-search-wrap i { position: absolute; top: 50%; right: 12px; transform: translateY(-50%); color: var(--bp-muted); font-size: 1rem; pointer-events: none; }
.bp-search-input { width: 100%; border: 1px solid var(--bp-border); border-radius: var(--bp-r); padding: 7px 38px 7px 12px; font-size: .85rem; color: var(--bp-text, #1a1a2e); background: #fff; font-family: inherit; transition: border-color .15s, box-shadow .15s; }
.bp-search-input:focus { outline: none; border-color: var(--bp-blue); box-shadow: 0 0 0 3px var(--bp-tint-blue); }
</style>
@endsection
