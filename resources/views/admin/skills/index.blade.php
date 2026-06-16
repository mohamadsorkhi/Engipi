@extends('layouts.master')

@section('title', 'مدیریت مهارت‌ها')

@section('content')
    <x-admin.breadcrumb
        title="مهارت‌ها"
        parent="زیرحوزه‌ها"
        parentUrl="{{ route('admin.subdomains.index') }}"
    />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">لیست مهارت‌ها</h5>
                    <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal" data-bs-target="#addSkillModal">
                        <i class="ri-add-line align-bottom me-1"></i> افزودن مهارت
                    </button>
                </div>
                <div class="ajax-table">
                    @include('admin.skills.components.table_and_pagination', ['skills' => $skills])
                </div>
            </div>
        </div>
    </div>

    @include('admin.skills.components.modals')
@endsection

@push('scripts')
<script>
$(document).on('show.bs.modal', '#editSkillModal', function (event) {
    const btn          = $(event.relatedTarget);
    const id           = btn.data('id');
    const name         = btn.data('name');
    const subdomainId  = btn.data('subdomain-id');
    const action       = "{{ route('admin.skills.update', ':id') }}".replace(':id', id);

    const $form = $('#editSkillForm');
    $form.attr('action', action);
    $('#skill-name-edit').val(name);
    $('#skill-subdomain-edit').val(subdomainId);

    // Clear previous errors
    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('.invalid-feedback').hide().find('span').text('');
});
</script>
@endpush
