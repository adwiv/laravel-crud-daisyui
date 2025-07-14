@props([
    'icon' => null,
    'text' => '',
    'href' => '#',
    'route' => null,
    'active' => null,
])

@php
  $currentUrl = url()->current();
  $url = $route ? route($route) : url($href);
  $isActive = $active && request()->is($active);
@endphp

<li {{ $attributes }}>
  <a href="{{ $url }}" @class(['w-full', 'menu-active' => $isActive])>
    @if ($icon)
      <i class="{{ $icon }} w-5"></i>
    @endif
    {{ $text }}
  </a>
</li>
