<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-2">Detail Pesanan</h1>

<div class="mb-4">
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
</div>

<div class="bg-white border rounded-2xl p-5">
    <div class="font-bold"><?php echo e($order->order_no); ?></div>
    <div class="text-sm text-slate-500">Status: <span class="font-semibold"><?php echo e($order->status); ?></span></div>
    <div class="text-sm text-slate-500">Metode bayar: <span class="font-semibold"><?php echo e($order->payment_method); ?></span></div>
    <div class="text-sm text-slate-500">Pengiriman: <span class="font-semibold"><?php echo e($order->shipping_courier ? $order->shipping_courier.' ' : ''); ?><?php echo e($order->shipping_service ?? '-'); ?></span>
        <?php if($order->shipping_etd): ?>
            <span class="text-xs text-slate-500">(<?php echo e($order->shipping_etd); ?>)</span>
        <?php endif; ?>
    </div>
    <?php if($order->payment_method === 'manual_transfer' && $order->payment_proof_path): ?>
        <div class="mt-3">
            <div class="text-sm font-semibold mb-2">Bukti Transfer</div>
            <img class="w-full max-w-md rounded-2xl border" src="<?php echo e(asset('storage/'.$order->payment_proof_path)); ?>" alt="Bukti transfer">
        </div>
    <?php endif; ?>

    <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2 text-sm text-slate-600">
        <?php if($order->paid_at): ?>
            <div>Dibayar: <span class="font-semibold"><?php echo e($order->paid_at->format('d M Y H:i')); ?></span></div>
        <?php endif; ?>
        <?php if($order->shipped_at): ?>
            <div>Dikirim: <span class="font-semibold"><?php echo e($order->shipped_at->format('d M Y H:i')); ?></span></div>
        <?php endif; ?>
        <?php if($order->delivered_at): ?>
            <div>Sampai: <span class="font-semibold"><?php echo e($order->delivered_at->format('d M Y H:i')); ?></span></div>
        <?php endif; ?>
        <?php if($order->received_at): ?>
            <div>Diterima: <span class="font-semibold"><?php echo e($order->received_at->format('d M Y H:i')); ?></span></div>
        <?php endif; ?>
    </div>

    <?php if(in_array($order->status, ['paid','processing'], true)): ?>
        <form class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="<?php echo e(route('seller.orders.status',$order)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="status" value="shipped" />
            <div class="md:col-span-2">
                <input name="tracking_no" value="<?php echo e($order->tracking_no); ?>" placeholder="Masukkan nomor resi" required
                       class="w-full rounded-xl border-slate-200" />
            </div>
            <button class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Kirim Pesanan</button>
        </form>
        <div class="text-xs text-slate-500 mt-2">Setelah dikirim, pembeli bisa melacak resi dan mengonfirmasi “Pesanan Diterima”.</div>
    <?php else: ?>
        <form class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="<?php echo e(route('seller.orders.status',$order)); ?>">
            <?php echo csrf_field(); ?>
            <select name="status" class="rounded-xl border-slate-200">
                <?php $__currentLoopData = ['pending','paid','processing','shipped','completed','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($st); ?>" <?php if($order->status===$st): echo 'selected'; endif; ?>><?php echo e($st); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <input name="tracking_no" value="<?php echo e($order->tracking_no); ?>" placeholder="Resi (opsional)"
                   class="rounded-xl border-slate-200" />
            <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Update</button>
        </form>
    <?php endif; ?>

    <?php if($order->status === 'shipped' && !$order->delivered_at): ?>
        <form class="mt-3" method="POST" action="<?php echo e(route('seller.orders.delivered',$order)); ?>" onsubmit="return confirm('Tandai pesanan sudah sampai (delivered)?');">
            <?php echo csrf_field(); ?>
            <button class="px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Tandai Sudah Sampai</button>
        </form>
        <div class="text-xs text-slate-500 mt-2">MVP: tombol ini untuk simulasi status “delivered” sebelum integrasi tracking kurir otomatis.</div>
    <?php endif; ?>

    <?php if(in_array($order->status, ['shipped','completed'], true)): ?>
        <div class="mt-5 p-4 rounded-2xl border bg-slate-50">
            <div class="font-bold text-sm">Tambah Checkpoint Tracking</div>
            <div class="text-xs text-slate-500 mt-1">Gunakan untuk update manual seperti “masuk DC Bandung”, “sedang diantar kurir”, dll.</div>

            <form class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-2" method="POST" action="<?php echo e(route('seller.orders.checkpoint', $order)); ?>">
                <?php echo csrf_field(); ?>
                <input name="title" class="rounded-xl border-slate-200" placeholder="Contoh: Masuk DC Bandung" required>
                <input name="location" class="rounded-xl border-slate-200" placeholder="Lokasi checkpoint" required>
                <input name="description" class="rounded-xl border-slate-200 md:col-span-3" placeholder="Keterangan tambahan (opsional)">
                <button class="md:col-span-3 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Tambah Checkpoint</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="mt-6 font-bold">Items</div>
    <div class="mt-2 space-y-2">
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between text-sm">
                <div>
                    <?php echo e($it->product_name); ?>

                    <?php if($it->variant_name): ?>
                        <span class="text-xs text-slate-500">(<?php echo e($it->variant_name); ?>)</span>
                    <?php endif; ?>
                    × <?php echo e($it->qty); ?>

                </div>
                <div class="font-semibold">Rp <?php echo e(number_format($it->line_total,0,',','.')); ?></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-4 pt-4 border-t flex justify-between">
        <span class="font-bold">Total</span>
        <span class="font-black text-rose-600">Rp <?php echo e(number_format($order->grand_total,0,',','.')); ?></span>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/orders/show.blade.php ENDPATH**/ ?>