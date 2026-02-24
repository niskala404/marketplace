<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Seller Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Saldo Tersedia</div>
        <div class="text-2xl font-black">Rp <?php echo e(number_format($balance ?? 0,0,',','.')); ?></div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Total Pendapatan (settled)</div>
        <div class="text-2xl font-black">Rp <?php echo e(number_format($totalEarnings ?? 0,0,',','.')); ?></div>
    </div>
    <div class="bg-white border rounded-2xl p-4">
        <div class="text-slate-500 text-sm">Total Sudah Dibayar</div>
        <div class="text-2xl font-black">Rp <?php echo e(number_format($totalPaidOut ?? 0,0,',','.')); ?></div>
    </div>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white border rounded-2xl p-4">
            <div class="text-slate-500 text-sm"><?php echo e(strtoupper($k)); ?></div>
            <div class="text-3xl font-black"><?php echo e($v); ?></div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="mt-6 flex gap-2">
    <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="<?php echo e(route('seller.products.index')); ?>">Kelola Produk</a>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="<?php echo e(route('seller.orders.index')); ?>">Kelola Pesanan</a>
    <a class="px-4 py-3 rounded-xl bg-white border font-bold" href="<?php echo e(route('seller.payouts.index')); ?>">💸 Payout</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/dashboard.blade.php ENDPATH**/ ?>