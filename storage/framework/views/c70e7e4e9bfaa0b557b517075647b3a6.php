<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Edit Produk</h1>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="<?php echo e(route('seller.products.update',$product)); ?>" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" value="<?php echo e($product->name); ?>" class="w-full rounded-xl border-slate-200" required>
        </div>

        <div>
            <label class="font-semibold">Kategori</label>
            <select name="category_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php if($product->category_id===$c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Harga</label>
                <input type="number" min="0" name="price" value="<?php echo e($product->price); ?>" class="w-full rounded-xl border-slate-200" required>
            </div>
            <div>
                <label class="font-semibold">Stok</label>
                <input type="number" min="0" name="stock" value="<?php echo e($product->stock); ?>" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Diskon</label>
                <select name="discount_type" class="w-full rounded-xl border-slate-200">
                    <option value="none" <?php if(($product->discount_type ?? 'none')==='none'): echo 'selected'; endif; ?>>Tidak ada</option>
                    <option value="percent" <?php if(($product->discount_type ?? '')==='percent'): echo 'selected'; endif; ?>>Persen (%)</option>
                    <option value="amount" <?php if(($product->discount_type ?? '')==='amount'): echo 'selected'; endif; ?>>Potongan (Rp)</option>
                </select>
                <div class="text-xs text-slate-500 mt-1">Diskon per produk. Contoh: 10% atau potongan Rp.</div>
            </div>
            <div>
                <label class="font-semibold">Nilai Diskon</label>
                <input type="number" min="0" name="discount_value" value="<?php echo e((int)($product->discount_value ?? 0)); ?>" class="w-full rounded-xl border-slate-200">
            </div>
        </div>
        <div>
            <label class="font-semibold">Berat (gram)</label>
            <input
                type="number"
                min="1"
                name="weight_grams"
                value="<?php echo e(old('weight_grams', $product->weight_grams)); ?>"
                class="w-full rounded-xl border-slate-200"
                required
            >
            <?php $__errorArgs = ['weight_grams'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label class="font-semibold">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200"><?php echo e($product->description); ?></textarea>
        </div>

        <div>
            <label class="font-semibold">Tambah gambar (opsional)</label>
            <input type="file" name="images[]" multiple class="w-full">
        </div>

        <?php if($product->images->count()): ?>
            <div>
                <div class="font-semibold mb-2">Gambar Produk</div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2">
                    <?php $__currentLoopData = $product->images->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="relative">
                            <img class="rounded-xl border object-cover aspect-square w-full" src="<?php echo e(asset('storage/'.$img->path)); ?>" alt="">
                            <form method="POST" action="<?php echo e(route('seller.products.images.destroy', [$product, $img])); ?>" class="absolute top-1 right-1">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="px-2 py-1 rounded-lg bg-white/90 border text-slate-700 hover:bg-white" title="Hapus">
                                    ✕
                                </button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="text-xs text-slate-500 mt-2">*Gambar pertama otomatis jadi thumbnail utama.</div>
            </div>
        <?php endif; ?>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" <?php if($product->is_active): echo 'checked'; endif; ?>>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-slate-900 text-white font-black">Update</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/products/edit.blade.php ENDPATH**/ ?>