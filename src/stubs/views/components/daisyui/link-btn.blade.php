@props(['icon' => null, 'label' => null, 'color' => 'primary', 'disabled' => false])

@php
  $color = $disabled ? 'disabled' : $color;
@endphp

<a {{ $attributes->merge(['class' => "btn btn-{$color}"]) }}>
  @isset($icon)
    <i class="{{ $icon }}"></i>
  @endisset
  @isset($label)
    {{ $label }}
  @endisset
</a>
