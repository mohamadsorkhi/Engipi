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
                    <div class="ep-select" id="dd-wrap">
                        <div class="ep-select__trigger" id="dd-trigger">
                            <span id="dd-label">انتخاب حوزه</span>
                            <i class="ri-arrow-down-s-line ep-select__chevron"></i>
                        </div>
                        <ul class="ep-select__menu" id="dd-menu"></ul>
                    </div>
                </div>

                <div id="selected-domains" class="mb-2 d-flex flex-wrap"></div>

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
                <div class="mb-4 bp-selected-section">
                    <label class="form-label fw-bold">مهارت‌های انتخاب شده (حداکثر ۵)</label>
                    <div id="selected-skills"></div>
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
    margin-bottom: 1.1rem;
}
.bp-skillsec-label i { font-size: 1.3rem; }
.bp-skillsec--blue .bp-skillsec-label { color: var(--bp-blue); }
.bp-skillsec--teal .bp-skillsec-label { color: var(--bp-teal); }
.bp-skillsec--teal { padding-bottom: 2.5rem; }
.bp-skillsec-divider {
    height: 1px;
    background: var(--bp-hair);
    margin: 44px 0 40px;
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
.bp-card-years {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    font-size: .75rem;
    color: var(--bp-muted);
    direction: rtl;
    justify-content: center;
}
.bp-card-years input {
    width: 52px;
    border: 1px solid var(--bp-border);
    border-radius: var(--bp-r);
    padding: 3px 5px;
    font-size: .78rem;
    text-align: center;
    color: var(--bp-text);
    background: #fff;
}
.bp-card-years input:focus { outline: none; border-color: var(--bp-blue); }
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
    // ─── CUSTOM DOMAIN DROPDOWN ─────────────────────────────────────────
    const ddWrap    = document.getElementById('dd-wrap');
    const ddTrigger = document.getElementById('dd-trigger');
    const ddLabel   = document.getElementById('dd-label');
    const ddMenu    = document.getElementById('dd-menu');

    function ddClose()            { ddWrap.classList.remove('ep-select--open'); }
    function ddReset(placeholder) { ddMenu.innerHTML = ''; ddLabel.textContent = placeholder; }
    function ddPopulate(items) {
        ddMenu.innerHTML = '';
        items.forEach(function (item) {
            const li = document.createElement('li');
            li.className = 'ep-select__option';
            li.textContent = item.name;
            li.addEventListener('click', function () { onDomainPick(item.id, item.name); });
            ddMenu.appendChild(li);
        });
    }

    ddTrigger.addEventListener('click', function () { ddWrap.classList.toggle('ep-select--open'); });
    document.addEventListener('click', function (e) { if (!ddWrap.contains(e.target)) ddClose(); });

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
    const domainsData = @json($domains->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values());
    ddPopulate(domainsData);

    function deselectDomain(domainId) {
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
            card.className = 'mb-0 bp-skill-card' + (skill.skill_type === 'field' ? ' bp-skill-card--teal' : '');

            // ── face (always visible) ──────────────────────────────────
            const face = document.createElement('div');
            face.className = 'bp-skill-card__face';
            face.innerHTML =
                '<span class="bp-skill-check" style="position:absolute;top:6px;left:6px;width:20px;height:20px;border-radius:50%;background:#2563eb;display:none;align-items:center;justify-content:center;z-index:2;">' +
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

            const levelSel = document.createElement('select');
            levelSel.className = 'bp-card-level-sel';
            ['مبتدی', 'متوسط', 'حرفه‌ای'].forEach(function (lvl) {
                const opt = document.createElement('option');
                opt.value = lvl;
                opt.textContent = lvl;
                levelSel.appendChild(opt);
            });

            const yearsWrap = document.createElement('div');
            yearsWrap.className = 'bp-card-years';
            const yearsInput = document.createElement('input');
            yearsInput.type = 'number';
            yearsInput.min  = '0';
            yearsInput.value = '1';
            yearsWrap.innerHTML = '<span>سال تجربه:</span>';
            yearsWrap.appendChild(yearsInput);

            const addBtn = document.createElement('button');
            addBtn.type = 'button';
            addBtn.className = 'bp-card-add-btn';
            addBtn.textContent = 'افزودن به مهارت‌ها';
            addBtn.style.background = theme.color;
            addBtn.addEventListener('click', function () {
                const level = levelSel.value;
                const years = parseInt(yearsInput.value, 10) || 0;

                selectedSkills.push({
                    id:          skill.id,
                    name:        skill.name,
                    subdomainId: subdomainID,
                    skillType:   skill.skill_type,
                    level:       level,
                    years:       years,
                    cardRef:     card,
                });

                card.classList.remove('bp-skill-card--expanded');
                card.classList.add('bp-skill-card--added');
                card.style.border    = '2.5px solid ' + theme.color;
                card.style.boxShadow = '0 0 0 3px ' + theme.ring;
                const iconDiv = card.querySelector('.skill-icon');
                if (iconDiv) { iconDiv.style.background = theme.color; iconDiv.style.color = '#fff'; }
                const checkEl = card.querySelector('.bp-skill-check');
                if (checkEl) { checkEl.style.display = 'flex'; }

                renderSelectedSkills();
                saveBtn.disabled = false;
            });

            controls.appendChild(levelSel);
            controls.appendChild(yearsWrap);
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

    // ─── SELECTED DOMAINS ───────────────────────────────────────────────────
    const selectedDomainsContainer = document.getElementById('selected-domains');
    function renderSelectedDomains() {
        selectedDomainsContainer.innerHTML = '';
        selectedDomains.forEach(function (domainId) {
            const domainName = (domainsData.find(function (d) { return d.id === domainId; }) || {}).name || domainId;
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'btn btn-primary m-1';
            chip.innerHTML = domainName + ' &times;';
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
        if (skill.skill_type === 'software') return 'ri-code-s-slash-line';
        for (var i = 0; i < SKILL_ICON_MAP.length; i++) {
            var kws = SKILL_ICON_MAP[i][0];
            for (var j = 0; j < kws.length; j++) {
                if (skill.name && skill.name.indexOf(kws[j]) !== -1) return SKILL_ICON_MAP[i][1];
            }
        }
        return 'ri-briefcase-line';
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

            const card = document.createElement('div');
            card.className = 'bp-sid-card';
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

            const levelSpan = document.createElement('span');
            levelSpan.className = 'bp-sid-card__level';
            levelSpan.style.background = theme.tint;
            levelSpan.style.color      = theme.color;
            levelSpan.textContent = skill.level;

            const yearsSpan = document.createElement('span');
            yearsSpan.className = 'bp-sid-card__years';
            yearsSpan.textContent = skill.years + ' سال';

            metaEl.appendChild(levelSpan);
            metaEl.appendChild(yearsSpan);
            body.appendChild(iconEl);
            body.appendChild(nameEl);
            body.appendChild(metaEl);

            // ── delete button ───────────────────────────────────────────
            const delBtn = document.createElement('button');
            delBtn.type = 'button';
            delBtn.className = 'bp-sid-card__del';
            delBtn.textContent = 'حذف مهارت';
            delBtn.addEventListener('click', function () {
                if (skill.cardRef) {
                    const t = getSkillTypeTheme(skill.skillType);
                    skill.cardRef.classList.remove('bp-skill-card--added', 'bp-skill-card--expanded');
                    skill.cardRef.style.border    = '';
                    skill.cardRef.style.boxShadow = '';
                    const iconDiv = skill.cardRef.querySelector('.skill-icon');
                    if (iconDiv) { iconDiv.style.background = t.tint; iconDiv.style.color = t.color; }
                    const checkEl = skill.cardRef.querySelector('.bp-skill-check');
                    if (checkEl) { checkEl.style.display = 'none'; }
                }
                selectedSkills.splice(index, 1);
                renderSelectedSkills();
                saveBtn.disabled = selectedSkills.length === 0;
            });

            card.appendChild(body);
            card.appendChild(delBtn);
            selectedSkillsContainer.appendChild(card);
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
