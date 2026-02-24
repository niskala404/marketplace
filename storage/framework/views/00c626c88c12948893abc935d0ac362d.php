<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tarif Ongkir</h1>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="<?php echo e(route('admin.shipping-rates.create')); ?>">+ Tarif</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__currentLoopData = $rates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-bold"><?php echo e($r->name); ?> <?php echo $r->is_active ? '<span class="text-emerald-600 text-xs font-semibold">aktif</span>' : '<span class="text-rose-600 text-xs font-semibold">nonaktif</span>'; ?></div>
                    <div class="text-sm text-slate-500">
                        Cakupan: <?php echo e($r->city ? 'Kota: '.$r->city : ($r->province ? 'Provinsi: '.$r->province : 'Default (semua)')); ?>

                        • Base: Rp <?php echo e(number_format($r->base_fee,0,',','.')); ?>

                        • Per kg: Rp <?php echo e(number_format($r->per_kg_fee,0,',','.')); ?>

                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="<?php echo e(route('admin.shipping-rates.edit',$r)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.shipping-rates.destroy',$r)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="mt-4"><?php echo e($rates->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/shipping_rates/index.blade.php ENDPATH**/ ?>