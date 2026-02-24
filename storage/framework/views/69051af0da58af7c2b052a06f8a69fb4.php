<?php $__env->startSection('content'); ?>
<style>
  #bannerSlider::-webkit-scrollbar { display: none; }
  #bannerSlider { -ms-overflow-style: none; scrollbar-width: none; }

  /* Mobile bottom-sheet animation */
  .sheet-backdrop{opacity:0;pointer-events:none;transition:opacity .2s ease;}
  .sheet-backdrop.open{opacity:1;pointer-events:auto;}
  .sheet-panel{transform:translateY(100%);transition:transform .25s ease;}
  .sheet-panel.open{transform:translateY(0);}

  /* Toast */
  .toast{opacity:0;transform:translateY(8px);pointer-events:none;transition:opacity .18s ease, transform .18s ease;}
  .toast.show{opacity:1;transform:translateY(0);pointer-events:auto;}

  /* Flash sale countdown + sold animation */
  .fs-countdown{font-variant-numeric:tabular-nums;}
  .fs-progress{position:relative;overflow:hidden;}
  .fs-progress::after{content:'';position:absolute;top:0;left:-45%;width:45%;height:100%;background:rgba(255,255,255,.35);transform:skewX(-20deg);animation:fs-shine 1.25s linear infinite;}
  @keyframes fs-shine{0%{left:-45%;}100%{left:120%;}}
</style>

<?php
  $activeFilterCount = 0;
  if(!empty($minPrice)) $activeFilterCount++;
  if(!empty($maxPrice)) $activeFilterCount++;
  if(!empty($minRating) && (float)$minRating > 0) $activeFilterCount++;
  if(!empty($sort) && $sort !== 'newest') $activeFilterCount++;

  $resetUrl = route('home');
  $baseParams = array_filter([
    'q' => $q,
    'category' => $category,
    'min_price' => $minPrice,
    'max_price' => $maxPrice,
    'min_rating' => $minRating,
    'sort' => $sort,
  ], fn($v) => !is_null($v) && $v !== '' && $v !== false);
?>

