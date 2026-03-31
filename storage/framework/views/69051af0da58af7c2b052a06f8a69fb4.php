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

  /* Mobile top navbar */
  .mobile-top-nav { display: none; }

  @media (max-width: 640px) {
    .mobile-top-nav {
      display: flex;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 50;
      background: white;
      border-bottom: 1px solid #e2e8f0;
      padding: 8px 12px;
      align-items: center;
      gap: 8px;
    }

    .main-content { padding-top: 60px; }
    .desktop-search { display: none; }
  }

  @media (min-width: 641px) {
    .mobile-top-nav { display: none !important; }
    .main-content { padding-top: 0; }
  }
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


<div class="mobile-top-nav">
  <a href="<?php echo e(route('home')); ?>" class="font-black text-rose-600 text-lg shrink-0">IlmiShop</a>

  <form action="<?php echo e(route('home')); ?>" method="GET" class="flex-1">
    <div class="relative">
      <input
        type="text"
        name="q"
        value="<?php echo e($q ?? ''); ?>"
        placeholder="Cari produk..."
        class="w-full pl-9 pr-3 py-2 rounded-full bg-slate-100 border-0 text-sm focus:ring-2 focus:ring-rose-500"
      >
      <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'search','class' => 'w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2']); ?>
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
  </form>

  <a href="<?php echo e(route('cart.index')); ?>" class="relative p-2 rounded-full hover:bg-slate-100">
    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shopping-cart','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-5 h-5 text-slate-700']); ?>
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
    <span id="mobileCartBadge" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center hidden">0</span>
  </a>

  <a href="<?php echo e(route('notifications.index')); ?>" class="relative p-2 rounded-full hover:bg-slate-100">
    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'bell','class' => 'w-5 h-5 text-slate-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-5 h-5 text-slate-700']); ?>
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
    <span id="mobileNotifBadge" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-600 text-white text-[10px] font-bold flex items-center justify-center hidden">0</span>
  </a>
</div>

