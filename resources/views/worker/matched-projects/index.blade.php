@extends('layouts.master')

@section('title', 'پروژه‌های پیشنهادی')

@section('content')
    <x-admin.breadcrumb title="پروژه‌های متناسب با مهارت‌های شما" parent="داشبورد" parentUrl="{{ route('root') }}" />

    <div class="row">
        @if($projects->isEmpty())
            <div class="col-lg-12">
                <div class="alert alert-info text-center">
                    در حال حاضر پروژه‌ای متناسب با مهارت‌های شما ثبت نشده است.
                </div>
            </div>
        @else
            @foreach($projects as $project)
                <div class="col-lg-12">
                    <x-worker.matched-project-card :project="$project" />
                </div>
            @endforeach
        @endif
    </div>

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
                    <input type="hidden" name="project_id" id="project_id_input">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="message" class="form-label">پیام (اختیاری)</label>
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="می‌توانید یک پیام برای کارفرما ارسال کنید..." minlength="10"></textarea>
                            <div class="form-text">اگر پیام وارد می‌کنید، حداقل ۱۰ کاراکتر باشد</div>
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
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var requestModal = document.getElementById('requestModal');
        requestModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var projectId = button.getAttribute('data-project-id');
            var modalProjectIdInput = requestModal.querySelector('#project_id_input');
            modalProjectIdInput.value = projectId;
        });
    });
</script>
@endpush
