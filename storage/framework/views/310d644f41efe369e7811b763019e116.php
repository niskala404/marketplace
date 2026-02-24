<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Checkout</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-3">
        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Alamat Pengiriman</div>

            <div class="space-y-2">
                <select id="address_select" class="w-full rounded-xl border-slate-200">
                    <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($a->id); ?>" <?php if($selectedAddress->id === $a->id): echo 'selected'; endif; ?>>
                            <?php echo e($a->label); ?> — <?php echo e($a->recipient_name); ?> (<?php echo e($a->phone); ?>) — <?php echo e($a->full_address); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <div class="text-sm text-slate-500">
                    Ongkir dihitung berdasarkan alamat yang dipilih. Kelola alamat di menu
                    <a class="text-rose-600 font-semibold" href="<?php echo e(route('account.addresses.index')); ?>">Alamat</a>.
                </div>
            </div>

            <div class="mt-4">
                <div class="font-bold mb-2">Voucher (opsional)</div>
                <form method="GET" action="<?php echo e(route('checkout.show')); ?>" class="space-y-3">
                    <input type="hidden" name="address_id" value="<?php echo e($selectedAddress->id); ?>">

                    <div>
                        <label class="text-sm font-semibold">Voucher Platform</label>
                        <div class="mt-1 flex gap-2">
                            <input name="platform_voucher" value="<?php echo e($platformVoucherCode ?? ''); ?>" placeholder="contoh: ILMIHEMAT"
                                   class="flex-1 rounded-xl border-slate-200">
                            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Hitung</button>
                        </div>
                        <?php if(!empty($platformVoucherError)): ?>
                            <div class="mt-1 text-sm text-rose-600"><?php echo e($platformVoucherError); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="border rounded-2xl p-3 bg-slate-50">
                        <div class="text-sm font-semibold">Voucher Toko</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            <?php $__currentLoopData = $shopSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="rounded-2xl border bg-white p-3">
                                    <div class="font-bold text-sm"><?php echo e($s['shop']->name); ?></div>
                                    <input
                                        class="mt-2 w-full rounded-xl border-slate-200"
                                        name="shop_voucher[<?php echo e($s['shop']->id); ?>]"
                                        value="<?php echo e($shopVoucherCodes[$s['shop']->id] ?? ''); ?>"
                                        placeholder="kode voucher toko"
                                    >
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php if(!empty($voucherErrors) && count($voucherErrors)): ?>
                            <div class="mt-2 text-sm text-rose-600 space-y-1">
                                <?php $__currentLoopData = $voucherErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div>• <?php echo e($e); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                        <div class="text-xs text-slate-500 mt-2">Voucher toko bisa dipakai per toko. Voucher platform dipakai 1x untuk toko dengan diskon terbesar.</div>
                    </div>
                </form>
            </div>

            <form method="POST" action="<?php echo e(route('checkout.place')); ?>" class="space-y-4 mt-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="address_id" value="<?php echo e($selectedAddress->id); ?>" id="address_id_hidden">
                <input type="hidden" name="platform_voucher" value="<?php echo e($platformVoucherCode ?? ''); ?>">
                <?php $__currentLoopData = ($shopVoucherCodes ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sid => $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="shop_voucher[<?php echo e((int)$sid); ?>]" value="<?php echo e($code); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <div class="font-bold pt-2">Metode Pembayaran</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <span class="font-semibold">COD</span>
                        <div class="text-sm text-slate-500">Bayar saat barang diterima</div>
                    </label>
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="manual_transfer">
                        <span class="font-semibold">Transfer Manual</span>
                        <div class="text-sm text-slate-500">Upload bukti transfer setelah order dibuat</div>
                    </label>
                    <label class="border rounded-2xl p-4 bg-slate-50">
                        <input type="radio" name="payment_method" value="midtrans">
                        <span class="font-semibold">Pembayaran Otomatis (Midtrans)</span>
                        <div class="text-sm text-slate-500">VA / QRIS / e-Wallet (otomatis terverifikasi)</div>
                    </label>
                </div>

                <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">
                    Buat Pesanan
                </button>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Item (otomatis dipisah per toko)</div>

            <div class="space-y-4">
                <?php $__currentLoopData = $shopSummaries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="font-bold"><?php echo e($s['shop']->name); ?></div>
                            <div class="text-xs text-slate-500">
                                Berat: <?php echo e(number_format($s['shippingMeta']['total_weight_grams']/1000, 2, ',', '.')); ?> kg • Tarif: <?php echo e($s['shippingMeta']['rate']->name ?? 'Default'); ?>

                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="text-sm font-semibold">Pilih Pengiriman</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-2">
                                <?php $__currentLoopData = ($s['shippingOptions'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="border rounded-2xl p-3 bg-slate-50 cursor-pointer">
                                        <div class="flex items-start gap-2">
                                            <input type="radio"
                                                   name="shipping_option[<?php echo e($s['shop']->id); ?>]"
                                                   value="<?php echo e($opt['code']); ?>"
                                                   <?php if(($s['shippingSelected'] ?? 'regular') === $opt['code']): echo 'checked'; endif; ?>>
                                            <div class="flex-1">
                                                <div class="font-semibold"><?php echo e($opt['label']); ?></div>
                                                <div class="text-xs text-slate-500">Estimasi: <?php echo e($opt['etd']); ?></div>
                                                <div class="text-sm font-black">Rp <?php echo e(number_format($opt['fee'],0,',','.')); ?></div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <div class="text-xs text-slate-500 mt-2">* Total di ringkasan menggunakan opsi default (Reguler). Saat submit, ongkir mengikuti pilihan kamu.</div>
                        </div>

                        <div class="mt-2 space-y-2">
                            <?php $__currentLoopData = $s['groupItems']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex justify-between text-sm">
                                    <div><?php echo e($it->product->name); ?> × <?php echo e($it->qty); ?></div>
                                    <?php
                                        $flashPriceMap = $flashPriceMap ?? [];
                                        $p = $it->product;
                                        $unit = $flashPriceMap[$p->id] ?? (method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price);
                                      ?>
                                    <div class="font-semibold">Rp <?php echo e(number_format($unit * (int)$it->qty,0,',','.')); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="mt-3 pt-3 border-t text-sm space-y-1">
                            <div class="flex justify-between"><span>Subtotal</span><span class="font-semibold">Rp <?php echo e(number_format($s['subtotal'],0,',','.')); ?></span></div>
                            <div class="flex justify-between"><span>Ongkir</span><span class="font-semibold">Rp <?php echo e(number_format($s['shippingFee'],0,',','.')); ?></span></div>
                            <?php if(!empty($s['discount']) && $s['discount'] > 0): ?>
                                <?php if(!empty($s['shippingDiscount']) && $s['shippingDiscount'] > 0): ?>
                                    <div class="flex justify-between"><span>Diskon Ongkir (<?php echo e($s['voucherApplied']); ?>)</span><span class="font-semibold text-emerald-700">- Rp <?php echo e(number_format($s['discount'],0,',','.')); ?></span></div>
                                <?php else: ?>
                                    <div class="flex justify-between"><span>Diskon (<?php echo e($s['voucherApplied']); ?>)</span><span class="font-semibold text-emerald-700">- Rp <?php echo e(number_format($s['discount'],0,',','.')); ?></span></div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="flex justify-between"><span class="font-bold">Total toko</span><span class="font-black text-rose-600">Rp <?php echo e(number_format($s['grandTotal'],0,',','.')); ?></span></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        </form>
    </div>

    <div class="bg-white border rounded-2xl p-5 h-fit">
        <div class="font-bold text-lg">Ringkasan</div>
        <div class="mt-3 flex justify-between">
            <span>Subtotal</span>
            <span class="font-semibold">Rp <?php echo e(number_format($subtotalAll,0,',','.')); ?></span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Ongkir (per toko)</span>
            <span class="font-semibold">Rp <?php echo e(number_format($shippingAll,0,',','.')); ?></span>
        </div>
        <?php if(!empty($discountAll) && $discountAll > 0): ?>
            <div class="mt-2 flex justify-between">
                <span>Diskon</span>
                <span class="font-semibold text-emerald-700">- Rp <?php echo e(number_format($discountAll,0,',','.')); ?></span>
            </div>
        <?php endif; ?>
        <div class="mt-3 pt-3 border-t flex justify-between">
            <span class="font-bold">Total</span>
            <span class="font-black text-rose-600">Rp <?php echo e(number_format($grandTotalAll,0,',','.')); ?></span>
        </div>
    </div>
</div>

<script>
    const sel = document.getElementById('address_select');
    const hidden = document.getElementById('address_id_hidden');
    sel.addEventListener('change', () => {
        hidden.value = sel.value;
        const url = new URL(window.location.href);
        url.searchParams.set('address_id', sel.value);
        window.location.href = url.toString();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/checkout/show.blade.php ENDPATH**/ ?>