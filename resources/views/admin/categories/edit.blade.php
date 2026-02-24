@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Edit Kategori</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('admin.categories.update',$category) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" value="{{ $category->name }}" class="w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="font-semibold">Parent</label>
            <select name="parent_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}" @selected($category->parent_id===$p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Gambar Kategori</label>

            @if($category->image_path)
                <div class="mt-2 flex items-center gap-3">
                    <img src="{{ $category->imageUrl() }}" class="w-16 h-16 rounded-2xl object-cover border" alt="{{ $category->name }}">
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remove_image" value="1" class="rounded">
                        <span>Hapus gambar</span>
                    </label>
                </div>
            @else
                <div class="text-sm text-slate-500 mt-1">Belum ada gambar.</div>
            @endif

            <input type="file" name="image" accept="image/*" class="mt-3 w-full rounded-xl border-slate-200 bg-white">
            <div class="text-xs text-slate-500 mt-1">Upload baru untuk mengganti. Rekomendasi 1:1, maks 2MB.</div>
        </div>

        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update</button>
    </form>
</div>
@endsection
