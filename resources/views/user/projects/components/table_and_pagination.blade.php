@if($projects->isEmpty())
    <div class="alert alert-info text-center mb-0">
        <i class="ri-information-line me-2"></i>
        هنوز پروژه‌ای ثبت نکرده‌اید.
        <a href="{{ route('user.projects.create') }}" class="alert-link">ثبت پروژه جدید</a>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-borderless table-centered align-middle mb-0">
            <thead class="text-muted table-light">
                <tr>
                    <th>عنوان</th>
                    <th>حوزه</th>
                    <th>نوع اجرا</th>
                    <th>درخواست‌ها</th>
                    <th>تاریخ ثبت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $workTypes = [
                        'remote' => ['name' => 'دورکاری', 'class' => 'bg-success'],
                        'onsite' => ['name' => 'حضوری', 'class' => 'bg-primary'],
                        'hybrid' => ['name' => 'ترکیبی', 'class' => 'bg-info'],
                    ];
                @endphp
                @foreach($projects as $project)
                    <tr>
                        <td>
                            <a href="{{ route('user.projects.show', $project) }}" class="fw-medium text-primary">
                                {{ Str::limit($project->title, 40) }}
                            </a>
                        </td>
                        <td>
                            @if($project->domains->isNotEmpty())
                                @foreach($project->domains as $domain)
                                    <span class="badge bg-primary-subtle text-primary me-1">{{ $domain->name }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            @php $wt = $workTypes[$project->work_type] ?? ['name' => '-', 'class' => 'bg-secondary']; @endphp
                            <span class="badge {{ $wt['class'] }}">{{ $wt['name'] }}</span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $project->requests_count ?? $project->requests()->count() }}</span>
                        </td>
                        <td class="text-muted">{{ $project->created_at }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.projects.show', $project) }}" class="btn btn-soft-primary btn-sm" title="مشاهده">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('user.projects.edit', $project) }}" class="btn btn-soft-info btn-sm" title="ویرایش">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('user.projects.destroy', $project) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit" 
                                        data-confirm="آیا از حذف این پروژه مطمئن هستید؟" title="حذف">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@endif
