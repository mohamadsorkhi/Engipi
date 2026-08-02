@props(['name', 'level' => null])
<span class="sp-skill-chip"><i class="ri-checkbox-circle-fill" aria-hidden="true"></i><span>{{ $name }}</span>@if($level)<small>{{ $level }}</small>@endif</span>