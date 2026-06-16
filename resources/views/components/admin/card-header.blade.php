@props([
    'title',
    'backUrl' => null,
    'addUrl' => null,
    'addText' => 'افزودن',
])

<div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="card-title mb-0">{{ $title }}</h4>
    <div>
        {{-- Render custom buttons/content passed into the slot --}}
        {{ $slot }}

        {{-- Render standard buttons from props --}}
        @if($addUrl)
            <a href="{{ $addUrl }}" class="btn btn-primary">
                <i class="ri-add-line align-bottom me-1"></i> {{ $addText }}
            </a>
        @endif
        @if($backUrl)
            <a href="{{ $backUrl }}" class="btn btn-secondary">
                <i class="ri-arrow-left-line align-bottom me-1"></i> بازگشت
            </a>
        @endif
    </div>
</div>
