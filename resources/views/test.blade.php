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

                {{-- SUBDOMAIN — custom dropdown (native <select> cannot be styled cross-browser) --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">زیرشاخه</label>
                    <div class="ep-select ep-select--disabled" id="sd-wrap">
                        <div class="ep-select__trigger" id="sd-trigger">
                            <span id="sd-label">اول حوزه را انتخاب کنید</span>
                            <i class="ri-arrow-down-s-line ep-select__chevron"></i>
                        </div>
                        <ul class="ep-select__menu" id="sd-menu"></ul>
                    </div>
                </div>

                {{-- SELECTED SUBDOMAINS --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">گرایش‌های انتخاب شده (حداکثر ۲)</label>
                    <div id="selected-subdomains" class="d-flex flex-wrap gap-2"></div>
                </div>

                {{-- AVAILABLE SKILLS --}}
                <div class="mb-4 bp-skillsec bp-skillsec--blue">
                    <label class="bp-skillsec-label">
                        <i class="ri-code-box-line"></i>پردازش‌ها
                    </label>
                    <div class="bp-skillsec-search">
                        <i class="ri-search-line"></i>
                        <input type="text" id="skillSearchSoftware" placeholder="جستجوی مهارت...">
                    </div>
                    <div id="skillsContainerSoftware" class="row g-2"></div>
                    <p class="bp-skillsec-empty d-none" id="skillsEmptySoftware">موردی یافت نشد</p>
                </div>

                <div class="bp-skillsec-divider"></div>

                <div class="mb-4 bp-skillsec bp-skillsec--teal">
                    <label class="bp-skillsec-label">
                        <i class="ri-tools-line"></i>مهارت‌های میدانی
                    </label>
                    <div class="bp-skillsec-search">
                        <i class="ri-search-line"></i>
                        <input type="text" id="skillSearchField" placeholder="جستجوی مهارت...">
                    </div>
                    <div id="skillsContainerField" class="row g-2"></div>
                    <p class="bp-skillsec-empty d-none" id="skillsEmptyField">موردی یافت نشد</p>
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
    z-index: 200;
    list-style: none;
    margin: 0;
    padding: .25rem 0;
    background: #ffffff;
    border: 1px solid var(--ep-border-2, #dee2e6);
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,.10);
    max-height: 260px;
    overflow-y: auto;
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
    margin-bottom: .75rem;
}
.bp-skillsec-label i { font-size: 1.3rem; }
.bp-skillsec--blue .bp-skillsec-label { color: var(--bp-blue); }
.bp-skillsec--teal .bp-skillsec-label { color: var(--bp-teal); }
.bp-skillsec-divider {
    height: 1px;
    background: var(--bp-hair);
    margin: 32px 0 28px;
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
    border: 2.5px solid #ced4da;
    border-radius: var(--bp-r-lg);
    width: 100%;
    min-height: 72px;
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

/* ── Selected-skill cards (مهارت‌های انتخاب شده) ─────────────────────────── */
.bp-selected-skill-card .card-body {
    padding: .6rem .65rem;
}
.bp-selected-skill-card h6 {
    font-weight: 700;
    font-size: .92rem;
    line-height: 1.3;
    letter-spacing: -.01em;
    margin: 0;
}
.bp-selected-skill-card .form-label {
    font-size: .7rem;
    font-weight: 600;
    color: var(--bp-muted);
    margin-bottom: 3px;
}
.bp-selected-skill-card .form-select-sm,
.bp-selected-skill-card .form-control-sm {
    font-size: .78rem;
    padding: .25rem .5rem;
}

/* ── Responsive ──────────────────────────────────────────────────────── */
@media (max-width: 767.98px) {
    #saveBtn { width: 100%; }
    #domainContainer .btn { font-size: 0.8rem; padding: 0.35rem 0.75rem; }
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
    const domainButtons               = document.querySelectorAll('.domain-card');

    // ─── CUSTOM SUBDOMAIN DROPDOWN ──────────────────────────────────────
    const sdWrap    = document.getElementById('sd-wrap');
    const sdTrigger = document.getElementById('sd-trigger');
    const sdLabel   = document.getElementById('sd-label');
    const sdMenu    = document.getElementById('sd-menu');

    function sdEnable()  { sdWrap.classList.remove('ep-select--disabled'); }
    function sdDisable() { sdWrap.classList.remove('ep-select--open'); sdWrap.classList.add('ep-select--disabled'); }
    function sdClose()   { sdWrap.classList.remove('ep-select--open'); }

    function sdReset(placeholder) {
        sdMenu.innerHTML = '';
        sdLabel.textContent = placeholder;
    }

    function sdPopulate(items) {
        sdMenu.innerHTML = '';
        items.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'ep-select__option';
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
        sdWrap.classList.toggle('ep-select--open');
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!sdWrap.contains(e.target)) sdClose();
    });

    // ─── STATE ──────────────────────────────────────────────────────────
    let selectedDomains          = [];
    let loadedSubdomainsByDomain = {};
    let selectedSubdomains       = [];
    let selectedSkills           = [];

    saveBtn.disabled = true;

    // ─── DOMAIN SELECTION ───────────────────────────────────────────────
    domainButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const domainId = btn.dataset.id;

            if (selectedDomains.includes(domainId)) {
                // Deselect
                selectedDomains = selectedDomains.filter(function (id) { return id !== domainId; });
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');

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
                return;
            }

            if (selectedDomains.length >= 2) {
                alert('حداکثر دو حوزه قابل انتخاب است');
                return;
            }

            selectedDomains.push(domainId);
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');

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
        });
    });

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
        }

        const response = await fetch('/api/skills/' + subdomainID);
        const skills   = await response.json();

        skills.forEach(function (skill) {
            const theme = getSkillTypeTheme(skill.skill_type);

            const col = document.createElement('div');
            col.className = 'col-6 col-sm-6 col-md-4 col-lg-2';
            col.dataset.subdomainId = subdomainID;
            col.dataset.skillName   = skill.name;

            const card = document.createElement('div');
            card.className = 'mb-0 bp-skill-card';

            card.innerHTML =
                '<div style="padding:8px 10px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">' +
                    '<div class="skill-icon" style="width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:' + theme.tint + ';color:' + theme.color + ';flex-shrink:0;">' +
                        '<i class="' + theme.icon + '" style="font-size:0.7rem;"></i>' +
                    '</div>' +
                    '<p style="margin:5px 0 0;font-weight:700;font-size:0.78rem;line-height:1.25;word-break:break-word;">' + skill.name + '</p>' +
                '</div>';

            card.addEventListener('click', function () {
                if (selectedSkills.some(function (s) { return s.id === skill.id; })) return;

                if (selectedSkills.length >= 5) {
                    alert('حداکثر ۵ مهارت قابل انتخاب است');
                    return;
                }

                selectedSkills.push({
                    id:          skill.id,
                    name:        skill.name,
                    subdomainId: subdomainID,
                    skillType:   skill.skill_type,
                    level:       'مبتدی',
                    years:       1,
                    cardRef:     card,
                });

                card.style.border    = '2.5px solid ' + theme.color;
                card.style.boxShadow = '0 0 0 3px ' + theme.ring;
                const iconDiv = card.querySelector('.skill-icon');
                if (iconDiv) { iconDiv.style.background = theme.color; iconDiv.style.color = '#fff'; }

                renderSelectedSkills();
                saveBtn.disabled = false;
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
            btn.innerHTML = item.name + ' &times;';
            btn.addEventListener('click', function () {
                removeSkillsBySubdomain(item.id);
                selectedSubdomains.splice(index, 1);
                renderSelectedSubdomains();
            });
            selectedSubdomainsContainer.appendChild(btn);
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

        emptyEl.classList.toggle('d-none', !anyCard || anyVisible);
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

    function clearSkillSelection() {
        selectedSkills = [];
        renderSelectedSkills();
        skillContainers.forEach(function (c) { c.innerHTML = ''; });
        refreshSkillFilters();
        saveBtn.disabled = true;
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
    }

    // ─── SELECTED SKILLS RENDERING ───────────────────────────────────────
    function renderSelectedSkills() {
        selectedSkillsContainer.innerHTML = '';

        selectedSkills.forEach(function (skill, index) {
            const theme = getSkillTypeTheme(skill.skillType);

            const col = document.createElement('div');
            col.className = 'col-6 col-sm-4 col-md-3 col-lg-3';

            const card = document.createElement('div');
            card.className = 'card mb-0 bp-selected-skill-card';
            card.style.border = '2px solid ' + theme.color;
            card.innerHTML =
                '<div class="card-body">' +
                    '<div class="d-flex align-items-center mb-2">' +
                        '<div style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:' + theme.color + ';color:#fff;margin-left:0.4rem;flex-shrink:0;">' +
                            '<i class="' + theme.icon + '" style="font-size:0.74rem;"></i>' +
                        '</div>' +
                        '<h6 class="mb-0">' + skill.name + '</h6>' +
                    '</div>' +
                    '<div class="d-flex" style="gap:6px;">' +
                        '<div style="flex:1;min-width:0;">' +
                            '<label class="form-label form-label-sm mb-1">سطح</label>' +
                            '<select class="form-select form-select-sm">' +
                                '<option value="مبتدی">مبتدی</option>' +
                                '<option value="متوسط">متوسط</option>' +
                                '<option value="حرفه ای">حرفه ای</option>' +
                            '</select>' +
                        '</div>' +
                        '<div style="flex:1;min-width:0;">' +
                            '<label class="form-label form-label-sm mb-1">سال‌های تجربه</label>' +
                            '<input type="number" class="form-control form-control-sm" value="' + skill.years + '" min="0">' +
                        '</div>' +
                    '</div>' +
                    '<button type="button" class="btn btn-soft-danger btn-sm w-100 mt-2">' +
                        '<i class="ri-delete-bin-line me-1"></i>حذف' +
                    '</button>' +
                '</div>';

            const sel = card.querySelector('select');
            sel.value = skill.level;
            sel.addEventListener('change', function () { skill.level = this.value; });

            card.querySelector('input').addEventListener('input', function () { skill.years = parseInt(this.value, 10) || 0; });

            card.querySelector('button').addEventListener('click', function () {
                if (skill.cardRef) {
                    const theme = getSkillTypeTheme(skill.skillType);
                    skill.cardRef.style.border    = '2.5px solid #ced4da';
                    skill.cardRef.style.boxShadow = '';
                    const iconDiv = skill.cardRef.querySelector('.skill-icon');
                    if (iconDiv) { iconDiv.style.background = theme.tint; iconDiv.style.color = theme.color; }
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
                alert(result.message);
                if (result.redirect) window.location.assign(result.redirect);
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
