<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Moderasi Produk</h1>
        <p class="text-slate-600 text-sm">Setujui / tolak produk dari seller sebelum tampil di marketplace.</p>
    </div>

    <div class="flex gap-2">
        <?php $__currentLoopData = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.products.moderation.index', ['status' => $k])); ?>"
               class="px-4 py-2 rounded-xl border <?php echo e($status === $k ? 'bg-slate-900 text-white border-slate-900' : 'bg-white'); ?>">
                <?php echo e($label); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead class="bg-slate-50">
            <tr>
                <th class="text-left p-3">Produk</th>
                <th class="text-left p-3">Toko</th>
                <th class="text-left p-3">Harga</th>
                <th class="text-left p-3">Status</th>
                <th class="text-right p-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="p-3">
                        <div class="font-bold"><?php echo e($p->name); ?></div>
                        <div class="text-sm text-slate-500"><?php echo e($p->category?->name ?? '-'); ?></div>
                    </td>
                    <td class="p-3">
                        <div class="font-semibold"><?php echo e($p->shop?->name ?? '-'); ?></div>
                        <div class="text-sm text-slate-500"><?php echo e($p->shop?->user?->email ?? ''); ?></div>
                    </td>
                    <td class="p-3">Rp <?php echo e(number_format($p->price,0,',','.')); ?></td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-lg text-xs border <?php echo e($p->approval_status==='pending' ? 'bg-amber-50 border-amber-200' : ($p->approval_status==='approved' ? 'bg-emerald-50 border-emerald-200' : 'bg-rose-50 border-rose-200')); ?>">
                            <?php echo e($p->approval_status); ?>

                        </span>
                    </td>
                    <td class="p-3 text-right">
                        <a class="px-3 py-2 rounded-xl bg-rose-600 text-white" href="<?php echo e(route('admin.products.moderation.show', $p)); ?>">Detail</a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td class="p-6 text-slate-600" colspan="5">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4"><?php echo e($products->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/products/moderation/index.blade.php ENDPATH**/ ?>