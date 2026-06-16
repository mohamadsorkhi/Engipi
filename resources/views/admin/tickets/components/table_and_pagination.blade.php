@include('admin.tickets.components.table')

<div class="mt-4">
    {{ $tickets->withQueryString()->links() }}
</div>
