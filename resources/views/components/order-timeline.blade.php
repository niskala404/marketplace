@props(['order'])

@php
    $events = $order->relationLoaded('shipmentEvents') ? $order->shipmentEvents : collect();
    // Fallback if no events yet: build basic milestones from timestamps
    if ($events->isEmpty()) {
        $events = collect([
            $order->paid_at ? ['status'=>'paid','title'=>'Pembayaran diterima','desc'=>null,'at'=>$order->paid_at] : null,
            $order->shipped_at ? ['status'=>'shipped','title'=>'Pesanan dikirim','desc'=>$order->tracking_no ? 'Nomor resi: '.$order->tracking_no : null,'at'=>$order->shipped_at] : null,
            $order->delivered_at ? ['status'=>'delivered','title'=>'Pesanan sampai','desc'=>null,'at'=>$order->delivered_at] : null,
            $order->received_at ? ['status'=>'received','title'=>'Pesanan diterima','desc'=>null,'at'=>$order->received_at] : null,
            $order->completed_at ? ['status'=>'completed','title'=>'Pesanan selesai','desc'=>null,'at'=>$order->completed_at] : null,
        ])->filter()->map(function ($e) {
            return (object)[
                'status' => $e['status'],
                'event_code' => $e['status'],
                'title' => $e['title'],
                'description' => $e['desc'],
                'location' => null,
                'happened_at' => $e['at'],
            ];
        });
    }

    $iconByCode = [
        'order_created' => 'check',
        'paid' => 'check',
        'processing' => 'package',
        'shipped' => 'truck',
        'warehouse_received' => 'package',
        'sorting_center' => 'package',
        'line_haul' => 'truck',
        'destination_dc' => 'package',
        'courier_delivery' => 'truck',
        'custom_checkpoint' => 'map-pin',
        'delivered' => 'map-pin',
        'received' => 'check',
        'completed' => 'check',
        'cancelled' => 'circle-x',
    ];
@endphp

<div class="bg-white border rounded-2xl p-5">
    <div class="flex items-center justify-between">
        <div class="font-bold">Tracking Pesanan</div>
        @if($order->tracking_no)
            <div class="text-xs text-slate-500">Resi: <span class="font-semibold text-slate-700">{{ $order->tracking_no }}</span></div>
        @endif
    </div>

    <div class="mt-4">
        @if($events->isEmpty())
            <div class="text-sm text-slate-500">Belum ada update tracking.</div>
        @else
            <ol class="relative border-s border-slate-200 ms-3">
                @foreach($events as $ev)
                    <li class="mb-6 ms-6">
                        <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-white shadow">
                            <x-ic :name="$iconByCode[$ev->event_code ?? $ev->status] ?? 'check'" class="w-4 h-4" />
                        </span>
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900">{{ $ev->title }}</div>
                                @if($ev->description)
                                    <div class="text-sm text-slate-600 mt-0.5">{{ $ev->description }}</div>
                                @endif
                                @if(!empty($ev->location))
                                    <div class="text-xs text-slate-500 mt-1">📍 {{ $ev->location }}</div>
                                @endif
                            </div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">
                                {{ optional($ev->happened_at)->format('d M Y H:i') }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</div>
