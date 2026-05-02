@extends('layouts.master')

@section('title', 'مدیریت درخواست‌ها')

@section('content')
    <x-admin.breadcrumb title="درخواست‌های همکاری" parent="داشبورد" parentUrl="{{ route('root') }}" />

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" id="request-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-filter="all" role="tab" href="#">
                        همه درخواست‌ها
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-filter="pending" role="tab" href="#">
                        <i class="ri-time-line me-1 align-bottom"></i> در انتظار بررسی
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-filter="accepted" role="tab" href="#">
                        <i class="ri-checkbox-circle-line me-1 align-bottom"></i> پذیرفته شده
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" data-filter="rejected" role="tab" href="#">
                        <i class="ri-close-circle-line me-1 align-bottom"></i> رد شده
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div id="requests-container" class="ajax-table">
                @include('employer.requests._list', ['requests' => $requests])
            </div>
            <div id="empty-state-message" class="alert alert-info text-center" style="display: none;">
                در این بخش درخواستی وجود ندارد.
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const container = $('#requests-container');
    const tabs = $('#request-tabs a.nav-link');
    const emptyStateMessage = $('#empty-state-message');
    let currentFilter = 'all';

    function applyFilter() {
        let hasVisibleItems = false;
        container.find('.request-card').each(function() {
            const status = $(this).data('status');
            if (currentFilter === 'all' || status === currentFilter) {
                $(this).show();
                hasVisibleItems = true;
            } else {
                $(this).hide();
            }
        });

        // Show/hide pagination only for 'all' tab
        container.find('.pagination').toggle(currentFilter === 'all');

        // Show/hide empty state message
        emptyStateMessage.toggle(!hasVisibleItems);
    }

    // Tab click handler
    tabs.on('click', function(e) {
        e.preventDefault();
        tabs.removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        applyFilter();
    });

    // --- AJAX Success Handler ---
    // Use a MutationObserver to detect when the content of the container changes.
    // This is more robust than overriding AJAX success handlers.
    const observer = new MutationObserver(function(mutations) {
        // When the list is re-rendered by an AJAX call, re-apply the current filter.
        applyFilter();
    });

    // Start observing the container for changes in its child list.
    observer.observe(container[0], {
        childList: true,
        subtree: true // Observe the container and its descendants
    });

    // Initial filter application on page load
    applyFilter();
});
</script>
@endpush
