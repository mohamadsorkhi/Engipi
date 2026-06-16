<div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ایجاد دپارتمان</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.ticket-departments.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">نام <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required minlength="2" maxlength="255">
                        <div class="form-text">حداقل ۲ کاراکتر</div>
                        <div class="invalid-feedback"><span></span></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="dep-active" checked>
                        <label class="form-check-label" for="dep-active">فعال</label>
                    </div>

                    <button type="submit" class="btn btn-primary ajax-submit">
                        <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                        ایجاد
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ویرایش دپارتمان</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-department-form" action="" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">نام <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required minlength="2" maxlength="255">
                        <div class="form-text">حداقل ۲ کاراکتر</div>
                        <div class="invalid-feedback"><span></span></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="dep-active-edit">
                        <label class="form-check-label" for="dep-active-edit">فعال</label>
                    </div>

                    <button type="submit" class="btn btn-primary ajax-submit">
                        <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                        ذخیره
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editDepartmentModal');
        if (!editModal) return;

        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var dep = button.getAttribute('data-department');
            if (!dep) return;
            dep = JSON.parse(dep);

            var form = document.getElementById('edit-department-form');
            form.action = '{{ url('admin/ticket-departments') }}/' + dep.id;
            form.querySelector('input[name=name]').value = dep.name || '';
            form.querySelector('#dep-active-edit').checked = !!dep.active;
        });
    });
</script>
@endpush
