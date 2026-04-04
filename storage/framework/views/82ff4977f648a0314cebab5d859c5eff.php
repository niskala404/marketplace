<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name')); ?> - <?php echo e($title ?? 'Marketplace'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>

    
    <style>
        .skeleton{
            position:relative;
            overflow:hidden;
            background:#eef2f7;
            border-radius:12px;
        }
        .skeleton::after{
            content:"";
            position:absolute;
            inset:0;
            transform:translateX(-100%);
            background:linear-gradient(90deg,transparent,rgba(255,255,255,.65),transparent);
            animation:shimmer 1.2s infinite;
        }
        @keyframes shimmer{100%{transform:translateX(100%)}}
        body.page-loading{cursor:progress;}
    </style>
</head>

<body class="min-h-screen bg-slate-50 text-slate-900 selection:bg-rose-200 selection:text-rose-900">


<div id="topLoader" class="fixed top-0 left-0 h-[3px] w-0 bg-rose-600 z-[9999] transition-all"></div>

<?php if(auth()->guard()->check()): ?>
    <script>
        window.App = window.App || {};
        window.App.userId = <?php echo json_encode(auth()->id(), 15, 512) ?>;
    </script>
<?php endif; ?>

<header class="sticky top-0 z-40 bg-white border-b border-slate-200">
    <div class="h-1 bg-rose-600"></div>

    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center gap-3">
        
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2">
            <span class="w-10 h-10 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black shadow-sm">ma</span>
            <span class="font-black text-xl tracking-tight">lik<span class="text-rose-600">ishop</span></span>
        </a>

        
        <form action="<?php echo e(route('home')); ?>" method="GET" class="flex-1">
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'search','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'search','class' => 'w-5 h-5']); ?>
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
                <input
                    name="q"
                    value="<?php echo e(request('q')); ?>"
                    id="searchInput"
                    class="w-full pl-10 pr-28 py-2.5 rounded-2xl border border-slate-200 bg-white focus:border-rose-500 focus:ring-rose-200 shadow-sm"
                    placeholder="Cari produk... (contoh: headset)">
                <button
                    class="absolute right-1.5 top-1/2 -translate-y-1/2 px-4 py-2 rounded-xl bg-rose-600 text-white font-semibold hover:bg-rose-700 active:scale-[0.99] transition">
                    Cari
                </button>

                
                <div id="searchSuggest" class="hidden absolute left-0 right-0 mt-2 bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-2">
                        <div class="text-[11px] uppercase tracking-wide text-slate-400 px-2 py-1">Saran pencarian</div>
                        <div id="searchSuggestList" class="divide-y"></div>
                    </div>
                </div>
            </div>
        </form>

        
        <div class="flex items-center gap-1">
            <?php if(auth()->guard()->check()): ?>
                <?php ($cartCount = (int) (auth()->user()->cart?->items()->sum('qty') ?? 0)); ?>
            <?php else: ?>
                <?php ($cartCount = 0); ?>
            <?php endif; ?>

            <a href="<?php echo e(route('cart.index')); ?>" class="relative px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Keranjang" aria-label="Keranjang">
                <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shopping-cart','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shopping-cart','class' => 'w-5 h-5']); ?>
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
                <span id="cartBadge" class="<?php echo e($cartCount > 0 ? '' : 'hidden'); ?> absolute -top-1 -right-1 text-[10px] font-black bg-rose-600 text-white rounded-full px-2 py-0.5 shadow">
                    <?php echo e($cartCount > 99 ? '99+' : $cartCount); ?>

                </span>
            </a>

            <?php if(auth()->guard()->check()): ?>
                <?php ($unread = auth()->user()->unreadNotifications()->count()); ?>

                <a href="<?php echo e(route('wishlist.index')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Wishlist" aria-label="Wishlist">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'heart','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'heart','class' => 'w-5 h-5']); ?>
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

                <a href="<?php echo e(route('messages.index')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Pesan" aria-label="Pesan">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'messages-square','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'messages-square','class' => 'w-5 h-5']); ?>
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

                <a href="<?php echo e(route('notifications.index')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition relative" title="Notifikasi" aria-label="Notifikasi">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'bell','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-5 h-5']); ?>
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
                    <span id="notifBadge" class="<?php echo e($unread > 0 ? '' : 'hidden'); ?> absolute -top-1 -right-1 text-[10px] font-bold bg-rose-600 text-white rounded-full px-2 py-0.5 shadow">
                        <?php echo e($unread > 99 ? '99+' : $unread); ?>

                    </span>
                </a>

                <a href="<?php echo e(route('wallet.index')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Saldo" aria-label="Saldo">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'wallet','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'wallet','class' => 'w-5 h-5']); ?>
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

                <?php if(auth()->user()->role === 'seller' && Route::has('seller.live.index')): ?>
                    <a href="<?php echo e(route('seller.live.index')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Live Seller" aria-label="Live Seller">
                        <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'video','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'video','class' => 'w-5 h-5']); ?>
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

                <a href="<?php echo e(route('account.profile')); ?>" class="px-3 py-2 rounded-2xl hover:bg-slate-100 active:scale-[0.98] transition" title="Akun" aria-label="Akun">
                    <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'user','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'user','class' => 'w-5 h-5']); ?>
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
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="px-4 py-2.5 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 active:scale-[0.99] transition shadow-sm">
                    Login
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="border-t border-slate-100">
        <div class="max-w-6xl mx-auto px-4 py-2 flex items-center justify-between text-sm">
            <div class="flex items-center gap-2 text-slate-600">
                <span class="px-2 py-1 rounded-full bg-rose-50 text-rose-700 font-semibold border border-rose-100">Promo</span>
                <span class="hidden sm:inline">Gratis ongkir* • Pembayaran aman • Chat penjual</span>
            </div>
            <div class="hidden md:block text-slate-500">Belanja nyaman & aman</div>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 py-6 pb-28">
    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="font-black mb-1">Ada yang perlu diperbaiki:</div>
            <ul class="list-disc pl-5 space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="mb-4 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 shadow-sm">
            <div class="font-bold">Berhasil</div>
            <div class="text-sm"><?php echo e(session('success')); ?></div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-4 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
            <div class="font-bold">Gagal</div>
            <div class="text-sm"><?php echo e(session('error')); ?></div>
        </div>
    <?php endif; ?>

    <?php echo e($slot ?? ''); ?>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<?php echo $__env->make('partials.mobile-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<footer class="fixed bottom-0 left-0 right-0 z-40 hidden md:block">
    <div class="bg-white border-t border-slate-200">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div class="text-sm text-slate-600">
                    <span class="font-black text-slate-900">ilm<span class="text-rose-600">ishop</span></span>
                    <span class="mx-2 text-slate-300">•</span>
                    <span class="font-semibold">Jual Beli Online</span>
                    <span class="hidden sm:inline"> — Aman, Cepat, dan Terpercaya</span>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'shield-check','class' => 'w-4 h-4 text-rose-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'shield-check','class' => 'w-4 h-4 text-rose-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $attributes = $__attributesOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__attributesOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $component = $__componentOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__componentOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?><span>Garansi</span></span>
                    <span class="inline-flex items-center gap-1"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'truck','class' => 'w-4 h-4 text-rose-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'truck','class' => 'w-4 h-4 text-rose-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $attributes = $__attributesOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__attributesOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $component = $__componentOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__componentOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?><span>Gratis Ongkir*</span></span>
                    <span class="inline-flex items-center gap-1"><?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'credit-card','class' => 'w-4 h-4 text-rose-600']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'credit-card','class' => 'w-4 h-4 text-rose-600']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $attributes = $__attributesOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__attributesOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal16783dc90daf260581c0ddf14436b31a)): ?>