<div class="max-w-6xl mx-auto space-y-4">

  
  <?php if(isset($banners) && $banners->count()): ?>
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
      <div id="bannerSlider" class="relative flex overflow-x-auto snap-x snap-mandatory scroll-smooth">
        <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="min-w-full snap-start">
            <img
              src="<?php echo e($b->image_url ?? asset('storage/'.$b->image_path)); ?>"
              class="w-full h-44 sm:h-56 object-cover"
              alt="<?php echo e($b->title ?? 'Banner'); ?>"
              loading="lazy"
            >
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
  <?php endif; ?>

  
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
    <div class="grid grid-cols-5 sm:grid-cols-10 gap-2">
      <?php
        $quickMenus = [
          ['label' => 'Gratis Ongkir', 'icon' => 'truck'],
          ['label' => 'Voucher', 'icon' => 'tag'],
          ['label' => 'Flash Sale', 'icon' => 'zap'],
          ['label' => 'Saldo', 'icon' => 'wallet'],
          ['label' => 'Keranjang', 'icon' => 'shopping-cart'],
          ['label' => 'Belanja', 'icon' => 'shopping-bag'],
          ['label' => 'Toko', 'icon' => 'store'],
          ['label' => 'Pembayaran', 'icon' => 'credit-card'],
          ['label' => 'Pesanan', 'icon' => 'package'],
          ['label' => 'Aman', 'icon' => 'shield-check'],
        ];
      ?>

      <?php $__currentLoopData = $quickMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="#products" class="group flex flex-col items-center gap-1 p-2 rounded-2xl hover:bg-slate-50">
          <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-slate-100 group-hover:bg-white border border-slate-200">
            <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => ''.e($m['icon']).'','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => ''.e($m['icon']).'','class' => 'w-5 h-5 text-slate-700']); ?>
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
          </span>
          <span class="text-[11px] font-semibold text-slate-700 text-center leading-tight line-clamp-2"><?php echo e($m['label']); ?></span>
        </a>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

  
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'bg-gradient-to-r from-rose-50 to-white border-rose-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-gradient-to-r from-rose-50 to-white border-rose-200']); ?>
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-rose-600">
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'tag','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'tag','class' => 'w-5 h-5 text-white']); ?>
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
        </span>
        <div class="min-w-0">
          <div class="font-black">Voucher Diskon</div>
          <div class="text-xs text-slate-500">Klaim voucher hemat belanja</div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'bg-gradient-to-r from-emerald-50 to-white border-emerald-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-gradient-to-r from-emerald-50 to-white border-emerald-200']); ?>
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-600">
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'truck','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'truck','class' => 'w-5 h-5 text-white']); ?>
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
        </span>
        <div class="min-w-0">
          <div class="font-black">Gratis Ongkir</div>
          <div class="text-xs text-slate-500">S&K berlaku, cek di checkout</div>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'bg-gradient-to-r from-slate-50 to-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-gradient-to-r from-slate-50 to-white']); ?>
      <div class="flex items-center gap-3">
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-slate-900">
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shield-check','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-5 h-5 text-white']); ?>
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
        </span>
        <div class="min-w-0">
          <div class="font-black">Belanja Aman</div>
          <div class="text-xs text-slate-500">Pembayaran terlindungi</div>
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
  </div>

  
  <?php if(isset($activeFlashSale) && $activeFlashSale && isset($flashItems) && $flashItems->count()): ?>
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'border-rose-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'border-rose-200']); ?>
      <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
          <div class="font-black text-lg text-rose-700">Flash Sale: <?php echo e($activeFlashSale->name); ?></div>
          <div class="text-xs text-slate-500 mt-0.5">
            Berakhir: <?php echo e(\Carbon\Carbon::parse($activeFlashSale->ends_at)->timezone(config('app.timezone'))->format('d/m/Y H:i')); ?>

          </div>
        </div>
        <div class="shrink-0 inline-flex items-center px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-black">
          FLASH SALE
        </div>
      </div>

      <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2 sm:gap-3">
        <?php $__currentLoopData = $flashItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php
            $p = $it->product;
            if(!$p) continue;
            $img = $p->images->first();
            $promo = $it->promo_price ?? $p->price;
            $endIso = \Carbon\Carbon::parse($activeFlashSale->ends_at)->toIso8601String();
            $sold = (int)($it->sold ?? 0);
            $quota = $it->quota !== null ? (int)$it->quota : null;
            $pct = $quota ? (int)round(min(1, $sold / max(1,$quota)) * 100) : null;
          ?>

          <a href="<?php echo e(route('product.show', $p->slug)); ?>" class="block rounded-2xl border overflow-hidden hover:shadow-sm transition bg-white">
            <div class="relative aspect-[4/3] bg-slate-100">
              <?php if($img): ?>
                <img
                  src="<?php echo e(asset('storage/'.$img->path)); ?>"
                  class="w-full h-full object-cover"
                  alt="<?php echo e($p->name); ?>"
                  loading="lazy"
                >
              <?php endif; ?>
              <div class="absolute left-2 top-2 inline-flex text-[10px] bg-rose-600 text-white px-2 py-1 rounded-full font-black">
                FLASH
              </div>

              
              <div
                class="absolute right-2 top-2 fs-countdown inline-flex items-center gap-1 text-[10px] px-2 py-1 rounded-full bg-white/95 border border-rose-200 text-rose-700 font-black shadow-sm"
                data-fs-countdown
                data-end="<?php echo e($endIso); ?>"
                aria-label="Sisa waktu flash sale"
              >
                <span data-hh>00</span><span class="opacity-60">:</span><span data-mm>00</span><span class="opacity-60">:</span><span data-ss>00</span>
              </div>
            </div>
            <div class="p-3">
              <div class="font-semibold line-clamp-2"><?php echo e($p->name); ?></div>

              <div class="mt-2">
                <div class="text-rose-600 font-black">
                  Rp <?php echo e(number_format($promo,0,',','.')); ?>

                </div>
                <?php if($it->promo_price !== null && $it->promo_price < $p->price): ?>
                  <div class="text-xs text-slate-400 line-through">
                    Rp <?php echo e(number_format($p->price,0,',','.')); ?>

                  </div>
                <?php endif; ?>
              </div>

              
              <div class="mt-2">
                <div class="flex items-center gap-2">
                  <span class="inline-flex items-center gap-1 text-[11px] font-black text-rose-700">
                    <span class="text-base leading-none animate-bounce">🔥</span>
                    <span><?php echo e($sold > 0 ? number_format($sold,0,',','.') . ' TERJUAL' : 'BARU'); ?></span>
                  </span>

                  <?php if($quota !== null): ?>
                    <span class="text-[10px] text-slate-400">/<?php echo e(number_format($quota,0,',','.')); ?></span>
                  <?php endif; ?>
                </div>

                <?php if($pct !== null): ?>
                  <div class="mt-1 h-3 rounded-full bg-rose-100 overflow-hidden">
                    <div class="h-full bg-rose-500 fs-progress rounded-full" style="width: <?php echo e($pct); ?>%"></div>
                  </div>
                <?php else: ?>
                  <div class="mt-1 h-3 rounded-full bg-rose-100 overflow-hidden">
                    <div class="h-full bg-rose-500 fs-progress rounded-full" style="width: <?php echo e(min(100, max(5, (int)round(($sold % 20) / 20 * 100)))); ?>%"></div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
  <?php endif; ?>

  
  <div class="mt-4">
    <?php if (isset($component)) { $__componentOriginaldae4cd48acb67888a4631e1ba48f2f93 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldae4cd48acb67888a4631e1ba48f2f93 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.card','data' => ['class' => 'bg-white/95 backdrop-blur shadow-sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-white/95 backdrop-blur shadow-sm']); ?>
      
      <div class="flex items-start sm:items-center justify-between gap-3">
        <div class="min-w-0">
          <div class="font-black text-base sm:text-lg leading-tight">Kategori</div>
          <div class="text-slate-500 text-xs sm:text-sm mt-0.5 flex flex-wrap items-center gap-1">
            <?php if($q): ?>
              <span class="truncate">Hasil untuk “<?php echo e($q); ?>”</span>
            <?php else: ?>
              <span>Jelajahi produk terbaru</span>
            <?php endif; ?>

            <span id="selectedCategoryWrap" class="<?php echo e($category ? '' : 'hidden'); ?>">
              <span>•</span>
              <span id="selectedCategoryName" class="text-rose-700 font-semibold">
                <?php echo e($category ? (optional($categories->firstWhere('id', (int)$category))->name ?? 'Kategori dipilih') : ''); ?>

              </span>
            </span>
          </div>
        </div>

        <a href="#products" class="hidden sm:inline-flex items-center gap-2 text-sm font-bold text-rose-700 hover:underline">
          Lihat produk
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'chevron-right','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-right','class' => 'w-4 h-4']); ?>
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
      </div>

      
      <div class="mt-3 rounded-2xl border bg-white/90 px-3 py-2">
        <div class="flex items-center gap-2">
          <form id="searchForm" data-ajax-search action="<?php echo e(route('home')); ?>" method="GET" class="flex-1">
            
            <?php if($category): ?>
              <input type="hidden" name="category" value="<?php echo e($category); ?>">
            <?php endif; ?>
            <?php if($minPrice !== null && $minPrice !== ''): ?>
              <input type="hidden" name="min_price" value="<?php echo e($minPrice); ?>">
            <?php endif; ?>
            <?php if($maxPrice !== null && $maxPrice !== ''): ?>
              <input type="hidden" name="max_price" value="<?php echo e($maxPrice); ?>">
            <?php endif; ?>
            <?php if($minRating !== null && $minRating !== '' && (float)$minRating > 0): ?>
              <input type="hidden" name="min_rating" value="<?php echo e($minRating); ?>">
            <?php endif; ?>
            <?php if($sort !== null && $sort !== '' && $sort !== 'newest'): ?>
              <input type="hidden" name="sort" value="<?php echo e($sort); ?>">
            <?php endif; ?>

         
          </form>

          
          <button
            type="button"
            id="filterBtn"
            class="relative inline-flex items-center justify-center w-11 h-11 rounded-2xl border bg-white hover:bg-slate-50"
            aria-expanded="false"
            aria-controls="filterDropdown"
            title="Filter"
          >
            <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'sliders-horizontal','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'sliders-horizontal','class' => 'w-5 h-5 text-slate-700']); ?>
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

            <span id="filterBadge" class="absolute -top-2 -right-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full bg-rose-600 text-white text-[11px] font-black <?php echo e($activeFilterCount > 0 ? '' : 'hidden'); ?>">
              <?php echo e($activeFilterCount); ?>

            </span>
          </button>

          
          <?php if($q || $category || $activeFilterCount > 0): ?>
            <a href="<?php echo e($resetUrl); ?>"
               class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-slate-900 text-white hover:bg-slate-800"
               title="Reset">
              <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'rotate-ccw','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'rotate-ccw','class' => 'w-5 h-5 text-white']); ?>
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

        
        <div id="filterDropdown" class="mt-3 hidden sm:block">
          <div id="filterDropdownInner" class="hidden rounded-2xl border bg-slate-50 p-4">
            <form action="<?php echo e(route('home')); ?>" method="GET" data-filter-form class="space-y-4 js-filter-form">
              <input type="hidden" name="q" value="<?php echo e($q); ?>">
              <?php if($category): ?>
                <input type="hidden" name="category" value="<?php echo e($category); ?>">
              <?php endif; ?>

              <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
                <div class="col-span-1">
                  <label class="text-xs font-semibold text-slate-600">Min Harga</label>
                  <input name="min_price" inputmode="numeric" value="<?php echo e($minPrice ?? ''); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="0">
                </div>

                <div class="col-span-1">
                  <label class="text-xs font-semibold text-slate-600">Max Harga</label>
                  <input name="max_price" inputmode="numeric" value="<?php echo e($maxPrice ?? ''); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="500000">
                </div>

                <div class="col-span-1">
                  <label class="text-xs font-semibold text-slate-600">Rating Min</label>
                  <select name="min_rating" class="mt-1 w-full rounded-xl border-slate-200">
                    <?php ($mr = (float)($minRating ?? 0)); ?>
                    <option value="0" <?php echo e($mr <= 0 ? 'selected' : ''); ?>>Semua</option>
                    <option value="4" <?php echo e($mr == 4 ? 'selected' : ''); ?>>4.0+</option>
                    <option value="4.5" <?php echo e($mr == 4.5 ? 'selected' : ''); ?>>4.5+</option>
                    <option value="5" <?php echo e($mr == 5 ? 'selected' : ''); ?>>5.0</option>
                  </select>
                </div>

                <div class="col-span-1 sm:col-span-2">
                  <label class="text-xs font-semibold text-slate-600">Urutkan</label>
                  <select name="sort" class="mt-1 w-full rounded-xl border-slate-200">
                    <option value="newest" <?php echo e(($sort ?? 'newest') === 'newest' ? 'selected' : ''); ?>>Terbaru</option>
                    <option value="best_selling" <?php echo e(($sort ?? '') === 'best_selling' ? 'selected' : ''); ?>>Terlaris</option>
                    <option value="rating" <?php echo e(($sort ?? '') === 'rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
                    <option value="price_asc" <?php echo e(($sort ?? '') === 'price_asc' ? 'selected' : ''); ?>>Harga Terendah</option>
                    <option value="price_desc" <?php echo e(($sort ?? '') === 'price_desc' ? 'selected' : ''); ?>>Harga Tertinggi</option>
                  </select>
                </div>
              </div>

              <div class="flex flex-col sm:flex-row gap-2 sm:items-center sm:justify-end">
                <button class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">
                  <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'check','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-5 h-5 text-white']); ?>
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
                  Terapkan
                </button>

                <?php if(request()->hasAny(['min_price','max_price','min_rating','sort']) && (request('min_price') || request('max_price') || (float)request('min_rating') > 0 || (request('sort') && request('sort') !== 'newest'))): ?>
                  <a href="<?php echo e(route('home', array_filter(['q' => $q, 'category' => $category]))); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border font-bold hover:bg-white">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'x','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5 text-slate-700']); ?>
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
                    Reset Filter
                  </a>
                <?php endif; ?>
              </div>
            </form>
          </div>
        </div>
      </div>

      
      <div class="mt-4">
        <div id="catChips" class="grid grid-cols-5 sm:grid-cols-8 md:grid-cols-10 gap-3">
          
          <a href="<?php echo e(route('home', array_filter(['q' => $q]))); ?>"
             data-cat-chip
             data-cat-id=""
             data-cat-name="Semua"
             class="group flex flex-col items-center text-center gap-2 p-2 rounded-2xl border transition <?php echo e(!$category ? 'bg-slate-900 text-white border-slate-900' : 'hover:bg-slate-50'); ?>">
            <div data-cat-icon-wrap class="w-14 h-14 rounded-full border overflow-hidden flex items-center justify-center <?php echo e(!$category ? 'bg-slate-800 border-slate-700' : 'bg-slate-100'); ?>">
              <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'grid-2x2','dataCatIcon' => true,'class' => 'w-6 h-6 '.e(!$category ? 'text-white' : 'text-slate-500').'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'grid-2x2','data-cat-icon' => true,'class' => 'w-6 h-6 '.e(!$category ? 'text-white' : 'text-slate-500').'']); ?>
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
            </div>
            <div data-cat-label class="text-[11px] leading-tight line-clamp-2 min-h-[28px] <?php echo e(!$category ? 'text-white' : 'text-slate-700 group-hover:text-rose-700'); ?>">
              Semua
            </div>
          </a>

          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('home', array_filter(['q' => $q, 'category' => $cat->id]))); ?>"
               data-cat-chip
               data-cat-id="<?php echo e($cat->id); ?>"
               data-cat-name="<?php echo e($cat->name); ?>"
               class="group flex flex-col items-center text-center gap-2 p-2 rounded-2xl border transition <?php echo e((string)$category === (string)$cat->id ? 'bg-slate-900 text-white border-slate-900' : 'hover:bg-slate-50'); ?>">
              <div data-cat-icon-wrap class="w-14 h-14 rounded-full border overflow-hidden flex items-center justify-center <?php echo e((string)$category === (string)$cat->id ? 'bg-slate-800 border-slate-700' : 'bg-slate-100'); ?>">
                <?php if($cat->image_path): ?>
                  <img src="<?php echo e($cat->imageUrl()); ?>" alt="<?php echo e($cat->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                  <span data-cat-fallback class="font-black <?php echo e((string)$category === (string)$cat->id ? 'text-white/80' : 'text-slate-400'); ?> text-lg">
                    <?php echo e(strtoupper(mb_substr($cat->name,0,1))); ?>

                  </span>
                <?php endif; ?>
              </div>
              <div data-cat-label class="text-[11px] leading-tight line-clamp-2 min-h-[28px] <?php echo e((string)$category === (string)$cat->id ? 'text-white' : 'text-slate-700 group-hover:text-rose-700'); ?>">
                <?php echo e($cat->name); ?>

              </div>
            </a>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
  </div>

  
  <div id="products"></div>
  <?php if($products->count() === 0): ?>
    <?php if (isset($component)) { $__componentOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d34c8741b1a71c3623a1c9c1f10e756 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.empty','data' => ['title' => 'Produk tidak ditemukan','message' => 'Coba kata kunci lain atau pilih kategori berbeda.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Produk tidak ditemukan','message' => 'Coba kata kunci lain atau pilih kategori berbeda.']); ?>
       <?php $__env->slot('action', null, []); ?> 
        <a href="<?php echo e(route('home')); ?>" class="inline-flex px-4 py-2 rounded-xl bg-slate-900 text-white font-bold hover:bg-slate-800">Lihat Semua Produk</a>
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
    <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2 sm:gap-3" data-next-url="<?php echo e($products->nextPageUrl()); ?>">
      <?php echo $__env->make('storefront._product_cards', ['products' => $products], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    
    <div class="mt-4">
      <div class="flex items-center justify-between">
        <div class="font-black">Trending</div>
        <div class="text-xs text-slate-500">Cari cepat</div>
      </div>
      <div class="mt-2 flex flex-wrap gap-2">
        <?php ($tr = ['Kaos pria','Skincare','Headset','Sepatu','Jaket','Powerbank','Hijab','Aksesoris','Laptop','Parfum','Kacamata','Vitamin']); ?>
        <?php $__currentLoopData = $tr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <a href="<?php echo e(route('home', array_merge($baseParams, ['q' => $kw]))); ?>" class="text-xs px-3 py-1.5 rounded-full border bg-white hover:bg-slate-50">
            <?php echo e($kw); ?>

          </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    
    <div class="mt-6 flex flex-col items-center gap-3">
      <button id="loadMoreBtn" type="button" class="hidden px-5 py-3 rounded-2xl border font-black hover:bg-slate-50">
        Muat lagi
      </button>
      <div id="loadMoreHint" class="text-xs text-slate-500">Scroll untuk memuat produk lainnya</div>
      <div id="loadMoreSentinel" class="h-1"></div>
    </div>
  <?php endif; ?>

</div>


<div id="toast" class="toast fixed inset-x-0 bottom-4 z-[60] flex justify-center px-4">
  <div class="max-w-md w-full">
    <div class="rounded-2xl bg-slate-900 text-white px-4 py-3 shadow-lg flex items-center justify-between gap-3">
      <div class="flex items-center gap-2 min-w-0">
        <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'check-circle','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check-circle','class' => 'w-5 h-5 text-white']); ?>
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
        <div id="toastMsg" class="text-sm font-bold truncate">Berhasil</div>
      </div>
      <button type="button" id="toastClose" class="p-1.5 rounded-xl hover:bg-white/10" aria-label="Tutup">
        <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'x','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-5 h-5 text-white']); ?>
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
    </div>
  </div>
