@extends('layouts.master')

@section('title', 'مکالمه با ' . $user->full_name)

@section('content')
    <x-admin.breadcrumb title="مکالمه" parent="پیام‌ها" parentUrl="{{ route('user.messages.index') }}"/>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-18">
                            {{ mb_substr($user->first_name ?? '؟', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ $user->full_name }}</div>
                    </div>
                </div>

                {{-- Messages thread --}}
                <div class="card-body" style="max-height: 520px; overflow-y: auto;" id="messages-container">
                    @if($messages->isEmpty())
                        <p class="text-muted text-center mb-0">هنوز پیامی رد و بدل نشده. اولین پیام را بفرستید!</p>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($messages as $msg)
                                @php $isMine = $msg->sender_id === Auth::id(); @endphp
                                <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div class="px-3 py-2 rounded-3"
                                         style="max-width:75%;
                                                background: {{ $isMine ? '#e6f7ff' : '#f1f5f9' }};
                                                border: 1px solid {{ $isMine ? '#b6e7ff' : '#e2e8f0' }};">
                                        @if($msg->project)
                                            <div class="small text-primary mb-1">
                                                <i class="ri-briefcase-line align-bottom"></i>
                                                {{ $msg->project->title }}
                                            </div>
                                        @endif
                                        <div>{!! nl2br(e($msg->body)) !!}</div>
                                        <div class="text-muted small mt-1 {{ $isMine ? 'text-start' : 'text-end' }}">
                                            {{ $msg->created_at }}
                                            @if($isMine && $msg->read_at)
                                                <i class="ri-check-double-line text-primary ms-1" title="خوانده شد"></i>
                                            @elseif($isMine)
                                                <i class="ri-check-line ms-1" title="ارسال شد"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Send form --}}
                <div class="card-footer">
                    @if(session('success'))
                        <div class="alert alert-success py-2 mb-2">{{ session('success') }}</div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger py-2 mb-2">{{ $errors->first() }}</div>
                    @endif

                    <form action="{{ route('user.messages.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">

                        <div class="d-flex gap-2 align-items-end">
                            <textarea
                                name="body"
                                class="form-control @error('body') is-invalid @enderror"
                                rows="2"
                                placeholder="پیام خود را بنویسید..."
                                required
                                minlength="1"
                                maxlength="5000"
                            >{{ old('body') }}</textarea>
                            <button type="submit" class="btn btn-primary flex-shrink-0">
                                <i class="ri-send-plane-2-line align-bottom"></i>
                                ارسال
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom of messages on load
    const container = document.getElementById('messages-container');
    if (container) container.scrollTop = container.scrollHeight;
</script>
@endpush
