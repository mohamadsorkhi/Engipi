@extends('layouts.master')

@section('title', 'درخواست‌های من')

@section('content')
    <x-admin.breadcrumb title="درخواست‌های من" parent="داشبورد" parentUrl="{{ route('root') }}" />

    <div class="row">
        <div class="col-lg-12">
            @if($requests->isEmpty())
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info text-center mb-0">
                            شما هنوز هیچ درخواست همکاری ارسال نکرده‌اید.
                        </div>
                    </div>
                </div>
            @else
                @foreach($requests as $request)
                    <x-worker.request-card :request="$request" />
                @endforeach

                <div class="mt-3">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
