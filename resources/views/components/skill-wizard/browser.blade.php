@props(['type', 'domains'])
@php
    $field = $type === 'field';
    $suffix = $field ? 'Field' : 'Software';
    $theme = $field ? 'teal' : 'blue';
    $icon = $field ? 'ri-hammer-line' : 'ri-code-s-slash-line';
    $title = $field ? 'مهارت‌های میدانی' : 'مهارت‌های پردازشی / نرم‌افزاری';
    $subtitle = $field ? 'توانمندی‌های اجرایی و کارگاهی' : 'ابزارها و فناوری‌های تخصصی';
    $searchLabel = $field ? 'مهارت‌های میدانی' : 'مهارت‌های پردازشی';
@endphp
<div class="bp-skill-layout">
    <div class="bp-skill-layout__main">
        <section class="mb-4 bp-skillsec bp-skillsec--{{ $theme }}" aria-labelledby="skillGroup{{ $suffix }}">
            <div class="bp-skill-browser__head">
                <div class="bp-category-heading bp-category-heading--{{ $field ? 'field' : 'software' }}" id="skillGroup{{ $suffix }}">
                    <span class="bp-category-heading__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
                    <span class="bp-category-heading__copy"><strong>{{ $title }}</strong><small>{{ $subtitle }}</small></span>
                </div>
                <span class="bp-skill-browser__limit">حداکثر ۵ مهارت</span>
            </div>
            <div class="bp-skill-toolbar">
                <div class="bp-skillsec-search">
                    <i class="ri-search-line" aria-hidden="true"></i>
                    <input type="search" id="skillSearch{{ $suffix }}" placeholder="جست‌وجوی مهارت..." aria-label="جست‌وجوی {{ $searchLabel }}">
                    <button type="button" class="bp-search-clear" data-clear-search="skillSearch{{ $suffix }}" aria-label="پاک کردن جست‌وجو" hidden><i class="ri-close-line"></i></button>
                </div>
                <select class="bp-category-filter" id="skillCategory{{ $suffix }}" aria-label="فیلتر گرایش {{ $searchLabel }}"><option value="">همه دسته‌بندی‌ها</option></select>
                <div class="bp-view-toggle" role="group" aria-label="نوع نمایش">
                    <button type="button" class="is-active" data-skill-view="grid" data-skill-target="{{ $type }}" aria-label="نمای شبکه‌ای" aria-pressed="true"><i class="ri-grid-fill"></i></button>
                    <button type="button" data-skill-view="list" data-skill-target="{{ $type }}" aria-label="نمای فهرستی" aria-pressed="false"><i class="ri-list-check-2"></i></button>
                </div>
            </div>
            <div id="skillsContainer{{ $suffix }}" class="row g-3 bp-{{ $field ? 'field' : 'software' }}-grid"></div>
            <div class="bp-skillsec-empty d-none" id="skillsEmpty{{ $suffix }}" role="status">
                <i class="{{ $field ? 'ri-tools-line' : 'ri-code-box-line' }}" aria-hidden="true"></i>
                <strong>برای گرایش‌های انتخاب‌شده مهارتی ثبت نشده است.</strong>
                <span>{{ $field ? 'اگر مهارت پردازشی انتخاب کرده‌اید، می‌توانید ادامه دهید.' : 'انتخاب مهارت پردازشی اختیاری است و می‌توانید ادامه دهید.' }}</span>
            </div>
        </section>
        <x-skill-suggestion :type="$type" :domains="$domains" />
        <div class="bp-next-preview"><i class="ri-arrow-left-circle-line"></i><div><strong>مرحله بعد</strong><span>سطح مهارت و سال‌های تجربهٔ هر انتخاب را مشخص خواهید کرد.</span></div></div>
    </div>
    <x-skill-selection-sidebar :type="$type" />
</div>
