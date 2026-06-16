@include('admin.ticket-departments.components.table')

<div class="mt-4">
    {{ $departments->withQueryString()->links() }}
</div>
