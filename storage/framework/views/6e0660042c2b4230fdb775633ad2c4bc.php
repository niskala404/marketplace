<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalddf44183544a95f193518110979774f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalddf44183544a95f193518110979774f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.page','data' => ['title' => 'Saldo','subtitle' => 'Refund dan penyesuaian saldo masuk ke sini']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Saldo','subtitle' => 'Refund dan penyesuaian saldo masuk ke sini']); ?>
  <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'mb-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4']); ?>
    <div class="flex items-center justify-between">
      <div>
        <div class="text-sm text-slate-500">Saldo Saat Ini</div>
        <div class="text-2xl font-black mt-1">Rp <?php echo e(number_format((int)($wallet->balance ?? 0), 0, ',', '.')); ?></div>
      </div>
      <div class="text-right">
        <div class="text-xs text-slate-500">Catatan</div>
        <div class="text-sm text-slate-700">Saldo ini berasal dari refund (dispute/cancel setelah bayar).</div>
      </div>
    </div>
   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

  <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['padding' => 'p-0','class' => 'overflow-hidden']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['padding' => 'p-0','class' => 'overflow-hidden']); ?>
    <div class="p-4 border-b">
      <div class="font-bold">Riwayat Saldo</div>
      <div class="text-sm text-slate-500">Transaksi terbaru akan muncul di atas.</div>
    </div>

    <div class="divide-y">
      <?php if($wallet): ?>
        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="p-4 flex items-start justify-between gap-4">
            <div class="min-w-0">
              <div class="font-semibold">
                <?php echo e($t->type === 'refund_credit' ? 'Refund' : ucfirst(str_replace('_',' ', $t->type))); ?>

              </div>
              <div class="text-sm text-slate-600 mt-1">
                <?php if($t->order_id): ?>
                  Order: <span class="font-mono"><?php echo e($t->meta['order_no'] ?? ('#'.$t->order_id)); ?></span>
                <?php else: ?>
                  <?php echo e($t->meta['note'] ?? '-'); ?>

                <?php endif; ?>
              </div>
              <div class="text-xs text-slate-400 mt-2"><?php echo e($t->created_at->format('d M Y H:i')); ?></div>
            </div>

            <div class="shrink-0 text-right">
              <div class="font-black <?php echo e($t->amount >= 0 ? 'text-emerald-700' : 'text-rose-700'); ?>">
                <?php echo e($t->amount >= 0 ? '+' : '-'); ?>Rp <?php echo e(number_format(abs((int)$t->amount), 0, ',', '.')); ?>

              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="p-8 text-center text-slate-600">
            <div class="text-lg font-black text-slate-900">Belum ada transaksi</div>
            <div class="text-sm text-slate-500 mt-1">Transaksi saldo akan muncul setelah ada refund.</div>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="p-8 text-center text-slate-600">
          <div class="text-lg font-black text-slate-900">Saldo kamu masih kosong</div>
          <div class="text-sm text-slate-500 mt-1">Saldo akan dibuat otomatis saat ada refund pertama.</div>
        </div>
      <?php endif; ?>
    </div>
   <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $attributes = $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93)): ?>
<?php $component = $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93; ?>
<?php unset($__componentOriginaldae4cd48acb67888a4631e1ba48f2f93); ?>
<?php endif; ?>

  <?php if($wallet): ?>
    <div class="mt-4"><?php echo e($transactions->links()); ?></div>
  <?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalddf44183544a95f193518110979774f8)): ?>
<?php $attributes = $__attributesOriginalddf44183544a95f193518110979774f8; ?>
<?php unset($__attributesOriginalddf44183544a95f193518110979774f8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalddf44183544a95f193518110979774f8)): ?>
<?php $component = $__componentOriginalddf44183544a95f193518110979774f8; ?>
<?php unset($__componentOriginalddf44183544a95f193518110979774f8); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/wallet/index.blade.php ENDPATH**/ ?>