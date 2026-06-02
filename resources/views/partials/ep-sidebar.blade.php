@php
    $activePage = $activePage ?? '';
    $navItems = [
        ['id' => 'dashboard',  'icon' => 'ri-dashboard-2-line',       'label' => 'داشبورد',                'badge' => null, 'route' => 'user.dashboard'],
        ['id' => 'matched',    'icon' => 'ri-lightbulb-flash-line',   'label' => 'پروژه‌های پیشنهادی',    'badge' => null, 'route' => 'user.matched-projects.index'],
        ['id' => 'projects',   'icon' => 'ri-briefcase-line',          'label' => 'پروژه‌های من',           'badge' => null, 'route' => 'user.projects.index'],
        ['id' => 'requests',   'icon' => 'ri-inbox-line',              'label' => 'درخواست‌ها',             'badge' => null, 'route' => 'user.requests.sent'],
        ['id' => 'skills',     'icon' => 'ri-star-line',               'label' => 'مهارت‌ها',              'badge' => null, 'route' => 'user.skills.index'],
        ['id' => 'tickets',    'icon' => 'ri-customer-service-2-line', 'label' => 'تیکت‌ها',               'badge' => null, 'route' => 'user.tickets.index'],
    ];
@endphp
<aside class="sidebar">
    <a class="sb-brand" href="{{ url('/') }}"><span class="accent">Eng</span>Pis</a>
    <div class="sb-section">منوی اصلی</div>
    <ul class="sb-nav">
        @foreach($navItems as $item)
            @php
                $isActive = ($activePage === $item['id']);
                try { $href = route($item['route']); } catch (\Exception $e) { $href = '#'; }
            @endphp
            <li>
                <a href="{{ $href }}" class="sb-item {{ $isActive ? 'active' : '' }}">
                    <i class="{{ $item['icon'] }}"></i>
                    <span>{{ $item['label'] }}</span>
                    @if($item['badge'])
                        <span class="sb-badge">{{ $item['badge'] }}</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</aside>
