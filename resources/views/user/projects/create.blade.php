@extends('layouts.master')

@section('title', 'ثبت پروژه مهندسی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">ثبت پروژه مهندسی جدید</h5>
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
                                <div class="form-text">حداقل ۲۰ کاراکتر</div>
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
                                    <label class="form-label">
                                        پردازش‌های مورد نیاز <span class="text-danger">*</span>
                                        <small class="text-muted">(حداقل ۱ پردازش انتخاب کنید)</small>
                                    </label>
                                    <div class="alert alert-info small mb-3">
                                        <i class="ri-information-line me-1"></i>
                                        برای هر پردازش انتخاب شده، سطح مهارت مورد نیاز را مشخص کنید.
                                    </div>
                                    <div id="processes-list" class="row g-3"></div>
                                    <div class="invalid-feedback d-block" id="processes-error"><span></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Skills (Optional) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-semibold text-primary mb-3">
                                    <i class="ri-tools-line me-2"></i>مهارت‌های مورد نیاز (اختیاری)
                                </h6>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="skills" class="form-label">مهارت‌ها</label>
                                <select class="form-select" id="skills" name="skills[]" multiple>
                                    @foreach($skills as $skill)
                                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">مهارت‌های خاص مورد نیاز پروژه را انتخاب کنید</div>
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
                                <input type="number" class="form-control" id="budget_min" name="budget_min" 
                                    min="0" placeholder="مثال: 5000000">
                                <div class="invalid-feedback"><span></span></div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="budget_max" class="form-label">حداکثر بودجه (تومان)</label>
                                <input type="number" class="form-control" id="budget_max" name="budget_max" 
                                    min="0" placeholder="مثال: 10000000">
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
document.addEventListener('DOMContentLoaded', function () {
    const form              = document.getElementById('projectForm');
    const submitBtn         = document.getElementById('submitBtn');
    const spinner           = submitBtn.querySelector('.spinner-border');
    const domainCheckboxes  = document.querySelectorAll('.domain-checkbox');
    const processesContainer= document.getElementById('processes-container');
    const processesList     = document.getElementById('processes-list');
    const workTypeRadios    = document.querySelectorAll('input[name="work_type"]');
    const budgetMin         = document.getElementById('budget_min');
    const budgetMax         = document.getElementById('budget_max');

    let allProcessesMap      = new Map();
    let selectedProcessesState = {};

    // ── Choices.js for skills ─────────────────────────────────────────────
    if (typeof Choices !== 'undefined') {
        const skillsSelect = document.getElementById('skills');
        if (skillsSelect) {
            new Choices(skillsSelect, {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'انتخاب کنید...',
                noResultsText: 'نتیجه‌ای یافت نشد',
                itemSelectText: 'انتخاب',
            });
        }
    }

    // ── Work-type card styling ────────────────────────────────────────────
    workTypeRadios.forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.work-type-card').forEach(function (card) {
                card.classList.remove('border-primary', 'bg-primary-subtle');
            });
            if (this.checked) {
                this.closest('.form-check').querySelector('.work-type-card')
                    .classList.add('border-primary', 'bg-primary-subtle');
            }
        });
    });

    // ── Budget max ≥ min ──────────────────────────────────────────────────
    budgetMax.addEventListener('input', function () {
        const min = parseFloat(budgetMin.value) || 0;
        const max = parseFloat(this.value) || 0;
        this.setCustomValidity(max > 0 && max < min ? 'حداکثر بودجه باید بزرگتر از حداقل بودجه باشد' : '');
    });

    // ── Render processes for selected domains ─────────────────────────────
    function renderProcesses(processes) {
        processesList.innerHTML = '';

        if (!processes || processes.length === 0) {
            processesList.innerHTML = '<div class="col-12"><p class="text-muted">پردازشی برای این حوزه تعریف نشده است.</p></div>';
            return;
        }

        processes.forEach(function (process) {
            const isSelected   = Object.prototype.hasOwnProperty.call(selectedProcessesState, process.id);
            const savedLevels  = isSelected ? selectedProcessesState[process.id] : ['practical'];

            const html = '<div class="col-md-6 col-lg-4">' +
                '<div class="card border process-card ' + (isSelected ? 'border-primary' : '') + '" data-process-id="' + process.id + '">' +
                '<div class="card-body">' +
                '<div class="form-check mb-3">' +
                '<input class="form-check-input process-checkbox" type="checkbox" id="process_' + process.id + '" data-process-id="' + process.id + '" ' + (isSelected ? 'checked' : '') + '>' +
                '<label class="form-check-label fw-medium" for="process_' + process.id + '">' + process.name + '</label>' +
                '</div>' +
                '<div class="level-select ' + (isSelected ? '' : 'd-none') + '">' +
                '<label class="form-label small text-muted mb-2">سطوح مهارت مورد نیاز:</label>' +
                ['practical', 'proficient', 'advanced'].map(function (lvl) {
                    const labels = { practical: 'عملی', proficient: 'مسلط', advanced: 'پیشرفته' };
                    return '<div class="form-check"><input class="form-check-input level-checkbox" type="checkbox" value="' + lvl + '" id="level_' + process.id + '_' + lvl + '" data-process-id="' + process.id + '" ' + (savedLevels.includes(lvl) ? 'checked' : '') + '><label class="form-check-label small" for="level_' + process.id + '_' + lvl + '">' + labels[lvl] + '</label></div>';
                }).join('') +
                '</div></div></div></div>';

            processesList.insertAdjacentHTML('beforeend', html);
        });

        // Attach listeners to freshly rendered checkboxes
        processesList.querySelectorAll('.process-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () {
                const card        = this.closest('.process-card');
                const levelSelect = card.querySelector('.level-select');
                const pid         = this.dataset.processId;

                if (this.checked) {
                    if (document.querySelectorAll('.process-checkbox:checked').length > 3) {
                        this.checked = false;
                        alert('حداکثر ۳ پردازش می‌توانید انتخاب کنید.');
                        return;
                    }
                    card.classList.add('border-primary');
                    levelSelect.classList.remove('d-none');
                    if (!selectedProcessesState[pid]) selectedProcessesState[pid] = ['practical'];
                } else {
                    card.classList.remove('border-primary');
                    levelSelect.classList.add('d-none');
                    delete selectedProcessesState[pid];
                }
            });
        });

        processesList.querySelectorAll('.level-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () {
                const pid = this.dataset.processId;
                const checked = Array.from(
                    processesList.querySelectorAll('.level-checkbox[data-process-id="' + pid + '"]:checked')
                ).map(function (el) { return el.value; });

                if (checked.length > 0) {
                    selectedProcessesState[pid] = checked;
                } else {
                    this.checked = true;
                    selectedProcessesState[pid] = [this.value];
                }
            });
        });
    }

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

            if (allProcessesMap.size > 0) {
                processesContainer.style.display = 'block';
                renderProcesses(Array.from(allProcessesMap.values()));
            } else {
                processesContainer.style.display = 'none';
                processesList.innerHTML = '';
            }
        });
    });

    // ── Build domain + process hidden inputs; returns true if valid ───────
    function buildHiddenInputs() {
        form.querySelectorAll('input[name^="processes"], input[name^="domains"]').forEach(function (el) { el.remove(); });

        const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');
        if (checkedDomains.length < 1 || checkedDomains.length > 3) {
            const el = document.getElementById('domains-error');
            if (el) { el.querySelector('span').textContent = 'حداقل ۱ و حداکثر ۳ حوزه انتخاب کنید'; el.style.display = 'block'; }
            return false;
        }
        checkedDomains.forEach(function (cb, i) {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'domains[' + i + ']'; inp.value = cb.value;
            form.appendChild(inp);
        });
        const domainsErr = document.getElementById('domains-error');
        if (domainsErr) domainsErr.style.display = 'none';

        // Processes are optional — clear any previous error and build inputs only for checked ones
        const checkedProcesses = document.querySelectorAll('.process-checkbox:checked');
        const processesErr2 = document.getElementById('processes-error');
        if (processesErr2) processesErr2.style.display = 'none';

        let idx = 0;
        let ok  = true;
        checkedProcesses.forEach(function (cb) {
            const pid    = cb.dataset.processId;
            const card   = cb.closest('.process-card');
            const levels = card ? card.querySelectorAll('.level-checkbox:checked') : [];
            if (!levels || levels.length === 0) {
                alert('لطفاً حداقل یک سطح مهارت برای «' + card.querySelector('.form-check-label').textContent.trim() + '» انتخاب کنید.');
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
        return true;
    }

    // ── Show server-side validation errors ────────────────────────────────
    function showErrors(errors) {
        Object.keys(errors).forEach(function (key) {
            const msg = errors[key][0];

            if (key === 'domains' || key === 'domains.0') {
                const el = document.getElementById('domains-error');
                if (el) { el.querySelector('span').textContent = msg; el.style.display = 'block'; }
                return;
            }
            if (key === 'processes' || key.startsWith('processes.')) {
                const el = document.getElementById('processes-error');
                if (el) { el.querySelector('span').textContent = msg; el.style.display = 'block'; }
                return;
            }

            // Map dot-notation key to field name
            const fieldName = key.replace(/\.(\w+)/g, '[$1]');
            const field     = form.querySelector('[name="' + fieldName + '"]')
                           || form.querySelector('[name="' + fieldName + '[]"]');
            if (!field) return;

            field.classList.add('is-invalid');
            const fb = field.parentElement.querySelector('.invalid-feedback')
                    || field.closest('.mb-3, .col-md-12, .col-md-4, .col-12')?.querySelector('.invalid-feedback');
            if (fb) { fb.querySelector('span').textContent = msg; fb.style.display = 'block'; }
        });
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
</style>
@endsection
