<div class="table-responsive table-card mb-1">
    <table class="table table-nowrap table-hover align-middle">
        <thead class="text-muted table-light">
        <tr>
            <th>#</th>
            <th>نام</th>
            <th>ایمیل</th>
            <th>تاریخ عضویت</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody class="list form-check-all">
        @forelse($users as $user)
            <tr>
                <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                <td>
                    <a href="{{ route('admin.users.show', $user) }}" class="fw-medium text-primary">
                        {{ $user->full_name }}
                    </a>
                </td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('Y/m/d') }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-eye-line align-bottom"></i>
                    </a>
                    <button class="btn btn-soft-info btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal"
                            data-user='{{ json_encode($user) }}'>
                        <i class="ri-pencil-line align-bottom"></i>
                    </button>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                data-confirm="آیا از حذف این کاربر اطمینان دارید؟ با حذف کاربر، تمام پروژه‌ها و درخواست‌های مرتبط با او نیز حذف خواهند شد.">
                            <i class="ri-delete-bin-line align-bottom"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">هیچ کاربری یافت نشد.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
