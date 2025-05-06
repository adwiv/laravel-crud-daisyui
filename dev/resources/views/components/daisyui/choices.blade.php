@props([
    'id',
    'label',
    'name',
    'type' => null,
    'placeholder' => null,
    'value' => null,
    'model' => null,
    'modelKey' => null,
    'oldKey' => null,
    'options' => null,
    'valueKey' => 'id',
    'labelKey' => 'name',
    'default' => null,
    'prefix' => null,
    'prefixIcon' => null,
    'hint' => null,
    'error' => null,
])
@aware(['id', 'label', 'model' => null])
@php
  if (!in_array($type, ['radio', 'checkbox', 'toggle', 'select'])) {
      throw new \Exception("Invalid choices type: $type");
  }
  if ($model && !is_object($model) && !is_array($model)) {
      throw new \Exception('Model must be an object or an array');
  }
  if ($options && !is_array($options) && !($options instanceof \Illuminate\Support\Collection)) {
      throw new \Exception('Options must be an array or a collection');
  }

  $options = collect($options)
      ->mapWithKeys(function ($option, $key) use ($labelKey, $valueKey) {
          if ($option instanceof BackedEnum) {
              return [
                  $option->value => method_exists($option, 'label')
                      ? $option->label()
                      : Str::title(Str::snake(Str::camel($option->value), ' ')),
              ];
          }
          if ($option instanceof UnitEnum) {
              return [$option->name => Str::title(Str::snake(Str::camel($option->name), ' '))];
          }
          if (is_object($option)) {
              return [$option->{$valueKey} => $option->{$labelKey}];
          }
          return ["$key" => "$option"];
      })
      ->toArray();

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

  // If value is not set, set it to default
  $value ??= $default;

  // Set value from old input if available.
  // 'none' is used to prevent the value from being set from old input.
  if ($oldKey != 'none') {
      $value = old($oldKey, $value);
  }

  // Convert value to an array to handle both single and multiple values
  $value = collect($value)
      ->map(function ($item) {
          if ($item instanceof BackedEnum) {
              return "{$item->value}";
          } elseif ($item instanceof UnitEnum) {
              return "{$item->name}";
          } elseif (is_bool($item)) {
              return $item ? '1' : '0';
          }
          return "$item";
      })
      ->toArray();

  $attributes = $attributes->merge(['class' => "$type"]);
  if ($oldKey != $name) {
      $attributes = $attributes->merge(['data-old-key' => $oldKey]);
  }

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
  @if ($type === 'select')
    @php
      $labelClass = '';
      $iconClass = '';
      if ($attributes->has('multiple')) {
          $attributes = $attributes->merge(['class' => 'w-full items-stretch !h-auto bg-none']);
          $labelClass = '!h-auto self-stretch my-1';
          $iconClass = 'self-start';
      } else {
          $attributes = $attributes->merge(['class' => 'w-full']);
      }

      // If placeholder is null, set it to default
      // pass emtpy string as placeholder to disable placeholder
      $placeholder ??= "Select $label";

      $noOptionSelected = true;
      foreach ($options as $key => $option) {
          if (in_array("$key", $value)) {
              $noOptionSelected = false;
              break;
          }
      }
    @endphp

    <label {{ $attributes->only('class') }}>
      @if (isset($prefix) || isset($prefixIcon))
        <label class="label {{ $labelClass }}">
          @isset($prefixIcon)
            <i class="{{ $prefixIcon }} {{ $iconClass }}"></i>
          @endisset
          @isset($prefix)
            {{ $prefix }}
          @endisset
        </label>
      @endif

      @if ($label && $isFloatingLabel)
        <span>{{ $label }}</span>
      @endif

      <select id="{{ $id }}" name="{{ $name }}" class="!p-2" {{ $attributes->except('class') }}>
        @if ($placeholder)
          <option value="" @if ($attributes->has('required')) disabled="disabled" @endif
            @if ($noOptionSelected) selected="selected" @endif>{{ $placeholder }}</option>
        @endif
        @foreach ($options as $key => $option)
          @php
            $selected = in_array("$key", $value);
          @endphp
          <option value="{{ $key }}" {{ $selected ? 'selected="selected"' : '' }}>{{ $option }}
          </option>
        @endforeach
      </select>
    </label>
  @else
    <div class="flex flex-wrap gap-3 py-2">
      @foreach ($options as $key => $option)
        @php
          $selected = in_array("$key", $value);
          $choiceId = $id . '.' . Str::slug($key, '-');
        @endphp
        <label class="label">
          <input type="{{ $type === 'toggle' ? 'checkbox' : $type }}" name="{{ $name }}"
            id="{{ $choiceId }}" {{ $attributes }} value="{{ $key }}"
            @if ($selected) checked="checked" @endif>{{ $option }}</label>
      @endforeach
    </div>
  @endif

  @if ($error)
    <div id="error-{{ $id }}" class="validator-hint mt-1 hidden">{{ $error }}</div>
  @endif

  @if ($hint)
    <p class="label mt-1 whitespace-normal text-xs">{{ $hint }}</p>
  @endif
</div>
