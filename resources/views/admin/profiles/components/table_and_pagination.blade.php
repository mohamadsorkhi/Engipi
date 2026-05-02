@if($profiles->isEmpty())
    <div class="alert alert-info text-center mb-0">
        هیچ پروفایلی ثبت نشده است.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-borderless table-centered align-middle mb-0">
            <thead class="text-muted table-light">
                <tr>
                    <th>کاربر</th>
                    <th>ایمیل</th>
                    <th>نوع پروفایل</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profiles as $profile)
                    <tr>
                        <td class="fw-medium">{{ $profile->user->name ?? '-' }}</td>
                        <td>{{ $profile->user->email ?? '-' }}</td>
                        <td>
                            @if($profile->type === 'employer')
                                <span class="badge bg-primary">کارفرما</span>
                            @else
                                <span class="badge bg-success">متخصص</span>
                            @endif
                        </td>
                        <td>{{ $profile->created_at }}</td>
                        <td>
                            <form action="{{ route('admin.profiles.destroy', $profile) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" data-confirm="آیا از حذف این پروفایل مطمئن هستید؟">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $profiles->withQueryString()->links() }}
    </div>
@endif
