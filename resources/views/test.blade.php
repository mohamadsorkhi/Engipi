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

{{-- Subdomain lookup keyed by domain UUID — injected in a script tag to avoid HTML-attribute JSON encoding issues --}}
<script>
const domainSubdomainsMap = @json(
    $domains->mapWithKeys(fn($d) => [
        $d->id => $d->subdomains->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()
    ])
);
</script>

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
        btn.addEventListener('click', function () {
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

            // Read subdomains from the pre-built JS map (no API call needed)
            const subdomains = domainSubdomainsMap[domainId] || [];

            loadedSubdomainsByDomain[domainId] = subdomains;
            const allFlat = Object.values(loadedSubdomainsByDomain).flat();
            if (allFlat.length > 0) {
                subdomain.disabled = false;
                renderSubdomains(allFlat);
            } else {
                subdomain.disabled = true;
                subdomain.innerHTML = '<option value="">زیرشاخه‌ای برای این حوزه تعریف نشده</option>';
            }
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
            card.className = 'mb-0';
            card.style.cursor          = 'pointer';
            card.style.transition      = 'border-color 0.15s, box-shadow 0.15s';
            card.style.border          = '2.5px solid #ced4da';
            card.style.borderRadius    = '8px';
            card.style.height          = '70px';
            card.style.display         = 'flex';
            card.style.flexDirection   = 'column';
            card.style.alignItems      = 'center';
            card.style.justifyContent  = 'center';
            card.style.background      = '#fff';
            card.style.overflow        = 'hidden';

            card.innerHTML = `
                <div style="padding:8px 4px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">
                    <div class="skill-icon" style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#e7f3ff;color:#405189;flex-shrink:0;">
                        <i class="ri-code-s-slash-line" style="font-size:0.78rem;"></i>
                    </div>
                    <p style="margin:4px 0 0;font-weight:500;font-size:0.72rem;line-height:1.2;word-break:break-word;">${skill.name}</p>
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

                card.style.border    = '2.5px solid #00d4aa';
                card.style.boxShadow = '0 0 0 3px rgba(0,212,170,0.15)';

                const iconDiv = card.querySelector('.skill-icon');
                if (iconDiv) { iconDiv.style.background = '#00d4aa'; iconDiv.style.color = '#fff'; }

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
            card.className = 'card mb-0';
            card.style.border = '2px solid #00d4aa';
            card.innerHTML = `
                <div class="card-body" style="padding:0.5rem;font-size:0.85rem;">
                    <div class="d-flex align-items-center mb-2">
                        <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#00d4aa;color:#fff;margin-left:0.4rem;flex-shrink:0;">
                            <i class="ri-code-s-slash-line" style="font-size:0.85rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-semibold" style="font-size:0.85rem;">${skill.name}</h6>
                    </div>
                    <div class="mb-2">
                        <label class="form-label form-label-sm mb-1">سطح</label>
                        <select class="form-select form-select-sm">
                            <option value="مبتدی">مبتدی</option>
                            <option value="متوسط">متوسط</option>
                            <option value="حرفه ای">حرفه ای</option>
                        </select>
                    </div>
                    <div class="mb-2">
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
                    skill.cardRef.style.border    = '2.5px solid #ced4da';
                    skill.cardRef.style.boxShadow = '';
                    const iconDiv = skill.cardRef.querySelector('.skill-icon');
                    if (iconDiv) { iconDiv.style.background = '#e7f3ff'; iconDiv.style.color = '#405189'; }
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
