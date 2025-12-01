@props(['status', 'type' => 'default'])

@php
  $classes = [
    'default' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-success-100 text-success-800',
    'danger' => 'bg-danger-100 text-danger-800',
    'warning' => 'bg-accent-100 text-accent-800',
    'info' => 'bg-primary-100 text-primary-800',
  ];

  $class = $classes[$type] ?? $classes['default'];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $class]) }}>
  <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 8 8">
    <circle cx="4" cy="4" r="3" />
  </svg>
  {{ $status }}
</span>