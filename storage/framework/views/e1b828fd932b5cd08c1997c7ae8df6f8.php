<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Kelola Flash Sale</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 space-y-4">
    <div class="bg-white border rounded-2xl p-5">
      <div class="font-bold text-lg mb-3">Item Promo</div>

      <form method="POST" action="<?php echo e(route('admin.flash-sales.items.add', $sale)); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-2 items-end">
        <?php echo csrf_field(); ?>
        <div class="md:col-span-2">
          <label class="font-semibold">Produk</label>
          <select name="product_id" class="w-full rounded-xl border-slate-200">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($p->id); ?>">#<?php echo e($p->id); ?> — <?php echo e($p->name); ?> (Rp <?php echo e(number_format($p->price,0,',','.')); ?>)</option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <div class="text-xs text-slate-500 mt-1">(List ini menampilkan 50 produk terbaru yang sudah approved)</div>
        </div>
        <div>
          <label class="font-semibold">Harga Promo</label>
          <input type="number" min="1" name="promo_price" class="w-full rounded-xl border-slate-200" required>
        </div>
        <div>
          <label class="font-semibold">Kuota (opsional)</label>
          <input type="number" min="1" name="quota" class="w-full rounded-xl border-slate-200">
        </div>
        <div class="md:col-span-4 flex justify-between items-center">
          <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300">
            <span class="font-semibold">Aktif</span>
          </label>
          <button class="px-4 py-2 rounded-xl bg-fuchsia-600 text-white font-bold">Tambah/Update</button>
        </div>
      </form>

      <div class="mt-4 divide-y border rounded-2xl overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="p-4 flex items-center justify-between">
            <div>
              <div class="font-semibold"><?php echo e($it->product?->name ?? ('Produk #'.$it->product_id)); ?></div>
              <div class="text-xs text-slate-500">
                Harga promo: Rp <?php echo e(number_format($it->promo_price,0,',','.')); ?>

                • Kuota: <?php echo e($it->quota ?? '∞'); ?> • Sold: <?php echo e($it->sold); ?>

              </div>
            </div>
            <div class="flex gap-2">
              <form method="POST" action="<?php echo e(route('admin.flash-sales.items.toggle', $it)); ?>">
                <?php echo csrf_field(); ?>
                <button class="px-3 py-2 rounded-xl <?php echo e($it->is_active ? 'bg-emerald-600' : 'bg-slate-900'); ?> text-white">
                  <?php echo e($it->is_active ? 'Aktif' : 'Nonaktif'); ?>

                </button>
              </form>
              <form method="POST" action="<?php echo e(route('admin.flash-sales.items.delete', $it)); ?>" onsubmit="return confirm('Hapus item?')">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
              </form>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="p-6 text-slate-600">Belum ada item.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="space-y-4">
    <div class="bg-white border rounded-2xl p-5">
      <div class="font-bold text-lg mb-3">Pengaturan</div>
      <form method="POST" action="<?php echo e(route('admin.flash-sales.update', $sale)); ?>" class="space-y-3">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div>
          <label class="font-semibold">Nama</label>
          <input name="name" value="<?php echo e($sale->name); ?>" class="w-full rounded-xl border-slate-200" required>
        </div>
        <div>
          <label class="font-semibold">Mulai</label>
          <input type="datetime-local" name="starts_at" value="<?php echo e($sale->starts_at->format('Y-m-d\TH:i')); ?>" class="w-full rounded-xl border-slate-200" required>
        </div>
        <div>
          <label class="font-semibold">Selesai</label>
          <input type="datetime-local" name="ends_at" value="<?php echo e($sale->ends_at->format('Y-m-d\TH:i')); ?>" class="w-full rounded-xl border-slate-200" required>
        </div>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="is_active" value="1" <?php echo e($sale->is_active ? 'checked' : ''); ?> class="rounded border-slate-300">
          <span class="font-semibold">Aktif</span>
        </label>
        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update</button>
      </form>

      <a href="<?php echo e(route('admin.flash-sales.index')); ?>" class="mt-3 block text-center px-4 py-3 rounded-xl border">Kembali</a>
    </div>

    <div class="bg-white border rounded-2xl p-5">
      <div class="text-sm text-slate-600">
        Homepage hanya menampilkan <span class="font-semibold">flash sale yang sedang aktif</span> (now berada di antara mulai & selesai).
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/flash_sales/edit.blade.php ENDPATH**/ ?>