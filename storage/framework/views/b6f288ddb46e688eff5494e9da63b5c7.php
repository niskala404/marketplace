<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Buat Flash Sale</h1>

<div class="bg-white border rounded-2xl p-5">
  <form method="POST" action="<?php echo e(route('admin.flash-sales.store')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
      <label class="font-semibold">Nama</label>
      <input name="name" class="w-full rounded-xl border-slate-200" placeholder="Flash Sale Payday" required>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="font-semibold">Mulai</label>
        <input type="datetime-local" name="starts_at" class="w-full rounded-xl border-slate-200" required>
      </div>
      <div>
        <label class="font-semibold">Selesai</label>
        <input type="datetime-local" name="ends_at" class="w-full rounded-xl border-slate-200" required>
      </div>
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300">
      <span class="font-semibold">Aktif</span>
    </label>

    <div class="flex gap-2">
      <a href="<?php echo e(route('admin.flash-sales.index')); ?>" class="px-4 py-3 rounded-xl border">Batal</a>
      <button class="px-4 py-3 rounded-xl bg-fuchsia-600 text-white font-black">Simpan</button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/flash_sales/create.blade.php ENDPATH**/ ?>