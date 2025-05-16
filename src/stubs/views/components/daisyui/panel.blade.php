@props(['title' => '', 'breadcrumbs' => null])
@aware(['title' => ''])

<!-- Header -->
<div class="flex items-center justify-between">
  <h3 class="text-lg font-medium">{{ $title }}</h3>
  <!-- Breadcrumbs -->
  @if ($breadcrumbs)
    <div class="breadcrumbs text-sm">
      <ul>
        {{ $breadcrumbs }}
      </ul>
    </div>
  @endif
</div>

<!-- Main Content -->
<main class="mt-6">
  {{ $slot }}
</main>
