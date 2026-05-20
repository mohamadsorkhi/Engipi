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

                 <div class="mb-3">

<label class="form-label">
    حوزه (حداکثر ۲)
</label>

<div id="domainContainer"
class="d-flex flex-wrap gap-2">

@foreach($domains as $item)

<button
type="button"
class="btn btn-outline-primary domain-card"
data-id="{{ $item->id }}">

{{ $item->name }}

</button>

@endforeach

</div>

</div>

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


{{-- SELECTED SUBDOMAINS --}}
<div class="mb-4">

    <label class="form-label fw-bold">
        گرایش های انتخاب شده
        (حداکثر ۲)
    </label>

    <div
        id="selectedSubdomains"
        class="d-flex flex-wrap gap-2"
    >

    </div>

</div>



{{-- SKILLS --}}
<div class="mb-4">

    <label class="form-label fw-bold">
        مهارت
    </label>

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

    const subdomain =
    document.getElementById('subdomain');

    const skillsContainer =
    document.getElementById('skillsContainer');

    const selectedSkillsContainer =
    document.getElementById('selected-skills');

    const selectedSubdomainsContainer =
    document.getElementById('selectedSubdomains');

    const saveBtn =
    document.getElementById('saveBtn');

    const domainButtons =
    document.querySelectorAll('.domain-card');

    let selectedDomains=[];
    let selectedSkills=[];
    let selectedSubdomains=[];

    saveBtn.disabled=true;


    // انتخاب حوزه
    domainButtons.forEach(button=>{

        button.addEventListener(
        'click',

        async function(){

            const domainId=
            button.dataset.id;


            // حذف انتخاب
            if(selectedDomains.includes(domainId)){

                selectedDomains=
                selectedDomains.filter(
                id=>id!=domainId
                );

                button.classList.remove(
                'btn-primary'
                );

                button.classList.add(
                'btn-outline-primary'
                );


                if(selectedDomains.length===0){

                    subdomain.innerHTML=
                    '<option value="">اول حوزه را انتخاب کنید</option>';

                    subdomain.disabled=true;

                    skillsContainer.innerHTML='';

                    selectedSkillsContainer.innerHTML='';

                    selectedSubdomainsContainer.innerHTML='';

                    selectedSubdomains=[];

                }

                return;
            }


            // محدودیت دو حوزه
            if(selectedDomains.length>=2){

                alert(
                'حداکثر ۲ حوزه'
                );

                return;
            }


            selectedDomains.push(
            domainId
            );


            button.classList.remove(
            'btn-outline-primary'
            );

            button.classList.add(
            'btn-primary'
            );


            const response=
            await fetch(
            `/api/subdomains/${domainId}`
            );


            const data=
            await response.json();


            const subdomains=
            Array.isArray(data)
            ? data
            : data.data;


            if(selectedDomains.length===1){

                subdomain.innerHTML=
                '<option value="">انتخاب زیررشته</option>';

            }


            subdomains.forEach(item=>{

                const exists=

                [...subdomain.options]

                .some(

                opt=>
                opt.value==item.id

                );


                if(!exists){

                    const option=

                    new Option(
                    item.name,
                    item.id
                    );

                    subdomain.add(
                    option
                    );

                }

            });


            subdomain.disabled=false;

        });

    });



    // انتخاب گرایش
    subdomain.addEventListener(
    'change',

    async function(){

        const subdomainID=
        this.value;


        if(!subdomainID)
        return;


        // حداکثر دو گرایش
        if(
        selectedSubdomains.length>=2
        ){

            alert(
            'حداکثر ۲ گرایش'
            );

            this.value='';

            return;
        }


        // تکراری نشود
        if(
        selectedSubdomains.includes(
        subdomainID
        )
        ){

            this.value='';

            return;
        }



        selectedSubdomains.push(
        subdomainID
        );


        const subdomainName=

        this.options[
        this.selectedIndex
        ].text;



        let tag=

        document.createElement(
        'div'
        );


        tag.className=
        'badge bg-primary p-2';



        tag.innerHTML=`

        ${subdomainName}

        <button
        class="btn-close btn-close-white ms-2 removeSubdomain">
        </button>

        `;



        tag.querySelector(
        '.removeSubdomain'
        )

        .addEventListener(
        'click',

        ()=>{

            selectedSubdomains=

            selectedSubdomains.filter(

            id=>
            id!=subdomainID

            );


            tag.remove();

        });



        selectedSubdomainsContainer
        .appendChild(tag);



        // مهارت ها
        skillsContainer.innerHTML='';

        selectedSkillsContainer.innerHTML='';

        selectedSkills=[];



        const response=

        await fetch(
        `/api/skills/${subdomainID}`
        );


        const skills=
        await response.json();



        skills.forEach(skill=>{

            let btn=

            document.createElement(
            'button'
            );


            btn.type='button';

            btn.className=
            'btn btn-outline-primary m-1';

            btn.innerText=
            skill.name;



            btn.addEventListener(
            'click',

            function(){

                if(
                selectedSkills.includes(
                skill.id
                )
                ){

                    return;
                }


                selectedSkills.push(
                skill.id
                );


                saveBtn.disabled=
                false;



                let card=

                document.createElement(
                'div'
                );


                card.className=
                'border rounded p-2 mt-2';



                card.innerHTML=`

                <div>
                ${skill.name}
                </div>

                <button
                class="btn btn-danger btn-sm removeSkill">

                حذف

                </button>

                `;



                card.querySelector(
                '.removeSkill'
                )

                .addEventListener(
                'click',

                ()=>{

                    selectedSkills=
                    selectedSkills.filter(

                    id=>
                    id!==skill.id

                    );


                    card.remove();


                    if(
                    selectedSkills.length===0
                    ){

                        saveBtn.disabled=true;

                    }

                });



                selectedSkillsContainer
                .appendChild(
                card
                );

            });



            skillsContainer
            .appendChild(
            btn
            );

        });

    });

});
</script>

@endpush