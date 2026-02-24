<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Tambah Produk</h1>

<div class="bg-white border rounded-2xl p-5">
    
    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-700">
            <ul class="list-disc pl-5 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('seller.products.store')); ?>" enctype="multipart/form-data" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div>
            <label class="font-semibold">Nama</label>
            <input name="name" value="<?php echo e(old('name')); ?>" class="w-full rounded-xl border-slate-200" required>
        </div>

        <div>
            <label class="font-semibold">Kategori</label>
            <select name="category_id" class="w-full rounded-xl border-slate-200">
                <option value="">-</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->id); ?>" <?php if(old('category_id') == $c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Harga (Rp)</label>
                <input type="number" min="0" name="price" value="<?php echo e(old('price')); ?>" class="w-full rounded-xl border-slate-200" required>
            </div>

            <div>
                <label class="font-semibold">Stok</label>
                <input type="number" min="0" name="stock" value="<?php echo e(old('stock')); ?>" class="w-full rounded-xl border-slate-200" required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Diskon</label>
                <select name="discount_type" class="w-full rounded-xl border-slate-200">
                    <option value="none" <?php if(old('discount_type','none')==='none'): echo 'selected'; endif; ?>>Tidak ada</option>
                    <option value="percent" <?php if(old('discount_type')==='percent'): echo 'selected'; endif; ?>>Persen (%)</option>
                    <option value="amount" <?php if(old('discount_type')==='amount'): echo 'selected'; endif; ?>>Potongan (Rp)</option>
                </select>
                <div class="text-xs text-slate-500 mt-1">Atur diskon per produk (Shopee-style). Jika tidak ada diskon pilih “Tidak ada”.</div>
            </div>
            <div>
                <label class="font-semibold">Nilai Diskon</label>
                <input type="number" min="0" name="discount_value" value="<?php echo e(old('discount_value',0)); ?>" class="w-full rounded-xl border-slate-200">
                <div class="text-xs text-slate-500 mt-1">Contoh: 10 untuk 10% atau 5000 untuk potongan Rp 5.000.</div>
            </div>
        </div>

        
        <div>
            <label class="font-semibold">Berat (gram)</label>
            <input
                type="number"
                min="1"
                name="weight_grams"
                value="<?php echo e(old('weight_grams')); ?>"
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
            <textarea name="description" rows="4" class="w-full rounded-xl border-slate-200"><?php echo e(old('description')); ?></textarea>
        </div>

        <div>
            <label class="font-semibold">Gambar (opsional, bisa banyak)</label>
            <input type="file" name="images[]" multiple class="w-full">
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" <?php echo e(old('is_active', '1') ? 'checked' : ''); ?>>
            <span class="font-semibold">Aktif</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/products/create.blade.php ENDPATH**/ ?>