@props(['request'])

<div class="card request-card mb-3" data-status="{{ $request->status }}">
    <div class="card-body">
        <!-- Header: Worker Info & Project Details -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div class="d-flex align-items-center">
                <!-- Generated Avatar -->
                <div class="avatar-sm flex-shrink-0 me-3">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20 fw-bold">
                        {{ mb_substr($request->user->name, 0, 1) }}
                    </div>
                </div>
                <div>
                    <h5 class="fs-15 mb-1">{{ $request->user->name }}</h5>
                    <p class="text-muted mb-0">متخصص</p>
                </div>
            </div>

            <div class="text-md-end">
                <a href="{{ route('employer.projects.show', $request->project) }}" class="d-block fw-medium text-primary mb-1">
                    {{ $request->project->title }}
                </a>
                <div class="text-muted fs-12">
                    <i class="ri-calendar-event-line align-middle me-1"></i>
                    <span class="d-inline-block">{{ \Morilog\Jalali\Jalalian::fromFormat("Y-m-d H:i:s", $request->created_at)->ago() }}</span>
                </div>
            </div>
        </div>

        <!-- Message Section -->
        <div class="mb-4">
            @if($request->message)
                <div class="p-3 bg-light rounded border border-light">
                    <h6 class="fs-13 fw-semibold text-muted mb-2">پیام متخصص:</h6>
                    <p class="mb-0 text-dark" style="line-height: 1.6;">{{ $request->message }}</p>
                </div>
            @else
                <div class="p-3 bg-light rounded border border-light">
                    <p class="text-muted fst-italic mb-0">متخصص پیامی ارسال نکرده است.</p>
                </div>
            @endif
        </div>

        <!-- Footer: Status & Actions -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top border-light">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted fs-13">وضعیت درخواست:</span>
                <x-request-status-badge :status="$request->status" />
            </div>

            <div class="d-flex gap-2">
                @if($request->status === 'pending')
                    <form action="{{ route('employer.requests.accept', $request) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm ajax-submit">
                            <i class="ri-check-line align-bottom me-1"></i> پذیرش
                        </button>
                    </form>
                    <form action="{{ route('employer.requests.reject', $request) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm ajax-submit">
                            <i class="ri-close-line align-bottom me-1"></i> رد کردن
                        </button>
                    </form>
                @elseif($request->status === 'rejected' || $request->status === 'accepted')
                     <form action="{{ route('employer.requests.revert-reject', $request) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-soft-warning btn-sm ajax-submit">
                            <i class="ri-refresh-line align-bottom me-1"></i> بازبینی مجدد
                        </button>
                    </form>
                @endif
                {{-- Profile button commented out as requested --}}
            </div>
        </div>
    </div>
</div>
