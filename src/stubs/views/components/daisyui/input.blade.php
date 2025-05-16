@props([
    'id',
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => null,
    'value' => null,
    'model' => null,
    'modelKey' => null,
    'oldKey' => null,
    'prefixIcon' => null,
    'suffixIcon' => null,
    'prefix' => null,
    'suffix' => null,
    'hint' => null,
    'error' => null,
])
@aware(['id', 'model' => null])
@php
  if ($model && !is_object($model) && !is_array($model)) {
      throw new \Exception('Model must be an object or an array');
  }

  $types = ['textarea', 'text', 'email', 'number', 'password', 'file', 'date', 'time', 'datetime-local', 'search', 'tel', 'url', 'hidden'];
  if (!in_array($type, $types)) {
      throw new \Exception("Invalid input type: $type");
  }

  $oldKey ??= preg_replace('@\[([^]]+)\]@', '.$1', preg_replace('@\[\]$@', '', $name));

  // Get value from model if available
  if (!$value && $model) {
      $modelKey ??= $oldKey;
      if (is_object($model) && isset($model->{$modelKey})) {
          $value = $model->{$modelKey};
      } elseif (is_array($model) && isset($model[$modelKey])) {
          $value = $model[$modelKey];
      }
  }

  // Set value from old input if available.
  // 'none' is used to prevent the value from being set from old input.
  // Ignore old value if its a string, eg. it is an array.
  if ($type !== 'password' && $type !== 'file' && $oldKey != 'none') {
      $oldValue = old($oldKey);
      if (is_string($oldValue)) {
          $value = $oldValue;
      }
  }

  if ($value instanceof \Carbon\Carbon) {
      if ($type === 'date') {
          $value = $value->format('Y-m-d');
      } elseif ($type === 'time') {
          $value = $value->format('H:i:s');
      } elseif ($type === 'datetime-local') {
          $value = $value->format('Y-m-d\TH:i:s');
      }
  }

  if ($oldKey != $name) {
      $attributes = $attributes->merge(['data-old-key' => $oldKey]);
  }

  $id ??= Str::slug($name);
  $placeholder ??= $label ? "Enter $label" : '';

  $classes = explode(' ', $attributes['class'] ?? '');
  $isFloatingLabel = in_array('floating-label', $classes);

  if ($error) {
      $attributes = $attributes->merge(['class' => 'validator']);
  }
@endphp

<div>
  @if ($label && !$isFloatingLabel)
    <p class="mb-1 text-xs font-semibold">{{ $label }}</p>
  @endif
  @if ($type === 'textarea')
    <label {{ $attributes->merge(['class' => 'input textarea !h-full group w-full pb-0 pe-0   items-stretch'])->only('class') }}>
      @if (isset($prefix) || isset($prefixIcon))
        <label class="label !me-0 mb-2 !h-auto self-stretch">
          @isset($prefixIcon)
            <i class="{{ $prefixIcon }} self-start pt-1"></i>
          @endisset
          @isset($prefix)
            {{ $prefix }}
          @endisset
        </label>
      @endif
      @if ($label && $isFloatingLabel)
        <span>{{ $label }}</span>
      @endif

      <textarea id="{{ $id }}" name="{{ $name }}" class="w-full pb-2 pe-3" placeholder="{{ $placeholder }}" {{ $attributes->except('class') }}>{{ $value }}</textarea>
      @if (isset($suffix) || isset($suffixIcon))
        <label class="label !mx-0 mb-2 !h-auto self-stretch">
          @isset($suffix)
            {{ $suffix }}
          @endisset
          @isset($suffixIcon)
            <i class="{{ $suffixIcon }} self-start pt-1"></i>
          @endisset
        </label>
      @endif
    </label>
  @else
    <label {{ $attributes->merge(['class' => 'input group w-full ' . ($type == 'hidden' ? 'hidden' : '')])->only('class') }}
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
        <span>{{ $label }}</span>
      @endif
      <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
        {{ $attributes->except('class') }}
        @if ($errors->has($name)) oninput="this.parentElement.removeAttribute('aria-invalid');this.reportValidity();" @endif />
      @if (isset($suffix) || isset($suffixIcon))
        <label class="label !ms-0">
          @isset($suffix)
            {{ $suffix }}
          @endisset
          @isset($suffixIcon)
            <i class="{{ $suffixIcon }}"></i>
          @endisset
        </label>
      @endif
    </label>
  @endif

  @if ($error)
    <div id="error-{{ $id }}" class="validator-hint mt-1 hidden">{{ $error }}</div>
  @endif

  @if ($hint)
    <p class="label mt-1 whitespace-normal text-xs">{{ $hint }}</p>
  @endif
</div>
