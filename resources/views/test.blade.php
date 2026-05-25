@extends('layouts.master')

@section('title')
    انتخاب مهارت
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h4 class="card-title">انتخاب مهارت</h4>
            </div>

            <div class="card-body">

                {{-- DOMAIN --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">حوزه (حداکثر ۲)</label>
                    <div id="domainContainer" class="d-flex flex-wrap gap-2">
                        @foreach($domains as $item)
                        <button
                            type="button"
                            class="btn btn-outline-primary domain-card"
                            data-id="{{ $item->id }}"
                        >
                            {{ $item->name }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- SUBDOMAIN --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">زیرشاخه</label>
                    <select id="subdomain" class="form-select" disabled>
                        <option value="">اول حوزه را انتخاب کنید</option>
                    </select>
                </div>

                {{-- SELECTED SUBDOMAINS --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">گرایش‌های انتخاب شده (حداکثر ۲)</label>
                    <div id="selected-subdomains" class="d-flex flex-wrap gap-2"></div>
                </div>

                {{-- AVAILABLE SKILLS --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">مهارت</label>
                    <div id="skillsContainer" class="row g-3"></div>
                </div>

                {{-- SELECTED SKILLS --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">مهارت‌های انتخاب شده (حداکثر ۵)</label>
                    <div id="selected-skills" class="row g-3"></div>
                </div>

                {{-- SAVE BUTTON --}}
                <button type="button" class="btn btn-primary" id="saveBtn" disabled>
                    ذخیره
                </button>

            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
@media (max-width: 767.98px) {
    #saveBtn { width: 100%; }
    #domainContainer .btn { font-size: 0.8rem; padding: 0.35rem 0.75rem; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const subdomain                 = document.getElementById('subdomain');
    const skillsContainer           = document.getElementById('skillsContainer');
    const selectedSkillsContainer   = document.getElementById('selected-skills');
    const selectedSubdomainsContainer = document.getElementById('selected-subdomains');
    const saveBtn                   = document.getElementById('saveBtn');
    const domainButtons             = document.querySelectorAll('.domain-card');

    let selectedDomains          = [];
    let loadedSubdomainsByDomain = {}; // keyed by domainId for clean deselect
    let selectedSubdomains       = [];
    let selectedSkills           = [];

    saveBtn.disabled = true;


    // ─── DOMAIN SELECTION ───────────────────────────────────────────────

    domainButtons.forEach(btn => {
        btn.addEventListener('click', async function () {
            const domainId = btn.dataset.id;

            if (selectedDomains.includes(domainId)) {
                selectedDomains = selectedDomains.filter(id => id != domainId);
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');

                // Remove subdomains belonging to this domain
                const removedIds = new Set(
                    (loadedSubdomainsByDomain[domainId] || []).map(s => s.id)
                );
                delete loadedSubdomainsByDomain[domainId];

                // Drop selected subdomains that came from this domain
                selectedSubdomains = selectedSubdomains.filter(s => !removedIds.has(s.id));
                renderSelectedSubdomains();

                const remaining = Object.values(loadedSubdomainsByDomain).flat();
                if (remaining.length === 0) {
                    subdomain.disabled = true;
                    subdomain.innerHTML = '<option value="">اول حوزه را انتخاب کنید</option>';
                } else {
                    renderSubdomains(remaining);
                }
                Array.from(removedIds).forEach(subId => removeSkillsBySubdomain(subId));
                return;
            }

            if (selectedDomains.length >= 2) {
                alert('حداکثر دو حوزه قابل انتخاب است');
                return;
            }

            selectedDomains.push(domainId);
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');

            const response  = await fetch(`/api/subdomains/${domainId}`);
            const data      = await response.json();
            const subdomains = Array.isArray(data) ? data : data.data;

            // Store keyed by domainId so deselect can clean up precisely
            loadedSubdomainsByDomain[domainId] = subdomains;
            subdomain.disabled = false;
            renderSubdomains(Object.values(loadedSubdomainsByDomain).flat());
            clearSkillSelection();
        });
    });


    // ─── SUBDOMAIN RENDERING ────────────────────────────────────────────

    function renderSubdomains(items) {
        subdomain.innerHTML = '<option value="">انتخاب زیررشته</option>';
        items.forEach(item => subdomain.add(new Option(item.name, item.id)));
    }


    function renderSelectedSubdomains() {
        selectedSubdomainsContainer.innerHTML = '';
        selectedSubdomains.forEach((item, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-primary m-1';
            btn.innerHTML = `${item.name} &times;`;
            btn.addEventListener('click', function () {
                removeSkillsBySubdomain(item.id);
                selectedSubdomains.splice(index, 1);
                renderSelectedSubdomains();
            });
            selectedSubdomainsContainer.appendChild(btn);
        });
    }


    // ─── CLEAR SKILL SELECTION ──────────────────────────────────────────

    function clearSkillSelection() {
        selectedSkills = [];
        renderSelectedSkills();
        skillsContainer.innerHTML = '';
        saveBtn.disabled = true;
    }

    function removeSkillsBySubdomain(subdomainId) {
        selectedSkills = selectedSkills.filter(skill => skill.subdomainId !== subdomainId);
        skillsContainer.querySelectorAll(`[data-subdomain-id="${subdomainId}"]`)
            .forEach(el => el.remove());
        renderSelectedSkills();
        saveBtn.disabled = selectedSkills.length === 0;
        if (selectedSubdomains.length === 0) {
            skillsContainer.innerHTML = '';
        }
    }


    // ─── SUBDOMAIN CHANGE → LOAD SKILLS ─────────────────────────────────

    subdomain.addEventListener('change', async function () {
        const subdomainID = this.value;
        if (!subdomainID) return;

        if (selectedSubdomains.some(x => x.id == subdomainID)) {
            alert('این گرایش قبلا انتخاب شده');
            this.value = '';
            return;
        }

        if (selectedSubdomains.length >= 2) {
            alert('حداکثر دو گرایش قابل انتخاب است');
            this.value = '';
            return;
        }

        const allSubs = Object.values(loadedSubdomainsByDomain).flat();
        const selectedItem = allSubs.find(x => x.id == subdomainID);
        if (selectedItem) {
            selectedSubdomains.push(selectedItem);
            renderSelectedSubdomains();
        }

        const response = await fetch(`/api/skills/${subdomainID}`);
        const skills   = await response.json();

        skills.forEach(skill => {
            const col  = document.createElement('div');
            col.className = 'col-6 col-md-4 col-lg-3';
            col.dataset.subdomainId = subdomainID;

            const card = document.createElement('div');
            card.className = 'card border border-dashed h-100 mb-0';
            card.style.cursor     = 'pointer';
            card.style.transition = 'border-color 0.15s, box-shadow 0.15s';

            card.innerHTML = `
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3">
                    <div class="avatar-sm mb-2">
                        <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-4">
                            <i class="ri-code-s-slash-line"></i>
                        </span>
                    </div>
                    <p class="mb-0 fw-medium small text-wrap">${skill.name}</p>
                </div>
            `;

            card.addEventListener('click', function () {
                if (selectedSkills.some(s => s.id == skill.id)) return;

                if (selectedSkills.length >= 5) {
                    alert('حداکثر ۵ مهارت قابل انتخاب است');
                    return;
                }

                selectedSkills.push({
                    id:          skill.id,
                    name:        skill.name,
                    subdomainId: subdomainID,
                    level:       'مبتدی',
                    years:       1,
                    cardRef:     card,
                });

                card.classList.remove('border-dashed');
                card.classList.add('border-primary', 'border-2');
                card.style.boxShadow = '0 0 0 0.2rem rgba(var(--vz-primary-rgb), 0.15)';

                const avatar = card.querySelector('.avatar-title');
                avatar.classList.remove('bg-primary-subtle', 'text-primary');
                avatar.classList.add('bg-primary', 'text-white');

                renderSelectedSkills();
                saveBtn.disabled = false;
            });

            col.appendChild(card);
            skillsContainer.appendChild(col);
        });
    });


    // ─── SELECTED SKILLS RENDERING ──────────────────────────────────────

    function renderSelectedSkills() {
        selectedSkillsContainer.innerHTML = '';

        selectedSkills.forEach((skill, index) => {
            const col = document.createElement('div');
            col.className = 'col-12 col-md-6 col-lg-4';

            const card = document.createElement('div');
            card.className = 'card border mb-0';
            card.innerHTML = `
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm me-2 flex-shrink-0">
                            <span class="avatar-title bg-primary text-white rounded-circle fs-5">
                                <i class="ri-code-s-slash-line"></i>
                            </span>
                        </div>
                        <h6 class="mb-0 fw-semibold">${skill.name}</h6>
                    </div>
                    <div class="mb-2">
                        <label class="form-label form-label-sm mb-1">سطح</label>
                        <select class="form-select form-select-sm">
                            <option value="مبتدی">مبتدی</option>
                            <option value="متوسط">متوسط</option>
                            <option value="حرفه ای">حرفه ای</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label form-label-sm mb-1">سال‌های تجربه</label>
                        <input type="number" class="form-control form-control-sm" value="${skill.years}" min="0">
                    </div>
                    <button type="button" class="btn btn-soft-danger btn-sm w-100">
                        <i class="ri-delete-bin-line me-1"></i>حذف
                    </button>
                </div>
            `;

            const select = card.querySelector('select');
            select.value = skill.level;
            select.addEventListener('change', function () { skill.level = this.value; });

            const input = card.querySelector('input');
            input.addEventListener('input', function () { skill.years = this.value; });

            card.querySelector('button').addEventListener('click', function () {
                if (skill.cardRef) {
                    skill.cardRef.classList.remove('border-primary', 'border-2');
                    skill.cardRef.classList.add('border-dashed');
                    skill.cardRef.style.boxShadow = '';
                    const avatar = skill.cardRef.querySelector('.avatar-title');
                    if (avatar) {
                        avatar.classList.remove('bg-primary', 'text-white');
                        avatar.classList.add('bg-primary-subtle', 'text-primary');
                    }
                }
                selectedSkills.splice(index, 1);
                renderSelectedSkills();
                saveBtn.disabled = selectedSkills.length === 0;
            });

            col.appendChild(card);
            selectedSkillsContainer.appendChild(col);
        });
    }


    // ─── SAVE ────────────────────────────────────────────────────────────

    saveBtn.addEventListener('click', async function () {
        saveBtn.disabled = true;

        const dataToSave = selectedSkills.map(skill => ({
            skill_id: skill.id,
            level:    skill.level,
            years:    skill.years,
        }));

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
                alert(result.message);
                if (result.redirect) {
                    window.location.assign(result.redirect);
                }
            } else {
                const errors = result.errors
                    ? Object.values(result.errors).flat().join('\n')
                    : result.message || 'خطا در ذخیره';
                alert(errors);
            }
        } catch (e) {
            alert('خطا در ارتباط با سرور');
        } finally {
            saveBtn.disabled = selectedSkills.length === 0;
        }
    });

});
</script>
@endpush
