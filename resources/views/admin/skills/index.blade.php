@extends('layouts.master')

@section('title', 'مدیریت مهارت‌ها')

@section('content')
    <x-admin.breadcrumb title="مدیریت مهارت‌ها" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}"/>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست مهارت‌ها</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
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
        $(document).ready(function() {
             // Handle modal opening for editing
            $('#editSkillModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var action = "{{ route('admin.skills.update', ':id') }}".replace(':id', id);

                var modal = $(this);
                modal.find('form').attr('action', action);
                modal.find('.modal-body #skill-name-edit').val(name);
            });
        });
    </script>
@endpush
