@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Produk Saya</h1>
    <div class="flex items-center gap-2">
        <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="{{ route('seller.products.bulk') }}">Bulk Tools</a>
        <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="{{ route('seller.products.create') }}">+ Produk</a>
    </div>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @foreach($products as $p)
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold">{{ $p->name }}</div>
                    <div class="text-sm text-slate-500">
                        Rp {{ number_format($p->price,0,',','.') }} • stok {{ $p->stock }} • {{ $p->is_active ? 'aktif' : 'nonaktif' }}
                        • status: <span class="font-semibold">{{ $p->approval_status ?? 'approved' }}</span>
                    </div>
                    @if(($p->approval_status ?? '') === 'rejected' && $p->rejected_reason)
                        <div class="text-sm text-rose-600 mt-1">Ditolak: {{ $p->rejected_reason }}</div>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('seller.products.edit',$p) }}">Edit</a>
                    <form method="POST" action="{{ route('seller.products.destroy',$p) }}">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