</div>


<div id="sheetBackdrop" class="sheet-backdrop fixed inset-0 z-50 sm:hidden bg-black/40">
  <div id="sheetPanel" class="sheet-panel absolute inset-x-0 bottom-0 bg-white rounded-t-3xl border-t shadow-2xl">
    <div class="p-4">
      <div class="flex items-center justify-between">
        <div class="font-black text-lg">Filter</div>
        <button type="button" id="sheetClose" class="p-2 rounded-xl hover:bg-slate-100">
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'x','class' => 'w-6 h-6 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'x','class' => 'w-6 h-6 text-slate-700']); ?>
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
      </div>
      <div class="text-xs text-slate-500 mt-1">Atur harga, rating, dan urutan.</div>

      <form action="<?php echo e(route('home')); ?>" method="GET" data-filter-form class="mt-4 space-y-4 js-filter-form">
        <input type="hidden" name="q" value="<?php echo e($q); ?>">
        <?php if($category): ?>
          <input type="hidden" name="category" value="<?php echo e($category); ?>">
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="text-xs font-semibold text-slate-600">Min Harga</label>
            <input name="min_price" inputmode="numeric" value="<?php echo e($minPrice ?? ''); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="0">
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-600">Max Harga</label>
            <input name="max_price" inputmode="numeric" value="<?php echo e($maxPrice ?? ''); ?>" class="mt-1 w-full rounded-xl border-slate-200" placeholder="500000">
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-600">Rating Min</label>
            <select name="min_rating" class="mt-1 w-full rounded-xl border-slate-200">
              <?php ($mr2 = (float)($minRating ?? 0)); ?>
              <option value="0" <?php echo e($mr2 <= 0 ? 'selected' : ''); ?>>Semua</option>
              <option value="4" <?php echo e($mr2 == 4 ? 'selected' : ''); ?>>4.0+</option>
              <option value="4.5" <?php echo e($mr2 == 4.5 ? 'selected' : ''); ?>>4.5+</option>
              <option value="5" <?php echo e($mr2 == 5 ? 'selected' : ''); ?>>5.0</option>
            </select>
          </div>

          <div>
            <label class="text-xs font-semibold text-slate-600">Urutkan</label>
            <select name="sort" class="mt-1 w-full rounded-xl border-slate-200">
              <option value="newest" <?php echo e(($sort ?? 'newest') === 'newest' ? 'selected' : ''); ?>>Terbaru</option>
              <option value="best_selling" <?php echo e(($sort ?? '') === 'best_selling' ? 'selected' : ''); ?>>Terlaris</option>
              <option value="rating" <?php echo e(($sort ?? '') === 'rating' ? 'selected' : ''); ?>>Rating Tertinggi</option>
              <option value="price_asc" <?php echo e(($sort ?? '') === 'price_asc' ? 'selected' : ''); ?>>Harga Terendah</option>
              <option value="price_desc" <?php echo e(($sort ?? '') === 'price_desc' ? 'selected' : ''); ?>>Harga Tertinggi</option>
            </select>
          </div>
        </div>

        <div class="flex gap-2">
          <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl bg-slate-900 text-white font-black hover:bg-slate-800">
            <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'check','class' => 'w-5 h-5 text-white']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-5 h-5 text-white']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $attributes = $__attributesOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__attributesOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $component = $__componentOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__componentOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?> Terapkan
          </button>
          <?php if($activeFilterCount > 0): ?>
            <a href="<?php echo e(route('home', array_filter(['q' => $q, 'category' => $category]))); ?>" class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border font-black hover:bg-slate-50">
              <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'rotate-ccw','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'rotate-ccw','class' => 'w-5 h-5 text-slate-700']); ?>
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
      </form>
    </div>
    <div class="h-4"></div>
  </div>
