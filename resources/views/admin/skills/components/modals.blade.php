<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillModalLabel">افزودن مهارت جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.skills.store') }}" method="POST" class="ajax-submit">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skill-name" class="form-label">نام مهارت</label>
                        <input type="text" class="form-control" id="skill-name" name="name">
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ثبت</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modal -->
<div class="modal fade" id="editSkillModal" tabindex="-1" aria-labelledby="editSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillModalLabel">ویرایش مهارت</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="skill-name-edit" class="form-label">نام مهارت</label>
                        <input type="text" class="form-control" id="skill-name-edit" name="name">
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ajax-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
