@props(['title', 'description' => null])

<div class="page-header">
  <div>
    <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
    @if($description)
      <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
    @endif
  </div>
  <div class="mt-4 sm:mt-0">
    {{ $slot }}
  </div>
</div>