</div>


<?php if(isset($banners) && $banners->count()): ?>
<script>
(function(){
  const slider = document.getElementById('bannerSlider');
  if(!slider) return;

  let index = 0;
  let timer = null;

  const countSlides = () => slider.children ? slider.children.length : 0;
  const goTo = (i) => {
    const w = slider.clientWidth;
    slider.scrollTo({ left: i * w, behavior: 'smooth' });
  };
  const syncIndex = () => {
    const w = slider.clientWidth || 1;
    index = Math.round(slider.scrollLeft / w);
  };
  const start = () => {
    stop();
    timer = setInterval(() => {
      const n = countSlides();
      if(n <= 1) return;
      index = (index + 1) % n;
      goTo(index);
    }, 3000);
  };
  const stop = () => { if(timer) clearInterval(timer); timer = null; };

  slider.addEventListener('mouseenter', stop);
  slider.addEventListener('mouseleave', start);
  slider.addEventListener('touchstart', stop, { passive: true });
  slider.addEventListener('touchend', start, { passive: true });

  slider.addEventListener('scroll', () => {
    window.clearTimeout(slider._t);
    slider._t = window.setTimeout(syncIndex, 120);
  });

  window.addEventListener('resize', () => goTo(index));
  start();
})();
</script>
<?php endif; ?>


