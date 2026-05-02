@extends('layouts.master')

@section('title', 'انتخاب پروفایل')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">مدیریت پروفایل‌ها</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        شما می‌توانید با یک ایمیل هم به عنوان کارفرما و هم به عنوان متخصص فعالیت کنید.
                        اطلاعات پروفایل‌های خود را بروزرسانی کنید.
                    </p>

                    @if($profiles->isEmpty())
                        <div class="alert alert-info">
                            <i class="ri-information-line me-2"></i>
                            شما هنوز پروفایلی ندارید. لطفا یک پروفایل ایجاد کنید.
                        </div>
                    @else
                        <h6 class="mb-3">پروفایل‌های موجود:</h6>
                        <div class="row g-3 mb-4">
                            @foreach($profiles as $profile)
                                <div class="col-md-6">
                                    <div class="card border border-primary">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title bg-{{ $profile->type === 'employer' ? 'primary' : 'success' }}-subtle text-{{ $profile->type === 'employer' ? 'primary' : 'success' }} rounded-circle fs-3">
                                                        <i class="ri-{{ $profile->type === 'employer' ? 'briefcase' : 'user-star' }}-line"></i>
                                                    </span>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">{{ $profile->type === 'employer' ? 'کارفرما' : 'متخصص' }}</h6>
                                                    <p class="text-muted mb-0 small">
                                                        <span class="badge bg-success">فعال</span>
                                                    </p>
                                                </div>
                                                <a href="{{ route('root') }}" class="btn btn-sm btn-primary">
                                                    ادامه
                                                </a>
                                            </div>

                                            <div class="mt-3">
                                                <button class="btn btn-sm btn-soft-info" type="button" data-bs-toggle="collapse" data-bs-target="#profile-edit-{{ $profile->id }}" aria-expanded="false">
                                                    ویرایش اطلاعات
                                                </button>

                                                <div class="collapse mt-3" id="profile-edit-{{ $profile->id }}">
                                                    <form action="{{ route('profiles.update', $profile) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')

                                                        @if($profile->type === 'employer')
                                                            <div class="mb-3">
                                                                <label class="form-label">نام شرکت (اختیاری)</label>
                                                                <input type="text" name="company_name" class="form-control" value="{{ $profile->company_name }}" maxlength="255">
                                                                <div class="invalid-feedback"><span></span></div>
                                                            </div>
                                                        @else
                                                            <div class="mb-3">
                                                                <label class="form-label">عنوان تخصصی <span class="text-danger">*</span></label>
                                                                <input type="text" name="headline" class="form-control" value="{{ $profile->headline }}" required minlength="2" maxlength="255">
                                                                <div class="form-text">حداقل ۲ کاراکتر</div>
                                                                <div class="invalid-feedback"><span></span></div>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">بیوگرافی (اختیاری)</label>
                                                                <textarea name="bio" class="form-control" rows="3">{{ $profile->bio }}</textarea>
                                                                <div class="invalid-feedback"><span></span></div>
                                                            </div>
                                                        @endif

                                                        <button type="submit" class="btn btn-sm btn-info ajax-submit">
                                                            <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                            ذخیره تغییرات
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @php
                        $hasEmployer = $profiles->where('type', 'employer')->isNotEmpty();
                        $hasSpecialist = $profiles->where('type', 'specialist')->isNotEmpty();
                    @endphp

                    @if(!$hasEmployer || !$hasSpecialist)
                        <hr>
                        <h6 class="mb-3">ایجاد پروفایل جدید:</h6>
                        <div class="row g-3">
                            @if(!$hasEmployer)
                                <div class="col-md-6">
                                    <form action="{{ route('profiles.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="profile_type" value="employer">
                                        <div class="card border border-dashed">
                                            <div class="card-body text-center">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                                        <i class="ri-briefcase-line"></i>
                                                    </span>
                                                </div>
                                                <h6>پروفایل کارفرما</h6>
                                                <p class="text-muted small mb-3">برای ثبت پروژه و استخدام متخصصین</p>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label">نام شرکت (اختیاری)</label>
                                                    <input type="text" name="company_name" class="form-control" maxlength="255">
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if(!$hasSpecialist)
                                <div class="col-md-6">
                                    <form action="{{ route('profiles.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="profile_type" value="specialist">
                                        <div class="card border border-dashed">
                                            <div class="card-body text-center">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                                        <i class="ri-user-star-line"></i>
                                                    </span>
                                                </div>
                                                <h6>پروفایل متخصص</h6>
                                                <p class="text-muted small mb-3">برای ارائه خدمات و همکاری در پروژه‌ها</p>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label">عنوان تخصصی <span class="text-danger">*</span></label>
                                                    <input type="text" name="headline" class="form-control" required minlength="2" maxlength="255">
                                                    <div class="form-text">حداقل ۲ کاراکتر</div>
                                                </div>
                                                <div class="mb-3 text-start">
                                                    <label class="form-label">بیوگرافی (اختیاری)</label>
                                                    <textarea name="bio" class="form-control" rows="3"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-sm ajax-submit">
                                                    <span class="spinner-border spinner-border-sm" role="status" style="display: none;"></span>
                                                    ایجاد پروفایل
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
