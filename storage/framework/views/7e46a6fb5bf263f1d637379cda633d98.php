<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
  <div>
    <h1 class="text-2xl font-black">Bulk Tools Produk</h1>
    <div class="text-sm text-slate-500">Ubah harga, stok, aktif/nonaktif, dan diskon sekaligus.</div>
  </div>
  <a href="<?php echo e(route('seller.products.index')); ?>" class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
  <form method="GET" class="flex gap-2">
    <input name="q" value="<?php echo e($q); ?>" placeholder="Cari nama produk..." class="flex-1 rounded-xl border-slate-200">
    <button class="px-4 py-2 rounded-xl bg-slate-900 text-white font-bold">Cari</button>
  </form>

  <?php if($products->count() === 0): ?>
    <div class="mt-6 text-slate-600">Tidak ada produk.</div>
  <?php else: ?>
    <form method="POST" action="<?php echo e(route('seller.products.bulk.update')); ?>" class="mt-5">
      <?php echo csrf_field(); ?>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left text-slate-500">
              <th class="py-2 pr-4">Produk</th>
              <th class="py-2 pr-4">Harga</th>
              <th class="py-2 pr-4">Stok</th>
              <th class="py-2 pr-4">Diskon</th>
              <th class="py-2 pr-4">Aktif</th>
              <th class="py-2 pr-4">Status</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td class="py-3 pr-4">
                  <div class="font-semibold"><?php echo e($p->name); ?></div>
                  <div class="text-xs text-slate-500">SKU: <?php echo e($p->id); ?> • Berat: <?php echo e((int)($p->weight_grams ?? 0)); ?>g</div>
                  <input type="hidden" name="items[<?php echo e($loop->index); ?>][id]" value="<?php echo e($p->id); ?>">
                </td>
                <td class="py-3 pr-4">
                  <input type="number" min="0" class="w-36 rounded-xl border-slate-200"
                         name="items[<?php echo e($loop->index); ?>][price]" value="<?php echo e((int)$p->price); ?>">
                </td>
                <td class="py-3 pr-4">
                  <input type="number" min="0" class="w-28 rounded-xl border-slate-200"
                         name="items[<?php echo e($loop->index); ?>][stock]" value="<?php echo e((int)$p->stock); ?>">
                </td>
                <td class="py-3 pr-4">
                  <div class="flex items-center gap-2">
                    <select class="rounded-xl border-slate-200" name="items[<?php echo e($loop->index); ?>][discount_type]">
                      <option value="none" <?php if(($p->discount_type ?? 'none')==='none'): echo 'selected'; endif; ?>>None</option>
                      <option value="percent" <?php if(($p->discount_type ?? '')==='percent'): echo 'selected'; endif; ?>>%</option>
                      <option value="amount" <?php if(($p->discount_type ?? '')==='amount'): echo 'selected'; endif; ?>>Rp</option>
                    </select>
                    <input type="number" min="0" class="w-24 rounded-xl border-slate-200"
                           name="items[<?php echo e($loop->index); ?>][discount_value]" value="<?php echo e((int)($p->discount_value ?? 0)); ?>">
                  </div>
                </td>
                <td class="py-3 pr-4">
                  <label class="inline-flex items-center gap-2">
                    <input type="hidden" name="items[<?php echo e($loop->index); ?>][is_active]" value="0">
                    <input type="checkbox" name="items[<?php echo e($loop->index); ?>][is_active]" value="1" <?php if((bool)$p->is_active): echo 'checked'; endif; ?>>
                    <span class="text-xs text-slate-600">Aktif</span>
                  </label>
                </td>
                <td class="py-3 pr-4">
                  <span class="text-xs px-2 py-1 rounded-full border <?php echo e(($p->approval_status ?? '')==='approved' ? 'bg-emerald-50 border-emerald-200 text-emerald-700' : 'bg-amber-50 border-amber-200 text-amber-700'); ?>">
                    <?php echo e($p->approval_status ?? '-'); ?>

                  </span>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>

      <div class="mt-4 flex items-center justify-between gap-3">
        <div class="text-xs text-slate-500">* Bulk edit otomatis set status produk ke <span class="font-semibold">pending</span> (butuh approval ulang).</div>
        <button class="px-5 py-2.5 rounded-xl bg-rose-600 text-white font-black">Simpan Perubahan</button>
      </div>

      <div class="mt-4"><?php echo e($products->links()); ?></div>
    </form>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/products/bulk.blade.php ENDPATH**/ ?>