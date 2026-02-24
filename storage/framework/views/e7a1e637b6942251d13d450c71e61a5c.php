<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'variant' => 'default', // default | primary | success | danger
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
  'variant' => 'default', // default | primary | success | danger
  'class' => '',
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
  $variants = [
    'default' => 'bg-slate-100 text-slate-700 border-slate-200',
    'primary' => 'bg-rose-50 text-rose-700 border-rose-200',
    'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
    'danger'  => 'bg-rose-50 text-rose-700 border-rose-200',
  ];
  $variantClass = $variants[$variant] ?? $variants['default'];
?>

<span <?php echo e($attributes->merge(['class' => "inline-flex items-center gap-1 text-xs font-bold px-2 py-1 rounded-full border $variantClass $class"])); ?>>
  <?php echo e($slot); ?>

</span>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>