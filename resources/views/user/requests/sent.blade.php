@extends('layouts.master')

@section('title', 'درخواست‌های ارسالی')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">درخواست‌های ارسالی</h5>
                    <a href="{{ route('user.matched-projects.index') }}" class="btn btn-soft-primary btn-sm">
                        <i class="ri-search-line me-1"></i> جستجوی پروژه
                    </a>
                </div>
                <div class="card-body">
                    @if($requests->isEmpty())
                        <div class="alert alert-info text-center mb-0">
                            <i class="ri-information-line me-2"></i>
                            هنوز درخواستی ارسال نکرده‌اید.
                            <a href="{{ route('user.matched-projects.index') }}" class="alert-link">پروژه‌های پیشنهادی را ببینید</a>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($requests as $request)
                                <div class="col-lg-6">
                                    <div class="card border mb-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div>
                                                    <a href="{{ route('user.matched-projects.show', $request->project) }}" class="fs-16 fw-medium text-primary">
                                                        {{ $request->project->title }}
                                                    </a>
                                                    <div class="text-muted small mt-1">
                                                        <i class="ri-user-line me-1"></i>{{ $request->project->employer->name ?? '-' }}
                                                    </div>
                                                </div>
                                                <x-request-status-badge :status="$request->status" />
                                            </div>
                                            
                                            @if($request->message)
                                                <p class="text-muted small mb-3">
                                                    <strong>پیام شما:</strong> {{ Str::limit($request->message, 100) }}
                                                </p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>{{ $request->created_at }}
                                                </small>
                                                <span class="badge bg-primary-subtle text-primary">{{ $request->project->domain->name ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
