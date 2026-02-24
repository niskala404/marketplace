<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'title' => 'Belum ada data',
  'message' => null,
  'class' => '',
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
  'title' => 'Belum ada data',
  'message' => null,
  'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => "bg-white border rounded-2xl p-10 text-center text-slate-600 shadow-sm $class"])); ?>>
  <div class="text-lg font-black text-slate-900"><?php echo e($title); ?></div>
  <?php if($message): ?>
    <div class="text-sm text-slate-500 mt-1"><?php echo e($message); ?></div>
  <?php endif; ?>
  <?php if(isset($action)): ?>
    <div class="mt-4"><?php echo e($action); ?></div>
  <?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/ui/empty.blade.php ENDPATH**/ ?>