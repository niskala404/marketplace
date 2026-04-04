<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tambah Alamat</h1>
    <a href="<?php echo e(route('account.addresses.index')); ?>" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="<?php echo e(route('account.addresses.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('account.addresses._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan Alamat</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/account/addresses/create.blade.php ENDPATH**/ ?>