@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Kategori</h1>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="{{ route('admin.categories.create') }}">+ Kategori</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @foreach($categories as $c)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 border overflow-hidden flex items-center justify-center shrink-0">
                        @if($c->image_path)
                            <img src="{{ $c->imageUrl() }}" class="w-full h-full object-cover" alt="{{ $c->name }}">
                        @else
                            <span class="font-black text-slate-400">{{ strtoupper(mb_substr($c->name,0,1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold truncate">{{ $c->name }}</div>
                        <div class="text-sm text-slate-500 truncate">{{ $c->slug }}</div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('admin.categories.edit',$c) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.categories.destroy',$c) }}">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $categories->links() }}</div>
@endsection
