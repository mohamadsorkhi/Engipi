@if($domains->isEmpty())
    <div class="alert alert-info text-center mb-0">
        هیچ حوزه‌ای ثبت نشده است.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-borderless table-centered align-middle mb-0">
            <thead class="text-muted table-light">
                <tr>
                    <th>نام حوزه</th>
                    <th>تعداد زیرحوزه‌ها</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($domains as $domain)
                    <tr>
                        <td class="fw-medium">{{ $domain->name }}</td>
                        <td>
                            <a href="{{ route('admin.subdomains.index', ['domain_id' => $domain->id]) }}"
                               class="badge bg-info-subtle text-info text-decoration-none">
                                {{ $domain->subdomains_count }} زیرحوزه
                            </a>
                        </td>
                        <td>{{ $domain->created_at->format('Y/m/d') }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.subdomains.index', ['domain_id' => $domain->id]) }}"
                                   class="btn btn-soft-info btn-sm"
                                   title="مدیریت زیرحوزه‌ها">
                                    <i class="ri-list-unordered"></i>
                                </a>
                                <button type="button" class="btn btn-soft-primary btn-sm edit-domain-btn"
                                    data-bs-toggle="modal" data-bs-target="#editDomainModal"
                                    data-domain-id="{{ $domain->id }}"
                                    data-domain-name="{{ $domain->name }}"
                                    data-update-url="{{ route('admin.domains.update', $domain) }}">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.domains.destroy', $domain) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-soft-danger btn-sm ajax-submit"
                                            data-confirm="آیا از حذف حوزه «{{ $domain->name }}» مطمئن هستید؟">
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
        {{ $domains->withQueryString()->links() }}
    </div>
@endif
