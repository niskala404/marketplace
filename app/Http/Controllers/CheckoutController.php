<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Product;
use App\Models\FlashSaleItem;
use App\Notifications\OrderPlacedNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\CartPricingService;
use App\Services\ShippingCalculator;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function estimateEtaDays(?float $distanceKm): string
    {
        if ($distanceKm === null) return '2-5 hari';
        if ($distanceKm <= 50) return '1-2 hari';
        if ($distanceKm <= 200) return '2-3 hari';
        if ($distanceKm <= 600) return '3-5 hari';
        return '5-8 hari';
    }

    public function show(Request $request, ShippingCalculator $shipping, VoucherService $vouchers, CartPricingService $pricing)
    {
        $cart = Cart::firstOrCreate(['user_id' => $request->user()->id]);

        if ($items->isEmpty()) return redirect()->route('cart.index')->with('error','Keranjang kosong.');

        $productIds = $items->pluck('product_id')->map(fn($v) => (int)$v)->all();
        $flashPriceMap = FlashSaleItem::promoPriceMap($productIds);

        $addresses = $request->user()->addresses()->orderByDesc('is_default')->get();
        if ($addresses->isEmpty()) {
            return redirect()->route('account.addresses.create')->with('error', 'Tambahkan alamat dulu untuk checkout.');
        }

        $selectedAddressId = (int)($request->query('address_id') ?? $addresses->first()->id);
        $selectedAddress = $addresses->firstWhere('id', $selectedAddressId) ?? $addresses->first();

        $platformVoucherCode = strtoupper(trim((string)$request->query('platform_voucher', '')));
        $shopVoucherCodes = (array) $request->query('shop_voucher', []);
        $shopVoucherCodes = collect($shopVoucherCodes)
            ->map(fn($v) => strtoupper(trim((string)$v)))
            ->filter(fn($v) => $v !== '')
            ->all();

        $groups = $items->groupBy(fn($it) => $it->product->shop_id);

        $shopSummaries = $groups->map(function ($groupItems) use ($shipping, $selectedAddress, $flashPriceMap, $pricing) {
            $shop = $groupItems->first()->product->shop;

            $subtotal = $groupItems->sum(function ($it) use ($flashPriceMap, $pricing) {
                $p = $it->product;
                $unit = $pricing->resolveUnitPrice($p, $it->variant, $flashPriceMap);
                return $unit * (int)$it->qty;
            });

            $ship = $shipping->calculate($selectedAddress, $groupItems);
            $options = $shipping->options($selectedAddress, $groupItems);
            $defaultOpt = collect($options)->firstWhere('code', 'regular') ?? ($options[0] ?? null);

            $shippingFee = (int)($defaultOpt['fee'] ?? $ship['fee']);
            $grandTotal = (int)$subtotal + (int)$shippingFee;

            return [
                'shop' => $shop,
                'subtotal' => (int)$subtotal,
                'shippingFee' => (int)$shippingFee,
                'shippingOptions' => $options,
                'shippingSelected' => $defaultOpt['code'] ?? 'regular',
                'discount' => 0,
                'voucherApplied' => null,
                'grandTotal' => (int)$grandTotal,
                'groupItems' => $groupItems,
                'shippingMeta' => $ship,
            ];
        })->values();

        // Shop voucher per shop
        $voucherErrors = [];
        foreach ($shopSummaries as $idx => $s) {
            $shopId = (int) $s['shop']->id;
            $code = $shopVoucherCodes[$shopId] ?? null;
            if (!$code) continue;

            $prev = $vouchers->preview($code, $request->user()->id, $shopId, (int)$s['subtotal'], (int)$s['shippingFee']);
            if ($prev['error']) {
                $voucherErrors[] = "{$code}: {$prev['error']}";
                continue;
            }

            if ($prev['voucher']) {
                $summary = $shopSummaries->get($idx);
                $summary['discount'] = (int) $prev['discount'];
                $summary['voucherApplied'] = $prev['voucher']->code;
                $summary['grandTotal'] = max(0, (int)$summary['subtotal'] + (int)$summary['shippingFee'] - (int)$summary['discount']);
                $shopSummaries->put($idx, $summary);
            }
        }

        // One platform voucher (best target)
        $platformVoucherError = null;
        if ($platformVoucherCode !== '') {
            $bestIdx = null;
            $bestExtra = 0;
            $bestVoucher = null;

            foreach ($shopSummaries as $idx => $s) {
                $shopId = (int) $s['shop']->id;
                $alreadyDiscount = (int) ($s['discount'] ?? 0);
                $subtotal = (int) ($s['subtotal'] ?? 0);
                $shippingFee = (int) ($s['shippingFee'] ?? 0);

                $prev = $vouchers->preview($platformVoucherCode, $request->user()->id, $shopId, $subtotal, $shippingFee);
                if ($prev['error']) {
                    $platformVoucherError = $prev['error'];
                    continue;
                }
                if (!$prev['voucher']) continue;

                if ($prev['voucher']->shop_id !== null) {
                    $platformVoucherError = 'Voucher ini bukan voucher platform.';
                    continue;
                }

                $maxApplicable = max(0, ($subtotal + $shippingFee) - $alreadyDiscount);
                $extra = min((int)$prev['discount'], $maxApplicable);

                if ($extra > $bestExtra) {
                    $bestExtra = $extra;
                    $bestIdx = $idx;
                    $bestVoucher = $prev['voucher'];
                }
            }

            if ($bestIdx === null) {
                $platformVoucherError = $platformVoucherError ?: 'Voucher platform tidak bisa dipakai untuk checkout ini.';
            } else {
                $summary = $shopSummaries->get($bestIdx);
                $summary['platformDiscount'] = $bestExtra;
                $summary['platformVoucherApplied'] = $bestVoucher->code;
                $summary['discount'] = (int)($summary['discount'] ?? 0) + $bestExtra;

                $summary['voucherApplied'] = trim(implode('+', array_filter([
                    $summary['voucherApplied'] ?? null,
                    $summary['platformVoucherApplied'] ?? null,
                ])));

                $summary['grandTotal'] = max(0, (int)$summary['subtotal'] + (int)$summary['shippingFee'] - (int)$summary['discount']);
                $shopSummaries->put($bestIdx, $summary);
            }
        }

        $subtotalAll = (int) $shopSummaries->sum('subtotal');
        $shippingAll = (int) $shopSummaries->sum('shippingFee');
        $discountAll = (int) $shopSummaries->sum('discount');
        $grandTotalAll = (int) $shopSummaries->sum('grandTotal');

        return view('checkout.show', [
            'items' => $items,
            'addresses' => $addresses,
            'selectedAddress' => $selectedAddress,
            'shopSummaries' => $shopSummaries,
            'subtotalAll' => $subtotalAll,
            'shippingAll' => $shippingAll,
            'discountAll' => $discountAll,
            'grandTotalAll' => $grandTotalAll,
            'platformVoucherCode' => $platformVoucherCode,
            'shopVoucherCodes' => $shopVoucherCodes,
            'voucherErrors' => $voucherErrors,
            'platformVoucherError' => $platformVoucherError,
            'flashPriceMap' => $flashPriceMap,
        ]);
    }

    public function place(Request $request, ShippingCalculator $shipping, VoucherService $vouchers, CartPricingService $pricing)
    {
        $request->validate([
            'address_id' => ['required','integer','exists:addresses,id'],
            'payment_method' => ['required','in:cod,manual_transfer,midtrans'],
            'platform_voucher' => ['nullable','string','max:40'],
            'shop_voucher' => ['nullable','array'],
            'shop_voucher.*' => ['nullable','string','max:40'],
            'shipping_option' => ['required','array'],
        ]);

        $user = $request->user();

        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        if ($items->isEmpty()) return back()->with('error','Keranjang kosong.');

        $address = $user->addresses()->where('id', $request->address_id)->firstOrFail();
        $groups = $items->groupBy(fn($it) => $it->product->shop_id);

        $productIds = $items->pluck('product_id')->map(fn($v) => (int)$v)->all();
        $flashPriceMap = FlashSaleItem::promoPriceMap($productIds);

        $platformVoucherCode = strtoupper(trim((string)$request->input('platform_voucher', '')));
        $shopVoucherCodes = collect((array)$request->input('shop_voucher', []))
            ->map(fn($v) => strtoupper(trim((string)$v)))
            ->filter(fn($v) => $v !== '')
            ->all();

        DB::transaction(function () use ($user, $items, $address, $groups, $request, $cart, $shipping, $vouchers, $platformVoucherCode, $shopVoucherCodes, $flashPriceMap, $pricing) {

            // cek stok final
            foreach ($items as $it) {
                $availableStock = $it->variant ? (int)$it->variant->stock : (int)$it->product->stock;
                if ($availableStock < (int)$it->qty) {
                    abort(400, 'Stok berubah, silakan refresh.');
                }
            }

            // lock flash sale items to avoid oversell quota
            $flashItemsLocked = FlashSaleItem::query()
                ->whereIn('product_id', array_keys($flashPriceMap))
                ->whereHas('flashSale', fn($q) => $q->activeNow())
                ->lockForUpdate()
                ->get()
                ->keyBy('product_id');

            // validate quota
            foreach ($items as $it) {
                $p = $it->product;
                if (!array_key_exists($p->id, $flashPriceMap)) continue;

                $fsi = $flashItemsLocked->get($p->id);
                if (!$fsi || !$fsi->is_active) {
                    abort(400, 'Flash sale item tidak aktif lagi. Silakan refresh.');
                }

                $rem = $fsi->remainingQuota();
                if ($rem !== null && (int)$it->qty > (int)$rem) {
                    abort(400, "Kuota flash sale untuk {$p->name} tersisa {$rem}. Silakan kurangi jumlah.");
                }
            }

            $addressSnapshot = json_encode([
                'label' => $address->label,
                'recipient_name' => $address->recipient_name,
                'phone' => $address->phone,
                'province' => $address->province,
                'city' => $address->city,
                'district' => $address->district,
                'village' => $address->village,
                'postal_code' => $address->postal_code,
                'full_address' => $address->full_address,
                'detail_address' => $address->detail_address,
                'latitude' => $address->latitude,
                'longitude' => $address->longitude,
            ], JSON_UNESCAPED_UNICODE);

            // 1) Pre-calc per shop subtotal, shipping, and shop voucher
            $calc = [];
            foreach ($groups as $shopId => $shopItems) {
                $shopId = (int) $shopId;

                $subtotal = (int) $shopItems->sum(function ($it) use ($flashPriceMap, $pricing) {
                    $p = $it->product;
                    $unit = $pricing->resolveUnitPrice($p, $it->variant, $flashPriceMap);
                    return $unit * (int)$it->qty;
                });

                $options = $shipping->options($address, $shopItems);
                $selectedCode = (string) data_get($request->input('shipping_option', []), (string)$shopId, 'regular');

                $selected = collect($options)->firstWhere('code', $selectedCode)
                    ?? collect($options)->firstWhere('code', 'regular')
                    ?? ($options[0] ?? null);

                $ship = $shipping->calculate($address, $shopItems);
                $shippingFee = (int) ($selected['fee'] ?? $ship['fee']);

                $shopCode = $shopVoucherCodes[$shopId] ?? null;
                $shopVoucherModel = null;
                $shopDiscount = 0;
                $voucherAppliedCodes = [];

                if ($shopCode) {
                    $prevShop = $vouchers->preview($shopCode, $user->id, $shopId, $subtotal, $shippingFee);
                    if ($prevShop['voucher'] && !$prevShop['error']) {
                        $shopVoucherModel = $prevShop['voucher'];
                        $shopDiscount = min((int)$prevShop['discount'], ($subtotal + $shippingFee));
                        $voucherAppliedCodes[] = $shopVoucherModel->code;
                    }
                }

                $calc[$shopId] = [
                    'subtotal' => $subtotal,
                    'shippingFee' => $shippingFee,
                    'shippingSelected' => (array) $selected,
                    'shippingMeta' => $ship,
                    'voucherCodes' => $voucherAppliedCodes,
                    'shopVoucherModel' => $shopVoucherModel,
                    'shopDiscount' => $shopDiscount,
                ];
            }

            // 2) Choose ONE platform voucher target order
            $platformTargetShopId = null;
            $platformTargetDiscount = 0;
            $platformVoucherModel = null;

            if ($platformVoucherCode !== '') {
                foreach ($calc as $shopId => $c) {
                    $prevPlat = $vouchers->preview($platformVoucherCode, $user->id, (int)$shopId, (int)$c['subtotal'], (int)$c['shippingFee']);
                    if ($prevPlat['voucher'] && !$prevPlat['error'] && $prevPlat['voucher']->shop_id === null) {
                        $maxApplicable = max(0, ((int)$c['subtotal'] + (int)$c['shippingFee']) - (int)$c['shopDiscount']);
                        $extra = min((int)$prevPlat['discount'], $maxApplicable);

                        if ($extra > $platformTargetDiscount) {
                            $platformTargetDiscount = $extra;
                            $platformTargetShopId = (int)$shopId;
                            $platformVoucherModel = $prevPlat['voucher'];
                        }
                    }
                }
            }

            // 3) Create orders per shop + redeem vouchers
            foreach ($groups as $shopId => $shopItems) {
                $shopId = (int) $shopId;
                $c = $calc[$shopId] ?? null;
                if (!$c) abort(400, 'Gagal menghitung checkout. Silakan ulangi.');

                $subtotal = (int)$c['subtotal'];
                $shippingFee = (int)$c['shippingFee'];
                $selected = (array)$c['shippingSelected'];

                $discount = (int)$c['shopDiscount'];
                $voucherAppliedCodes = (array)$c['voucherCodes'];

                if ($platformTargetShopId !== null && $shopId === (int)$platformTargetShopId && $platformVoucherModel && $platformTargetDiscount > 0) {
                    $discount += (int)$platformTargetDiscount;
                    $voucherAppliedCodes[] = $platformVoucherModel->code;
                }

                $grandTotal = max(0, ($subtotal + $shippingFee) - $discount);

                $order = Order::create([
                    'order_no' => 'ILMI-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                    'user_id' => $user->id,
                    'shop_id' => (int)$shopId,
                    'status' => $request->payment_method === 'cod' ? 'processing' : 'pending',
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'shipping_courier' => $selected['courier'] ?? null,
                    'shipping_service' => $selected['service'] ?? null,
                    'shipping_etd' => $selected['etd'] ?? null,
                    'voucher_code' => $voucherAppliedCodes ? implode('+', array_values(array_unique($voucherAppliedCodes))) : null,
                    'discount_amount' => (int)$discount,
                    'grand_total' => (int)$grandTotal,
                    'payment_method' => $request->payment_method,
                    'payment_gateway' => $request->payment_method === 'midtrans' ? 'midtrans' : null,
                    'expires_at' => $request->payment_method === 'manual_transfer'
                        ? now()->addHours((int)config('ilmishop.manual_transfer_expiry_hours', 24))
                        : ($request->payment_method === 'midtrans'
                            ? now()->addMinutes((int)config('ilmishop.midtrans_expiry_minutes', 30))
                            : null),
                    'shipping_address_snapshot' => $addressSnapshot,
                ]);

                $order->logShipmentEvent(
                    'pending',
                    'Pesanan dibuat',
                    'Pesanan berhasil dibuat dan menunggu proses pembayaran.',
                    now(),
                    'order_created'
                );

                if ($order->status === 'processing') {
                    $order->logShipmentEvent(
                        'processing',
                        'Pesanan diproses',
                        'Pembayaran COD, pesanan langsung diproses penjual.',
                        now(),
                        'processing'
                    );
                }

                // redeem shop voucher
                if ($c['shopVoucherModel'] && (int)$c['shopDiscount'] > 0) {
                    $vouchers->redeem($c['shopVoucherModel'], $user->id, (int)$order->id, (int)$c['shopDiscount']);
                }

                // redeem platform voucher (only for target)
                if ($platformTargetShopId !== null && $shopId === (int)$platformTargetShopId && $platformVoucherModel && $platformTargetDiscount > 0) {
                    $vouchers->redeem($platformVoucherModel, $user->id, (int)$order->id, (int)$platformTargetDiscount);
                }

                // notify seller
                $shop = Shop::with('user')->find((int)$shopId);
                if ($shop?->user) {
                    $shop->user->notify(new OrderPlacedNotification($order));
                }

                foreach ($shopItems as $it) {
                    $unit = $pricing->resolveUnitPrice($it->product, $it->variant, $flashPriceMap);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $it->product->id,
                        'product_name' => $it->product->name,
                        'product_variant_id' => $it->variant?->id,
                        'variant_name' => $it->variant?->name,

                        'price' => (int)$unit,
                        'qty' => (int)$it->qty,
                        'line_total' => ((int)$unit * (int)$it->qty),
                    ]);

                    // increment sold for flash sale item if applicable
                    if (array_key_exists($it->product->id, $flashPriceMap)) {
                        $fsi = $flashItemsLocked->get($it->product->id);
                        if ($fsi) $fsi->increment('sold', (int)$it->qty);
                    }

                    $it->product->decrement('stock', (int)$it->qty);
                    if ($it->variant) {
                        $it->variant->decrement('stock', (int)$it->qty);
                    }
                }

                // auto thank-you message
                if ($shop?->user) {
                    $conv = Conversation::firstOrCreate(
                        ['shop_id' => (int)$shopId, 'buyer_id' => $user->id],
                        ['last_message_at' => now()]
                    );

                    $names = $shopItems->map(fn($it) => $it->product->name.' x'.$it->qty)->values()->all();
                    $eta = $this->estimateEtaDays(null);

                    $body = "Terima kasih sudah berbelanja di {$shop->name}!\n".
                        "Pesanan kamu: \n- ".implode("\n- ", $names)."\n\n".
                        "Estimasi sampai: {$eta}.\n".
                        "Kalau ada pertanyaan, chat kami di sini ya 😊";

                    Message::create([
                        'conversation_id' => $conv->id,
                        'sender_id' => $shop->user->id,
                        'body' => $body,
                    ]);

                    $conv->update(['last_message_at' => now()]);
                }
            }

            $cart->items()->delete();
        });

        if ($request->payment_method === 'midtrans') {
            $pending = $user->orders()
                ->where('status', 'pending')
                ->where('payment_method', 'midtrans')
                ->latest('id')
                ->first();

            if ($pending) {
                return redirect()->route('payments.midtrans.pay', $pending)
                    ->with('success', 'Pesanan dibuat. Silakan lanjut bayar.');
            }
        }

        return redirect()->route('orders.mine')->with('success','Pesanan berhasil dibuat (otomatis dipisah per toko).');
    }

    public function myOrders(Request $request)
    {
        $orders = $request->user()->orders()->with('shop')->latest()->paginate(10);
        return view('orders.mine', compact('orders'));
    }

    public function showOrder(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);
        $order->load(['shop', 'items.product', 'items.review', 'dispute', 'shipmentEvents']);
        return view('orders.show', compact('order'));
    }

    public function confirmReceived(Request $request, Order $order)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum bisa dikonfirmasi diterima.');
        }

        $old = $order->status;

        DB::transaction(function () use ($order) {
            $fresh = Order::query()->whereKey($order->getKey())->lockForUpdate()->first();
            if (!$fresh || $fresh->status !== 'shipped') return;

            $fresh->forceFill([
                'status' => 'completed',
                'received_at' => $fresh->received_at ?: now(),
                'completed_at' => $fresh->completed_at ?: now(),
            ])->save();
            $fresh->logShipmentEvent('completed', 'Pesanan diterima pembeli', 'Pembeli sudah mengonfirmasi paket diterima.', now(), 'received');
            $fresh->logShipmentEvent('completed', 'Pesanan selesai', 'Transaksi selesai dan dana diproses ke penjual.', now(), 'completed');

            $fresh->settleCommissionIfNeeded();
        });

        $order->refresh()->loadMissing('shop.user');
        if ($order->shop?->user && $old !== $order->status) {
            $order->shop->user->notify(new \App\Notifications\OrderStatusChangedNotification($order, $old, $order->status));
        }

        return back()->with('success', 'Pesanan dikonfirmasi diterima. Terima kasih!');
    }

    public function cancel(Request $request, Order $order, VoucherService $vouchers)
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        if (!($order->status === 'pending' && $order->payment_method === 'manual_transfer' && !$order->payment_verified_at)) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan.');
        }

        $old = $order->status;

        DB::transaction(function () use ($order, $vouchers) {
            $locked = Order::query()->whereKey($order->getKey())->lockForUpdate()->first();
            if (!$locked) return;

            if (!($locked->status === 'pending' && $locked->payment_method === 'manual_transfer' && !$locked->payment_verified_at)) {
                return;
            }

            $locked->loadMissing('items');

            foreach ($locked->items as $item) {
                Product::query()->whereKey($item->product_id)->increment('stock', (int)$item->qty);
                if ($item->product_variant_id) {
                    DB::table('product_variants')
                        ->where('id', (int) $item->product_variant_id)
                        ->increment('stock', (int) $item->qty);
                }
            }

            $vouchers->rollbackForOrder((int)$locked->id);

            $locked->forceFill([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason' => 'buyer_cancelled',
            ])->save();
            $locked->logShipmentEvent('cancelled', 'Pesanan dibatalkan pembeli', 'Pesanan dibatalkan sebelum pembayaran diverifikasi.', now(), 'cancelled');
        });

        $order->refresh()->loadMissing(['shop.user', 'user']);
        if ($old !== $order->status) {
            if ($order->shop?->user) $order->shop->user->notify(new OrderStatusChangedNotification($order, $old, $order->status));
            if ($order->user) $order->user->notify(new OrderStatusChangedNotification($order, $old, $order->status));
        }

        return back()->with('success', 'Pesanan berhasil dibatalkan. Stok dikembalikan.');
    }

}
