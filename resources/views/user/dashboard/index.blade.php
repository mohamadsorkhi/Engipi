@extends('layouts.master')
@section('title', 'داشبورد کاربر')

@section('content')
    <x-admin.breadcrumb title="داشبورد" panel="user" />

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">مشاوره ها</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">برای شروع یا ادامه دادن پرسشنامه روی دکمه زیر کلیک کنید.</p>
                    <a href="{{ route('user.consultations.index') }}" class="btn btn-primary">مشاهده پرسشنامه</a>
                </div>
            </div>
        </div>
    </div>
@endsection
