@props([
    'name',
    'id' => null,
    'label' => null,
    'prefixIcon' => null,
    'suffixIcon' => null,
    'suffixBtn' => null,
    'prefix' => null,
    'suffix' => null,
    'hint' => null,
    'error' => null,
    'disabled' => false,
])
@aware(['model' => null])
@php
  $id ??= Str::slug($name);
  $placeholder ??= $label ? "Enter $label" : '';

  $classes = explode(' ', $attributes['class'] ?? '');
  $isFloatingLabel = in_array('floating-label', $classes);

  $isRequired = $attributes->get('required') ? true : false;
  $isRequired = $attributes->get('disabled') ? false : $isRequired;
  $labelIndicator = $isRequired ? ' <span class="text-error">*</span>' : '';

  $attributes = $attributes->merge(['class' => 'input-validator']);
  $error ??= 'Please select a file to upload.';

  $hasPrefix = isset($prefix) || isset($prefixIcon);
  $hasSuffix = isset($suffix) || isset($suffixIcon);

  $prefixClasses = $hasPrefix ? '' : 'ps-1';
  $suffixClasses = $hasSuffix ? '' : 'pe-1';

  $sizeSuffix = 'sm';
  if (in_array('file-input-xl', $classes)) {
      $sizeSuffix = 'lg';
  } elseif (in_array('file-input-lg', $classes)) {
      $sizeSuffix = 'md';
  } elseif (in_array('file-input-sm', $classes)) {
      $sizeSuffix = 'xs';
  } elseif (in_array('file-input-xs', $classes)) {
      $sizeSuffix = 'xs';
  }
@endphp

<div>
  @if ($label && !$isFloatingLabel)
    <p class="mb-1 text-xs font-semibold">{{ $label }}{!! $labelIndicator !!}</p>
  @endif

  <div id="label-{{ $id }}" {{ $attributes->merge(['class' => "input w-full $prefixClasses $suffixClasses"])->only('class') }}
    @if ($errors->has($name)) aria-invalid="true" @endif>
    @if (isset($prefix) || isset($prefixIcon))
      <label class="label !me-0">
        @isset($prefixIcon)
          <i class="{{ $prefixIcon }}"></i>
        @endisset
        @isset($prefix)
          {{ $prefix }}
        @endisset
      </label>
    @endif
    @if ($label && $isFloatingLabel)
      <span>{{ $label }}{!! $labelIndicator !!}</span>
    @endif
    <input type="file" id="{{ $id }}" name="{{ $name }}" {{ $attributes->except('class') }} {{ $disabled ? 'disabled' : '' }}
      class="file-input file-input-{{ $sizeSuffix }} file-input-ghost w-full p-0 !shadow-none !outline-none"
      @if ($errors->has($name)) oninput="this.parentElement.removeAttribute('aria-invalid');this.reportValidity();" @endif>
    @if (isset($suffix) || isset($suffixIcon) || isset($suffixBtn))
      <label class="label !ms-0 !ps-1">
        @isset($suffix)
          {{ $suffix }}
        @endisset
        @isset($suffixBtn)
          <x-daisyui.button id="suffix-btn-{{ $id }}" type="submit" label="{{ $suffixBtn }}" class="btn-{{ $sizeSuffix }}" />
        @endisset
        @isset($suffixIcon)
          <i class="{{ $suffixIcon }}"></i>
        @endisset
      </label>
    @endif
  </div>

  <div id="error-{{ $id }}" class="validator-hint mt-1 hidden">{!! $error !!}</div>

  @if ($hint)
    <p class="label mt-1 whitespace-normal text-xs">{{ $hint }}</p>
  @endif
</div>

@isset($suffixBtn)
  @push('js')
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('{{ $id }}');
        const submitButton = document.getElementById('suffix-btn-{{ $id }}');

        if (!fileInput || !submitButton) return;

        // Initially disable button since input is empty
        submitButton.disabled = true;

        fileInput.addEventListener('change', function() {
          submitButton.disabled = !this.files.length;
        });
      });
    </script>
  @endpush
@endisset
