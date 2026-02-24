<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Kategori</h1>
    <a class="px-4 py-3 rounded-xl bg-rose-600 text-white font-bold" href="<?php echo e(route('admin.categories.create')); ?>">+ Kategori</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 border overflow-hidden flex items-center justify-center shrink-0">
                        <?php if($c->image_path): ?>
                            <img src="<?php echo e($c->imageUrl()); ?>" class="w-full h-full object-cover" alt="<?php echo e($c->name); ?>">
                        <?php else: ?>
                            <span class="font-black text-slate-400"><?php echo e(strtoupper(mb_substr($c->name,0,1))); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <div class="font-bold truncate"><?php echo e($c->name); ?></div>
                        <div class="text-sm text-slate-500 truncate"><?php echo e($c->slug); ?></div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="<?php echo e(route('admin.categories.edit',$c)); ?>">Edit</a>
                    <form method="POST" action="<?php echo e(route('admin.categories.destroy',$c)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="mt-4"><?php echo e($categories->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>