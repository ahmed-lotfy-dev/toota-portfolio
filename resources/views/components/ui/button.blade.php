@props(['variant' => 'primary', 'size' => 'md', 'href' => null])

@php
  $baseClasses = 'inline-flex items-center justify-center font-medium rounded transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed';

  $variants = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'link' => 'text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline-offset-4 hover:underline p-0 h-auto',
  ];

  $sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
  ];

  // If it's a link variant, remove padding/height constraints from size unless overridden
  if ($variant === 'link') {
    $sizes = [
      'sm' => 'text-sm',
      'md' => 'text-sm', // Default size for links usually matches text
      'lg' => 'text-base',
    ];
  }

  $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
  <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
  </a>
@else
  <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }}>
    {{ $slot }}
  </button>
@endif