<div class="main-content max-w-6xl mx-auto space-y-4">

  
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
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-rose-600"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
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
<?php endif; ?></span>
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
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-600"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
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
<?php endif; ?></span>
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
        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-slate-900"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
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
<?php endif; ?></span>
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
        <div class="shrink-0 inline-flex items-center px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-black">FLASH SALE</div>
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
                <img src="<?php echo e(asset('storage/'.$img->path)); ?>" class="w-full h-full object-cover" alt="<?php echo e($p->name); ?>" loading="lazy">
              <?php endif; ?>
              <div class="absolute left-2 top-2 inline-flex text-[10px] bg-rose-600 text-white px-2 py-1 rounded-full font-black">FLASH</div>

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
                <div class="text-rose-600 font-black">Rp <?php echo e(number_format($promo,0,',','.')); ?></div>
                <?php if($it->promo_price !== null && $it->promo_price < $p->price): ?>
                  <div class="text-xs text-slate-400 line-through">Rp <?php echo e(number_format($p->price,0,',','.')); ?></div>
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
                  <div class="mt-1 h-3 rounded-full bg-rose-100 overflow-hidden"><div class="h-full bg-rose-500 fs-progress rounded-full" style="width: <?php echo e($pct); ?>%"></div></div>
                <?php else: ?>
                  <div class="mt-1 h-3 rounded-full bg-rose-100 overflow-hidden"><div class="h-full bg-rose-500 fs-progress rounded-full" style="width: <?php echo e(min(100, max(5, (int)round(($sold % 20) / 20 * 100)))); ?>%"></div></div>
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

  
  <div class="desktop-search">
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
      <form action="<?php echo e(route('home')); ?>" method="GET" class="flex gap-2">
        <div class="relative flex-1">
          <input
            type="text"
            name="q"
            value="<?php echo e($q ?? ''); ?>"
            placeholder="Cari produk..."
            class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200"
          >
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'search','class' => 'w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2']); ?>
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
        <button type="submit" class="px-6 py-3 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700">Cari</button>
      </form>
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
              <span class="truncate">Hasil untuk "<?php echo e($q); ?>"</span>
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
            <span id="filterBadge" class="absolute -top-2 -right-2 inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 rounded-full bg-rose-600 text-white text-[11px] font-black <?php echo e($activeFilterCount > 0 ? '' : 'hidden'); ?>"><?php echo e($activeFilterCount); ?></span>
          </button>

          <?php if($q || $category || $activeFilterCount > 0): ?>
            <a href="<?php echo e($resetUrl); ?>" class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-slate-900 text-white hover:bg-slate-800" title="Reset">
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
          <a href="<?php echo e(route('home', array_filter(['q' => $q]))); ?>" data-cat-chip data-cat-id="" data-cat-name="Semua" class="group flex flex-col items-center text-center gap-2 p-2 rounded-2xl border transition <?php echo e(!$category ? 'bg-slate-900 text-white border-slate-900' : 'hover:bg-slate-50'); ?>">
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
            <div data-cat-label class="text-[11px] leading-tight line-clamp-2 min-h-[28px] <?php echo e(!$category ? 'text-white' : 'text-slate-700 group-hover:text-rose-700'); ?>">Semua</div>
          </a>

          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('home', array_filter(['q' => $q, 'category' => $cat->id]))); ?>" data-cat-chip data-cat-id="<?php echo e($cat->id); ?>" data-cat-name="<?php echo e($cat->name); ?>" class="group flex flex-col items-center text-center gap-2 p-2 rounded-2xl border transition <?php echo e((string)$category === (string)$cat->id ? 'bg-slate-900 text-white border-slate-900' : 'hover:bg-slate-50'); ?>">
              <div data-cat-icon-wrap class="w-14 h-14 rounded-full border overflow-hidden flex items-center justify-center <?php echo e((string)$category === (string)$cat->id ? 'bg-slate-800 border-slate-700' : 'bg-slate-100'); ?>">
                <?php if($cat->image_path): ?>
                  <img src="<?php echo e($cat->imageUrl()); ?>" alt="<?php echo e($cat->name); ?>" class="w-full h-full object-cover">
                <?php else: ?>
                  <span data-cat-fallback class="font-black <?php echo e((string)$category === (string)$cat->id ? 'text-white/80' : 'text-slate-400'); ?> text-lg"><?php echo e(strtoupper(mb_substr($cat->name,0,1))); ?></span>
                <?php endif; ?>
              </div>
              <div data-cat-label class="text-[11px] leading-tight line-clamp-2 min-h-[28px] <?php echo e((string)$category === (string)$cat->id ? 'text-white' : 'text-slate-700 group-hover:text-rose-700'); ?>"><?php echo e($cat->name); ?></div>
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
          <a href="<?php echo e(route('home', array_merge($baseParams, ['q' => $kw]))); ?>" class="text-xs px-3 py-1.5 rounded-full border bg-white hover:bg-slate-50"><?php echo e($kw); ?></a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="mt-6 flex flex-col items-center gap-3">
      <button id="loadMoreBtn" type="button" class="hidden px-5 py-3 rounded-2xl border font-black hover:bg-slate-50">Muat lagi</button>
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
      <button type="button" id="toastClose" class="p-1.5 rounded-xl hover:bg-white/10" aria-label="Tutup"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
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
<?php endif; ?></button>
    </div>
  </div>
</div>


