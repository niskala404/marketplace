@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Edit Alamat</h1>
    <a href="{{ route('account.addresses.index') }}" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="{{ route('account.addresses.update', $address) }}" class="space-y-4">
        @csrf
        @method('PUT')

        @include('account.addresses._form', ['address' => $address])

        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update Alamat</button>
    </form>
</div>
@endsection
