<div class="card-body">
    @include('admin.projects.components.table', ['projects' => $projects])
</div>
@if ($projects->hasPages())
    <div class="card-footer">
        {{ $projects->withQueryString()->links() }}
    </div>
@endif
