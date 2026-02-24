<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Payout (Penarikan Saldo)</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Pendapatan (settled)</div>
    <div class="text-xl font-black mt-1">Rp <?php echo e(number_format($totalEarnings,0,',','.')); ?></div>
  </div>
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Total Sudah Dibayar</div>
    <div class="text-xl font-black mt-1">Rp <?php echo e(number_format($totalPaidOut,0,',','.')); ?></div>
  </div>
  <div class="bg-white border rounded-2xl p-4">
    <div class="text-sm text-slate-500">Saldo Tersedia</div>
    <div class="text-xl font-black mt-1">Rp <?php echo e(number_format($balance,0,',','.')); ?></div>
    <div class="text-xs text-slate-500 mt-1">Minimal penarikan: Rp <?php echo e(number_format($min,0,',','.')); ?></div>
  </div>
</div>

<div class="mb-4">
  <a href="<?php echo e(route('seller.payouts.create')); ?>" class="inline-flex items-center px-4 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">
    Ajukan Payout
  </a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <div class="divide-y">
    <?php $__empty_1 = true; $__currentLoopData = $payouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <div class="p-4 flex items-center justify-between">
        <div>
          <div class="font-bold">Rp <?php echo e(number_format($p->amount,0,',','.')); ?></div>
          <div class="text-sm text-slate-500"><?php echo e($p->bank_name); ?> • <?php echo e($p->account_number); ?> • <?php echo e($p->account_name); ?></div>
          <?php if($p->note): ?>
            <div class="text-xs text-slate-500 mt-1">Catatan: <?php echo e($p->note); ?></div>
          <?php endif; ?>
          <?php if($p->admin_note): ?>
            <div class="text-xs text-slate-500 mt-1">Admin: <?php echo e($p->admin_note); ?></div>
          <?php endif; ?>
        </div>
        <div class="text-right">
          <div class="font-semibold"><?php echo e($p->status); ?></div>
          <div class="text-xs text-slate-500"><?php echo e($p->created_at->format('d M Y H:i')); ?></div>
        </div>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <div class="p-6 text-slate-600">Belum ada permintaan payout.</div>
    <?php endif; ?>
  </div>
</div>

<div class="mt-4"><?php echo e($payouts->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/seller/payouts/index.blade.php ENDPATH**/ ?>