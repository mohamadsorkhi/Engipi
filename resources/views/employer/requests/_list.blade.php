@if($requests->isEmpty())
    {{--<div class="card">
        <div class="card-body">
            <div class="alert alert-info text-center mb-0">
                هیچ درخواستی برای نمایش وجود ندارد.
            </div>
        </div>
    </div>--}}
@else
    @foreach($requests as $request)
        <x-employer.request-card :request="$request" />
    @endforeach

    <div class="mt-3">
        {{ $requests->links() }}
    </div>
@endif
