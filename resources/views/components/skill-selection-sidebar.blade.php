@props(['type'])
@php
    $processing = $type === 'software';
    $listId = 'selectedSidebar'.ucfirst($type);
@endphp
<aside class="bp-skill-sidebar-stack" aria-label="راهنما و مهارت‌های انتخاب‌شده">
    <section class="bp-sidebar-card bp-sidebar-card--guide">
        <header><span class="bp-sidebar-card__icon"><i class="ri-lightbulb-line" aria-hidden="true"></i></span><strong>راهنما</strong></header>
        <ul>
            <li>مهارت‌هایی را انتخاب کنید که واقعاً در آن‌ها تجربه دارید.</li>
            <li>حداکثر ۵ مهارت قابل انتخاب است.</li>
            <li>در مرحله بعد، سطح مهارت و سابقه را مشخص می‌کنید.</li>
        </ul>
    </section>
    <section class="bp-sidebar-card bp-sidebar-card--selected">
        <header><span class="bp-sidebar-card__icon"><i class="{{ $processing ? 'ri-code-box-line' : 'ri-tools-line' }}" aria-hidden="true"></i></span><strong>مهارت‌های انتخاب‌شده</strong></header>
        <div class="bp-skill-sidebar__list" id="{{ $listId }}"></div>
        <footer>
            <span><b data-sidebar-count>۰</b> از ۵ مهارت انتخاب شده</span>
            <div class="bp-skill-sidebar__progress"><span data-sidebar-progress></span></div>
        </footer>
    </section>
    <section class="bp-sidebar-card bp-sidebar-card--tip">
        <header><span class="bp-sidebar-card__icon"><i class="ri-star-line" aria-hidden="true"></i></span><strong>نکته</strong></header>
        <p>انتخاب دقیق مهارت‌ها باعث نمایش پروژه‌های مرتبط‌تر می‌شود.</p>
    </section>
</aside>
