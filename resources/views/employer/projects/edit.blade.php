@extends('layouts.master')

@section('title', 'ویرایش پروژه')

@section('content')
    <x-admin.breadcrumb title="ویرایش پروژه: {{ $project->title }}" parent="پروژه‌های من"
                        parentUrl="{{ route('employer.projects.index') }}"/>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">فرم ویرایش پروژه</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employer.projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان پروژه</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title', $project->title) }}"
                                   placeholder="مثال: طراحی وبسایت فروشگاهی">
                            <div class="invalid-feedback"><span></span></div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">توضیح پروژه</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                      placeholder="توضیحات کامل در مورد پروژه و نیازمندی‌ها">{{ old('description', $project->description) }}</textarea>
                            <div class="invalid-feedback"><span></span></div>
                        </div>


                        <div class="mb-3">
                            <label for="work_type" class="form-label">نوع همکاری</label>
                            <select class="form-select" id="work_type" name="work_type">
                                <option value="remote" @selected($project->work_type == 'remote')>دورکاری</option>
                                <option value="onsite" @selected($project->work_type == 'onsite')>حضوری</option>
                                <option value="hybrid" @selected($project->work_type == 'hybrid')>ترکیبی</option>
                            </select>
                            <div class="invalid-feedback"><span></span></div>
                        </div>


                        <div class="mb-3">
                            <label for="skills-select" class="form-label">مهارت‌های مورد نیاز</label>
                             @if($skills->isEmpty())
                                <div class="alert alert-warning text-center">
                                    در حال حاضر هیچ مهارتی در سیستم برای انتخاب وجود ندارد. امکان ویرایش پروژه تا زمان اضافه شدن مهارت‌ها توسط مدیر سیستم وجود ندارد.
                                </div>
                            @else
                                <select class="form-control choices-select" id="skills-select" name="skills[]" multiple>
                                    @php $projectSkillIds = $project->skills->pluck('id')->toArray(); @endphp
                                    @foreach($skills as $skill)
                                        <option value="{{ $skill->id }}" @selected(in_array($skill->id, $projectSkillIds))>
                                            {{ $skill->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"><span></span></div>
                            @endif
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary ajax-submit" @if($skills->isEmpty()) disabled @endif>
                                <div class="spinner-border spinner-border-sm" role="status" style="display: none;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span class="flex-grow-1">ویرایش پروژه</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
