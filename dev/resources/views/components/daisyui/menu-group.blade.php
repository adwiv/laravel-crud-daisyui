@props([
    'icon' => null,
    'text' => '',
])

@php
  // Check if any child items are active
  $hasActiveChild = false;
  if ($slot->isNotEmpty()) {
      $hasActiveChild = str_contains($slot->toHtml(), 'menu-active');
  }
@endphp

<li>
  <details {{ $hasActiveChild ? 'open' : '' }}>
    <summary>
      @if ($icon)
        <i class="{{ $icon }} w-5"></i>
      @endif
      {{ $text }}
    </summary>
    <ul>
      {{ $slot }}
    </ul>
  </details>
</li>
