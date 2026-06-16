{{-- ═══ Add Skill Modal ═══ --}}
<div class="modal fade" id="addSkillModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">افزودن مهارت جدید</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.skills.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">زیرحوزه <span class="text-danger">*</span></label>
                        <select name="subdomain_id" class="form-select" required id="add-subdomain-select">
                            <option value="">انتخاب کنید</option>
                            @foreach($domains as $domain)
                                <optgroup label="{{ $domain->name }}">
                                    @foreach($domain->subdomains as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نام مهارت <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name"
                               id="skill-name" required maxlength="255">
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary ajax-submit">ثبت</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══ Edit Skill Modal ═══ --}}
<div class="modal fade" id="editSkillModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ویرایش مهارت</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSkillForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">زیرحوزه <span class="text-danger">*</span></label>
                        <select name="subdomain_id" class="form-select" required id="skill-subdomain-edit">
                            <option value="">انتخاب کنید</option>
                            @foreach($domains as $domain)
                                <optgroup label="{{ $domain->name }}">
                                    @foreach($domain->subdomains as $sub)
                                        <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">نام مهارت <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="skill-name-edit"
                               name="name" required maxlength="255">
                        <div class="invalid-feedback"><span></span></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                    <button type="submit" class="btn btn-primary ajax-submit">ذخیره تغییرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
