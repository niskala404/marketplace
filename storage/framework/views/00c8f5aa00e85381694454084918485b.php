<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Verifikasi Pembayaran</h1>
        <div class="text-sm text-slate-500">Daftar pesanan transfer manual yang menunggu verifikasi</div>
    </div>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <div class="font-bold"><?php echo e($o->order_no); ?></div>
                    <div class="text-sm text-slate-500">Pembeli: <?php echo e($o->user->name); ?> • <?php echo e($o->user->email); ?></div>
                    <div class="text-sm text-slate-500">Toko: <?php echo e($o->shop->name); ?></div>
                    <div class="mt-2 font-black text-rose-600">Rp <?php echo e(number_format($o->grand_total,0,',','.')); ?></div>
                    <div class="text-sm">Status: <span class="font-semibold"><?php echo e($o->status); ?></span></div>
                </div>

                <div>
                    <?php if($o->payment_proof_path): ?>
                        <img class="w-full max-w-md rounded-2xl border" src="<?php echo e(asset('storage/'.$o->payment_proof_path)); ?>" alt="Bukti transfer">
                    <?php else: ?>
                        <div class="text-sm text-slate-600">Belum ada bukti transfer.</div>
                    <?php endif; ?>
                </div>

                <div class="flex flex-col gap-2">
                    <form method="POST" action="<?php echo e(route('admin.payments.verify', $o)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="w-full px-4 py-3 rounded-xl bg-emerald-600 text-white font-bold">Verifikasi (PAID)</button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.payments.reject', $o)); ?>">
                        <?php echo csrf_field(); ?>
                        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-bold">Tolak Bukti</button>
                    </form>
                    <div class="text-xs text-slate-500">Catatan: detail item bisa dilihat via panel seller (order terkait toko) atau buat halaman detail admin (tahap berikutnya).</div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 text-slate-600">Tidak ada pembayaran yang menunggu verifikasi.</div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/payments/index.blade.php ENDPATH**/ ?>