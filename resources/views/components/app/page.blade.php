@props([
  'title' => null,
  'subtitle' => null,
])

<div class="max-w-6xl mx-auto space-y-4">
  @if($title)
    <div class="bg-white border rounded-2xl p-4 shadow-sm">
      <div class="flex items-start justify-between gap-3 flex-wrap">
        <div>
          <h1 class="text-2xl font-black">{{ $title }}</h1>
          @if($subtitle)
            <div class="text-sm text-slate-500 mt-1">{{ $subtitle }}</div>
          @endif
        </div>
        @if(isset($actions))
          <div class="flex items-center gap-2">{{ $actions }}</div>
        @endif
      </div>
      @if(isset($toolbar))
        <div class="mt-4">{{ $toolbar }}</div>
      @endif
    </div>
  @endif

  {{ $slot }}
</div>