<div id="sheetBackdrop" class="sheet-backdrop fixed inset-0 z-50 sm:hidden bg-black/40">
  <div id="sheetPanel" class="sheet-panel absolute inset-x-0 bottom-0 bg-white rounded-t-3xl border-t shadow-2xl">
    <div class="p-4">
      <div class="flex items-center justify-between">
        <div class="font-black text-lg">Filter</div>
        <button type="button" id="sheetClose" class="p-2 rounded-xl hover:bg-slate-100"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
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
<?php endif; ?></button>
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

  const setCartCount = (n) => {
    const num = Number(n || 0);
    const desktopBadge = document.getElementById('cartBadge');
    const mobileBadge = document.getElementById('mobileCartBadge');

    const updateBadge = (badge) => {
      if(!badge) return;
      if(!Number.isFinite(num) || num <= 0) {
        badge.classList.add('hidden');
        badge.textContent = '0';
        return;
      }
      badge.textContent = num > 99 ? '99+' : String(num);
      badge.classList.remove('hidden');
    };

    updateBadge(desktopBadge);
    updateBadge(mobileBadge);
  };

  const setNotifCount = (n) => {
    const num = Number(n || 0);
    const mobileBadge = document.getElementById('mobileNotifBadge');
    if(!mobileBadge) return;
    if(!Number.isFinite(num) || num <= 0) {
      mobileBadge.classList.add('hidden');
      mobileBadge.textContent = '0';
      return;
    }
    mobileBadge.textContent = num > 99 ? '99+' : String(num);
    mobileBadge.classList.remove('hidden');
  };

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

  // Real-time flash sale countdown
  const pad2 = (n) => String(n).padStart(2, '0');
  const updateCountdown = (el) => {
    const end = el.dataset.end;
    if(!end) return;

    const endTime = new Date(end).getTime();
    const now = Date.now();
    let diff = Math.max(0, endTime - now);

    const hh = Math.floor(diff / 3600000);
    diff %= 3600000;
    const mm = Math.floor(diff / 60000);
    diff %= 60000;
    const ss = Math.floor(diff / 1000);

    const hhEl = el.querySelector('[data-hh]');
    const mmEl = el.querySelector('[data-mm]');
    const ssEl = el.querySelector('[data-ss]');

    if(hhEl) hhEl.textContent = pad2(hh);
    if(mmEl) mmEl.textContent = pad2(mm);
    if(ssEl) ssEl.textContent = pad2(ss);

    if(endTime <= now) {
      el.textContent = 'HABIS';
      el.classList.remove('text-rose-700', 'border-rose-200');
      el.classList.add('text-slate-500');
      return true;
    }
    return false;
  };

  const countdownEls = Array.from(document.querySelectorAll('[data-fs-countdown]'));
  const tickCountdowns = () => {
    if(!countdownEls.length) return;
    countdownEls.forEach(el => updateCountdown(el));
  };
  tickCountdowns();
  setInterval(tickCountdowns, 1000);

  // Filter dropdown + bottom sheet
  const filterBtn = document.getElementById('filterBtn');
  const filterDropdownInner = document.getElementById('filterDropdownInner');
  const sheetBackdrop = document.getElementById('sheetBackdrop');
  const sheetPanel = document.getElementById('sheetPanel');
  const sheetClose = document.getElementById('sheetClose');

  const openSheet = () => {
    sheetBackdrop?.classList.add('open');
    sheetPanel?.classList.add('open');
  };
  const closeSheet = () => {
    sheetBackdrop?.classList.remove('open');
    sheetPanel?.classList.remove('open');
  };

  filterBtn?.addEventListener('click', () => {
    if(window.matchMedia('(max-width: 640px)').matches) openSheet();
    else filterDropdownInner?.classList.toggle('hidden');
  });
  sheetClose?.addEventListener('click', closeSheet);
  sheetBackdrop?.addEventListener('click', (e) => {
    if(e.target === sheetBackdrop) closeSheet();
  });

  // Quick add to cart
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
          if(typeof data.notif_count !== 'undefined') {
            setNotifCount(data.notif_count);
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

  // Infinite scroll (optional; works only if backend supports nextPageUrl)
  const grid = document.getElementById('productsGrid');
  const sentinel = document.getElementById('loadMoreSentinel');
  const loadMoreBtn = document.getElementById('loadMoreBtn');
  const loadMoreHint = document.getElementById('loadMoreHint');

  let loading = false;
  let nextUrl = grid?.dataset.nextUrl || '';

  const loadNextPage = async () => {
    if(loading || !nextUrl) return;
    loading = true;
    loadMoreBtn?.classList.add('hidden');
    if(loadMoreHint) loadMoreHint.textContent = 'Memuat...';

    try {
      const resp = await fetch(nextUrl, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'text/html,application/xhtml+xml',
        }
      });

      if(!resp.ok) throw new Error('Failed');

      const html = await resp.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');
      const newGrid = doc.getElementById('productsGrid');

      if(newGrid) {
        const children = Array.from(newGrid.children);
        children.forEach(node => grid.appendChild(node));
        nextUrl = newGrid.dataset.nextUrl || '';
        grid.dataset.nextUrl = nextUrl;
        initSkeleton(grid);
        bindQuickAdd(grid);
      } else {
        nextUrl = '';
      }

      if(!nextUrl && loadMoreBtn) loadMoreBtn.remove();
      if(loadMoreHint) loadMoreHint.textContent = nextUrl ? 'Scroll untuk memuat produk lainnya' : 'Semua produk sudah dimuat';
    } catch (e) {
      if(loadMoreHint) loadMoreHint.textContent = 'Gagal memuat data.';
      if(loadMoreBtn) loadMoreBtn.classList.remove('hidden');
    } finally {
      loading = false;
    }
  };

  loadMoreBtn?.addEventListener('click', loadNextPage);

  if('IntersectionObserver' in window && sentinel) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if(entry.isIntersecting) loadNextPage();
      });
    }, { rootMargin: '300px 0px' });
    io.observe(sentinel);
  }
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/storefront/index.blade.php ENDPATH**/ ?>