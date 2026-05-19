@extends('layouts.master')

@section('title')
    انتخاب مهارت
@endsection

@section('content')

<div class="row">

    <div class="col-12">

        <div class="card">

            <div class="card-header">
                <h4 class="card-title">
                    انتخاب مهارت
                </h4>
            </div>

            <div class="card-body">

                {{-- DOMAIN --}}
                <div class="mb-3">

                    <label class="form-label">
                        حوزه
                    </label>

                  <select id="domain" class="form-select">
                 <option value="">انتخاب حوزه</option>

                  @foreach($domains as $item)
                <option value="{{ $item->id }}">
                  {{ $item->name }}
                 </option>
                 @endforeach
                 </select>

                </div>



                {{-- SUBDOMAIN --}}
                <div class="mb-3">

                    <label class="form-label">
                        زیرشاخه
                    </label>

                    <select
                        id="subdomain"
                        class="form-select"
                        disabled
                    >

                        <option value="">
                            اول حوزه را انتخاب کنید
                        </option>

                    </select>

                </div>



               {{-- SKILLS --}}
<div class="mb-4">

    <label class="form-label fw-bold">
        مهارت
    </label>

    <!-- کارت های مهارت -->
    <div
        id="skillsContainer"
        class="d-flex flex-wrap gap-2"
    >
    </div>

</div>

{{-- SELECTED SKILLS --}}
<div class="mb-4">

    <label class="form-label fw-bold">
        مهارت های انتخاب شده
    </label>

    <div
        id="selected-skills"
        class="d-flex flex-wrap gap-2"
    >
    </div>

</div>



                {{-- BUTTON --}}
               <button
    type="button"
    class="btn btn-primary"
    id="saveBtn"
    disabled
>
    ذخیره
    </button>
            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    const domain = document.getElementById('domain');
    const subdomain = document.getElementById('subdomain');
    const skillsContainer = document.getElementById('skillsContainer');
    const selectedSkillsContainer = document.getElementById('selected-skills');
    const saveBtn = document.getElementById('saveBtn');

    let selectedSkills = [];

    saveBtn.disabled = true;

    // DOMAIN => SUBDOMAIN

    domain.addEventListener('change', async function () {

        const domainId = this.value;

        subdomain.innerHTML =
        `<option value="">انتخاب زیرشاخه</option>`;

        skillsContainer.innerHTML = '';
        selectedSkillsContainer.innerHTML = '';
        selectedSkills = [];

        if(!domainId) return;

        const response =
        await fetch(`/api/subdomains/${domainId}`);

        const data = await response.json();

       console.log("API:", data);
        
        const subdomains = Array.isArray(data)
        ? data
        : data.data;

        subdomains.forEach(item => {

            subdomain.innerHTML += `
            <option value="${item.id}">
                ${item.name}
            </option>`;
        });

        subdomain.disabled = false;

    });

    // SUBDOMAIN => SKILLS

  subdomain.addEventListener('change', async function () {

    const subdomainID = this.value;

    console.log("SELECTED:", subdomainID);

    skillsContainer.innerHTML = '';
    selectedSkillsContainer.innerHTML = '';
    selectedSkills = [];

    if(!subdomainID) return;

    const response =
    await fetch(`/api/skills/${subdomainID}`);

    const skills =
    await response.json();

    console.log("SKILLS:", skills);

    skills.forEach(skill => {

        let button =
        document.createElement('button');

        button.type='button';

        button.className =
        'btn btn-outline-primary m-1';

        button.innerText =
        skill.name;
        button.addEventListener('click', () => {

    if(selectedSkills.includes(skill.id))
        return;

    selectedSkills.push(skill.id);

    saveBtn.disabled = false;

    let card=document.createElement('div');

    card.className =
    'border rounded p-2 mt-2 w-100';

    card.innerHTML=`

        <div class="fw-bold mb-2">
            ${skill.name}
        </div>

        <select class="form-select mb-2">

            <option value="beginner">
                مبتدی
            </option>

            <option value="medium">
                متوسط
            </option>

            <option value="advanced">
                حرفه‌ای
            </option>

        </select>

        <input
            type="number"
            class="form-control mb-2"
            placeholder="سال تجربه"
        >

        <button
            class="btn btn-danger btn-sm">
            حذف
        </button>

    `;

    card.querySelector('button')
    .addEventListener('click',()=>{

        selectedSkills=
        selectedSkills.filter(
            id=>id!==skill.id
        );

        card.remove();

        if(selectedSkills.length===0)
        {
            saveBtn.disabled=true;
        }

    });

    selectedSkillsContainer
    .appendChild(card);

});

        skillsContainer.appendChild(button);
    });

});

saveBtn.addEventListener('click', async () => {

  const skillIds = selectedSkills;

    const response = await fetch('/save-skills', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN':
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content
        },

        body: JSON.stringify({
            skills: skillIds
        })
    });

    const data = await response.json();

console.log(data);

}); // پایان saveBtn

}); // پایان DOMContentLoaded

</script>
@endpush