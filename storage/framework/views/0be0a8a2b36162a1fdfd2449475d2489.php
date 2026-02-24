<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Edit Voucher</h1>

<form method="POST" action="<?php echo e(route('admin.vouchers.update',$voucher)); ?>" class="bg-white border rounded-2xl p-5 space-y-4">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div>
        <div class="font-semibold">Kode</div>
        <input value="<?php echo e($voucher->code); ?>" readonly class="w-full rounded-xl border-slate-200 bg-slate-50">
    </div>

    <div>
        <div class="font-semibold">Nama</div>
        <input name="name" value="<?php echo e(old('name',$voucher->name)); ?>" class="w-full rounded-xl border-slate-200">
        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="font-semibold">Scope</div>
            <select name="shop_id" class="w-full rounded-xl border-slate-200">
                <option value="" <?php if(old('shop_id',$voucher->shop_id)===null): echo 'selected'; endif; ?>>Platform (semua toko)</option>
                <?php $__currentLoopData = $shops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->id); ?>" <?php if((string)old('shop_id',$voucher->shop_id)===(string)$s->id): echo 'selected'; endif; ?>><?php echo e($s->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <div class="font-semibold">Tipe</div>
            <select name="type" class="w-full rounded-xl border-slate-200">
                <option value="fixed" <?php if(old('type',$voucher->type)==='fixed'): echo 'selected'; endif; ?>>Fixed (rupiah)</option>
                <option value="percent" <?php if(old('type',$voucher->type)==='percent'): echo 'selected'; endif; ?>>Percent (%)</option>
                <option value="shipping" <?php if(old('type',$voucher->type)==='shipping'): echo 'selected'; endif; ?>>Diskon Ongkir (rupiah)</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <div class="font-semibold">Nilai</div>
            <input type="number" name="value" value="<?php echo e(old('value',$voucher->value)); ?>" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Min Subtotal</div>
            <input type="number" name="min_subtotal" value="<?php echo e(old('min_subtotal',$voucher->min_subtotal)); ?>" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Max Discount (percent)</div>
            <input type="number" name="max_discount" value="<?php echo e(old('max_discount',$voucher->max_discount)); ?>" class="w-full rounded-xl border-slate-200" placeholder="Opsional">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <div class="font-semibold">Usage Limit (total)</div>
            <input type="number" name="usage_limit" value="<?php echo e(old('usage_limit',$voucher->usage_limit)); ?>" class="w-full rounded-xl border-slate-200" placeholder="Opsional">
        </div>
        <div>
            <div class="font-semibold">Per User Limit</div>
            <input type="number" name="per_user_limit" value="<?php echo e(old('per_user_limit',$voucher->per_user_limit)); ?>" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Aktif</div>
            <select name="is_active" class="w-full rounded-xl border-slate-200">
                <option value="1" <?php if(old('is_active',$voucher->is_active)==true): echo 'selected'; endif; ?>>Ya</option>
                <option value="0" <?php if(old('is_active',$voucher->is_active)==false): echo 'selected'; endif; ?>>Tidak</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <div class="font-semibold">Mulai</div>
            <input type="datetime-local" name="starts_at" value="<?php echo e(old('starts_at', optional($voucher->starts_at)->format('Y-m-d\TH:i'))); ?>" class="w-full rounded-xl border-slate-200">
        </div>
        <div>
            <div class="font-semibold">Berakhir</div>
            <input type="datetime-local" name="ends_at" value="<?php echo e(old('ends_at', optional($voucher->ends_at)->format('Y-m-d\TH:i'))); ?>" class="w-full rounded-xl border-slate-200">
        </div>
    </div>

    <div class="flex gap-2">
        <button class="px-5 py-3 rounded-xl bg-amber-600 text-white font-black">Update</button>
        <a class="px-5 py-3 rounded-xl bg-slate-100 font-bold" href="<?php echo e(route('admin.vouchers.index')); ?>">Kembali</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/vouchers/edit.blade.php ENDPATH**/ ?>