@if($subdomains->isEmpty())
    <div class="alert alert-info text-center mb-0">
        هیچ زیرحوزه‌ای ثبت نشده است.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-borderless table-centered align-middle mb-0">
            <thead class="text-muted table-light">
                <tr>
                    <th>نام زیرحوزه</th>
                    <th>حوزه</th>
                    <th>تعداد مهارت‌ها</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subdomains as $subdomain)
                    <tr>
                        <td class="fw-medium">{{ $subdomain->name }}</td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $subdomain->domain?->name ?? '—' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.skills.index', ['subdomain_id' => $subdomain->id]) }}"
                               class="badge bg-success-subtle text-success text-decoration-none">
                                {{ $subdomain->skills_count }} مهارت
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.skills.index', ['subdomain_id' => $subdomain->id]) }}"
                                   class="btn btn-soft-success btn-sm" title="مشاهده مهارت‌ها">
                                    <i class="ri-list-unordered"></i>
                                </a>
                                <button type="button" class="btn btn-soft-primary btn-sm edit-subdomain-btn"
                                    data-bs-toggle="modal" data-bs-target="#editSubdomainModal"
                                    data-id="{{ $subdomain->id }}"
                                    data-name="{{ $subdomain->name }}"
                                    data-domain-id="{{ $subdomain->skill_domain_id }}"
                                    data-update-url="{{ route('admin.subdomains.update', $subdomain) }}">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.subdomains.destroy', $subdomain) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                            data-confirm="آیا از حذف زیرحوزه «{{ $subdomain->name }}» مطمئن هستید؟&#10;مهارت‌های مرتبط بدون زیرحوزه می‌مانند.">
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
        {{ $subdomains->withQueryString()->links() }}
    </div>
@endif
