<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Voucher</h1>
    <a class="px-4 py-3 rounded-xl bg-amber-600 text-white font-bold" href="<?php echo e(route('admin.vouchers.create')); ?>">+ Voucher</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $vouchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold"><?php echo e($v->code); ?> — <?php echo e($v->name); ?></div>
                    <div class="text-sm text-slate-500">
                        Scope: <?php echo e($v->shop ? $v->shop->name : 'Platform'); ?>

                        •
                        <?php if($v->type === 'percent'): ?>
                            <?php echo e($v->value); ?>%
                        <?php elseif($v->type === 'shipping'): ?>
                            Diskon Ongkir Rp <?php echo e(number_format($v->value,0,',','.')); ?>

                        <?php else: ?>
                            Rp <?php echo e(number_format($v->value,0,',','.')); ?>

                        <?php endif; ?>
                        • Min: Rp <?php echo e(number_format($v->min_subtotal,0,',','.')); ?>

                        <?php if($v->type==='percent' && $v->max_discount): ?>
                            • Max: Rp <?php echo e(number_format($v->max_discount,0,',','.')); ?>

                        <?php endif; ?>
                    </div>
                    <div class="text-xs text-slate-500">
                        Used: <?php echo e($v->used_count); ?>

                        <?php if($v->usage_limit): ?> / <?php echo e($v->usage_limit); ?> <?php endif; ?>
                        • Per user: <?php echo e($v->per_user_limit); ?>

                        • <?php echo e($v->is_active ? 'aktif' : 'nonaktif'); ?>

                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="<?php echo e(route('admin.vouchers.edit',$v)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.vouchers.destroy',$v)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 text-slate-600">Belum ada voucher.</div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4"><?php echo e($vouchers->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/vouchers/index.blade.php ENDPATH**/ ?>