@props(['type', 'domains'])
@php
 $processing = $type === \App\Models\SkillSuggestion::TYPE_PROCESSING;
 $modalId = 'skillSuggestionModal-'.$type;
 $label = $processing ? 'پردازشی' : 'میدانی';
@endphp
<div class="bp-suggest-skill mb-4">
 <span class="bp-suggest-skill__icon" aria-hidden="true"><i class="ri-lightbulb-flash-line"></i></span>
 <div><strong>پیشنهاد مهارت جدید</strong><span>مهارت موردنظر در فهرست نیست؟ آن را برای اضافه شدن به EngiPi پیشنهاد دهید.</span></div>
 <button type="button" class="btn {{ $processing ? 'btn-outline-primary' : 'btn-outline-success' }}" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">+ پیشنهاد مهارت جدید</button>
</div>
<div class="modal fade skill-suggestion-modal" id="{{ $modalId }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
<form class="skill-suggestion-form" action="{{ route('skill-suggestions.store') }}" method="POST">@csrf
<input type="hidden" name="skill_type" value="{{ $type }}">
<div class="modal-header"><div><h5>پیشنهاد مهارت {{ $label }} جدید</h5><p class="text-muted small mb-0">نوع مهارت براساس صفحه مبدأ ثبت می‌شود.</p></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
<div class="modal-body"><div class="alert d-none skill-suggestion-alert"></div>
<div class="mb-3"><label class="form-label">نام مهارت پیشنهادی *</label><input class="form-control" name="skill_name" maxlength="255" required><div class="invalid-feedback" data-error-for="skill_name"></div></div>
<div class="mb-3"><label class="form-label">حوزه / زیرشاخه مرتبط *</label><select class="form-select" name="subdomain_id" required><option value="">انتخاب کنید</option>@foreach($domains as $domain)<optgroup label="{{ $domain->name }}">@foreach($domain->subdomains as $subdomain)<option value="{{ $subdomain->id }}">{{ $subdomain->name }}</option>@endforeach</optgroup>@endforeach</select><div class="invalid-feedback" data-error-for="subdomain_id"></div></div>
<div><label class="form-label">توضیح کوتاه (اختیاری)</label><textarea class="form-control" name="description" rows="3" maxlength="1000"></textarea><div class="invalid-feedback" data-error-for="description"></div></div></div>
<div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button><button type="submit" class="btn {{ $processing ? 'btn-primary' : 'btn-success' }}"><span class="spinner-border spinner-border-sm d-none"></span> ارسال پیشنهاد</button></div>
</form></div></div></div>
@once
@push('styles')<style>
.bp-suggest-skill{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 20px;border:1px solid #ddd6fe;border-inline-start:4px solid #7c3aed;border-radius:var(--bp-r-lg);background:linear-gradient(135deg,#f5f3ff,#fff)}.bp-suggest-skill strong,.bp-suggest-skill span{display:block}.bp-suggest-skill>div{flex:1}.bp-suggest-skill>div span{color:var(--bp-muted);font-size:.84rem}.bp-suggest-skill__icon{width:40px;height:40px;display:grid!important;place-items:center;flex:0 0 40px;border-radius:50%;background:linear-gradient(145deg,#a78bfa,#7c3aed);color:#fff;font-size:1.1rem;box-shadow:0 7px 15px rgba(124,58,237,.2)}.skill-suggestion-modal .modal-content{border:0;border-radius:var(--bp-r-xl)}@media(max-width:576px){.bp-suggest-skill{align-items:stretch;flex-direction:column}.bp-suggest-skill__icon{align-self:flex-start}.bp-suggest-skill .btn{width:100%}}
</style>@endpush
@push('scripts')<script>
document.addEventListener('DOMContentLoaded',()=>document.querySelectorAll('.skill-suggestion-form').forEach(form=>form.addEventListener('submit',async e=>{e.preventDefault();const btn=form.querySelector('[type=submit]'),spin=btn.querySelector('.spinner-border'),alert=form.querySelector('.skill-suggestion-alert');form.querySelectorAll('.is-invalid').forEach(x=>x.classList.remove('is-invalid'));btn.disabled=true;spin.classList.remove('d-none');try{const res=await fetch(form.action,{method:'POST',headers:{Accept:'application/json','X-CSRF-TOKEN':form.querySelector('[name=_token]').value},body:new FormData(form)}),data=await res.json();if(res.ok){alert.className='alert alert-success skill-suggestion-alert';alert.textContent=data.message;const type=form.skill_type.value;form.reset();form.skill_type.value=type}else{Object.entries(data.errors||{}).forEach(([name,msg])=>{const field=form.querySelector('[name="'+name+'"]'),feedback=form.querySelector('[data-error-for="'+name+'"]');if(field)field.classList.add('is-invalid');if(feedback)feedback.textContent=msg[0]})}}catch(x){alert.className='alert alert-danger skill-suggestion-alert';alert.textContent='ارتباط با سرور برقرار نشد.'}finally{btn.disabled=false;spin.classList.add('d-none')}})));
</script>@endpush
@endonce
