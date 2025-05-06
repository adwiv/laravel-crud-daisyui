@props([
    'timeout' => null,
])

@php    
    $attributes = $attributes->merge(['class' => 'toast toast-top z-50']);
    $toastId = 'toast-' . uniqid();
@endphp

<div {{ $attributes }} id="{{ $toastId }}">
    {{ $slot }}
</div>

@if($timeout !== null && $timeout !== 0)
<script>    
console.log('{{ $timeout }}');
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const toast = document.getElementById('{{ $toastId }}');
            if (toast) {
                toast.style.transition = 'opacity 0.5s ease-out';
                toast.style.opacity = '0';
                
                setTimeout(function() {
                    toast.remove();
                }, 500);
            }
        }, {{ $timeout }}); 
    });    
</script>
    @endif