@props(['user'])

@php
    $roleNames = [
        'admin' => 'ادمین',
        'employer' => 'کارفرما',
        'worker' => 'متخصص',
    ];
@endphp

<tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $roleNames[$user->role] ?? $user->role }}</td>
    <td>
        @if($user->active)
            <span class="badge bg-success-subtle text-success">فعال</span>
        @else
            <span class="badge bg-danger-subtle text-danger">غیرفعال</span>
        @endif
    </td>
    <td>{{ $user->created_at->format('Y/m/d') }}</td>
    <td>
        <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-{{ $user->active ? 'danger' : 'success' }} ajax-submit">
                {{ $user->active ? 'غیرفعال' : 'فعال' }}
            </button>
        </form>
    </td>
</tr>