<script>
(function(){
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // Cart badge updater (global header)
  const cartBadge = document.getElementById('cartBadge');
  const setCartCount = (n) => {
    if(!cartBadge) return;
    const num = Number(n || 0);
    if(!Number.isFinite(num) || num <= 0) {
      cartBadge.classList.add('hidden');
      cartBadge.textContent = '0';
      return;
    }
    cartBadge.textContent = num > 99 ? '99+' : String(num);
    cartBadge.classList.remove('hidden');
  };

  // Toast
  const toast = document.getElementById('toast');
  const toastMsg = document.getElementById('toastMsg');
  const toastClose = document.getElementById('toastClose');
  let toastTimer = null;
  const showToast = (msg) => {
    if(!toast || !toastMsg) return;
    toastMsg.textContent = msg || 'Berhasil.';
    toast.classList.add('show');
    window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2400);
  };
  toastClose?.addEventListener('click', () => toast?.classList.remove('show'));

  // Skeleton images
  const initSkeleton = (root) => {
    (root || document).querySelectorAll('[data-skel-img]').forEach(img => {
      if(img.dataset._skelBound) return;
      img.dataset._skelBound = '1';
      const wrap = img.closest('[data-product-card]') || img.parentElement;
      const skel = wrap?.querySelector('[data-skel]');

      const done = () => {
        img.classList.remove('opacity-0');
        if(skel) skel.remove();
      };
      if(img.complete) {
        done();
      } else {
        img.addEventListener('load', done, { once:true });
        img.addEventListener('error', () => { if(skel) skel.remove(); }, { once:true });
      }
    });
  };
  initSkeleton(document);

  // AJAX search & filters (apply without full reload)
  const grid = document.getElementById('productsGrid');
  const sentinel = document.getElementById('loadMoreSentinel');
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const loadMoreHint = document.getElementById('loadMoreHint');
  const filterBadge = document.getElementById('filterBadge');
  const searchForm = document.getElementById('searchForm');
  const catChips = document.getElementById('catChips');
  const selectedCategoryWrap = document.getElementById('selectedCategoryWrap');
  const selectedCategoryName = document.getElementById('selectedCategoryName');

  const setFilterBadge = (n) => {
    if(!filterBadge) return;
    const num = Number(n || 0);
    if(!Number.isFinite(num) || num <= 0) {
      filterBadge.classList.add('hidden');
      filterBadge.textContent = '0';
      return;
    }
    filterBadge.textContent = num > 99 ? '99+' : String(num);
    filterBadge.classList.remove('hidden');
  };

  const countActiveFilters = (params) => {
    let c = 0;
    const minp = (params.get('min_price') || '').trim();
    const maxp = (params.get('max_price') || '').trim();
    const mr = parseFloat(params.get('min_rating') || '0') || 0;
    const sort = (params.get('sort') || 'newest').trim();
    if(minp !== '' && Number(minp) > 0) c++;
    if(maxp !== '' && Number(maxp) > 0) c++;
    if(mr > 0) c++;
    if(sort && sort !== 'newest') c++;
    return c;
  };

  const setNext = (url) => { if(grid) grid.setAttribute('data-next-url', url || ''); };
  const getNext = () => grid ? (grid.getAttribute('data-next-url') || '') : '';

  const normalizeParams = (params) => {
    // Remove empty + default values
    for(const k of Array.from(params.keys())) {
      const v = (params.get(k) || '').trim();
      if(v === '') { params.delete(k); continue; }
      if(k === 'min_rating' && (parseFloat(v) || 0) <= 0) { params.delete(k); continue; }
      if(k === 'sort' && v === 'newest') { params.delete(k); continue; }
    }
    return params;
  };

  const syncSearchHidden = (params) => {
    if(!searchForm) return;
    const keep = new Set(['q']);
    searchForm.querySelectorAll('input[type=hidden]').forEach(inp => {
      if(!keep.has(inp.name)) inp.remove();
    });

    const fields = ['category','min_price','max_price','min_rating','sort'];
    fields.forEach(name => {
      const v = (params.get(name) || '').trim();
      if(!v) return;
      if(name === 'min_rating' && (parseFloat(v) || 0) <= 0) return;
      if(name === 'sort' && v === 'newest') return;
      const h = document.createElement('input');
      h.type = 'hidden';
      h.name = name;
      h.value = v;
      searchForm.appendChild(h);
    });
  };

  const setActiveCategoryChip = (categoryId, categoryName) => {
    if(!catChips) return;

    catChips.querySelectorAll('[data-cat-chip]').forEach(a => {
      const id = (a.getAttribute('data-cat-id') || '');
      const isActive = String(id) === String(categoryId || '');

      // card container
      a.classList.remove('bg-slate-900','text-white','border-slate-900','hover:bg-slate-50');
      if(isActive) {
        a.classList.add('bg-slate-900','text-white','border-slate-900');
      } else {
        a.classList.add('hover:bg-slate-50');
      }

      // icon wrapper
      const iconWrap = a.querySelector('[data-cat-icon-wrap]');
      if(iconWrap) {
        iconWrap.classList.remove('bg-slate-800','border-slate-700','bg-slate-100');
        if(isActive) iconWrap.classList.add('bg-slate-800','border-slate-700');
        else iconWrap.classList.add('bg-slate-100');
      }

      // label
      const label = a.querySelector('[data-cat-label]');
      if(label) {
        label.classList.remove('text-white','text-slate-700','group-hover:text-rose-700');
        if(isActive) label.classList.add('text-white');
        else label.classList.add('text-slate-700','group-hover:text-rose-700');
      }

      // icon (for "Semua")
      const icon = a.querySelector('[data-cat-icon]');
      if(icon) {
        icon.classList.remove('text-white','text-slate-500');
        if(isActive) icon.classList.add('text-white');
        else icon.classList.add('text-slate-500');
      }

      // fallback letter (for categories without image)
      const fb = a.querySelector('[data-cat-fallback]');
      if(fb) {
        fb.classList.remove('text-white/80','text-slate-400');
        if(isActive) fb.classList.add('text-white/80');
        else fb.classList.add('text-slate-400');
      }
    });

    if(!selectedCategoryWrap || !selectedCategoryName) return;
    const has = String(categoryId || '').trim() !== '';
    if(!has) {
      selectedCategoryWrap.classList.add('hidden');
      selectedCategoryName.textContent = '';
    } else {
      selectedCategoryWrap.classList.remove('hidden');
      selectedCategoryName.textContent = categoryName || 'Kategori dipilih';
    }
  };

  const applyQuery = async (params, opts = {}) => {
    if(!window.fetch || !grid) return;
    const push = opts.push !== false;

    params = normalizeParams(params);
    const url = new URL(window.location.href);
    url.search = params.toString();

    const prev = grid.innerHTML;
    grid.classList.add('opacity-60');
    loadMoreBtn?.setAttribute('disabled', 'disabled');
    if(loadMoreHint) loadMoreHint.textContent = 'Memuat...';

    try {
      const resp = await fetch(url.toString(), {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json',
        }
      });
      const data = await resp.json().catch(() => ({}));
      if(!resp.ok) {
        showToast(data.message || 'Gagal memuat produk.');
        grid.innerHTML = prev;
        return;
      }

      grid.innerHTML = data.html || '';
      setNext(data.next_page_url || '');
      initSkeleton(grid);
      bindQuickAdd(grid);

      const hasNext = !!getNext();
      if(loadMoreBtn) loadMoreBtn.classList.toggle('hidden', !hasNext);
      if(loadMoreHint) loadMoreHint.textContent = hasNext ? 'Scroll untuk memuat produk lainnya' : 'Semua produk sudah dimuat.';

      setFilterBadge(countActiveFilters(params));
      syncSearchHidden(params);

      // Sync category card active state
      const cid = params.get('category') || '';
      const esc = (window.CSS && typeof CSS.escape === 'function') ? CSS.escape(cid) : String(cid).replace(/"/g, '\\"');
      const chip = catChips?.querySelector(`[data-cat-chip][data-cat-id="${esc}"]`)
        || catChips?.querySelector('[data-cat-chip][data-cat-id=""]');
      const cname = chip?.getAttribute('data-cat-name') || (cid ? 'Kategori dipilih' : 'Semua');
      setActiveCategoryChip(cid, cname);

      if(push) history.pushState({sf:1}, '', url.toString());
      window.dispatchEvent(new CustomEvent('filters:applied'));
    } catch (e) {
      showToast('Gagal memuat produk.');
      grid.innerHTML = prev;
    } finally {
      grid.classList.remove('opacity-60');
      loadMoreBtn?.removeAttribute('disabled');
    }
  };

  const bindCategoryChips = () => {
    if(!catChips) return;
    catChips.querySelectorAll('[data-cat-chip]').forEach(a => {
      if(a.dataset._bound) return;
      a.dataset._bound = '1';
      a.addEventListener('click', (e) => {
        if(!window.fetch || !grid) return;
        e.preventDefault();

        const cid = a.getAttribute('data-cat-id') || '';
        const cname = a.getAttribute('data-cat-name') || 'Semua';

        const params = new URLSearchParams(window.location.search);
        if(cid === '') params.delete('category');
        else params.set('category', cid);

        const q = (searchForm?.querySelector('input[name="q"]')?.value || '').trim();
        if(q) params.set('q', q); else params.delete('q');

        params.delete('page');

        setActiveCategoryChip(cid, cname);
        applyQuery(params);
        document.getElementById('products')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  };
  bindCategoryChips();

  // Bind filter forms
  document.querySelectorAll('form[data-filter-form]').forEach(form => {
    if(form.dataset._ajaxBound) return;
    form.dataset._ajaxBound = '1';
    form.addEventListener('submit', (e) => {
      if(!window.fetch) return;
      e.preventDefault();
      const params = new URLSearchParams(new FormData(form));
      applyQuery(params);
    });
  });

  // Bind search form
  if(searchForm && !searchForm.dataset._ajaxBound) {
    searchForm.dataset._ajaxBound = '1';
    searchForm.addEventListener('submit', (e) => {
      if(!window.fetch) return;
      e.preventDefault();
      const params = new URLSearchParams(new FormData(searchForm));
      applyQuery(params);
    });
  }

  // Back/forward support
  window.addEventListener('popstate', () => {
    const params = new URLSearchParams(window.location.search);
    applyQuery(params, {push:false});
  });

  // Quick Add to Cart (AJAX + toast)
  const bindQuickAdd = (root) => {
    (root || document).querySelectorAll('form.js-quick-add, form.js-buy-now').forEach(form => {
      if(form.dataset._bound) return;
      form.dataset._bound = '1';

      form.addEventListener('submit', async (e) => {
        if(!window.fetch) return;
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        btn?.setAttribute('disabled', 'disabled');

        try {
          const fd = new FormData(form);
          const resp = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
              ...(csrf ? {'X-CSRF-TOKEN': csrf} : {}),
            },
            body: fd,
          });

          const data = await resp.json().catch(() => ({}));

          if(!resp.ok) {
            showToast(data.message || 'Gagal menambahkan ke keranjang.');
            return;
          }

          if(typeof data.cart_count !== 'undefined') {
            setCartCount(data.cart_count);
          }

          if(data.redirect) {
            window.location.href = data.redirect;
            return;
          }

          showToast(data.message || 'Berhasil ditambahkan ke keranjang.');
        } catch (err) {
          showToast('Terjadi kesalahan. Coba lagi.');
        } finally {
          btn?.removeAttribute('disabled');
        }
      });
    });
  };
  bindQuickAdd(document);

  // Infinite scroll / Load more
  const grid2 = grid;
  const sentinel2 = sentinel;
  const btn = loadMoreBtn;
  const hint = loadMoreHint;
  if(!grid2) return;

  let loading = false;

  const loadNext = async () => {
    const next = getNext();
    if(!next || loading) return;
    loading = true;
    btn?.setAttribute('disabled', 'disabled');
    hint && (hint.textContent = 'Memuat...');

    try {
      const resp = await fetch(next, { headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } });
      const data = await resp.json();
      if(!resp.ok) {
        showToast(data?.message || 'Gagal memuat produk.');
        return;
      }
      if(data?.html) {
        const tmp = document.createElement('div');
        tmp.innerHTML = data.html;
        const nodes = Array.from(tmp.children);
        nodes.forEach(n => grid.appendChild(n));
        initSkeleton(grid);
        bindQuickAdd(grid);
      }
      setNext(data?.next_page_url || '');
    } catch (e) {
      showToast('Gagal memuat produk.');
    } finally {
      loading = false;
      btn?.removeAttribute('disabled');
      const hasNext = !!getNext();
      if(hint) hint.textContent = hasNext ? 'Scroll untuk memuat produk lainnya' : 'Semua produk sudah dimuat.';
      if(btn) btn.classList.toggle('hidden', !hasNext);
    }
  };

  // Show fallback button if browser doesn't support IntersectionObserver
  const hasIO = 'IntersectionObserver' in window;
  if(btn) {
    btn.classList.toggle('hidden', !getNext());
    btn.addEventListener('click', loadNext);
  }
  if(!hasIO) {
    hint && (hint.textContent = getNext() ? 'Klik "Muat lagi" untuk menampilkan produk lainnya' : 'Semua produk sudah dimuat.');
    return;
  }

  const io = new IntersectionObserver((entries) => {
    entries.forEach(ent => {
      if(ent.isIntersecting) loadNext();
    });
  }, { rootMargin: '600px 0px' });
  if(sentinel2) io.observe(sentinel2);
})();
</script>


