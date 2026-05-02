<div class="table-responsive table-card mb-1">
    <table class="table table-nowrap table-hover align-middle">
        <thead class="text-muted table-light">
        <tr>
            <th>نام</th>
            <th>وضعیت</th>
            <th>تاریخ</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody class="list form-check-all">
        @forelse($departments as $department)
            <tr>
                <td class="fw-medium">{{ $department->name }}</td>
                <td>
                    @if($department->active)
                        <span class="badge bg-success">فعال</span>
                    @else
                        <span class="badge bg-secondary">غیرفعال</span>
                    @endif
                </td>
                <td>{{ $department->created_at }}</td>
                <td class="d-flex gap-2">
                    <button class="btn btn-soft-info btn-sm" data-bs-toggle="modal" data-bs-target="#editDepartmentModal" data-department='@json($department)'>
                        <i class="ri-pencil-line align-bottom"></i>
                    </button>

                    <form action="{{ route('admin.ticket-departments.destroy', $department) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از حذف این دپارتمان اطمینان دارید؟">
                            <i class="ri-delete-bin-line align-bottom"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">دپارتمانی یافت نشد.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
