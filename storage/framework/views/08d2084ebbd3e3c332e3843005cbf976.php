<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['order']));

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

foreach (array_filter((['order']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $events = $order->relationLoaded('shipmentEvents') ? $order->shipmentEvents : collect();
    // Fallback if no events yet: build basic milestones from timestamps
    if ($events->isEmpty()) {
        $events = collect([
            $order->paid_at ? ['status'=>'paid','title'=>'Pembayaran diterima','desc'=>null,'at'=>$order->paid_at] : null,
            $order->shipped_at ? ['status'=>'shipped','title'=>'Pesanan dikirim','desc'=>$order->tracking_no ? 'Nomor resi: '.$order->tracking_no : null,'at'=>$order->shipped_at] : null,
            $order->delivered_at ? ['status'=>'delivered','title'=>'Pesanan sampai','desc'=>null,'at'=>$order->delivered_at] : null,
            $order->received_at ? ['status'=>'received','title'=>'Pesanan diterima','desc'=>null,'at'=>$order->received_at] : null,
            $order->completed_at ? ['status'=>'completed','title'=>'Pesanan selesai','desc'=>null,'at'=>$order->completed_at] : null,
        ])->filter()->map(function ($e) {
            return (object)[
                'status' => $e['status'],
                'title' => $e['title'],
                'description' => $e['desc'],
                'happened_at' => $e['at'],
            ];
        });
    }
?>

<div class="bg-white border rounded-2xl p-5">
    <div class="flex items-center justify-between">
        <div class="font-bold">Tracking Pesanan</div>
        <?php if($order->tracking_no): ?>
            <div class="text-xs text-slate-500">Resi: <span class="font-semibold text-slate-700"><?php echo e($order->tracking_no); ?></span></div>
        <?php endif; ?>
    </div>

    <div class="mt-4">
        <?php if($events->isEmpty()): ?>
            <div class="text-sm text-slate-500">Belum ada update tracking.</div>
        <?php else: ?>
            <ol class="relative border-s border-slate-200 ms-3">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="mb-6 ms-6">
                        <span class="absolute -start-3 flex h-6 w-6 items-center justify-center rounded-full bg-rose-600 text-white shadow">
                            <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'check','class' => 'w-4 h-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'check','class' => 'w-4 h-4']); ?>
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
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="font-semibold text-slate-900"><?php echo e($ev->title); ?></div>
                                <?php if($ev->description): ?>
                                    <div class="text-sm text-slate-600 mt-0.5"><?php echo e($ev->description); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="text-xs text-slate-500 whitespace-nowrap">
                                <?php echo e(optional($ev->happened_at)->format('d M Y H:i')); ?>

                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/order-timeline.blade.php ENDPATH**/ ?>