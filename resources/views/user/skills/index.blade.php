@extends('layouts.master')

@section('title', 'مهارت‌های من')

@section('content')

<div class="row">
<div class="col-12">
<div class="card">

    <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title mb-0">مهارت‌های من</h5>
        <a href="{{ route('skill.select') }}" class="btn btn-primary btn-sm">
            <i class="ri-add-line me-1"></i>افزودن / ویرایش مهارت‌ها
        </a>
    </div>

    <div class="card-body">

        {{-- DOMAINS --}}
        @if($selectedDomains->isNotEmpty())
        <div class="mb-4">
            <p class="text-muted small fw-semibold mb-2">
                <i class="ri-stack-line me-1"></i>حوزه‌های تخصصی شما:
            </p>
            <div class="d-flex flex-wrap gap-2">
                @foreach($selectedDomains as $domain)
                    <span class="badge bg-primary-subtle text-primary fs-12 px-3 py-2">
                        {{ $domain->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- SKILLS --}}
        @if($skills->isEmpty())

            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle" style="font-size:2rem;">
                        <i class="ri-award-line"></i>
                    </span>
                </div>
                <h5 class="mb-2">هنوز مهارتی ثبت نکرده‌اید</h5>
                <p class="text-muted mb-4">مهارت‌های خود را وارد کنید تا با پروژه‌های مناسب match شوید.</p>
                <a href="{{ route('skill.select') }}" class="btn btn-primary">
                    <i class="ri-add-line me-1"></i>افزودن مهارت
                </a>
            </div>

        @else

            <p class="text-muted small mb-3">
                سطح و سال‌های تجربه را ویرایش کنید، سپس «ذخیره تغییرات» را بزنید.
            </p>

            <div class="row g-3" id="skillsGrid">
                @foreach($skills as $skill)
                <div class="col-12 col-md-6 col-lg-4" data-skill-id="{{ $skill->id }}">
                    <div class="card border h-100 mb-0">
                        <div class="card-body p-3">

                            <div class="d-flex align-items-start mb-3">
                                <div class="avatar-sm flex-shrink-0 me-2">
                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-5">
                                        <i class="ri-code-s-slash-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $skill->name }}</h6>
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-icon btn-soft-danger delete-skill-btn flex-shrink-0"
                                    title="حذف"
                                >
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </div>

                            <div class="mb-2">
                                <label class="form-label form-label-sm mb-1">سطح</label>
                                <select class="form-select form-select-sm skill-level">
                                    <option value="مبتدی"    {{ $skill->pivot->level === 'مبتدی'    ? 'selected' : '' }}>مبتدی</option>
                                    <option value="متوسط"    {{ $skill->pivot->level === 'متوسط'    ? 'selected' : '' }}>متوسط</option>
                                    <option value="حرفه ای"  {{ $skill->pivot->level === 'حرفه ای'  ? 'selected' : '' }}>حرفه ای</option>
                                </select>
                            </div>

                            <div>
                                <label class="form-label form-label-sm mb-1">سال‌های تجربه</label>
                                <input
                                    type="number"
                                    class="form-control form-control-sm skill-years"
                                    value="{{ $skill->pivot->years_of_experience }}"
                                    min="0"
                                    max="50"
                                >
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('skill.select') }}" class="btn btn-outline-primary btn-sm">
                    <i class="ri-add-line me-1"></i>افزودن گروه جدید مهارت
                </a>
                <button type="button" class="btn btn-primary" id="saveBtn">
                    <span class="spinner-border spinner-border-sm me-1" role="status" style="display:none;"></span>
                    ذخیره تغییرات
                </button>
            </div>

        @endif

    </div>
</div>
</div>
</div>

@endsection

@push('scripts')
@if($skills->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', function () {

    const saveBtn   = document.getElementById('saveBtn');
    const spinner   = saveBtn.querySelector('.spinner-border');
    const grid      = document.getElementById('skillsGrid');

    // ─── DELETE ─────────────────────────────────────────────────────────
    grid.addEventListener('click', function (e) {
        const btn = e.target.closest('.delete-skill-btn');
        if (!btn) return;

        const card = btn.closest('[data-skill-id]');
        if (!card) return;

        if (!confirm('این مهارت حذف شود؟')) return;

        card.remove();

        if (!grid.querySelector('[data-skill-id]')) {
            location.reload();
        }
    });

    // ─── SAVE ────────────────────────────────────────────────────────────
    saveBtn.addEventListener('click', async function () {
        const cards = grid.querySelectorAll('[data-skill-id]');

        if (cards.length === 0) {
            alert('هیچ مهارتی برای ذخیره وجود ندارد.');
            return;
        }

        const skills = [];
        let hasError = false;

        cards.forEach(card => {
            const skillId = card.dataset.skillId;
            const level   = card.querySelector('.skill-level').value;
            const years   = parseInt(card.querySelector('.skill-years').value, 10);

            if (!level || isNaN(years) || years < 0) {
                hasError = true;
            }

            skills.push({ skill_id: skillId, level, years });
        });

        if (hasError) {
            alert('لطفاً همه فیلدها را به درستی پر کنید.');
            return;
        }

        saveBtn.disabled = true;
        spinner.style.display = 'inline-block';

        try {
            const res = await fetch('{{ route("user.skills.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ skills }),
            });

            const data = await res.json();

            if (res.ok && data.status === 'success') {
                alert(data.message);
            } else {
                const msg = data.errors
                    ? Object.values(data.errors).flat().join('\n')
                    : data.message || 'خطا در ذخیره';
                alert(msg);
            }
        } catch {
            alert('خطا در ارتباط با سرور');
        } finally {
            saveBtn.disabled = false;
            spinner.style.display = 'none';
        }
    });

});
</script>
@endif
@endpush
