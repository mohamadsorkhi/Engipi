@extends('layouts.master')

@section('title', 'ثبت پروژه جدید')

@section('content')
    <x-admin.breadcrumb
        title="ثبت پروژه جدید"
        parent="پروژه‌های من"
        parentUrl="{{ route('user.projects.index') }}"
    />

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-add-circle-line me-2 text-primary"></i>ثبت پروژه مهندسی جدید
                    </h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-1"></i>
                            لطفاً خطاهای زیر را برطرف کنید.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form id="projectForm" action="{{ route('employer.projects.store') }}" method="POST">
                        @csrf

                        {{-- ── Title ────────────────────────────────────── --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                عنوان پروژه <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="title" name="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}"
                                placeholder="مثال: طراحی سیستم کنترل PID در MATLAB"
                                maxlength="191" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ── Description ──────────────────────────────── --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                توضیحات فنی پروژه <span class="text-danger">*</span>
                            </label>
                            <textarea id="description" name="description" rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="شرح کامل پروژه، الزامات فنی، استانداردها و خروجی‌های مورد انتظار..."
                                required>{{ old('description') }}</textarea>
                            <div class="form-text">حداقل ۲۰ کاراکتر</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ── Engineering Domain ───────────────────────── --}}
                        <div class="mb-4">
                            <label for="domain_select" class="form-label fw-semibold">
                                حوزه تخصصی <span class="text-danger">*</span>
                            </label>
                            <select id="domain_select" name="domains[0]"
                                class="form-select @error('domains') is-invalid @enderror @error('domains.0') is-invalid @enderror"
                                required>
                                <option value="">انتخاب کنید...</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}"
                                        {{ old('domains.0') === $domain->id ? 'selected' : '' }}>
                                        {{ $domain->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('domains')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('domains.0')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ── Skills (filtered by domain) ──────────────── --}}
                        <div class="mb-4" id="skills-section" style="display: none;">
                            <label class="form-label fw-semibold">
                                مهارت‌های مورد نیاز
                                <span class="text-muted fw-normal fs-12">(اختیاری)</span>
                            </label>
                            <div class="border rounded p-3 bg-light">
                                <p class="text-muted mb-0 text-center small" id="skills-empty-msg" style="display: none;">
                                    مهارتی برای این حوزه تعریف نشده است.
                                </p>
                                <div id="skills-list" class="row g-2"></div>
                            </div>
                            @error('skills')
                                <div class="text-danger small mt-1">
                                    <i class="ri-error-warning-line me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- ── Budget ───────────────────────────────────── --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                بودجه پروژه
                                <span class="text-muted fw-normal fs-12">(اختیاری — تومان)</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="budget_min" class="form-label small text-muted">حداقل بودجه</label>
                                    <input type="number" id="budget_min" name="budget_min"
                                        class="form-control @error('budget_min') is-invalid @enderror"
                                        value="{{ old('budget_min') }}"
                                        min="0" placeholder="مثال: ۵۰۰۰۰۰۰">
                                    @error('budget_min')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="budget_max" class="form-label small text-muted">حداکثر بودجه</label>
                                    <input type="number" id="budget_max" name="budget_max"
                                        class="form-control @error('budget_max') is-invalid @enderror"
                                        value="{{ old('budget_max') }}"
                                        min="0" placeholder="مثال: ۱۵۰۰۰۰۰۰">
                                    @error('budget_max')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- ── Deadline ─────────────────────────────────── --}}
                        <div class="mb-4">
                            <label for="deadline_date" class="form-label fw-semibold">
                                مهلت تحویل پروژه
                                <span class="text-muted fw-normal fs-12">(اختیاری)</span>
                            </label>
                            <input type="date" id="deadline_date" name="deadline_date"
                                class="form-control @error('deadline_date') is-invalid @enderror"
                                value="{{ old('deadline_date') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('deadline_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('user.projects.index') }}" class="btn btn-light">
                                <i class="ri-arrow-go-back-line align-bottom me-1"></i>انصراف
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="ri-check-line align-bottom me-1"></i>ثبت پروژه
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Domain → Skills map injected as JSON for JS filtering --}}
    <script>
    const domainSkillsMap = @json(
        $domains->mapWithKeys(fn ($domain) => [
            $domain->id => $domain->subdomains
                ->flatMap(fn ($sub) => $sub->skills)
                ->map(fn ($skill) => ['id' => $skill->id, 'name' => $skill->name])
                ->values(),
        ])
    );
    const preSelectedSkills = @json(array_map('strval', (array) old('skills', [])));
    </script>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const domainSelect  = document.getElementById('domain_select');
    const skillsSection = document.getElementById('skills-section');
    const skillsList    = document.getElementById('skills-list');
    const emptyMsg      = document.getElementById('skills-empty-msg');
    const budgetMin     = document.getElementById('budget_min');
    const budgetMax     = document.getElementById('budget_max');

    function renderSkills(domainId) {
        const skills = domainSkillsMap[domainId] || [];
        skillsList.innerHTML = '';

        if (skills.length === 0) {
            skillsSection.style.display = 'block';
            emptyMsg.style.display = 'block';
            return;
        }

        emptyMsg.style.display = 'none';
        skillsSection.style.display = 'block';

        skills.forEach(function (skill) {
            const isChecked = preSelectedSkills.includes(skill.id);
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6';
            col.innerHTML =
                '<div class="form-check">' +
                    '<input class="form-check-input" type="checkbox"' +
                        ' id="skill_' + skill.id + '"' +
                        ' name="skills[]"' +
                        ' value="' + skill.id + '"' +
                        (isChecked ? ' checked' : '') + '>' +
                    '<label class="form-check-label" for="skill_' + skill.id + '">' +
                        skill.name +
                    '</label>' +
                '</div>';
            skillsList.appendChild(col);
        });
    }

    domainSelect.addEventListener('change', function () {
        if (this.value) {
            renderSkills(this.value);
        } else {
            skillsSection.style.display = 'none';
            skillsList.innerHTML = '';
            emptyMsg.style.display = 'none';
        }
    });

    // Restore skills panel on page reload after validation error
    if (domainSelect.value) {
        renderSkills(domainSelect.value);
    }

    // Client-side budget sanity check
    budgetMax.addEventListener('input', function () {
        const min = parseFloat(budgetMin.value) || 0;
        const max = parseFloat(this.value) || 0;
        if (max > 0 && max < min) {
            this.setCustomValidity('حداکثر بودجه باید بزرگتر از حداقل بودجه باشد');
        } else {
            this.setCustomValidity('');
        }
    });
});
</script>
@endsection
