@props(['type'])

@php
  $darkTextClass = match ($type) {
      'info' => 'dark:text-info',
      'success' => 'dark:text-success',
      'warning' => 'dark:text-warning',
      'error' => 'dark:text-error',
      default => throw new \InvalidArgumentException("Invalid alert bordered type '$type'"),
  };

  $attributes = $attributes->merge(['class' => "bg-base-100 $darkTextClass"]);
@endphp

@include('components.daisyui.alert')
