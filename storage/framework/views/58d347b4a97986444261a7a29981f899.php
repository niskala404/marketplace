<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'title' => null,
  'subtitle' => null,
]));

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

foreach (array_filter(([
  'title' => null,
  'subtitle' => null,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="max-w-6xl mx-auto space-y-4">
  <?php if($title): ?>
    <div class="bg-white border rounded-2xl p-4 shadow-sm">
      <div class="flex items-start justify-between gap-3 flex-wrap">
        <div>
          <h1 class="text-2xl font-black"><?php echo e($title); ?></h1>
          <?php if($subtitle): ?>
            <div class="text-sm text-slate-500 mt-1"><?php echo e($subtitle); ?></div>
          <?php endif; ?>
        </div>
        <?php if(isset($actions)): ?>
          <div class="flex items-center gap-2"><?php echo e($actions); ?></div>
        <?php endif; ?>
      </div>
      <?php if(isset($toolbar)): ?>
        <div class="mt-4"><?php echo e($toolbar); ?></div>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php echo e($slot); ?>

</div>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/app/page.blade.php ENDPATH**/ ?>