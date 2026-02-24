<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['p']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['p']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="group bg-white border border-slate-200 rounded-xl overflow-hidden hover:shadow-sm transition" data-product-card>
  <div class="relative">
    <a href="<?php echo e(route('product.show',$p->slug)); ?>" class="block">
      <div class="relative aspect-square bg-slate-100 overflow-hidden">
        
        <div class="absolute inset-0 animate-pulse bg-slate-200" data-skel></div>
        <img
          src="<?php echo e($p->mainImageUrl()); ?>"
          class="w-full h-full object-cover opacity-0 transition duration-300"
          alt="<?php echo e($p->name); ?>"
          loading="lazy"
          data-skel-img
        >

        
        <div class="absolute top-1 left-1 flex flex-col gap-1">
          <?php if($p->shop && ($p->shop->is_official ?? false)): ?>
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded bg-slate-900 text-white">Official</span>
          <?php endif; ?>
          <?php if(method_exists($p,'hasDiscount') && $p->hasDiscount()): ?>
            <span class="text-[10px] font-black px-1.5 py-0.5 rounded bg-emerald-600 text-white">Diskon</span>
          <?php endif; ?>
        </div>

        
        <?php if((int)($p->sold_count ?? 0) > 0): ?>
          <div class="absolute bottom-1 left-1 text-[10px] font-black px-1.5 py-0.5 rounded bg-rose-600 text-white">
            Terjual <?php echo e((int)$p->sold_count); ?>

          </div>
        <?php endif; ?>
      </div>
    </a>

    
    <?php if(auth()->guard()->check()): ?>
      <form action="<?php echo e(route('cart.add', $p->id)); ?>" method="POST" class="absolute top-1 right-1 js-quick-add">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="qty" value="1">
        <button
          type="submit"
          class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-slate-200 hover:bg-slate-50"
          title="Tambah ke keranjang"
        >
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shopping-cart','class' => 'w-4 h-4 text-slate-800']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-4 h-4 text-slate-800']); ?>
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
    <?php else: ?>
      <a
        href="<?php echo e(route('login')); ?>"
        class="absolute top-1 right-1 inline-flex items-center justify-center w-9 h-9 rounded-full bg-white/95 backdrop-blur border border-slate-200 hover:bg-slate-50"
        title="Login untuk beli"
      >
        <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shopping-cart','class' => 'w-4 h-4 text-slate-800']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-4 h-4 text-slate-800']); ?>
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
      </a>
    <?php endif; ?>
  </div>

  <a href="<?php echo e(route('product.show',$p->slug)); ?>" class="block p-2">
    <div class="text-[12px] leading-snug line-clamp-2 min-h-[32px] text-slate-800"><?php echo e($p->name); ?></div>

    <div class="mt-1 flex items-baseline gap-1">
      <?php if(method_exists($p,'hasDiscount') && $p->hasDiscount()): ?>
        <div class="font-extrabold text-[13px] text-slate-900">Rp <?php echo e(number_format($p->discountedPrice(),0,',','.')); ?></div>
        <div class="text-[10px] text-slate-400 line-through">Rp <?php echo e(number_format($p->price,0,',','.')); ?></div>
      <?php else: ?>
        <div class="font-extrabold text-[13px] text-slate-900">Rp <?php echo e(number_format($p->price,0,',','.')); ?></div>
      <?php endif; ?>
    </div>

    <div class="mt-1 flex items-center justify-between gap-2 text-[11px] text-slate-500">
      <div class="flex items-center gap-1 min-w-0">
        <span class="text-rose-600">★</span>
        <?php ($avg = (float)($p->reviews_avg_rating ?? 0)); ?>
        <span><?php echo e($avg > 0 ? number_format($avg, 1) : '0.0'); ?></span>
        <span class="text-slate-300">•</span>
        <span class="truncate"><?php echo e((int)($p->reviews_count ?? 0)); ?> ulasan</span>
      </div>
      <div class="truncate max-w-[92px]">
        <?php echo e($p->shop?->name ?? '-'); ?>

      </div>
    </div>
  </a>
</div>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/storefront/product-card.blade.php ENDPATH**/ ?>