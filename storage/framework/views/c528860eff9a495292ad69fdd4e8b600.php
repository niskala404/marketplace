<?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php if (isset($component)) { $__componentOriginalf005a9b650879d5c895a4baa7406938a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf005a9b650879d5c895a4baa7406938a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.storefront.product-card','data' => ['p' => $p]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('storefront.product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['p' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($p)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf005a9b650879d5c895a4baa7406938a)): ?>
<?php $attributes = $__attributesOriginalf005a9b650879d5c895a4baa7406938a; ?>
<?php unset($__attributesOriginalf005a9b650879d5c895a4baa7406938a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf005a9b650879d5c895a4baa7406938a)): ?>
<?php $component = $__componentOriginalf005a9b650879d5c895a4baa7406938a; ?>
<?php unset($__componentOriginalf005a9b650879d5c895a4baa7406938a); ?>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/storefront/_product_cards.blade.php ENDPATH**/ ?>