<?php $component = $__componentOriginal16783dc90daf260581c0ddf14436b31a; ?>
<?php unset($__componentOriginal16783dc90daf260581c0ddf14436b31a); ?>
<?php endif; ?><span>Pembayaran Aman</span></span>
                    <span class="hidden md:inline text-slate-300">|</span>
                    <a href="#" class="hover:underline">Tentang</a>
                    <a href="#" class="hover:underline">Bantuan</a>
                    <a href="#" class="hover:underline">S&K</a>
                </div>
            </div>

            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                <div>© <?php echo e(date('Y')); ?> ilmishop</div>
                <div class="hidden sm:block">*S&K berlaku</div>
            </div>
        </div>
        <div class="h-1 bg-rose-600"></div>
    </div>
</footer>


<audio id="notifSound" preload="auto">
    <source src="<?php echo e(asset('sounds/notification.mp3')); ?>" type="audio/mpeg">
</audio>


<div id="notifToast"
     class="fixed top-5 right-5 z-[99999] hidden w-[320px] max-w-[calc(100vw-2rem)] rounded-2xl border border-rose-100 bg-white shadow-2xl overflow-hidden">
    <div class="flex items-start gap-3 p-4">
        <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-rose-50 text-rose-600">
            <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'bell','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'bell','class' => 'w-5 h-5']); ?>
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

        <div class="flex-1">
            <div id="notifToastTitle" class="font-black text-slate-900">Notifikasi Baru</div>
            <div id="notifToastBody" class="mt-1 text-sm text-slate-600">Ada pemberitahuan baru untuk Anda.</div>

            <div class="mt-3 flex items-center gap-2">
                <a href="<?php echo e(route('notifications.index')); ?>"
                   class="inline-flex items-center rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                    Lihat
                </a>
                <button type="button" id="notifToastClose"
                        class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>


