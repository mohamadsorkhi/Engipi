@if($processes->isEmpty())
    <div class="alert alert-info text-center mb-0">
        هیچ پردازشی ثبت نشده است.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-borderless table-centered align-middle mb-0">
            <thead class="text-muted table-light">
                <tr>
                    <th>نام پردازش</th>
                    <th>حوزه تخصصی</th>
                    <th>تعداد مهارت‌ها</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($processes as $process)
                    <tr>
                        <td class="fw-medium">{{ $process->name }}</td>
                        <td><span class="badge bg-primary">{{ $process->domain->name ?? '-' }}</span></td>
                        <td><span class="badge bg-info">{{ $process->skills_count }}</span></td>
                        <td>{{ $process->created_at }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-soft-primary btn-sm" 
                                    data-bs-toggle="modal" data-bs-target="#editProcessModal{{ $process->id }}">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.processes.destroy', $process) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از حذف این پردازش مطمئن هستید؟">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editProcessModal{{ $process->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ویرایش پردازش</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.processes.update', $process) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">حوزه تخصصی <span class="text-danger">*</span></label>
                                            <select class="form-select" name="skill_domain_id" required>
                                                @foreach(\App\Models\SkillDomain::orderBy('name')->get() as $domain)
                                                    <option value="{{ $domain->id }}" @selected($process->skill_domain_id === $domain->id)>{{ $domain->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback"><span></span></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">نام پردازش <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $process->name }}" required>
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
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $processes->withQueryString()->links() }}
    </div>
@endif
