@props(['title' => '', 'breadcrumbs' => null])
@aware(['title' => ''])

<div class="flex flex-col gap-4 p-4 md:p-6">
  <!-- Header -->
  <div class="dynamic-justify flex items-center">
    <!-- Title -->
    @if ($title)
      <h3 class="text-xl font-semibold sm:text-2xl">{{ $title }}</h3>
    @endif
    <!-- Breadcrumbs -->
    @if ($breadcrumbs)
      <div class="breadcrumbs hidden text-sm md:block">
        <ul>
          {{ $breadcrumbs }}
        </ul>
      </div>
    @endif
  </div>

  <!-- Main Content -->
  <main {{ $attributes }}>
    {{ $slot }}
  </main>
</div>
