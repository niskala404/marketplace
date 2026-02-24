<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalddf44183544a95f193518110979774f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalddf44183544a95f193518110979774f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.page','data' => ['title' => 'Notifikasi','subtitle' => 'Update terbaru untuk akunmu']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Notifikasi','subtitle' => 'Update terbaru untuk akunmu']); ?>
   <?php $__env->slot('actions', null, []); ?> 
    <form method="POST" action="<?php echo e(route('notifications.read_all')); ?>">
      <?php echo csrf_field(); ?>
      <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'md']); ?>
        <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'check-check','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-check','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $attributes = $__attributesOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__attributesOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $component = $__componentOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__componentOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
        <span>Tandai semua dibaca</span>
       <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
    </form>
   <?php $__env->endSlot(); ?>

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
    <div class="divide-y" id="notifList">
      <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
          $data = $n->data;
          $title = $data['title'] ?? 'Notifikasi';
          $msg = $data['message'] ?? '';
          $url = $data['url'] ?? null;
        ?>
        <div class="p-4 flex items-start justify-between gap-4 <?php echo e($n->read_at ? '' : 'bg-rose-50'); ?>" data-nid="<?php echo e($n->id); ?>">
          <div class="min-w-0">
            <div class="font-bold"><?php echo e($title); ?></div>
            <div class="text-sm text-slate-600 mt-1 whitespace-pre-line"><?php echo e($msg); ?></div>
            <div class="text-xs text-slate-400 mt-2"><?php echo e($n->created_at->format('d M Y H:i')); ?></div>
          </div>

          <div class="flex gap-2 shrink-0">
            <?php if($url): ?>
              <a href="<?php echo e($url); ?>" class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Lihat</a>
            <?php else: ?>
              <form method="POST" action="<?php echo e(route('notifications.read', $n->id)); ?>">
                <?php echo csrf_field(); ?>
                <button class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Tandai</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="p-8 text-center text-slate-600" id="notifEmpty">
          <div class="text-lg font-black text-slate-900">Belum ada notifikasi</div>
          <div class="text-sm text-slate-500 mt-1">Notifikasi akan muncul setelah ada aktivitas.</div>
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

  <div class="mt-4"><?php echo e($notifications->links()); ?></div>

  <?php if(auth()->guard()->check()): ?>
  <script>
  (function(){
    const list = document.getElementById('notifList');
    const empty = document.getElementById('notifEmpty');
    const userId = <?php echo json_encode(auth()->id(), 15, 512) ?>;

    if(!window.Echo || !userId || !list) return;

    function escapeHtml(s){
      return (s ?? '').toString().replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
    }

    function formatTime(ts){
      try { return new Date(ts).toLocaleString('id-ID'); } catch(e){ return ts; }
    }

    window.Echo.private(`App.Models.User.${userId}`)
      .notification((n) => {
        if(empty) empty.remove();

        const title = escapeHtml(n.title || 'Notifikasi');
        const message = escapeHtml(n.message || '');
        const created = formatTime(n.created_at || new Date().toISOString());
        const href = n.url ? n.url : '<?php echo e(route('notifications.index')); ?>';

        const row = document.createElement('div');
        row.className = 'p-4 flex items-start justify-between gap-4 bg-rose-50';
        row.innerHTML = `
          <div class="min-w-0">
            <div class="font-bold">${title}</div>
            <div class="text-sm text-slate-600 mt-1 whitespace-pre-line">${message}</div>
            <div class="text-xs text-slate-400 mt-2">${created}</div>
          </div>
          <div class="flex gap-2 shrink-0">
            <a href="${href}" class="px-3 py-2 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Lihat</a>
          </div>
        `;
        list.prepend(row);
      });
  })();
  </script>
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

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/notifications/index.blade.php ENDPATH**/ ?>