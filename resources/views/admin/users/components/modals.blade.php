<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">ویرایش کاربر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="user-name-edit" class="form-label">نام</label>
                        <input type="text" class="form-control" id="user-name-edit" name="name" required>
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-email-edit" class="form-label">ایمیل</label>
                        <input type="email" class="form-control" id="user-email-edit" name="email" required>
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                    <div class="mb-3">
                        <label for="user-password-edit" class="form-label">رمز عبور جدید</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="user-password-edit" name="password">
                            <button type="button" class="btn btn-light border toggle-password" data-target="#user-password-edit" title="نمایش رمز">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <div class="form-text">در صورتی که قصد تغییر رمز عبور را ندارید، این فیلد را خالی رها کنید.</div>
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary ajax-submit">
                        <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="flex-grow-1">ذخیره تغییرات</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var input = document.querySelector(btn.getAttribute('data-target'));
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});
</script>
