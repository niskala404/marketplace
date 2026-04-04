<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Produk Saya</h1>
    <div class="flex items-center gap-2">
        <a class="px-4 py-3 rounded-xl border font-bold" href="<?php echo e(route('seller.live.index')); ?>">Live Streaming</a>
        <a class="px-4 py-3 rounded-xl bg-slate-900 text-white font-bold" href="<?php echo e(route('seller.products.bulk')); ?>">Bulk Tools</a>
        <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="<?php echo e(route('seller.products.create')); ?>">+ Produk</a>
    </div>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold"><?php echo e($p->name); ?></div>
                    <div class="text-sm text-slate-500">
                        Rp <?php echo e(number_format($p->price,0,',','.')); ?> • stok <?php echo e($p->stock); ?> • <?php echo e($p->is_active ? 'aktif' : 'nonaktif'); ?>

                        • status: <span class="font-semibold"><?php echo e($p->approval_status ?? 'approved'); ?></span>
                    </div>
                    <?php if(($p->approval_status ?? '') === 'rejected' && $p->rejected_reason): ?>
                        <div class="text-sm text-rose-600 mt-1">Ditolak: <?php echo e($p->rejected_reason); ?></div>
                    <?php endif; ?>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl border" href="<?php echo e(route('seller.products.variants.index',$p)); ?>">Varian</a>
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="<?php echo e(route('seller.products.edit',$p)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('seller.products.destroy',$p)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="mt-4"><?php echo e($products->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/products/index.blade.php ENDPATH**/ ?>