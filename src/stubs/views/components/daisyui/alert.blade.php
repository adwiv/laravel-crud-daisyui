@props([
    'type' => null,
    'icon' => null,
    'emoji' => null,
    'title' => null,
    'subtitle' => null,
    'vertical' => false,
    'dismissable' => false,
])
@php
  $validTypes = ['info', 'success', 'warning', 'error'];
  if ($type !== null && !in_array($type, $validTypes)) {
      throw new \InvalidArgumentException("Invalid type '$type'. Alert type must be one of: " . implode(', ', $validTypes));
  }

  $classes = 'alert';
  // Hint for Tailwind CSS npm package to load the alert classes
  // 'alert-info', 'alert-success', 'alert-warning', 'alert-error'
  if ($type != null) {
      $classes .= " alert-$type";
  }
  if ($vertical) {
      $classes .= ' alert-vertical sm:alert-horizontal';
  }
  $icon = $icon ?? $type;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
  @if ($emoji)
    <span class="text-xl">{{ $emoji }}</span>
  @elseif ($icon !== null && $icon !== 'none')
    <span>
      @if ($icon === 'success')
        <i class="fas fa-circle-check"></i>
      @elseif($icon === 'error')
        <i class="fas fa-circle-xmark"></i>
      @elseif($icon === 'warning')
        <i class="fas fa-triangle-exclamation"></i>
      @elseif($icon === 'info')
        <i class="fas fa-circle-info"></i>
      @else
        <i class="fas fa-{{ $icon }}"></i>
      @endif
    </span>
  @endif

  @if ($subtitle)
    <div>
      <h3 class="font-bold">{!! $title !!}</h3>
      <div class="text-sm">{!! $subtitle !!}</div>
    </div>
  @else
    <div>{!! $title !!}</div>
  @endif

  @if ($dismissable)
    <button class="btn btn-sm btn-{{ $type }} btn-ghost btn-circle transition-transform duration-300 hover:rotate-90"
      onclick="return this.parentNode.remove();">
      <i class="fa-solid fa-xmark"></i>
    </button>
  @endif

  {{ $slot }}
</div>
