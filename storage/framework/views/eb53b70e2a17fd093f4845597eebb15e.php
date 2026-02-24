<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Ajukan Payout</h1>

<div class="bg-white border rounded-2xl p-5">
  <div class="text-slate-600 mb-4">
    Saldo tersedia: <span class="font-bold">Rp <?php echo e(number_format($balance,0,',','.')); ?></span>
    <span class="text-sm text-slate-500">(minimal Rp <?php echo e(number_format($min,0,',','.')); ?>)</span>
  </div>

  <form method="POST" action="<?php echo e(route('seller.payouts.store')); ?>" class="space-y-4">
    <?php echo csrf_field(); ?>

    <div>
      <label class="font-semibold">Nominal (Rp)</label>
      <input type="number" min="1" name="amount" value="<?php echo e(old('amount')); ?>"
             class="w-full rounded-xl border-slate-200" required>
      <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="font-semibold">Nama Bank</label>
        <input name="bank_name" value="<?php echo e(old('bank_name')); ?>" class="w-full rounded-xl border-slate-200" placeholder="BCA" required>
        <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div>
        <label class="font-semibold">No Rekening</label>
        <input name="account_number" value="<?php echo e(old('account_number')); ?>" class="w-full rounded-xl border-slate-200" placeholder="1234567890" required>
        <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
      <div>
        <label class="font-semibold">Atas Nama</label>
        <input name="account_name" value="<?php echo e(old('account_name')); ?>" class="w-full rounded-xl border-slate-200" placeholder="Nama pemilik" required>
        <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>
    </div>

    <div>
      <label class="font-semibold">Catatan (opsional)</label>
      <textarea name="note" rows="3" class="w-full rounded-xl border-slate-200"><?php echo e(old('note')); ?></textarea>
      <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-sm text-rose-600 mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="flex items-center gap-2">
      <a href="<?php echo e(route('seller.payouts.index')); ?>" class="px-4 py-2 rounded-xl border bg-white hover:bg-slate-50">Batal</a>
      <button class="px-4 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Kirim Permintaan</button>
    </div>
  </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/payouts/create.blade.php ENDPATH**/ ?>