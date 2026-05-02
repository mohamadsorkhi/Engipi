<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th class="w-100">نام مهارت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @forelse($skills as $skill)
            <tr>
                <td>{{ $loop->iteration + ($skills->currentPage() - 1) * $skills->perPage() }}</td>
                <td>{{ $skill->name }}</td>
                <td class="d-flex gap-2">
                    <button class="btn btn-soft-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editSkillModal"
                            data-id="{{ $skill->id }}"
                            data-name="{{ $skill->name }}">
                        <i class="ri-pencil-line align-bottom"></i>
                    </button>
                    <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                data-confirm="آیا از حذف این مهارت اطمینان دارید؟">
                            <i class="ri-delete-bin-line align-bottom"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">هیچ مهارتی یافت نشد.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
