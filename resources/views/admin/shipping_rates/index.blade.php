@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tarif Ongkir</h1>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="{{ route('admin.shipping-rates.create') }}">+ Tarif</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @foreach($rates as $r)
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold">{{ $r->name }} {!! $r->is_active ? '<span class="text-emerald-600 text-xs font-semibold">aktif</span>' : '<span class="text-rose-600 text-xs font-semibold">nonaktif</span>' !!}</div>
                    <div class="text-sm text-slate-500">
                        Cakupan: {{ $r->city ? 'Kota: '.$r->city : ($r->province ? 'Provinsi: '.$r->province : 'Default (semua)') }}
                        • Base: Rp {{ number_format($r->base_fee,0,',','.') }}
                        • Per kg: Rp {{ number_format($r->per_kg_fee,0,',','.') }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('admin.shipping-rates.edit',$r) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.shipping-rates.destroy',$r) }}">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $rates->links() }}</div>
@endsection
