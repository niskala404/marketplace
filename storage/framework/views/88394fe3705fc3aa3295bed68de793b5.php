<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalddf44183544a95f193518110979774f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalddf44183544a95f193518110979774f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.page','data' => ['title' => 'Pesan','subtitle' => 'Percakapan dengan toko']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Pesan','subtitle' => 'Percakapan dengan toko']); ?>
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
    <div class="divide-y">
      <?php $__empty_1 = true; $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $name = $c->shop->name ?? 'Toko';
          $initial = strtoupper(mb_substr($name, 0, 1));
          $preview = $c->latestMessage?->body ?? 'Mulai percakapan';
          $time = optional($c->last_message_at)->diffForHumans();
          $unread = $c->unread_count ?? null;
        ?>

        <a href="<?php echo e(route('messages.show', $c)); ?>" class="block p-4 hover:bg-slate-50 transition">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center font-black text-rose-700 shrink-0">
              <?php echo e($initial); ?>

            </div>

            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                  <div class="font-extrabold truncate"><?php echo e($name); ?></div>
                  <div class="text-sm text-slate-500 truncate"><?php echo e($preview); ?></div>
                </div>

                <div class="flex flex-col items-end gap-2 shrink-0">
                  <div class="text-xs text-slate-400 whitespace-nowrap"><?php echo e($time); ?></div>
                  <?php if(!is_null($unread) && (int)$unread > 0): ?>
                    <div class="min-w-[20px] h-5 px-2 rounded-full bg-rose-600 text-white text-[11px] font-bold flex items-center justify-center">
                      <?php echo e((int)$unread > 99 ? '99+' : (int)$unread); ?>

                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="text-slate-300 shrink-0">›</div>
          </div>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-8 text-center">
          <div class="text-lg font-black">Belum ada percakapan</div>
          <div class="text-sm text-slate-500 mt-1">Kamu bisa mulai chat dari halaman toko.</div>
          <a href="<?php echo e(route('home')); ?>" class="inline-flex mt-4 px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Cari Produk</a>
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

  <div class="mt-4"><?php echo e($conversations->links()); ?></div>
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

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/messages/index.blade.php ENDPATH**/ ?>