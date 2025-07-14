@props(['text', 'size' => 'xs'])

@php
  $attributes = $attributes->merge(['class' => "btn btn-$size btn-ghost p-0"]);
@endphp

<div class="tooltip -my-2" data-tip="Click to copy" onclick="copyToClipboard(this, '{{ $text }}')">
  <button type="button" {{ $attributes }}>
    <div class="">
      <span class="copy-action flex items-center gap-1"><i class="fas fa-copy"></i> Copy</span>
      <span class="copy-success text-success flex hidden items-center gap-1"><i class="fas fa-copy"></i> Copied!</span>
    </div>
  </button>
</div>

@once
  @push('js')
    <script>
      function copyToClipboard(button, text) {
        navigator.clipboard.writeText(text);

        const actionEl = button.querySelector('.copy-action');
        const successEl = button.querySelector('.copy-success');

        actionEl.classList.add('hidden');
        successEl.classList.remove('hidden');

        setTimeout(() => {
          actionEl.classList.remove('hidden');
          successEl.classList.add('hidden');
        }, 3000);
      }
    </script>
  @endpush
@endonce
