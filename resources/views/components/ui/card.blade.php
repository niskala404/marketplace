@props([
  'class' => '',
  'padding' => 'p-4',
])

<div {{ $attributes->merge(['class' => "bg-white border rounded-2xl shadow-sm {$padding} {$class}"]) }}>
  {{ $slot }}
</div>
