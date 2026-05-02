<div class="table-responsive table-card mb-1">
    <table class="table table-nowrap table-hover align-middle">
        <thead class="text-muted table-light">
        <tr>
            <th>عنوان</th>
            <th>کاربر</th>
            <th>دپارتمان</th>
            <th>وضعیت</th>
            <th>تاریخ</th>
            <th>عملیات</th>
        </tr>
        </thead>
        <tbody class="list form-check-all">
        @forelse($tickets as $ticket)
            <tr>
                <td class="fw-medium">{{ $ticket->subject }}</td>
                <td>{{ $ticket->user?->full_name ?? '-' }}</td>
                <td>{{ $ticket->department?->name ?? '-' }}</td>
                <td>
                    @if($ticket->status === 'open')
                        <span class="badge bg-success">باز</span>
                    @else
                        <span class="badge bg-secondary">بسته</span>
                    @endif
                </td>
                <td>{{ $ticket->created_at }}</td>
                <td>
                    <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-eye-line align-bottom"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">تیکتی یافت نشد.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
