@extends('layouts.master')

@section('title', 'مدیریت زیرحوزه‌ها')

@section('content')
    <x-admin.breadcrumb
        title="زیرحوزه‌ها"
        parent="حوزه‌های تخصصی"
        parentUrl="{{ route('admin.domains.index') }}"
    />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="card-title mb-0">لیست زیرحوزه‌ها</h5>
                        @if($selectedDomain)
                            <span class="badge bg-primary">
                                {{ $selectedDomain->name }}
                                <a href="{{ route('admin.subdomains.index') }}"
                                   class="text-white ms-1" title="حذف فیلتر">×</a>
                            </span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        {{-- Domain filter --}}
                        <select id="domain-filter" class="form-select form-select-sm" style="min-width:180px;">
                            <option value="">همه حوزه‌ها</option>
                            @foreach($domains as $domain)
                                <option value="{{ $domain->id }}"
                                    {{ optional($selectedDomain)->id === $domain->id ? 'selected' : '' }}>
                                    {{ $domain->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#addSubdomainModal">
                            <i class="ri-add-line align-bottom me-1"></i> افزودن زیرحوزه
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="table-container" class="ajax-table">
                        @include('admin.subdomains.components.table_and_pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ Add Modal ═══ --}}
    <div class="modal fade" id="addSubdomainModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن زیرحوزه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.subdomains.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">حوزه <span class="text-danger">*</span></label>
                            <select name="skill_domain_id" class="form-select" required id="add-domain-select">
                                <option value="">انتخاب کنید</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}"
                                        {{ optional($selectedDomain)->id === $domain->id ? 'selected' : '' }}>
                                        {{ $domain->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نام زیرحوزه <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required maxlength="255">
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary ajax-submit">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ Edit Modal ═══ --}}
    <div class="modal fade" id="editSubdomainModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش زیرحوزه</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSubdomainForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">حوزه <span class="text-danger">*</span></label>
                            <select name="skill_domain_id" class="form-select" required id="edit-domain-select">
                                <option value="">انتخاب کنید</option>
                                @foreach($domains as $domain)
                                    <option value="{{ $domain->id }}">{{ $domain->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نام زیرحوزه <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   id="edit-subdomain-name" required maxlength="255">
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary ajax-submit">ذخیره</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
// Domain filter → reload page with query string
document.getElementById('domain-filter').addEventListener('change', function () {
    const domainId = this.value;
    const url = new URL(window.location.href);
    if (domainId) {
        url.searchParams.set('domain_id', domainId);
    } else {
        url.searchParams.delete('domain_id');
    }
    window.location.href = url.toString();
});

// Populate edit modal
$(document).on('click', '.edit-subdomain-btn', function () {
    const $btn = $(this);
    $('#editSubdomainForm').attr('action', $btn.data('update-url'));
    $('#edit-subdomain-name').val($btn.data('name'));
    $('#edit-domain-select').val($btn.data('domain-id'));
    // Clear previous errors
    $('#editSubdomainForm .is-invalid').removeClass('is-invalid');
    $('#editSubdomainForm .invalid-feedback').hide().find('span').text('');
});
</script>
@endpush
