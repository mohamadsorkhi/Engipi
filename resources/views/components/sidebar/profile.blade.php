@props(['user', 'roleLabel', 'href' => null, 'completion' => null, 'admin' => false])

<section class="engipi-profile-card {{ $admin ? 'is-admin-profile' : '' }}" data-tooltip="{{ $user->name }}">
    <div class="engipi-profile-head">
        <span class="engipi-profile-avatar">
            @if($user->avatar)
                <img src="{{ asset('images/'.$user->avatar) }}" alt="تصویر {{ $user->name }}">
            @else
                <span aria-hidden="true">{{ mb_substr($user->name, 0, 1) }}</span>
            @endif
        </span>
        <span class="engipi-profile-copy">
            <strong>{{ $user->name }}</strong>
            <small>
                {{ $roleLabel }}
                @if($user->email_verified_at)
                    <span class="engipi-verified" title="ایمیل تأیید شده" aria-label="ایمیل تأیید شده"><i class="ri-verified-badge-fill"></i></span>
                @endif
            </small>
        </span>
    </div>

    @if(is_numeric($completion))
        <div class="engipi-profile-progress">
            <span><small>تکمیل پروفایل</small><strong>{{ (int) $completion }}٪</strong></span>
            <div role="progressbar" aria-label="تکمیل پروفایل" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ (int) $completion }}">
                <i style="width: {{ max(0, min(100, (int) $completion)) }}%"></i>
            </div>
        </div>
    @endif

    @if($href)
        <a href="{{ $href }}" class="engipi-profile-link">
            <span>مشاهده پروفایل</span><i class="ri-arrow-left-line" aria-hidden="true"></i>
        </a>
    @endif
</section>
