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

گرایش های انتخاب شده (حداکثر ۲)

</label>

<div
id="selected-subdomains"
class="d-flex flex-wrap gap-2">

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

    <div
        id="skillsContainer"
        class="d-flex flex-wrap gap-2"
    >
    </div>

</div>



{{-- SELECTED SKILLS --}}
<div class="mb-4">

    <label class="form-label fw-bold">
        مهارت های انتخاب شده (حداکثر ۵)
    </label>

    <div
        id="selected-skills"
        class="row g-2"
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

document.addEventListener('DOMContentLoaded',function(){

const subdomain=
document.getElementById('subdomain');

const skillsContainer=
document.getElementById('skillsContainer');

const selectedSkillsContainer=
document.getElementById('selected-skills');

const selectedSubdomainsContainer=
document.getElementById('selected-subdomains');

const saveBtn=
document.getElementById('saveBtn');

const domainButtons=
document.querySelectorAll('.domain-card');


let selectedDomains=[];
let loadedSubdomains=[];
let selectedSubdomains=[];
let selectedSkills=[];

saveBtn.disabled=true;



domainButtons.forEach(btn=>{

btn.addEventListener(
'click',

async function(){

const domainId=
btn.dataset.id;


// حذف حوزه
if(
selectedDomains.includes(domainId)
){

selectedDomains=
selectedDomains.filter(
id=>id!=domainId
);

btn.classList.remove(
'btn-primary'
);

btn.classList.add(
'btn-outline-primary'
);

return;

}


// حداکثر دو حوزه
if(selectedDomains.length>=2){

alert(
'حداکثر دو حوزه'
);

return;

}


selectedDomains.push(
domainId
);


btn.classList.remove(
'btn-outline-primary'
);

btn.classList.add(
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
?data
:data.data;


subdomain.disabled=false;


subdomains.forEach(item=>{

if(
loadedSubdomains.some(
x=>x.id==item.id
)
){
return;
}

loadedSubdomains.push(
item
);

});


renderSubdomains();

});

});



function renderSubdomains(){

subdomain.innerHTML=
'<option value="">انتخاب زیررشته</option>';


loadedSubdomains.forEach(item=>{

const option=
new Option(
item.name,
item.id
);

subdomain.add(
option
);

});

}



function renderSelectedSubdomains(){

selectedSubdomainsContainer.innerHTML='';

selectedSubdomains.forEach(
(item,index)=>{

const btn=
document.createElement(
'button'
);

btn.type='button';

btn.className=
'btn btn-primary m-1';

btn.innerHTML=
`${item.name} ×`;


btn.addEventListener(
'click',

function(){

selectedSubdomains.splice(
index,
1
);

renderSelectedSubdomains();

});

selectedSubdomainsContainer
.appendChild(btn);

});

}




subdomain.addEventListener(
'change',

async function(){

const subdomainID=
this.value;


if(!subdomainID){
return;
}


// تکراری
if(
selectedSubdomains.some(
x=>x.id==subdomainID
)
){

alert(
'این گرایش قبلا انتخاب شده'
);

this.value='';

return;

}



// محدودیت
if(
selectedSubdomains.length>=2
){

alert(
'حداکثر دو گرایش قابل انتخاب است'
);

this.value='';

return;

}



const selectedItem=
loadedSubdomains.find(
x=>x.id==subdomainID
);


if(selectedItem){

selectedSubdomains.push(
selectedItem
);

renderSelectedSubdomains();

}



skillsContainer.innerHTML='';


const response=
await fetch(
`/api/skills/${subdomainID}`
);


const skills=
await response.json();



skills.forEach(skill=>{

const btn=
document.createElement(
'button'
);

btn.type='button';

btn.innerText=
skill.name;

btn.className=
'btn btn-outline-primary m-1';



btn.addEventListener(
'click',

function(){

if(
selectedSkills.some(
s=>s.id==skill.id
)
){
return;
}



if(
selectedSkills.length>=5
){

alert(
'حداکثر ۵ مهارت'
);

return;

}


selectedSkills.push({

id:skill.id,

name:skill.name,

level:'مبتدی',

years:1

});


btn.classList.remove(
'btn-outline-primary'
);

btn.classList.add(
'btn-primary'
);


renderSelectedSkills();

saveBtn.disabled=false;

});


skillsContainer.appendChild(
btn
);

});

});




function renderSelectedSkills(){

selectedSkillsContainer.innerHTML='';


selectedSkills.forEach(
(skill,index)=>{


const card=
document.createElement(
'div'
);

card.className=
'card p-2 m-1';


card.innerHTML=`

<div>
<b>${skill.name}</b>
</div>

<select class="form-select mt-2">

<option value="مبتدی">
مبتدی
</option>

<option value="متوسط">
متوسط
</option>

<option value="حرفه ای">
حرفه ای
</option>

</select>

<input
type="number"
class="form-control mt-2"
value="${skill.years}"
min="1">

<button
class="btn btn-danger mt-2">
حذف
</button>

`;


const select=
card.querySelector(
'select'
);

select.value=
skill.level;


select.addEventListener(
'change',

function(){

skill.level=
this.value;

}
);


const years=
card.querySelector(
'input'
);


years.addEventListener(
'input',

function(){

skill.years=
this.value;

}
);


card.querySelector(
'button'
)

.addEventListener(
'click',

function(){

selectedSkills.splice(
index,
1
);

renderSelectedSkills();

saveBtn.disabled=
selectedSkills.length===0;

});


selectedSkillsContainer.appendChild(
card
);

});

}




saveBtn.addEventListener(
'click',

async function(){

const dataToSave=
selectedSkills.map(
skill=>({

skill_id:
skill.id,

level:
skill.level,

years:
skill.years

}));


const response=
await fetch(

'/save-user-skills',

{

method:'POST',

headers:{

'Content-Type':
'application/json',

'X-CSRF-TOKEN':

document.querySelector(
'meta[name="csrf-token"]'
).content

},

body:JSON.stringify({

skills:
dataToSave

})

}

);


const result=
await response.json();

alert(
result.message
);

});

});

</script>