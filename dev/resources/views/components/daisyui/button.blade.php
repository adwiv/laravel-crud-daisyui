@props([
    'color' => 'primary', 'type'=>'submit', 'icon' => null, 'label' => null
])

<button {{ $attributes->merge(['class' => "btn btn-{$color}", 'type' => $type]) }}>
    @isset($icon) <i class="{{ $icon }}"></i> @endisset
    @isset($label) {{ $label }} @endisset
</button>