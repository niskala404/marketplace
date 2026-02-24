<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <div>
        <h1 class="text-2xl font-black">Detail Produk</h1>
        <p class="text-slate-600 text-sm">Moderasi: <span class="font-semibold"><?php echo e($product->approval_status); ?></span></p>
    </div>
    <a href="<?php echo e(route('admin.products.moderation.index', ['status' => $product->approval_status])); ?>" class="px-4 py-2 rounded-xl border">← Kembali</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="md:col-span-2 bg-white border rounded-2xl p-4">
        <div class="flex items-start gap-4">
            <img src="<?php echo e($product->mainImageUrl()); ?>" class="w-28 h-28 rounded-xl object-cover border" />
            <div>
                <div class="text-xl font-black"><?php echo e($product->name); ?></div>
                <div class="text-slate-600">Rp <?php echo e(number_format($product->price,0,',','.')); ?> • stok <?php echo e($product->stock); ?> • <?php echo e($product->weight_grams); ?> gr</div>
                <div class="text-sm text-slate-500 mt-1">Kategori: <?php echo e($product->category?->name ?? '-'); ?></div>
                <div class="text-sm text-slate-500">Toko: <?php echo e($product->shop?->name ?? '-'); ?> (<?php echo e($product->shop?->user?->email ?? ''); ?>)</div>
            </div>
        </div>

        <div class="mt-4">
            <div class="font-bold mb-1">Deskripsi</div>
            <div class="prose max-w-none"><?php echo nl2br(e($product->description)); ?></div>
        </div>

        <?php if($product->images && $product->images->count() > 1): ?>
            <div class="mt-4">
                <div class="font-bold mb-2">Gambar</div>
                <div class="flex gap-2 flex-wrap">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/'.$img->path)); ?>" class="w-20 h-20 rounded-xl object-cover border" />
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="bg-white border rounded-2xl p-4">
        <div class="font-black mb-2">Aksi Moderasi</div>

        <?php if($product->approval_status !== 'approved'): ?>
            <form method="POST" action="<?php echo e(route('admin.products.moderation.approve', $product)); ?>">
                <?php echo csrf_field(); ?>
                <button class="w-full px-4 py-2 rounded-xl bg-emerald-600 text-white font-bold">Approve</button>
            </form>
        <?php endif; ?>

        <div class="my-3 border-t"></div>

        <form method="POST" action="<?php echo e(route('admin.products.moderation.reject', $product)); ?>" class="space-y-2">
            <?php echo csrf_field(); ?>
            <label class="text-sm text-slate-700">Alasan penolakan</label>
            <textarea name="reason" rows="4" class="w-full border rounded-xl p-2" placeholder="Contoh: Deskripsi tidak jelas / gambar tidak sesuai"><?php echo e(old('reason', $product->rejected_reason)); ?></textarea>
            <button class="w-full px-4 py-2 rounded-xl bg-rose-600 text-white font-bold">Reject</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/products/moderation/show.blade.php ENDPATH**/ ?>