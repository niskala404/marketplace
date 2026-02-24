@props([
  'variant' => 'default', // default | primary | success | danger
  'class' => '',
])

@php
  $variants = [
    'default' => 'bg-slate-100 text-slate-700 border-slate-200',
    'primary' => 'bg-rose-50 text-rose-700 border-rose-200',
    'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'danger'  => 'bg-rose-50 text-rose-700 border-rose-200',
  ];
  $variantClass = $variants[$variant] ?? $variants['default'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full border $variantClass $class"]) }}>
  {{ $slot }}
</span>
