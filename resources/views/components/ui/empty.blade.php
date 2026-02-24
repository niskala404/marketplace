@props([
  'title' => 'Belum ada data',
  'message' => null,
  'class' => '',
])

<div {{ $attributes->merge(['class' => "bg-white border rounded-2xl p-10 text-center text-slate-600 shadow-sm $class"]) }}>
  <div class="text-lg font-black text-slate-900">{{ $title }}</div>
  @if($message)
    <div class="text-sm text-slate-500 mt-1">{{ $message }}</div>
  @endif
  @if(isset($action))
    <div class="mt-4">{{ $action }}</div>
  @endif
</div>
