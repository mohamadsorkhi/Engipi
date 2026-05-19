@extends('layouts.master')

@section('title', 'مدیریت مهارت‌ها')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">مدیریت مهارت‌های من</h5>
                    <div class="btn-group" role="group" id="viewToggle">
                        <input type="radio" class="btn-check" name="viewType" id="cardView" value="card" checked>
                        <label class="btn btn-outline-primary btn-sm" for="cardView">
                            <i class="ri-layout-grid-line"></i> کارت
                        </label>
                        <input type="radio" class="btn-check" name="viewType" id="tableView" value="table">
                        <label class="btn btn-outline-primary btn-sm" for="tableView">
                            <i class="ri-table-line"></i> جدول
                        </label>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        حوزه‌های تخصصی و پردازش‌های مورد نظر خود را انتخاب کنید. می‌توانید چندین حوزه انتخاب کنید. این اطلاعات برای پیشنهاد پروژه‌های مناسب به شما استفاده می‌شود.
                    </p>

                    <!-- Card View -->
                    <div id="cardViewContainer">

                    @if($domains->isEmpty())
                        <div class="alert alert-warning text-center">
                            <i class="ri-alert-line me-2"></i>
                            در حال حاضر هیچ حوزه تخصصی در سیستم ثبت نشده است. لطفا بعدا مراجعه کنید.
                        </div>
                    @else
                        <form id="skillsForm" action="{{ route('user.skills.store') }}" method="POST">
                            @csrf

                            <!-- Step 1: Select Domains (Multiple) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <span class="badge bg-primary me-2">۱</span>
                                    انتخاب حوزه‌های تخصصی <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted small mb-3">یک یا چند حوزه تخصصی انتخاب کنید.</p>
                                
                                <div class="row g-3" id="domainSelection">
                                    @foreach($domains as $domain)
                                        @php $isSelected = $selectedDomains->contains('id', $domain->id); @endphp
                                        <div class="col-md-4 col-lg-3">
                                            <div class="card border domain-card {{ $isSelected ? 'border-primary bg-primary-subtle' : '' }}" data-domain-id="{{ $domain->id }}">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input domain-checkbox" type="checkbox" 
                                                            id="domain_{{ $domain->id }}" value="{{ $domain->id }}"
                                                            data-subdomains='@json($domain->subdomains)'
                                                            {{ $isSelected ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-medium" for="domain_{{ $domain->id }}">
                                                            {{ $domain->name }}
                                                           {{ $domain->subdomains->count() }} گرایش
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback d-block" id="domain-error"></div>
                            </div>
<!-- Step 2: Select Subdomains -->
<div class="mb-4" id="subdomainsSection" style="display: none;">

    <label class="form-label fw-semibold">
        <span class="badge bg-primary me-2">۲</span>
        انتخاب گرایش‌ها
    </label>

    <p class="text-muted small mb-3">
        گرایش‌های مرتبط را انتخاب کنید.
    </p>

    <div class="row g-3" id="subdomainsContainer">
    </div>

</div>
                            <!-- Step 2: Select Processes -->
                            <div class="mb-4" id="processesSection" style="display: none;">
                                <label class="form-label fw-semibold">
                                   <span class="badge bg-primary me-2">۲</span>
                                    انتخاب پردازش‌ها <span class="text-danger">*</span>
                                </label>
                                <p class="text-muted small mb-3">حداقل ۱ پردازش انتخاب کنید و سطح مهارت خود را مشخص نمایید.</p>
                                
                                <div class="alert alert-info d-flex align-items-center mb-3">
                                    <i class="ri-information-line me-2 fs-5"></i>
                                    <div>
                                        <strong>سطوح مهارت:</strong>
                                        <span class="badge bg-secondary ms-2">عملی</span> تجربه اولیه
                                        <span class="badge bg-info ms-2">مسلط</span> تجربه کافی
                                        <span class="badge bg-success ms-2">پیشرفته</span> متخصص
                                    </div>
                                </div>

                                <div id="processesContainer" class="row g-3">
                                    <!-- Processes will be loaded dynamically -->
                                </div>
                                <div class="invalid-feedback" id="processes-error"></div>
                            </div>

                            <!-- Selected Summary -->
                            <div class="mb-4" id="summarySection" style="display: none;">
                                <label class="form-label fw-semibold">
                                    <span class="badge bg-success me-2">✓</span>
                                    خلاصه انتخاب‌ها
                                </label>
                                <div id="summaryContent" class="p-3 bg-light rounded">
                                    <!-- Summary will be shown here -->
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('root') }}" class="btn btn-light">انصراف</a>
                                <button type="submit" class="btn btn-primary ajax-submit" id="submitBtn" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                    ذخیره مهارت‌ها
                                </button>
                            </div>
                        </form>
                    </div>
                    <!-- End Card View -->

                    <!-- Table View -->
                    <div id="tableViewContainer" style="display: none;">
                        @if($selectedDomains->isEmpty() && $selectedProcesses->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="ri-information-line me-2"></i>
                                هنوز هیچ حوزه یا پردازشی انتخاب نکرده‌اید. از نمای کارت برای شروع استفاده کنید.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>حوزه تخصصی</th>
                                            <th>پردازش‌ها</th>
                                            <th width="120">عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($selectedDomains as $domain)
                                            @php
                                                $domainProcesses = $selectedProcesses->filter(fn($p) => $p->skill_domain_id === $domain->id);
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs flex-shrink-0 me-2">
                                                            <span class="avatar-title bg-primary-subtle text-primary rounded">
                                                                <i class="ri-stack-line"></i>
                                                            </span>
                                                        </div>
                                                        <strong>{{ $domain->name }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($domainProcesses->isNotEmpty())
                                                        @foreach($domainProcesses as $process)
                                                            <span class="badge bg-info me-1 mb-1">
                                                                {{ $process->name }}
                                                                @php
                                                                    $levelMap = ['practical' => 'عملی', 'proficient' => 'مسلط', 'advanced' => 'پیشرفته'];
                                                                    $level = $levelMap[$process->pivot->level] ?? '-';
                                                                @endphp
                                                                ({{ $level }})
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-soft-primary edit-domain-btn" 
                                                        data-domain-id="{{ $domain->id }}" 
                                                        data-domain-name="{{ $domain->name }}">
                                                        <i class="ri-edit-line"></i> ویرایش
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <!-- End Table View -->
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Domain Modal -->
    <div class="modal fade" id="editDomainModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش حوزه: <span id="modalDomainName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">پردازش‌ها و سطح مهارت خود را برای این حوزه مشخص کنید.</p>
                    <div id="modalProcessesContainer" class="row g-3">
                        <!-- Processes will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">بستن</button>
                    <button type="button" class="btn btn-primary" id="saveModalChanges">ذخیره تغییرات</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('skillsForm');
    const domainCheckboxes = document.querySelectorAll('.domain-checkbox');
    const processesSection = document.getElementById('processesSection');
    const processesContainer = document.getElementById('processesContainer');
    const summarySection = document.getElementById('summarySection');
    const summaryContent = document.getElementById('summaryContent');
    const submitBtn = document.getElementById('submitBtn');
    
    // View toggle
    const cardViewContainer = document.getElementById('cardViewContainer');
    const tableViewContainer = document.getElementById('tableViewContainer');
    const cardViewBtn = document.getElementById('cardView');
    const tableViewBtn = document.getElementById('tableView');
    
    let allProcessesMap = new Map();
    
    const levelLabels = {
        'practical': 'عملی',
        'proficient': 'مسلط',
        'advanced': 'پیشرفته'
    };

    // Pre-selected data from server - convert collection to {id: level} format
    const selectedProcessesRaw = @json($selectedProcesses);
    const selectedProcesses = {};
    if (Array.isArray(selectedProcessesRaw)) {
        selectedProcessesRaw.forEach(p => {
            if (p.pivot && p.pivot.level) {
                selectedProcesses[p.id] = p.pivot.level;
            }
        });
    }
    let currentProcesses = [];

    // View toggle functionality
    if (cardViewBtn && tableViewBtn) {
        cardViewBtn.addEventListener('change', function() {
            if (this.checked) {
                cardViewContainer.style.display = 'block';
                tableViewContainer.style.display = 'none';
            }
        });
        
        tableViewBtn.addEventListener('change', function() {
            if (this.checked) {
                cardViewContainer.style.display = 'none';
                tableViewContainer.style.display = 'block';
            }
        });
    }

    // Handle domain checkbox changes - aggregate processes from selected domains
    domainCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.domain-card');
            const checkedDomains = document.querySelectorAll('.domain-checkbox:checked');
            const domainName = this.closest('.form-check').querySelector('label').textContent.trim();
            
            if (this.checked) {
                card.classList.add('border-primary', 'bg-primary-subtle');
            } else {
                card.classList.remove('border-primary', 'bg-primary-subtle');
            }
            
            // Aggregate processes from all selected domains (preserve existing selections)
            allProcessesMap.clear();
            checkedDomains.forEach(domainCheckbox => {
                const processesData = domainCheckbox.dataset.processes;
                const currentDomainName = domainCheckbox.closest('.form-check').querySelector('label').textContent.trim();
                if (processesData) {
                    const processes = JSON.parse(processesData);
                    processes.forEach(process => {
                        if (!allProcessesMap.has(process.id)) {
                            // Add domain name to process object
                            process.domain_name = currentDomainName;
                            allProcessesMap.set(process.id, process);
                        }
                    });
                }
            });
            
            if (allProcessesMap.size > 0) {
                processesSection.style.display = 'block';
                renderProcesses(Array.from(allProcessesMap.values()));
            } else {
                processesSection.style.display = 'none';
                processesContainer.innerHTML = '';
            }
            
            updateSummary();
        });
    });

    // Initialize with selected domains on page load
    const checkedDomainsInit = document.querySelectorAll('.domain-checkbox:checked');
    if (checkedDomainsInit.length > 0) {
        checkedDomainsInit.forEach(domainCheckbox => {
            const processesData = domainCheckbox.dataset.processes;
            const domainName = domainCheckbox.closest('.form-check').querySelector('label').textContent.trim();
            if (processesData) {
                const processes = JSON.parse(processesData);
                processes.forEach(process => {
                    if (!allProcessesMap.has(process.id)) {
                        // Add domain name to process object
                        process.domain_name = domainName;
                        allProcessesMap.set(process.id, process);
                    }
                });
            }
        });
        
        if (allProcessesMap.size > 0) {
            processesSection.style.display = 'block';
            renderProcesses(Array.from(allProcessesMap.values()));
        }
    }

    function renderProcesses(processes) {
        processesContainer.innerHTML = '';
        
        processes.forEach(process => {
            const isSelected = selectedProcesses.hasOwnProperty(process.id);
            const selectedLevel = isSelected ? selectedProcesses[process.id] : '';
            
            const html = `
                <div class="col-md-6 col-lg-4">
                    <div class="card border process-card ${isSelected ? 'border-success' : ''}" data-process-id="${process.id}" data-domain-name="${process.domain_name || ''}">
                        <div class="card-body">
                            <div class="form-check mb-2">
                                <input class="form-check-input process-checkbox" type="checkbox" 
                                    id="process_${process.id}" value="${process.id}"
                                    ${isSelected ? 'checked' : ''}>
                                <label class="form-check-label fw-medium" for="process_${process.id}">
                                    ${process.name}
                                </label>
                            </div>
                            <div class="mb-3">
                                <span class="badge bg-primary-subtle text-primary small">
                                    <i class="ri-stack-line"></i> ${process.domain_name || 'نامشخص'}
                                </span>
                            </div>
                            <div class="level-selection ${isSelected ? '' : 'd-none'}">
                                <label class="form-label small text-muted">سطح مهارت:</label>
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check level-radio" name="level_${process.id}" 
                                        id="level_practical_${process.id}" value="practical"
                                        ${selectedLevel === 'practical' ? 'checked' : ''}>
                                    <label class="btn btn-outline-secondary btn-sm" for="level_practical_${process.id}">عملی</label>
                                    
                                    <input type="radio" class="btn-check level-radio" name="level_${process.id}" 
                                        id="level_proficient_${process.id}" value="proficient"
                                        ${selectedLevel === 'proficient' ? 'checked' : ''}>
                                    <label class="btn btn-outline-info btn-sm" for="level_proficient_${process.id}">مسلط</label>
                                    
                                    <input type="radio" class="btn-check level-radio" name="level_${process.id}" 
                                        id="level_advanced_${process.id}" value="advanced"
                                        ${selectedLevel === 'advanced' ? 'checked' : ''}>
                                    <label class="btn btn-outline-success btn-sm" for="level_advanced_${process.id}">پیشرفته</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            processesContainer.insertAdjacentHTML('beforeend', html);
        });

        // Add event listeners
        document.querySelectorAll('.process-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.process-card');
                const levelSection = card.querySelector('.level-selection');
                
                if (this.checked) {
                    // Check max 3 limit
                    const checkedCount = document.querySelectorAll('.process-checkbox:checked').length;
                    if (checkedCount > 3) {
                        this.checked = false;
                        showToast('حداکثر ۳ پردازش می‌توانید انتخاب کنید.', 'warning');
                        return;
                    }
                    card.classList.add('border-success');
                    levelSection.classList.remove('d-none');
                } else {
                    card.classList.remove('border-success');
                    levelSection.classList.add('d-none');
                }
                updateSummary();
            });
        });

        document.querySelectorAll('.level-radio').forEach(radio => {
            radio.addEventListener('change', updateSummary);
        });

        updateSummary();
    }

    function updateSummary() {
        const selectedDomains = document.querySelectorAll('.domain-checkbox:checked');
        const checkedProcesses = document.querySelectorAll('.process-checkbox:checked');
        
        let isValid = selectedDomains.length >= 1 && checkedProcesses.length >= 1;
        let allLevelsSelected = true;

        let summaryHtml = '';
        
        if (selectedDomains.length > 0) {
            const domainNames = Array.from(selectedDomains).map(d => d.closest('.form-check').querySelector('label').textContent.trim()).join('، ');
            summaryHtml += `<p class="mb-2"><strong>حوزه‌ها:</strong> ${domainNames}</p>`;
        }

        if (checkedProcesses.length > 0) {
            // Group processes by domain
            const processesByDomain = {};
            checkedProcesses.forEach(checkbox => {
                const processId = checkbox.value;
                const processCard = checkbox.closest('.process-card');
                const domainName = processCard.dataset.domainName || 'نامشخص';
                const processName = checkbox.closest('.card-body').querySelector('label').textContent.trim();
                const levelRadio = document.querySelector(`input[name="level_${processId}"]:checked`);
                
                if (!processesByDomain[domainName]) {
                    processesByDomain[domainName] = [];
                }
                
                if (levelRadio) {
                    const levelText = levelLabels[levelRadio.value];
                    processesByDomain[domainName].push(`${processName} - <span class="badge bg-info">${levelText}</span>`);
                } else {
                    allLevelsSelected = false;
                    processesByDomain[domainName].push(`${processName} - <span class="text-danger">سطح انتخاب نشده</span>`);
                }
            });
            
            summaryHtml += '<p class="mb-1"><strong>پردازش‌ها:</strong></p>';
            Object.keys(processesByDomain).forEach(domainName => {
                summaryHtml += `<div class="mb-2">`;
                summaryHtml += `<span class="badge bg-primary-subtle text-primary mb-1"><i class="ri-stack-line"></i> ${domainName}</span>`;
                summaryHtml += '<ul class="mb-0">';
                processesByDomain[domainName].forEach(processHtml => {
                    summaryHtml += `<li>${processHtml}</li>`;
                });
                summaryHtml += '</ul></div>';
            });
        }

        summaryContent.innerHTML = summaryHtml || '<p class="text-muted mb-0">هنوز چیزی انتخاب نشده است.</p>';
        
        if (checkedProcesses.length > 0) {
            summarySection.style.display = 'block';
        } else {
            summarySection.style.display = 'none';
        }

        // Enable submit only if valid
        submitBtn.disabled = !(isValid && allLevelsSelected);
    }

    function buildHiddenInputs() {
        // Remove existing hidden inputs
        document.querySelectorAll('.process-hidden-input, .domain-hidden-input').forEach(el => el.remove());
        
        // Build domain hidden inputs
        const selectedDomains = document.querySelectorAll('.domain-checkbox:checked');
        selectedDomains.forEach((checkbox, index) => {
            const domainInput = document.createElement('input');
            domainInput.type = 'hidden';
            domainInput.name = `domains[${index}]`;
            domainInput.value = checkbox.value;
            domainInput.className = 'domain-hidden-input';
            form.appendChild(domainInput);
        });
        
        // Build process hidden inputs
        const processesData = [];
        document.querySelectorAll('.process-checkbox:checked').forEach(checkbox => {
            const processId = checkbox.value;
            const levelRadio = document.querySelector(`input[name="level_${processId}"]:checked`);
            if (levelRadio) {
                processesData.push({
                    id: processId,
                    level: levelRadio.value
                });
            }
        });

        processesData.forEach((p, index) => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `processes[${index}][id]`;
            idInput.value = p.id;
            idInput.className = 'process-hidden-input';
            form.appendChild(idInput);

            const levelInput = document.createElement('input');
            levelInput.type = 'hidden';
            levelInput.name = `processes[${index}][level]`;
            levelInput.value = p.level;
            levelInput.className = 'process-hidden-input';
            form.appendChild(levelInput);
        });
    }

    // Important: the global "ajax-submit" handler serializes on CLICK, not on form submit.
    // So we build hidden inputs on click (capture phase) before jQuery serializes the form.
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'submitBtn') {
            buildHiddenInputs();
        }
    }, true);

    // Keep a normal submit hook too (non-ajax / enter key)
    form.addEventListener('submit', function() {
        buildHiddenInputs();
    });

    function showToast(message, type = 'info') {
        // Simple alert fallback - can be replaced with proper toast
        alert(message);
    }

    // Handle edit button clicks in table view
    document.querySelectorAll('.edit-domain-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const domainId = this.dataset.domainId;
            const domainName = this.dataset.domainName;
            
            // Set modal title
            document.getElementById('modalDomainName').textContent = domainName;
            
            // Find the domain checkbox to get processes
            const domainCheckbox = document.querySelector(`.domain-checkbox[value="${domainId}"]`);
            if (!domainCheckbox) return;
            
            const processes = JSON.parse(domainCheckbox.dataset.processes);
            const modalContainer = document.getElementById('modalProcessesContainer');
            modalContainer.innerHTML = '';
            
            // Render processes in modal
            processes.forEach(process => {
                const isSelected = selectedProcesses.hasOwnProperty(process.id);
                const selectedLevel = isSelected ? selectedProcesses[process.id] : '';
                
                const html = `
                    <div class="col-md-6">
                        <div class="card border ${isSelected ? 'border-success' : ''}" data-process-id="${process.id}">
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input modal-process-checkbox" type="checkbox" 
                                        id="modal_process_${process.id}" value="${process.id}"
                                        ${isSelected ? 'checked' : ''}>
                                    <label class="form-check-label fw-medium" for="modal_process_${process.id}">
                                        ${process.name}
                                    </label>
                                </div>
                                <div class="level-selection ${isSelected ? '' : 'd-none'}">
                                    <label class="form-label small text-muted">سطح مهارت:</label>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check modal-level-radio" name="modal_level_${process.id}" 
                                            id="modal_level_practical_${process.id}" value="practical"
                                            ${selectedLevel === 'practical' ? 'checked' : ''}>
                                        <label class="btn btn-outline-secondary btn-sm" for="modal_level_practical_${process.id}">عملی</label>
                                        
                                        <input type="radio" class="btn-check modal-level-radio" name="modal_level_${process.id}" 
                                            id="modal_level_proficient_${process.id}" value="proficient"
                                            ${selectedLevel === 'proficient' ? 'checked' : ''}>
                                        <label class="btn btn-outline-info btn-sm" for="modal_level_proficient_${process.id}">مسلط</label>
                                        
                                        <input type="radio" class="btn-check modal-level-radio" name="modal_level_${process.id}" 
                                            id="modal_level_advanced_${process.id}" value="advanced"
                                            ${selectedLevel === 'advanced' ? 'checked' : ''}>
                                        <label class="btn btn-outline-success btn-sm" for="modal_level_advanced_${process.id}">پیشرفته</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                modalContainer.insertAdjacentHTML('beforeend', html);
            });
            
            // Add event listeners for modal checkboxes
            document.querySelectorAll('.modal-process-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const card = this.closest('.card');
                    const levelSection = card.querySelector('.level-selection');
                    
                    if (this.checked) {
                        card.classList.add('border-success');
                        levelSection.classList.remove('d-none');
                    } else {
                        card.classList.remove('border-success');
                        levelSection.classList.add('d-none');
                    }
                });
            });
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editDomainModal'));
            modal.show();
        });
    });

    // Handle save changes in modal
    document.getElementById('saveModalChanges').addEventListener('click', function() {
        // Update selectedProcesses object with modal selections
        document.querySelectorAll('.modal-process-checkbox').forEach(checkbox => {
            const processId = checkbox.value;
            
            if (checkbox.checked) {
                const levelRadio = document.querySelector(`input[name="modal_level_${processId}"]:checked`);
                if (levelRadio) {
                    selectedProcesses[processId] = levelRadio.value;
                }
            } else {
                // Remove from selectedProcesses if unchecked
                delete selectedProcesses[processId];
            }
        });
        
        // Re-render processes in card view to reflect changes
        if (allProcessesMap.size > 0) {
            renderProcesses(Array.from(allProcessesMap.values()));
        }
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editDomainModal'));
        modal.hide();
        
        showToast('تغییرات ذخیره شد. برای ثبت نهایی دکمه "ذخیره مهارت‌ها" را بزنید.', 'success');
    });
});
</script>

<style>
.domain-card {
    cursor: pointer;
    transition: all 0.2s ease;
}
.domain-card:hover {
    border-color: var(--vz-primary) !important;
}
.process-card {
    transition: all 0.2s ease;
}
.card-radio .form-check-input {
    display: none;
}
</style>
@endsection