<script>
(function () {
    // ===== NOTIFICATION SYSTEM =====
    const notifSound = document.getElementById('notifSound');
    const notifToast = document.getElementById('notifToast');
    const notifToastTitle = document.getElementById('notifToastTitle');
    const notifToastBody = document.getElementById('notifToastBody');
    const notifToastClose = document.getElementById('notifToastClose');

    let toastTimer = null;
    let lastNotifCount = <?php echo json_encode(auth()->check() ? auth()->user()->unreadNotifications()->count() : 0, 15, 512) ?>;
    let audioPrimed = false;

    function primeAudioSilently() {
        if (!notifSound || audioPrimed) return;

        // Unlock browser audio policy tanpa bunyi yang terdengar
        notifSound.muted = true;
        const p = notifSound.play();
        if (p !== undefined) {
            p.then(() => {
                notifSound.pause();
                notifSound.currentTime = 0;
                notifSound.muted = false;
                audioPrimed = true;
            }).catch(() => {
                notifSound.muted = false;
            });
        } else {
            notifSound.muted = false;
        }
    }

    function playNotifSound() {
        if (!notifSound) return;
        notifSound.currentTime = 0;
        const p = notifSound.play();
        if (p !== undefined) p.catch(() => {});
    }

    function showNotifToast(title, message) {
        if (!notifToast) return;

        if (notifToastTitle) notifToastTitle.textContent = title || 'Notifikasi Baru';
        if (notifToastBody) notifToastBody.textContent = message || 'Ada pemberitahuan baru untuk Anda.';

        notifToast.classList.remove('hidden');
        notifToast.classList.add('block');

        // animasi sederhana
        notifToast.style.opacity = '0';
        notifToast.style.transform = 'translateY(-8px)';
        notifToast.style.transition = 'opacity .2s ease, transform .2s ease';
        requestAnimationFrame(() => {
            notifToast.style.opacity = '1';
            notifToast.style.transform = 'translateY(0)';
        });

        if (toastTimer) clearTimeout(toastTimer);
        toastTimer = setTimeout(hideNotifToast, 4500);
    }

    function hideNotifToast() {
        if (!notifToast) return;
        notifToast.style.opacity = '0';
        notifToast.style.transform = 'translateY(-8px)';
        setTimeout(() => {
            notifToast.classList.add('hidden');
            notifToast.classList.remove('block');
        }, 200);
    }

    if (notifToastClose) {
        notifToastClose.addEventListener('click', hideNotifToast);
    }

    // Unlock audio secara diam-diam, bukan memutar bunyi saat klik
    document.addEventListener('pointerdown', primeAudioSilently, { once: true, passive: true });

    async function checkNotifications() {
        <?php if(auth()->guard()->check()): ?>
        try {
            const res = await fetch(<?php echo json_encode(route('notifications.check'), 15, 512) ?>, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!res.ok) return;

            const data = await res.json();
            const count = Number(data.count || 0);

            if (count > lastNotifCount) {
                const diff = count - lastNotifCount;
                lastNotifCount = count;

                const badge = document.getElementById('notifBadge');
                if (badge) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.classList.remove('hidden');
                }

                showNotifToast(
                    'Notifikasi Baru',
                    diff > 1
                        ? `Ada ${diff} notifikasi baru untuk Anda.`
                        : 'Ada notifikasi baru untuk Anda.'
                );

                // bunyi hanya saat ada notifikasi baru
                playNotifSound();
            } else {
                lastNotifCount = count;
            }
        } catch (e) {
            // diam saja kalau gagal
        }
        <?php endif; ?>
    }

    // cek awal dan lanjut polling
    window.addEventListener('load', () => {
        checkNotifications();
        setInterval(checkNotifications, 10000);
    });

    // ===== TOP LOADER =====
    const bar = document.getElementById('topLoader');
    function startBar(){
        if(!bar) return;
        bar.style.width = '30%';
        setTimeout(()=> bar.style.width = '60%', 200);
        setTimeout(()=> bar.style.width = '85%', 600);
    }
    function doneBar(){
        if(!bar) return;
        bar.style.width = '100%';
        setTimeout(()=> bar.style.width = '0%', 250);
    }

    // ===== SKELETON (optional; only if page provides #productGrid & #productSkeleton) =====
    const grid = document.getElementById('productGrid');
    const skel = document.getElementById('productSkeleton');

    function showSkeleton(){
        if (!grid || !skel) return;
        document.body.classList.add('page-loading');
        grid.classList.add('hidden');
        skel.classList.remove('hidden');
    }
    function hideSkeleton(){
        if (!grid || !skel) return;
        document.body.classList.remove('page-loading');
        skel.classList.add('hidden');
        grid.classList.remove('hidden');
    }

    const conn = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
    if (conn && (conn.effectiveType === '2g' || conn.effectiveType === 'slow-2g' || conn.saveData)) {
        showSkeleton();
        window.addEventListener('load', () => setTimeout(hideSkeleton, 250));
    }

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if(!a) return;
        const href = a.getAttribute('href');
        if(!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
        if(a.getAttribute('target') === '_blank') return;

        startBar();
        showSkeleton();
    });

    document.addEventListener('submit', () => {
        startBar();
        showSkeleton();
    });

    window.addEventListener('load', () => {
        doneBar();
        hideSkeleton();
    });

    window.addEventListener('pageshow', (e) => {
        doneBar();
        if (e.persisted) hideSkeleton();
    });

    // ===== FLASH SALE COUNTDOWN (global) =====
    function pad(n){ return String(n).padStart(2,'0'); }

    function tickFlash(){
        document.querySelectorAll('[data-fs-ends]').forEach(el=>{
            const ends = el.getAttribute('data-fs-ends');
            const t = Date.parse(ends);
            if(!t) return;

            const now = Date.now();
            let diff = Math.max(0, Math.floor((t - now) / 1000));

            const h = Math.floor(diff / 3600);
            diff -= h * 3600;
            const m = Math.floor(diff / 60);
            const s = diff - (m * 60);

            const hh = (h > 99 ? 99 : h);
            const timer = el.querySelector('.fs-timer');
            if(timer) timer.textContent = `${pad(hh)}:${pad(m)}:${pad(s)}`;
        });
    }
    tickFlash();
    setInterval(tickFlash, 1000);
})();
</script>

</body>
</html>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/layouts/market.blade.php ENDPATH**/ ?>