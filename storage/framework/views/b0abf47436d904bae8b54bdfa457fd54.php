<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Detail Pesanan</h1>
        <div class="text-slate-500 text-sm"><?php echo e($order->order_no); ?> • <?php echo e($order->created_at->format('d M Y H:i')); ?></div>
    </div>
    <a href="<?php echo e(route('orders.mine')); ?>" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <?php if (isset($component)) { $__componentOriginal1559e45aa3b06c08378a24b14c08207e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1559e45aa3b06c08378a24b14c08207e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.order-timeline','data' => ['order' => $order]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('order-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['order' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($order)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1559e45aa3b06c08378a24b14c08207e)): ?>
<?php $attributes = $__attributesOriginal1559e45aa3b06c08378a24b14c08207e; ?>
<?php unset($__attributesOriginal1559e45aa3b06c08378a24b14c08207e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1559e45aa3b06c08378a24b14c08207e)): ?>
<?php $component = $__componentOriginal1559e45aa3b06c08378a24b14c08207e; ?>
<?php unset($__componentOriginal1559e45aa3b06c08378a24b14c08207e); ?>
<?php endif; ?>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-2">Toko</div>
            <div class="text-slate-700"><?php echo e($order->shop->name); ?></div>
            <div class="mt-3 text-sm">Status: <span class="font-semibold"><?php echo e($order->status); ?></span></div>
            <div class="text-sm">Metode bayar: <span class="font-semibold"><?php echo e($order->payment_method); ?></span></div>
            <div class="text-sm">Pengiriman: <span class="font-semibold"><?php echo e($order->shipping_courier ? $order->shipping_courier.' ' : ''); ?><?php echo e($order->shipping_service ?? '-'); ?></span>
                <?php if($order->shipping_etd): ?>
                    <span class="text-xs text-slate-500">(<?php echo e($order->shipping_etd); ?>)</span>
                <?php endif; ?>
            </div>
            <?php if($order->status === 'pending' && $order->payment_method === 'manual_transfer' && $order->expires_at): ?>
                <div class="text-sm mt-1">
                    Batas bayar:
                    <span class="font-semibold"><?php echo e($order->expires_at->format('d M Y H:i')); ?></span>
                    <span id="payCountdown" class="ml-2 text-rose-600 font-bold" data-exp="<?php echo e($order->expires_at->toIso8601String()); ?>"></span>
                </div>
                <div class="text-xs text-slate-500 mt-1">Jika melewati batas waktu, pesanan akan otomatis dibatalkan.</div>
            <?php endif; ?>
            <?php if($order->paid_at): ?>
                <div class="text-sm">Dibayar: <span class="font-semibold"><?php echo e($order->paid_at->format('d M Y H:i')); ?></span></div>
            <?php endif; ?>
            <?php if($order->tracking_no): ?>
                <div class="mt-2 text-sm">Resi: <span class="font-semibold"><?php echo e($order->tracking_no); ?></span></div>
            <?php endif; ?>
            <?php if($order->shipped_at): ?>
                <div class="text-sm">Dikirim: <span class="font-semibold"><?php echo e($order->shipped_at->format('d M Y H:i')); ?></span></div>
            <?php endif; ?>
            <?php if($order->delivered_at): ?>
                <div class="text-sm">Sampai: <span class="font-semibold"><?php echo e($order->delivered_at->format('d M Y H:i')); ?></span></div>
            <?php endif; ?>
            <?php if($order->received_at): ?>
                <div class="text-sm">Diterima: <span class="font-semibold"><?php echo e($order->received_at->format('d M Y H:i')); ?></span></div>
            <?php endif; ?>
            <?php if($order->completed_at): ?>
                <div class="text-sm">Selesai: <span class="font-semibold"><?php echo e($order->completed_at->format('d M Y H:i')); ?></span></div>
            <?php endif; ?>

            <?php if($order->status === 'shipped' && !$order->received_at): ?>
                <form class="mt-4" method="POST" action="<?php echo e(route('orders.confirm_received', $order)); ?>" onsubmit="return confirm('Konfirmasi pesanan sudah diterima? Setelah dikonfirmasi, pesanan akan selesai dan dana seller dapat diproses.');">
                    <?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Pesanan Diterima</button>
                </form>
            <?php endif; ?>

            <?php if($order->status === 'pending' && $order->payment_method === 'manual_transfer' && !$order->payment_verified_at): ?>
                <form class="mt-3" method="POST" action="<?php echo e(route('orders.cancel', $order)); ?>" onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan.');">
                    <?php echo csrf_field(); ?>
                    <button class="px-4 py-2 rounded-xl bg-slate-200 text-slate-900 font-bold">Batalkan Pesanan</button>
                </form>
            <?php endif; ?>

            <div class="mt-4">
                <?php if($order->dispute): ?>
                    <a href="<?php echo e(route('disputes.show', $order->dispute)); ?>" class="inline-flex px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Lihat Dispute</a>
                <?php elseif(in_array($order->status, ['shipped','completed'], true)): ?>
                    <a href="<?php echo e(route('disputes.create', $order)); ?>" class="inline-flex px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Ajukan Dispute / Retur</a>
                <?php endif; ?>
            </div>
        </div>

        <?php if($order->payment_method === 'manual_transfer'): ?>
            <div class="bg-white border rounded-2xl p-5">
                <div class="font-bold mb-2">Bukti Transfer</div>

                <?php if($order->payment_proof_path): ?>
                    <img class="w-full max-w-md rounded-2xl border" src="<?php echo e(asset('storage/'.$order->payment_proof_path)); ?>" alt="Bukti transfer">
                    <div class="text-sm text-slate-500 mt-2">Jika bukti salah, kamu bisa unggah ulang selama status masih <span class="font-semibold">pending</span>.</div>
                <?php else: ?>
                    <div class="text-sm text-slate-600">Belum ada bukti transfer.</div>
                <?php endif; ?>

                <?php if($order->status === 'pending'): ?>
                    <form class="mt-4" method="POST" enctype="multipart/form-data" action="<?php echo e(route('orders.payment_proof.upload', $order)); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="payment_proof" accept="image/*" required>
                        <button class="mt-3 px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">
                            Upload Bukti Transfer
                        </button>
                    </form>
                <?php elseif($order->status === 'cancelled'): ?>
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan dibatalkan<?php echo e($order->cancel_reason === 'expired_unpaid' ? ' (melewati batas bayar).' : '.'); ?></div>
                <?php elseif($order->status === 'refunded'): ?>
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan direfund.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if($order->payment_method === 'midtrans'): ?>
            <div class="bg-white border rounded-2xl p-5">
                <div class="font-bold mb-2">Pembayaran (Midtrans)</div>

                <div class="text-sm text-slate-600">
                    Status pembayaran: <span class="font-semibold"><?php echo e($order->payment_status ?? ($order->status === 'pending' ? 'pending' : $order->status)); ?></span>
                </div>

                <?php if($order->status === 'pending'): ?>
                    <?php if($order->expires_at): ?>
                        <div class="mt-2 text-sm text-rose-600">
                            Batas bayar: <span class="font-semibold"><?php echo e($order->expires_at->format('d M Y H:i')); ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo e(route('payments.midtrans.pay', $order)); ?>" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">
                        Bayar Sekarang
                    </a>
                <?php elseif($order->status === 'cancelled'): ?>
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan dibatalkan.</div>
                <?php elseif($order->status === 'refunded'): ?>
                    <div class="mt-3 text-sm text-rose-600 font-semibold">Pesanan direfund.</div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-3">Item</div>
            <div class="space-y-3">
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold">
                                <?php echo e($it->product_name); ?>

                                <?php if($it->variant_name): ?>
                                    <span class="text-xs text-slate-500">(<?php echo e($it->variant_name); ?>)</span>
                                <?php endif; ?>
                                × <?php echo e($it->qty); ?>

                            </div>
                            <div class="font-black text-rose-600">Rp <?php echo e(number_format($it->line_total,0,',','.')); ?></div>
                        </div>

                        <?php if($order->status === 'completed'): ?>
                            <?php if($it->review): ?>
                                <div class="mt-3 text-sm">
                                    <div class="font-semibold">Ulasan kamu</div>
                                    <div>⭐ <?php echo e($it->review->rating); ?> / 5</div>
                                    <?php if($it->review->comment): ?>
                                        <div class="text-slate-700 whitespace-pre-line mt-1"><?php echo e($it->review->comment); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <form class="mt-3" method="POST" action="<?php echo e(route('orders.items.review', [$order, $it])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="text-sm font-semibold">Rating</label>
                                            <select name="rating" class="w-full rounded-xl border-slate-200" required>
                                                <option value="5">5 - Sangat puas</option>
                                                <option value="4">4 - Puas</option>
                                                <option value="3">3 - Cukup</option>
                                                <option value="2">2 - Kurang</option>
                                                <option value="1">1 - Buruk</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="text-sm font-semibold">Komentar (opsional)</label>
                                            <input name="comment" class="w-full rounded-xl border-slate-200" placeholder="Tulis pengalamanmu...">
                                        </div>
                                    </div>
                                    <button class="mt-3 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Kirim Ulasan</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="mt-2 text-xs text-slate-500">Ulasan bisa diberikan setelah status pesanan <span class="font-semibold">completed</span>.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="bg-white border rounded-2xl p-5">
            <div class="font-bold mb-2">Alamat Pengiriman</div>
            <?php ($addr = json_decode($order->shipping_address_snapshot, true) ?: []); ?>
            <div class="text-slate-700">
                <div class="font-semibold"><?php echo e($addr['recipient_name'] ?? '-'); ?> (<?php echo e($addr['phone'] ?? '-'); ?>)</div>
                <div class="text-sm text-slate-600 mt-1"><?php echo e($addr['full_address'] ?? '-'); ?></div>
                <?php if(!empty($addr['detail_address'])): ?>
                    <div class="text-sm text-slate-500 mt-1">Patokan: <?php echo e($addr['detail_address']); ?></div>
                <?php endif; ?>
                <div class="text-sm text-slate-500"><?php echo e($addr['village'] ?? ''); ?> <?php echo e($addr['district'] ?? ''); ?> <?php echo e($addr['city'] ?? ''); ?> <?php echo e($addr['province'] ?? ''); ?> <?php echo e($addr['postal_code'] ?? ''); ?></div>
                <?php if(!empty($addr['latitude']) && !empty($addr['longitude'])): ?>
                    <a target="_blank" rel="noopener" class="inline-block mt-2 text-sm text-rose-600 font-semibold hover:underline"
                       href="https://www.google.com/maps?q=<?php echo e($addr['latitude']); ?>,<?php echo e($addr['longitude']); ?>">
                        Lihat lokasi pengiriman
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded-2xl p-5 h-fit">
        <div class="font-bold text-lg">Ringkasan</div>
        <div class="mt-3 flex justify-between">
            <span>Subtotal</span>
            <span class="font-semibold">Rp <?php echo e(number_format($order->subtotal,0,',','.')); ?></span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Pengiriman</span>
            <span class="font-semibold">
                <?php echo e($order->shipping_courier ? $order->shipping_courier.' ' : ''); ?><?php echo e($order->shipping_service ?? '-'); ?>

                <?php if($order->shipping_etd): ?>
                    <span class="text-xs text-slate-500">(<?php echo e($order->shipping_etd); ?>)</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="mt-2 flex justify-between">
            <span>Ongkir</span>
            <span class="font-semibold">Rp <?php echo e(number_format($order->shipping_fee,0,',','.')); ?></span>
        </div>
        <?php if(($order->shipping_discount ?? 0) > 0): ?>
            <div class="mt-2 flex justify-between">
                <span>Diskon Ongkir <?php if($order->voucher_code): ?> (<?php echo e($order->voucher_code); ?>) <?php endif; ?></span>
                <span class="font-semibold text-emerald-700">- Rp <?php echo e(number_format($order->shipping_discount,0,',','.')); ?></span>
            </div>
        <?php endif; ?>
        <?php if($order->tracking_no): ?>
            <div class="mt-2 flex justify-between">
                <span>Resi</span>
                <span class="font-semibold"><?php echo e($order->tracking_no); ?></span>
            </div>
        <?php endif; ?>
        <?php if($order->discount_amount > 0): ?>
            <div class="mt-2 flex justify-between">
                <span>Diskon <?php if($order->voucher_code): ?> (<?php echo e($order->voucher_code); ?>) <?php endif; ?></span>
                <span class="font-semibold text-emerald-700">- Rp <?php echo e(number_format($order->discount_amount,0,',','.')); ?></span>
            </div>
        <?php endif; ?>
        <div class="mt-3 pt-3 border-t flex justify-between">
            <span class="font-bold">Total</span>
            <span class="font-black text-rose-600">Rp <?php echo e(number_format($order->grand_total,0,',','.')); ?></span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(() => {
  const el = document.getElementById('payCountdown');
  if (!el) return;
  const exp = el.getAttribute('data-exp');
  const expAt = new Date(exp);
  const pad = (n) => String(n).padStart(2, '0');

  const tick = () => {
    const now = new Date();
    const diff = expAt.getTime() - now.getTime();
    if (diff <= 0) {
      el.textContent = '(expired)';
      return;
    }
    const totalSec = Math.floor(diff / 1000);
    const h = Math.floor(totalSec / 3600);
    const m = Math.floor((totalSec % 3600) / 60);
    const s = totalSec % 60;
    el.textContent = `(${pad(h)}:${pad(m)}:${pad(s)} tersisa)`;
    setTimeout(tick, 1000);
  };

  tick();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/orders/show.blade.php ENDPATH**/ ?>