@extends('layouts.master')

@section('title', 'مدیریت حوزه‌های تخصصی')

@section('content')
    <x-admin.breadcrumb title="حوزه‌های تخصصی" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">لیست حوزه‌های تخصصی</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDomainModal">
                        <i class="ri-add-line align-bottom me-1"></i> افزودن حوزه
                    </button>
                </div>
                <div class="card-body">
                    <div id="table-container" class="ajax-table">
                        @include('admin.domains.components.table_and_pagination')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Domain Modal -->
    <div class="modal fade" id="addDomainModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">افزودن حوزه تخصصی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.domains.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">نام حوزه <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
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

    <!-- Edit Domain Modal (Shared) -->
    <div class="modal fade" id="editDomainModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ویرایش حوزه تخصصی</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDomainForm" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_domain_name" class="form-label">نام حوزه <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_domain_name" name="name" required>
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
// Populate edit modal when edit button is clicked
$(document).on('click', '.edit-domain-btn', function() {
    const $btn = $(this);
    const domainId = $btn.data('domain-id');
    const domainName = $btn.data('domain-name');
    const updateUrl = $btn.data('update-url');
    
    const $modal = $('#editDomainModal');
    const $form = $modal.find('#editDomainForm');
    
    // Set form action and populate input
    $form.attr('action', updateUrl);
    $form.find('#edit_domain_name').val(domainName);
    
    // Clear any previous validation errors
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide().find('span').text('');
});
</script>
@endpush
