@extends('layouts.master')

@section('title', 'جزئیات پروژه: ' . $project->title)

@section('content')
    @php
        $user = Auth::user();
        $isAdmin = $user && $user->role === 'admin';
        $isOwner = !$isAdmin && $user && $user->id === $project->employer_id;

        $sentRequest = $sentRequest ?? null; // Default to null if not passed

        // Breadcrumb logic
        if ($isAdmin) {
            $breadcrumbParent = 'مدیریت پروژه‌ها';
            $breadcrumbParentUrl = route('admin.projects.index');
        } elseif ($isOwner) {
            $breadcrumbParent = 'پروژه‌های من';
            $breadcrumbParentUrl = route('employer.projects.index');
        } else {
            $breadcrumbParent = 'پروژه‌های مشابه';
            $breadcrumbParentUrl = route('specialist.matched-projects.index');
        }

        $work_types = [
            'remote' => ['name' => 'دورکاری', 'icon' => 'ri-global-line'],
            'onsite' => ['name' => 'حضوری', 'icon' => 'ri-building-line'],
            'hybrid' => ['name' => 'ترکیبی', 'icon' => 'ri-git-merge-line'],
        ];
        $work_type = $work_types[$project->work_type] ?? ['name' => 'نامشخص', 'icon' => 'ri-question-mark'];
    @endphp

    <x-admin.breadcrumb :title="$project->title" :parent="$breadcrumbParent" :parentUrl="$breadcrumbParentUrl" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                     <h4 class="mb-2 text-primary">{{ $project->title }}</h4>
                                     <div class="d-flex align-items-center text-muted mb-3">
                                        <div class="d-flex align-items-center me-4" title="زمان انتشار">
                                            <i class="ri-calendar-event-line me-2"></i>
                                            <span class="d-inline-block">{{ $project->created_at->ago() }}</span>
                                        </div>
                                        <div class="d-flex align-items-center" title="نوع همکاری">
                                            <i class="{{ $work_type['icon'] }} me-2"></i>
                                            <span>{{ $work_type['name'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($isOwner || $isAdmin)
                                <div class="flex-shrink-0 ms-3">
                                    <div class="d-flex gap-2">
                                        @if($isOwner)
                                            <a href="{{ route('employer.projects.edit', $project) }}" class="btn btn-soft-info">
                                                <i class="ri-pencil-line"></i> ویرایش
                                            </a>
                                            <form action="{{ route('employer.projects.destroy', $project) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-soft-danger ajax-submit" data-confirm="آیا برای حذف این پروژه مطمئن هستید؟">
                                                    <i class="ri-delete-bin-line"></i> حذف
                                                </button>
                                            </form>
                                        @elseif($isAdmin)
                                            <button class="btn btn-soft-danger ajax-submit"
                                                    data-url="{{ route('admin.projects.destroy', $project) }}"
                                                    data-method="DELETE"
                                                    data-redirect="{{ route('admin.projects.index') }}"
                                                    data-confirm="آیا از حذف این پروژه اطمینان دارید؟">
                                                <i class="ri-delete-bin-line"></i> حذف
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>


                            <div class="mt-4 text-muted">
                                <h6 class="mb-3 fw-semibold">توضیحات کامل پروژه:</h6>
                                <p>{!! nl2br(e($project->description)) !!}</p>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="fw-medium text-muted me-2">مهارت ها:</span>
                                    @foreach($project->skills as $skill)
                                        <span class="badge bg-primary-subtle text-primary me-1 mb-1">{{ $skill->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            @if(!$isOwner && !$isAdmin)
                                <hr class="my-4">
                                <div class="d-flex justify-content-end align-items-center">
                                     @if(Auth::check() && Auth::user()->role === 'worker')
                                        @if($sentRequest)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="text-muted">وضعیت درخواست شما:</span>
                                                <x-request-status-badge :status="$sentRequest->status" />
                                            </div>
                                        @else
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestModal" data-project-id="{{ $project->id }}">ارسال درخواست همکاری</button>
                                        @endif
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$isOwner && !$isAdmin && !$sentRequest)
    <!-- Request Modal -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">ارسال درخواست همکاری</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('specialist.requests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="project_id" id="project_id_input" value="{{ $project->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="message" class="form-label">پیام</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="یک پیام برای کارفرما ارسال کنید... (حداقل ۱۰ کاراکتر)" minlength="10" required></textarea>
                            <div class="form-text">حداقل ۱۰ کاراکتر</div>
                            <div class="invalid-feedback"><span></span></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">انصراف</button>
                        <button type="submit" class="btn btn-primary ajax-submit">
                            <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="flex-grow-1">ارسال درخواست</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var requestModal = document.getElementById('requestModal');
        if(requestModal) {
            requestModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var projectId = button.getAttribute('data-project-id');
                var modalProjectIdInput = requestModal.querySelector('#project_id_input');
                if(modalProjectIdInput) {
                    modalProjectIdInput.value = projectId;
                }
            });
        }
    });
</script>
@endpush
