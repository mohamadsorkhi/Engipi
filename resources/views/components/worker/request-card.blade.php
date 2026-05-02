@props(['request'])

<div class="card mb-3">
    <div class="card-body">
        <!-- Header: Project Info & Status -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-3 gap-3">
            <div>
                <a href="{{ route('specialist.matched-projects.show', $request->project) }}" class="d-block fs-16 fw-medium text-primary mb-1">
                    {{ $request->project->title }}
                </a>
                <div class="d-flex align-items-center text-muted">
                    <span class="me-3"><i class="ri-user-3-line align-middle me-1"></i>{{ $request->project->employer->name }}</span>
                    <span><i class="ri-calendar-event-line align-middle me-1"></i><span class="d-inline-block">{{ \Morilog\Jalali\Jalalian::fromFormat("Y-m-d H:i:s", $request->created_at)->ago() }}</span></span>
                </div>
            </div>
            <div class="d-flex flex-column align-items-md-end">
                 <span class="text-muted fs-13 mb-2">وضعیت درخواست:</span>
                <x-request-status-badge :status="$request->status" />
            </div>
        </div>

        <!-- Message Section -->
        <div class="mb-0">
            @if($request->message)
                <div class="p-3 bg-light rounded border border-light">
                    <h6 class="fs-13 fw-semibold text-muted mb-2">پیام شما:</h6>
                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $request->message }}</p>
                </div>
            @else
                <div class="p-3 bg-light rounded border border-light">
                    <p class="text-muted fst-italic mb-0">شما پیامی ارسال نکرده‌اید.</p>
                </div>
            @endif
        </div>
    </div>
</div>
