@extends('layouts.market')

@section('content')
<h1 class="text-2xl font-black mb-4">Tambah Kategori</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" class="w-full rounded-xl border-slate-200" required>
        </div>
        <div>
            <label class="font-semibold">Parent (opsional)</label>
            <select name="parent_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                @foreach($parents as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Gambar Kategori (opsional)</label>
            <input type="file" name="image" accept="image/*" class="w-full rounded-xl border-slate-200 bg-white">
            <div class="text-xs text-slate-500 mt-1">Rekomendasi: PNG/JPG, 1:1 (kotak), maks 2MB.</div>
        </div>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>
@endsection
