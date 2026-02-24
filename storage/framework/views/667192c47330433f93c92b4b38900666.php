<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Pesanan Saya</h1>

<?php ($cur = $status ?? 'all'); ?>
<div class="mb-3 flex gap-2 overflow-x-auto no-scrollbar">
  <?php ($tabs = [
    'all' => 'Semua',
    'pending' => 'Belum Bayar',
    'processing' => 'Diproses',
    'shipped' => 'Dikirim',
    'completed' => 'Selesai',
    'cancelled' => 'Dibatalkan',
  ]); ?>
  <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('orders.mine', ['status' => $k])); ?>"
      class="px-3 py-2 rounded-full border text-sm font-semibold whitespace-nowrap <?php echo e($cur===$k?'bg-slate-900 text-white border-slate-900':'bg-white hover:bg-slate-50'); ?>">
      <?php echo e($label); ?>

    </a>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
    <div class="divide-y">
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('orders.show', $o)); ?>" class="block p-4 hover:bg-slate-50">
                <div>
                    <div class="font-bold"><?php echo e($o->order_no); ?></div>
                    <div class="text-sm text-slate-500"><?php echo e($o->shop->name); ?> • <?php echo e($o->created_at->format('d M Y H:i')); ?></div>
                </div>
                <div class="text-right">
                    <div class="font-black text-rose-600">Rp <?php echo e(number_format($o->grand_total,0,',','.')); ?></div>
                    <div class="text-sm text-slate-600">Status: <span class="font-semibold"><?php echo e($o->status); ?></span></div>
                    <?php if($o->status === 'pending' && $o->payment_method === 'manual_transfer' && $o->expires_at): ?>
                        <div class="text-xs text-rose-600 font-semibold">Bayar sebelum <?php echo e($o->expires_at->format('d M Y H:i')); ?></div>
                    <?php endif; ?>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 text-slate-600">Belum ada pesanan.</div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-4"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/orders/mine.blade.php ENDPATH**/ ?>