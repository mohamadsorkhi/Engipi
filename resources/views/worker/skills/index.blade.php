@extends('layouts.master')

@section('title', 'مدیریت مهارت‌ها')

@section('content')
    <x-admin.breadcrumb title="مهارت‌های من" parent="داشبورد" parentUrl="{{ route('root') }}" />

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">مدیریت مهارت‌های من</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">مهارت‌های خود را از لیست زیر انتخاب یا حذف کنید. این مهارت‌ها برای پیشنهاد پروژه‌های مناسب به شما استفاده خواهند شد.</p>

                    @if($allSkills->isEmpty())
                        <div class="alert alert-warning text-center">
                            در حال حاضر هیچ مهارتی در سیستم ثبت نشده است. لطفا بعدا مراجعه کنید.
                        </div>
                    @else
                        <form action="{{ route('specialist.skills.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="skills-select" class="form-label">لیست مهارت‌ها</label>
                                <select class="form-control choices-select" id="skills-select" name="skills[]" multiple>
                                    @foreach($allSkills as $skill)
                                        <option value="{{ $skill->id }}" @selected(in_array($skill->id, $workerSkillIds))>
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"><span></span></div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary ajax-submit">
                                    <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <span class="flex-grow-1">ذخیره تغییرات</span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
