@extends('layouts.market')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Voucher</h1>
    <a class="px-4 py-3 rounded-xl bg-amber-600 text-white font-bold" href="{{ route('admin.vouchers.create') }}">+ Voucher</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        @forelse($vouchers as $v)
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold">{{ $v->code }} — {{ $v->name }}</div>
                    <div class="text-sm text-slate-500">
                        Scope: {{ $v->shop ? $v->shop->name : 'Platform' }}
                        •
                        @if($v->type === 'percent')
                            {{ $v->value }}%
                        @elseif($v->type === 'shipping')
                            Diskon Ongkir Rp {{ number_format($v->value,0,',','.') }}
                        @else
                            Rp {{ number_format($v->value,0,',','.') }}
                        @endif
                        • Min: Rp {{ number_format($v->min_subtotal,0,',','.') }}
                        @if($v->type==='percent' && $v->max_discount)
                            • Max: Rp {{ number_format($v->max_discount,0,',','.') }}
                        @endif
                    </div>
                    <div class="text-xs text-slate-500">
                        Used: {{ $v->used_count }}
                        @if($v->usage_limit) / {{ $v->usage_limit }} @endif
                        • Per user: {{ $v->per_user_limit }}
                        • {{ $v->is_active ? 'aktif' : 'nonaktif' }}
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="{{ route('admin.vouchers.edit',$v) }}">Edit</a>
                    <form method="POST" action="{{ route('admin.vouchers.destroy',$v) }}">
                        @csrf @method('DELETE')
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-6 text-slate-600">Belum ada voucher.</div>
        @endforelse
    </div>
</div>

<div class="mt-4">{{ $vouchers->links() }}</div>
@endsection
