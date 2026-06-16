@extends('layouts.master')

@section('title', 'مدیریت کاربران')

@section('content')
    <x-admin.breadcrumb title="مدیریت کاربران" parent="داشبورد" parentUrl="{{ route('admin.dashboard') }}"/>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">لیست کاربران</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET" id="user-filter-form">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="user-q" class="form-label">جستجو</label>
                                <input type="text" name="q" id="user-q" class="form-control" value="{{ request('q') }}" placeholder="نام، ایمیل یا موبایل">
                            </div>
                            <div class="col-md-3">
                                <label for="profile-type" class="form-label">نوع پروفایل</label>
                                <select name="profile_type" id="profile-type" class="form-select" onchange="$('#user-filter-form button[type=submit]').click()">
                                    <option value="">همه</option>
                                    <option value="employer" @selected(request('profile_type') == 'employer')>کارفرما</option>
                                    <option value="specialist" @selected(request('profile_type') == 'specialist')>متخصص</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="has_profile" id="has-profile" value="1" @checked(request('has_profile')) onchange="$('#user-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="has-profile">دارای پروفایل</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="has_projects" id="has-projects" value="1" @checked(request('has_projects')) onchange="$('#user-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="has-projects">دارای پروژه</label>
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="has_skills" id="has-skills" value="1" @checked(request('has_skills')) onchange="$('#user-filter-form button[type=submit]').click()">
                                    <label class="form-check-label" for="has-skills">دارای مهارت</label>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary ajax-submit">اعمال</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light">پاک کردن</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="ajax-table">
                    @include('admin.users.components.table_and_pagination', ['users' => $users])
                </div>
            </div>
        </div>
    </div>

    @include('admin.users.components.modals')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var editModal = document.getElementById('editUserModal');

            // Handle modal opening for editing a user
            editModal.addEventListener('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var user = button.data('user');
                var action = "{{ route('admin.users.update', ':id') }}".replace(':id', user.id);

                var modal = $(this);
                modal.find('form').attr('action', action);
                modal.find('.modal-body #user-name-edit').val(user.name);
                modal.find('.modal-body #user-email-edit').val(user.email);
                modal.find('.modal-body #user-password-edit').val(''); // Clear password field
            });

            // Handle modal closing to reset the filter
            editModal.addEventListener('hidden.bs.modal', function () {
                // Check if the ajax-submit was successful (the table was updated)
                // This is a simple check, a more robust solution might involve custom events
                if ($(this).find('form').data('submitted-successfully')) {
                     $('#user-filter-form')[0].reset();
                     // Reset the flag
                     $(this).find('form').data('submitted-successfully', false);
                }
            });

            // A global listener to set a flag on successful form submission
            $(document).ajaxComplete(function(event, xhr, settings) {
                // Check if the request was for updating a user and was successful
                if (settings.url.includes('/admin/users/') && settings.type === 'POST' && xhr.status === 200) {
                     var response = xhr.responseJSON;
                     if (response && response.success && response.close) {
                         $('#editUserModal').find('form').data('submitted-successfully', true);
                     }
                }
            });
        });
    </script>
@endpush