<script>
(function(){
  const imgs = document.querySelectorAll('[data-skel-img]');
  imgs.forEach((img) => {
    const wrap = img.closest('.relative');
    const skel = wrap ? wrap.querySelector('[data-skel]') : null;

    const done = () => {
      img.classList.remove('opacity-0');
      img.classList.add('opacity-100');
      if(skel) skel.remove();
    };

    if(img.complete && img.naturalWidth > 0) {
      done();
    } else {
      img.addEventListener('load', done, { once: true });
      img.addEventListener('error', () => {
        if(skel) skel.classList.remove('animate-pulse');
        img.classList.remove('opacity-0');
        img.classList.add('opacity-100');
      }, { once: true });
    }
  });
})();
</script>


<script>
(function(){
  const nodes = Array.from(document.querySelectorAll('[data-fs-countdown][data-end]'));
  if(!nodes.length) return;

  const pad2 = (n) => String(n).padStart(2,'0');

  const tick = () => {
    const now = Date.now();
    nodes.forEach((el) => {
      const endStr = el.getAttribute('data-end');
      const end = endStr ? Date.parse(endStr) : NaN;
      if(!end || Number.isNaN(end)) return;

      let diff = Math.max(0, end - now);
      const hh = Math.floor(diff / 3600000);
      diff -= hh * 3600000;
      const mm = Math.floor(diff / 60000);
      diff -= mm * 60000;
      const ss = Math.floor(diff / 1000);

      const hhEl = el.querySelector('[data-hh]');
      const mmEl = el.querySelector('[data-mm]');
      const ssEl = el.querySelector('[data-ss]');
      if(hhEl) hhEl.textContent = pad2(hh);
      if(mmEl) mmEl.textContent = pad2(mm);
      if(ssEl) ssEl.textContent = pad2(ss);

      if(end - now <= 0) {
        el.classList.add('opacity-60');
      }
    });
  };

  tick();
  setInterval(tick, 1000);
})();
</script>


