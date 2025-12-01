@props(['title', 'value', 'subtitle' => null, 'icon', 'color' => 'primary'])

@php
  $colorClasses = [
    'primary' => 'bg-primary-100 text-primary-600',
    'success' => 'bg-success-100 text-success-600',
    'warning' => 'bg-accent-100 text-accent-600',
    'danger' => 'bg-danger-100 text-danger-600',
    'info' => 'bg-blue-100 text-blue-600',
  ];

  $iconBg = $colorClasses[$color] ?? $colorClasses['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'stat-card']) }}>
  <div class="flex items-center">
    <div class="flex-shrink-0">
      <div class="h-12 w-12 rounded-full {{ $iconBg }} flex items-center justify-center">
        {!! $icon !!}
      </div>
    </div>
    <div class="ml-4 flex-1">
      <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
      <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
      @if($subtitle)
        <p class="text-sm text-gray-500">{{ $subtitle }}</p>
      @endif
    </div>
  </div>
</div>