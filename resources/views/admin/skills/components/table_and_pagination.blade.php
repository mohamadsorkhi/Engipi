<div class="card-body">
    @include('admin.skills.components.table', ['skills' => $skills])
</div>
<div class="card-footer">
    {{ $skills->withQueryString()->links() }}
</div>
