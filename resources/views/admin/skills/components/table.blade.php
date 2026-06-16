<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
        <tr>
            <th>#</th>
            <th>نام مهارت</th>
            <th>زیرحوزه</th>
            <th>حوزه</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody>
        @forelse($skills as $skill)
            <tr>
                <td>{{ $loop->iteration + ($skills->currentPage() - 1) * $skills->perPage() }}</td>
                <td class="fw-medium">{{ $skill->name }}</td>
                <td>
                    @if($skill->subdomain)
                        <span class="badge bg-primary-subtle text-primary">{{ $skill->subdomain->name }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($skill->subdomain?->domain)
                        <span class="badge bg-secondary-subtle text-secondary">{{ $skill->subdomain->domain->name }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-2">
                        <button class="btn btn-soft-info btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editSkillModal"
                                data-id="{{ $skill->id }}"
                                data-name="{{ $skill->name }}"
                                data-subdomain-id="{{ $skill->subdomain_id }}">
                            <i class="ri-pencil-line align-bottom"></i>
                        </button>
                        <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                    data-confirm="آیا از حذف مهارت «{{ $skill->name }}» اطمینان دارید؟">
                                <i class="ri-delete-bin-line align-bottom"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">هیچ مهارتی یافت نشد.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