<script>
(function(){
  const btn = document.getElementById('filterBtn');
  if(!btn) return;

  const activeCount = <?php echo e((int)$activeFilterCount); ?>;

  // Desktop dropdown
  const dropdown = document.getElementById('filterDropdownInner');
  const dropdownWrap = document.getElementById('filterDropdown');

  const isDesktop = () => window.matchMedia('(min-width: 640px)').matches;

  const openDesktop = () => {
    if(!dropdown) return;
    dropdown.classList.remove('hidden');
    btn.setAttribute('aria-expanded', 'true');
  };
  const closeDesktop = () => {
    if(!dropdown) return;
    dropdown.classList.add('hidden');
    btn.setAttribute('aria-expanded', 'false');
  };
  const desktopOpen = () => dropdown && !dropdown.classList.contains('hidden');

  // Mobile sheet
  const backdrop = document.getElementById('sheetBackdrop');
  const panel = document.getElementById('sheetPanel');
  const closeBtn = document.getElementById('sheetClose');

  const openSheet = () => {
    if(!backdrop || !panel) return;
    backdrop.classList.add('open');
    panel.classList.add('open');
    document.body.classList.add('overflow-hidden');
  };
  const closeSheet = () => {
    if(!backdrop || !panel) return;
    backdrop.classList.remove('open');
    panel.classList.remove('open');
    document.body.classList.remove('overflow-hidden');
  };

  if(activeCount > 0 && isDesktop()) openDesktop();

  btn.addEventListener('click', (e) => {
    e.preventDefault();
    if(isDesktop()) {
      desktopOpen() ? closeDesktop() : openDesktop();
    } else {
      openSheet();
    }
  });

  document.addEventListener('click', (e) => {
    if(!isDesktop()) return;
    if(!desktopOpen()) return;
    if(btn.contains(e.target)) return;
    if(dropdownWrap && dropdownWrap.contains(e.target)) return;
    closeDesktop();
  });

  if(closeBtn) closeBtn.addEventListener('click', closeSheet);
  if(backdrop) backdrop.addEventListener('click', (e) => {
    if(e.target === backdrop) closeSheet();
  });

  document.addEventListener('keydown', (e) => {
    if(e.key !== 'Escape') return;
    if(isDesktop()) {
      if(desktopOpen()) closeDesktop();
    } else {
      closeSheet();
    }
  });

  window.addEventListener('filters:applied', () => {
    closeDesktop();
    closeSheet();
  });

  window.addEventListener('resize', () => {
    if(isDesktop()) {
      closeSheet();
    } else {
      closeDesktop();
    }
  });
})();
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/storefront/index.blade.php ENDPATH**/ ?>