@props(['href', 'icon', 'color' => 'base-content', 'tooltip' => '', 'disabled' => false])

@php
  $attributes = $attributes->merge(['class' => 'flex items-center p-2']);
@endphp
<div class="tooltip" data-tip="{{ $tooltip }}{{ $disabled ? ' (Disabled)' : '' }}">
  @if ($disabled)
    <span {{ $attributes->merge(['class' => "text-{$color}/30"]) }}>
      <i class="{{ $icon }}"></i>
    </span>
  @else
    <a href="{{ $href }}" {{ $attributes->merge(['class' => "text-{$color}/70 hover:text-{$color}"]) }}>
      <i class="{{ $icon }}"></i>
    </a>
  @endif
</div>
