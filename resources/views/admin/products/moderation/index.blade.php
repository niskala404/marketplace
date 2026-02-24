@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Moderasi Produk</h1>
        <p class="text-slate-600 text-sm">Setujui / tolak produk dari seller sebelum tampil di marketplace.</p>
    </div>

    <div class="flex gap-2">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $k => $label)
            <a href="{{ route('admin.products.moderation.index', ['status' => $k]) }}"
               class="px-4 py-2 rounded-xl border {{ $status === $k ? 'bg-slate-900 text-white border-slate-900' : 'bg-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left p-3">Produk</th>
                <th class="text-left p-3">Toko</th>
                <th class="text-left p-3">Harga</th>
                <th class="text-left p-3">Status</th>
                <th class="text-right p-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($products as $p)
                <tr>
                    <td class="p-3">
                        <div class="font-bold">{{ $p->name }}</div>
                        <div class="text-sm text-slate-500">{{ $p->category?->name ?? '-' }}</div>
                    </td>
                    <td class="p-3">
                        <div class="font-semibold">{{ $p->shop?->name ?? '-' }}</div>
                        <div class="text-sm text-slate-500">{{ $p->shop?->user?->email ?? '' }}</div>
                    </td>
                    <td class="p-3">Rp {{ number_format($p->price,0,',','.') }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-lg text-xs border {{ $p->approval_status==='pending' ? 'bg-amber-50 border-amber-200' : ($p->approval_status==='approved' ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200') }}">
                            {{ $p->approval_status }}
                        </span>
                    </td>
                    <td class="p-3 text-right">
                        <a class="px-3 py-2 rounded-xl bg-rose-600 text-white" href="{{ route('admin.products.moderation.show', $p) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="p-6 text-slate-600" colspan="5">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $products->links() }}</div>
@endsection
