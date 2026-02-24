@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Alamat Saya</h1>
        <div class="text-slate-500 text-sm">Kelola alamat pengiriman untuk checkout.</div>
    </div>
    <a href="{{ route('account.addresses.create') }}" class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold">+ Alamat</a>
</div>

@if($addresses->isEmpty())
    <div class="bg-white border rounded-2xl p-6 text-slate-600">
        Belum ada alamat. Tambahkan alamat untuk bisa checkout.
    </div>
@else
    <div class="space-y-3">
        @foreach($addresses as $a)
            <div class="bg-white border rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-bold">{{ $a->label }}</div>
                            @if($a->is_default)
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold">Default</span>
                            @endif
                        </div>
                        <div class="mt-1 text-slate-700 font-semibold">{{ $a->recipient_name }} ({{ $a->phone }})</div>
                        <div class="text-sm text-slate-600 mt-1">{{ $a->full_address }}</div>
                        <div class="text-sm text-slate-500">{{ $a->district }} {{ $a->city }} {{ $a->province }} {{ $a->postal_code }}</div>
                    </div>

                    <div class="flex flex-col gap-2 w-40">
                        <a href="{{ route('account.addresses.edit', $a) }}" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-center">Edit</a>
                        <form method="POST" action="{{ route('account.addresses.destroy', $a) }}">
                            @csrf @method('DELETE')
                            <button class="w-full px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
