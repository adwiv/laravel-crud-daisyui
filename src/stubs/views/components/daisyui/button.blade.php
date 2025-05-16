@props([
    'color' => 'primary',
    'type' => 'submit',
    'icon' => null,
    'label' => null,
])
@php
  // Valid button classes: 'btn-neutral', 'btn-primary', 'btn-secondary', 'btn-accent',
  // 'btn-error', 'btn-warning', 'btn-success', 'btn-info'
  $validColors = ['neutral', 'primary', 'secondary', 'accent', 'error', 'warning', 'success', 'info'];
  if (!in_array($color, $validColors)) {
      throw new \Exception("Invalid color: $color. Valid colors are: " . implode(', ', $validColors));
  }
@endphp

<button {{ $attributes->merge(['class' => "btn btn-{$color}", 'type' => $type]) }}>
  @isset($icon)
    <i class="{{ $icon }}"></i>
  @endisset
  @isset($label)
    {{ $label }}
  @endisset
</button>
