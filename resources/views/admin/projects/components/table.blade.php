<div class="table-responsive table-card mb-1">
    <table class="table table-nowrap table-hover align-middle">
        <thead class="text-muted table-light">
        <tr>
            <th>عنوان پروژه</th>
            <th>کارفرما</th>
            <th>نوع همکاری</th>
            <th>تاریخ ایجاد</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody class="list form-check-all">
        @forelse($projects as $project)
            <tr>
                <td>{{ $project->title }}</td>
                <td>{{ $project->employer->full_name }}</td>
                <td>{{ __('project.work_type.' . $project->work_type) }}</td>
                <td>{{ $project->created_at->format('Y/m/d') }}</td>
                <td class="d-flex gap-2">
                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-eye-line align-bottom"></i>
                    </a>

                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-soft-danger btn-sm ajax-submit"
                                data-confirm="آیا از حذف این پروژه اطمینان دارید؟">
                            <i class="ri-delete-bin-line align-bottom"></i>
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">
                    پروژه ای یافت نشد.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
