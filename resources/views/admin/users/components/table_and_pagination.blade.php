<div class="card-body border-top border-top-dashed">
    @include('admin.users.components.table', ['users' => $users])
</div>
@if ($users->hasPages())
    <div class="card-footer">
        {{ $users->withQueryString()->links() }}
    </div>
@endif
