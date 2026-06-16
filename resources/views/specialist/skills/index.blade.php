@extends('layouts.master')

@section('title', 'مدیریت مهارت‌ها')

@section('content')
    <x-admin.breadcrumb title="مهارت‌های من" parent="داشبورد" parentUrl="{{ route('specialist.dashboard') }}" />

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">مدیریت مهارت‌های من</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        ابتدا حوزه تخصصی خود را انتخاب کنید، سپس پردازش‌های مورد نظر (حداقل ۱ و حداکثر ۳) را انتخاب کرده و سطح مهارت خود را در هر پردازش مشخص نمایید.
                    </p>

                    @if($domains->isEmpty())
                        <div class="alert alert-warning text-center">
                            در حال حاضر هیچ حوزه تخصصی در سیستم ثبت نشده است. لطفا بعدا مراجعه کنید.
                        </div>
                    @else
                        <form action="{{ route('specialist.skills.store') }}" method="POST" id="skills-form">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="domain_id" class="form-label">
                                            حوزه تخصصی <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="domain_id" name="domain_id" required>
                                            <option value="">انتخاب کنید...</option>
                                            @foreach($domains as $domain)
                                                <option value="{{ $domain->id }}" 
                                                    data-processes="{{ $domain->processes->toJson() }}"
                                                    {{ $currentDomainId == $domain->id ? 'selected' : '' }}>
                                                    {{ $domain->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback"><span></span></div>
                                    </div>
                                </div>
                            </div>

                            <div id="processes-container" class="mb-4" style="{{ $currentDomainId ? '' : 'display: none;' }}">
                                <label class="form-label">
                                    پردازش‌ها <span class="text-danger">*</span>
                                    <small class="text-muted">(حداقل ۱ و حداکثر ۳ پردازش انتخاب کنید)</small>
                                </label>
                                <div class="alert alert-info small">
                                    <i class="ri-information-line me-1"></i>
                                    برای هر پردازش انتخاب شده، سطح مهارت خود را مشخص کنید.
                                </div>
                                <div id="processes-list" class="row g-3">
                                </div>
                                <div class="invalid-feedback d-block" id="processes-error"><span></span></div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary ajax-submit" id="submit-btn">
                                    <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="flex-grow-1">ذخیره تغییرات</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const domainSelect = document.getElementById('domain_id');
    const processesContainer = document.getElementById('processes-container');
    const processesList = document.getElementById('processes-list');
    const form = document.getElementById('skills-form');
    
    // Pre-selected processes from server
    const selectedProcesses = @json($selectedProcesses->keyBy('id'));
    
    const levelLabels = {
        'practical': 'عملی',
        'proficient': 'مسلط',
        'advanced': 'پیشرفته'
    };

    function renderProcesses(processes) {
        processesList.innerHTML = '';
        
        if (!processes || processes.length === 0) {
            processesList.innerHTML = '<div class="col-12"><p class="text-muted">پردازشی برای این حوزه تعریف نشده است.</p></div>';
            return;
        }

        processes.forEach((process, index) => {
            const isSelected = selectedProcesses[process.id] !== undefined;
            const selectedLevel = isSelected ? selectedProcesses[process.id].pivot.level : 'practical';
            
            const processHtml = `
                <div class="col-md-6 col-lg-4">
                    <div class="card border ${isSelected ? 'border-primary' : ''} process-card" data-process-id="${process.id}">
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input process-checkbox" type="checkbox" 
                                    id="process_${process.id}" 
                                    data-process-id="${process.id}"
                                    ${isSelected ? 'checked' : ''}>
                                <label class="form-check-label fw-medium" for="process_${process.id}">
                                    ${process.name}
                                </label>
                            </div>
                            <div class="level-select ${isSelected ? '' : 'd-none'}">
                                <label class="form-label small text-muted">سطح مهارت:</label>
                                <select class="form-select form-select-sm level-dropdown" data-process-id="${process.id}">
                                    <option value="practical" ${selectedLevel === 'practical' ? 'selected' : ''}>عملی</option>
                                    <option value="proficient" ${selectedLevel === 'proficient' ? 'selected' : ''}>مسلط</option>
                                    <option value="advanced" ${selectedLevel === 'advanced' ? 'selected' : ''}>پیشرفته</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            processesList.insertAdjacentHTML('beforeend', processHtml);
        });

        // Add event listeners
        document.querySelectorAll('.process-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const card = this.closest('.process-card');
                const levelSelect = card.querySelector('.level-select');
                
                if (this.checked) {
                    // Check max 3 limit
                    const checkedCount = document.querySelectorAll('.process-checkbox:checked').length;
                    if (checkedCount > 3) {
                        this.checked = false;
                        alert('حداکثر ۳ پردازش می‌توانید انتخاب کنید.');
                        return;
                    }
                    card.classList.add('border-primary');
                    levelSelect.classList.remove('d-none');
                } else {
                    card.classList.remove('border-primary');
                    levelSelect.classList.add('d-none');
                }
            });
        });
    }

    domainSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const processesData = selectedOption.dataset.processes;
        
        if (processesData) {
            const processes = JSON.parse(processesData);
            processesContainer.style.display = 'block';
            renderProcesses(processes);
        } else {
            processesContainer.style.display = 'none';
            processesList.innerHTML = '';
        }
    });

    // Initial render if domain is pre-selected
    if (domainSelect.value) {
        const selectedOption = domainSelect.options[domainSelect.selectedIndex];
        const processesData = selectedOption.dataset.processes;
        if (processesData) {
            renderProcesses(JSON.parse(processesData));
        }
    }

    // Form submission - build the processes array
    form.addEventListener('submit', function(e) {
        const checkedProcesses = document.querySelectorAll('.process-checkbox:checked');
        
        if (checkedProcesses.length === 0) {
            e.preventDefault();
            document.getElementById('processes-error').querySelector('span').textContent = 'حداقل یک پردازش باید انتخاب شود.';
            return;
        }

        // Remove any existing hidden inputs
        form.querySelectorAll('input[name^="processes"]').forEach(el => el.remove());

        // Add hidden inputs for each selected process
        checkedProcesses.forEach((checkbox, index) => {
            const processId = checkbox.dataset.processId;
            const card = checkbox.closest('.process-card');
            const level = card.querySelector('.level-dropdown').value;

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `processes[${index}][id]`;
            idInput.value = processId;
            form.appendChild(idInput);

            const levelInput = document.createElement('input');
            levelInput.type = 'hidden';
            levelInput.name = `processes[${index}][level]`;
            levelInput.value = level;
            form.appendChild(levelInput);
        });
    });
});
</script>
@endsection
