@extends('layouts.master')

@section('title', 'پروژه‌های پیشنهادی')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">پروژه‌های پیشنهادی</h4>
                <div>
                    <a href="{{ route('user.skills.index') }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-settings-3-line me-1"></i> مدیریت مهارت‌ها
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($projects->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ri-search-line display-4 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted">پروژه‌ای متناسب با مهارت‌های شما یافت نشد</h5>
                        <p class="text-muted mb-4">
                            برای دریافت پیشنهاد پروژه، مهارت‌ها و حوزه تخصصی خود را تکمیل کنید.
                        </p>
                        <a href="{{ route('user.skills.index') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i> مدیریت مهارت‌ها
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @php
            $workTypes = [
                'remote'  => ['label' => 'دورکاری',  'class' => 'bg-success'],
                'onsite'  => ['label' => 'حضوری',    'class' => 'bg-primary'],
                'hybrid'  => ['label' => 'ترکیبی',   'class' => 'bg-info'],
            ];
            $statusLabels = [
                'pending'  => ['label' => 'در انتظار بررسی', 'class' => 'warning'],
                'accepted' => ['label' => 'پذیرفته شد',      'class' => 'success'],
                'rejected' => ['label' => 'رد شد',           'class' => 'danger'],
            ];
        @endphp

        <div class="row g-4" id="projects-container">
            @foreach($projects as $project)
                @php
                    $wt        = $workTypes[$project->work_type] ?? ['label' => '-', 'class' => 'bg-secondary'];
                    $status    = $requestedProjects[$project->id] ?? null;
                    $statusInfo = $status ? ($statusLabels[$status] ?? null) : null;
                    $budgetText = null;
                    if ($project->budget_min && $project->budget_max) {
                        $budgetText = number_format($project->budget_min) . ' - ' . number_format($project->budget_max) . ' تومان';
                    } elseif ($project->budget_min) {
                        $budgetText = 'از ' . number_format($project->budget_min) . ' تومان';
                    } elseif ($project->budget_max) {
                        $budgetText = 'تا ' . number_format($project->budget_max) . ' تومان';
                    }
                @endphp

                <div class="col-lg-6 col-xl-4">
                    <div class="card h-100 border shadow-sm">
                        <div class="card-body d-flex flex-column">

                            {{-- Header --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <a href="{{ route('user.matched-projects.show', $project) }}"
                                   class="fs-16 fw-semibold text-primary text-truncate me-2"
                                   style="max-width: 75%;">
                                    {{ $project->title }}
                                </a>
                                <span class="badge {{ $wt['class'] }} flex-shrink-0">{{ $wt['label'] }}</span>
                            </div>

                            {{-- Description --}}
                            <p class="text-muted mb-3 flex-grow-1" style="font-size: 0.875rem; line-height: 1.6;">
                                {{ Str::limit($project->description, 140) }}
                            </p>

                            {{-- Domains & Processes --}}
                            @if($project->domains->isNotEmpty() || $project->processes->isNotEmpty())
                                <div class="d-flex flex-wrap gap-1 mb-3">
                                    @foreach($project->domains->take(2) as $domain)
                                        <span class="badge bg-primary-subtle text-primary">{{ $domain->name }}</span>
                                    @endforeach
                                    @foreach($project->processes->take(3) as $process)
                                        <span class="badge bg-info-subtle text-info">{{ $process->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Budget & Deadline --}}
                            <div class="row g-2 mb-3">
                                @if($budgetText)
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="ri-money-dollar-circle-line me-1 fs-16 text-success"></i>
                                            <span>{{ $budgetText }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($project->deadline_date)
                                    <div class="{{ $budgetText ? 'col-6' : 'col-12' }}">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="ri-calendar-event-line me-1 fs-16 text-danger"></i>
                                            <span>مهلت: {{ \Morilog\Jalali\Jalalian::fromDateTime($project->deadline_date)->format('Y/m/d') }}</span>
                                        </div>
                                    </div>
                                @endif
                                @if($project->duration_days)
                                    <div class="col-6">
                                        <div class="d-flex align-items-center text-muted" style="font-size: 0.8rem;">
                                            <i class="ri-time-line me-1 fs-16 text-warning"></i>
                                            <span>{{ $project->duration_days }} روز</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Action --}}
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top">
                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>{{ $project->created_at }}
                                </small>
                                @if($statusInfo)
                                    <span class="badge bg-{{ $statusInfo['class'] }}-subtle text-{{ $statusInfo['class'] }} px-3 py-2">
                                        <i class="ri-checkbox-circle-line me-1"></i>{{ $statusInfo['label'] }}
                                    </span>
                                @else
                                    <button type="button"
                                            class="btn btn-success btn-sm send-request-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#requestModal"
                                            data-project-id="{{ $project->id }}"
                                            data-project-title="{{ $project->title }}">
                                        <i class="ri-send-plane-line me-1"></i> ارسال درخواست
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    @endif

    {{-- Request Modal --}}
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="requestForm" method="POST" action="{{ route('user.requests.store') }}">
                    @csrf
                    <input type="hidden" name="project_id" id="modal-project-id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel">ارسال درخواست همکاری</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-light border mb-3">
                            <i class="ri-folder-line me-1 text-primary"></i>
                            <strong id="modal-project-title" class="text-primary"></strong>
                        </div>

                        <div class="mb-3">
                            <label for="modal-message" class="form-label">
                                پیام برای کارفرما
                                <small class="text-muted">(اختیاری)</small>
                            </label>
                            <textarea class="form-control" id="modal-message" name="message"
                                      rows="4" maxlength="1000"
                                      placeholder="توضیح دهید چرا برای این پروژه مناسب هستید..."></textarea>
                            <div class="form-text">حداکثر ۱۰۰۰ کاراکتر</div>
                        </div>

                        <div id="request-alert" class="alert d-none" role="alert"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-success" id="submit-request-btn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="request-spinner"></span>
                            <i class="ri-send-plane-line me-1" id="submit-icon"></i>
                            ارسال درخواست
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const requestModal = document.getElementById('requestModal');

    requestModal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('modal-project-id').value = btn.dataset.projectId;
        document.getElementById('modal-project-title').textContent = btn.dataset.projectTitle;
        document.getElementById('modal-message').value = '';
        document.getElementById('request-alert').className = 'alert d-none';
    });

    document.getElementById('requestForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = this;
        const spinner = document.getElementById('request-spinner');
        const icon = document.getElementById('submit-icon');
        const btn = document.getElementById('submit-request-btn');
        const alert = document.getElementById('request-alert');

        spinner.classList.remove('d-none');
        icon.classList.add('d-none');
        btn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                project_id: document.getElementById('modal-project-id').value,
                message: document.getElementById('modal-message').value || null,
            }),
        })
        .then(r => r.json())
        .then(data => {
            alert.className = 'alert ' + (data.status === 'success' ? 'alert-success' : 'alert-danger');
            alert.textContent = data.message;

            if (data.status === 'success') {
                // Replace the button with a "pending" badge
                const projectId = document.getElementById('modal-project-id').value;
                const triggerBtn = document.querySelector(`.send-request-btn[data-project-id="${projectId}"]`);
                if (triggerBtn) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-warning-subtle text-warning px-3 py-2';
                    badge.innerHTML = '<i class="ri-checkbox-circle-line me-1"></i>در انتظار بررسی';
                    triggerBtn.replaceWith(badge);
                }
                setTimeout(() => bootstrap.Modal.getInstance(requestModal).hide(), 1500);
            }
        })
        .catch(() => {
            alert.className = 'alert alert-danger';
            alert.textContent = 'خطایی رخ داد. لطفا دوباره تلاش کنید.';
        })
        .finally(() => {
            spinner.classList.add('d-none');
            icon.classList.remove('d-none');
            btn.disabled = false;
        });
    });
</script>
@endpush
