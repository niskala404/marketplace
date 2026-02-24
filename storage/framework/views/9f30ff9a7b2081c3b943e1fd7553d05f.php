<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Alamat Saya</h1>
        <div class="text-slate-500 text-sm">Kelola alamat pengiriman untuk checkout.</div>
    </div>
    <a href="<?php echo e(route('account.addresses.create')); ?>" class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold">+ Alamat</a>
</div>

<?php if($addresses->isEmpty()): ?>
    <div class="bg-white border rounded-2xl p-6 text-slate-600">
        Belum ada alamat. Tambahkan alamat untuk bisa checkout.
    </div>
<?php else: ?>
    <div class="space-y-3">
        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white border rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <div class="font-bold"><?php echo e($a->label); ?></div>
                            <?php if($a->is_default): ?>
                                <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 font-semibold">Default</span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-1 text-slate-700 font-semibold"><?php echo e($a->recipient_name); ?> (<?php echo e($a->phone); ?>)</div>
                        <div class="text-sm text-slate-600 mt-1"><?php echo e($a->full_address); ?></div>
                        <div class="text-sm text-slate-500"><?php echo e($a->district); ?> <?php echo e($a->city); ?> <?php echo e($a->province); ?> <?php echo e($a->postal_code); ?></div>
                    </div>

                    <div class="flex flex-col gap-2 w-40">
                        <a href="<?php echo e(route('account.addresses.edit', $a)); ?>" class="px-3 py-2 rounded-xl bg-slate-900 text-white text-center">Edit</a>
                        <form method="POST" action="<?php echo e(route('account.addresses.destroy', $a)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="w-full px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/account/addresses/index.blade.php ENDPATH**/ ?>