<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-black">Tambah Alamat</h1>
    <a href="<?php echo e(route('account.addresses.index')); ?>" class="px-4 py-2 rounded-xl bg-slate-900 text-white">Kembali</a>
</div>

<div class="bg-white border rounded-2xl p-5">
    <form method="POST" action="<?php echo e(route('account.addresses.store')); ?>" class="space-y-4">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Label</label>
                <input name="label" value="<?php echo e(old('label','Rumah')); ?>" class="w-full rounded-xl border-slate-200" required>
                <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-rose-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="font-semibold">Kode Pos (opsional)</label>
                <input name="postal_code" value="<?php echo e(old('postal_code')); ?>" class="w-full rounded-xl border-slate-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">Nama Penerima</label>
                <input name="recipient_name" value="<?php echo e(old('recipient_name')); ?>" class="w-full rounded-xl border-slate-200" required>
                <?php $__errorArgs = ['recipient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-rose-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div>
                <label class="font-semibold">No. HP</label>
                <input name="phone" value="<?php echo e(old('phone')); ?>" class="w-full rounded-xl border-slate-200" required>
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-rose-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="font-semibold">Provinsi (opsional)</label>
                <input name="province" value="<?php echo e(old('province')); ?>" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="font-semibold">Kota (opsional)</label>
                <input name="city" value="<?php echo e(old('city')); ?>" class="w-full rounded-xl border-slate-200">
            </div>
            <div>
                <label class="font-semibold">Kecamatan (opsional)</label>
                <input name="district" value="<?php echo e(old('district')); ?>" class="w-full rounded-xl border-slate-200">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="font-semibold">RajaOngkir City ID (opsional)</label>
                <input name="rajaongkir_city_id" value="<?php echo e(old('rajaongkir_city_id')); ?>" class="w-full rounded-xl border-slate-200" placeholder="contoh: 39">
                <div class="text-slate-500 text-sm mt-1">Isi jika kamu mau ongkir real (RajaOngkir). Kalau kosong, pakai ongkir demo berdasarkan ShippingRate.</div>
                <?php $__errorArgs = ['rajaongkir_city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-rose-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div>
            <label class="font-semibold">Alamat Lengkap</label>
            <textarea name="full_address" rows="4" class="w-full rounded-xl border-slate-200" required><?php echo e(old('full_address')); ?></textarea>
            <?php $__errorArgs = ['full_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-rose-600 text-sm mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <label class="inline-flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1" <?php echo e(old('is_default') ? 'checked' : ''); ?>>
            <span class="font-semibold">Jadikan default</span>
        </label>

        <button class="w-full px-4 py-3 rounded-xl bg-rose-600 text-white font-black">Simpan</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/account/addresses/create.blade.php ENDPATH**/ ?>