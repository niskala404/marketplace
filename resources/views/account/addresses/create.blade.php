@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tambah Alamat</h1>
    <a href="{{ route('account.addresses.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('account.addresses.store') }}" class="space-y-4">
        @csrf
        @include('account.addresses._form')

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan Alamat</button>
    </form>
</div>
@endsection
