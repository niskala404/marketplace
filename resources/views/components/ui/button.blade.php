@props([
  'variant' => 'primary', // primary | secondary | outline | danger
  'size' => 'md',        // sm | md | lg
  'class' => '',
])

@php
  $sizes = [
    'sm' => 'px-3 py-2 text-sm rounded-xl',
    'md' => 'px-4 py-2.5 text-sm rounded-xl',
    'lg' => 'px-4 py-3 text-base rounded-2xl',
  ];

  $variants = [
    'primary' => 'bg-rose-600 text-white hover:bg-rose-700',
    'secondary' => 'bg-slate-900 text-white hover:bg-slate-800',
    'outline' => 'border bg-white hover:bg-slate-50 text-slate-900',
    'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
  ];

  $base = 'inline-flex items-center justify-center gap-2 font-bold active:scale-[0.99] transition';
  $sizeClass = $sizes[$size] ?? $sizes['md'];
  $variantClass = $variants[$variant] ?? $variants['primary'];
@endphp

<button {{ $attributes->merge(['class' => "$base $sizeClass $variantClass $class"]) }}>
  {{ $slot }}
</button>
