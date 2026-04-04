<?php $__env->startSection('content'); ?>
<?php if (isset($component)) { $__componentOriginalddf44183544a95f193518110979774f8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalddf44183544a95f193518110979774f8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app.page','data' => ['title' => 'Keranjang','subtitle' => 'Cek barang sebelum checkout']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app.page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Keranjang','subtitle' => 'Cek barang sebelum checkout']); ?>

  <?php if($items->isEmpty()): ?>
    <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty','data' => ['title' => 'Keranjang kamu kosong','message' => 'Yuk cari produk menarik dulu.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Keranjang kamu kosong','message' => 'Yuk cari produk menarik dulu.']); ?>
       <?php $__env->slot('action', null, []); ?> 
        <a href="<?php echo e(route('home')); ?>" class="inline-flex px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Mulai Belanja</a>
       <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756)): ?>
<?php $attributes = $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756; ?>
<?php unset($__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756)): ?>
<?php $component = $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756; ?>
<?php unset($__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756); ?>
<?php endif; ?>
  <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-3">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $p = $it->product;
            $orig = (int)($p->price ?? 0);
            $flashPriceMap = $flashPriceMap ?? [];
            $flashPromo = $flashPriceMap[$p->id] ?? null;
            $price = $flashPromo !== null
                ? (int)$flashPromo
                : (method_exists($p,'discountedPrice') ? (int)$p->discountedPrice() : (int)$p->price);
            $line = $price * (int)$it->qty;
          ?>

          <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="flex gap-4">
              <img class="w-24 h-24 rounded-2xl object-cover border bg-slate-100" src="<?php echo e($p->mainImageUrl()); ?>" alt="<?php echo e($p->name); ?>">

              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <div class="font-extrabold line-clamp-2"><?php echo e($p->name); ?></div>
                    <?php if($it->variant): ?>
                      <div class="text-xs text-slate-500 mt-1">Varian: <span class="font-semibold"><?php echo e($it->variant->name); ?></span></div>
                    <?php endif; ?>
                    <div class="text-sm text-slate-500 mt-0.5 inline-flex items-center gap-1">
                      <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'store','class' => 'w-4 h-4 text-slate-400']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'store','class' => 'w-4 h-4 text-slate-400']); ?>
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
                      <span class="truncate"><?php echo e($p->shop->name ?? '-'); ?></span>
                    </div>
                  </div>

                  <form method="POST" action="<?php echo e(route('cart.remove',$it->id)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700" title="Hapus" aria-label="Hapus">
                      <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'trash-2','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash-2','class' => 'w-5 h-5']); ?>
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
                    </button>
                  </form>
                </div>

                <div class="mt-3 flex items-end justify-between gap-3">
                  <div>
                    <div class="flex items-center gap-2">
                      <div class="text-rose-600 font-black text-lg">Rp <?php echo e(number_format($price,0,',','.')); ?></div>
                      <?php if($flashPromo !== null): ?>
                        <span class="px-2 py-0.5 rounded-full text-xs font-black bg-rose-600 text-white">FLASH SALE</span>
                      <?php endif; ?>
                    </div>
                    <?php if(method_exists($p,'hasDiscount') && $p->hasDiscount()): ?>
                      <div class="text-xs text-slate-500 line-through">Rp <?php echo e(number_format($orig,0,',','.')); ?></div>
                    <?php endif; ?>
                    <div class="text-xs text-slate-500 mt-0.5">Subtotal: <span class="font-semibold">Rp <?php echo e(number_format($line,0,',','.')); ?></span></div>
                  </div>

                  <form method="POST" action="<?php echo e(route('cart.update',$it->id)); ?>" class="flex items-center gap-2 qtyForm">
                    <?php echo csrf_field(); ?>
                    <button type="button" class="w-10 h-10 rounded-xl border bg-white hover:bg-slate-50 font-black qtyMinus" aria-label="Kurangi">−</button>
                    <input name="qty" type="number" min="1" value="<?php echo e($it->qty); ?>" class="w-16 text-center rounded-xl border-slate-200 bg-slate-50 qtyInput">
                    <button type="button" class="w-10 h-10 rounded-xl border bg-white hover:bg-slate-50 font-black qtyPlus" aria-label="Tambah">+</button>
                  </form>
                </div>
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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>

      <div class="hidden lg:block">
        <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'sticky top-24','padding' => 'p-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'sticky top-24','padding' => 'p-5']); ?>
          <div class="font-black text-lg">Ringkasan Belanja</div>
          <div class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-slate-600">Subtotal</span>
              <span class="font-bold">Rp <?php echo e(number_format($subtotal,0,',','.')); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-600">Ongkir</span>
              <span class="text-slate-500">Dihitung saat checkout</span>
            </div>
            <div class="border-t pt-3 flex justify-between">
              <span class="font-black">Total</span>
              <span class="font-black text-rose-600">Rp <?php echo e(number_format($subtotal,0,',','.')); ?></span>
            </div>
          </div>

          <a href="<?php echo e(route('checkout.show')); ?>" class="mt-4 block text-center px-4 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700">
            Checkout
          </a>

          <div class="text-xs text-slate-500 mt-3">*S&K berlaku</div>
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
      </div>
    </div>

    
    <div class="fixed bottom-0 left-0 right-0 z-30 lg:hidden">
      <div class="max-w-6xl mx-auto px-4 pb-3">
        <div class="bg-white border rounded-2xl shadow-lg p-3 flex items-center gap-3">
          <div class="flex-1">
            <div class="text-xs text-slate-500">Total</div>
            <div class="font-black text-rose-600">Rp <?php echo e(number_format($subtotal,0,',','.')); ?></div>
          </div>
          <a href="<?php echo e(route('checkout.show')); ?>" class="px-5 py-3 rounded-2xl bg-rose-600 text-white font-black hover:bg-rose-700">Checkout</a>
        </div>
      </div>
    </div>
    <div class="h-24 lg:hidden"></div>

    <script>
    (function(){
      function clamp(v){
        v = parseInt(v || '1', 10);
        if(isNaN(v) || v < 1) v = 1;
        return v;
      }
      document.querySelectorAll('.qtyForm').forEach(form => {
        const input = form.querySelector('.qtyInput');
        const minus = form.querySelector('.qtyMinus');
        const plus  = form.querySelector('.qtyPlus');

        minus.addEventListener('click', () => {
          input.value = Math.max(1, clamp(input.value) - 1);
          form.submit();
        });
        plus.addEventListener('click', () => {
          input.value = clamp(input.value) + 1;
          form.submit();
        });
        input.addEventListener('change', () => {
          input.value = clamp(input.value);
          form.submit();
        });
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

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/cart/index.blade.php ENDPATH**/ ?>