<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
  'variant' => 'primary', // primary | secondary | outline | danger
  'size' => 'md',        // sm | md | lg
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
  'variant' => 'primary', // primary | secondary | outline | danger
  'size' => 'md',        // sm | md | lg
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
  $sizes = [
    'sm' => 'px-3 py-2 text-sm rounded-xl',
    'md' => 'px-4 py-2.5 text-sm rounded-xl',
    'lg' => 'px-4 py-3 text-base rounded-2xl',
  ];

  $variants = [
    'primary' => 'bg-rose-600 text-white hover:bg-rose-700',
    'secondary' => 'bg-slate-900 text-white hover:bg-slate-800',
    'outline' => 'border bg-white hover:bg-slate-50 text-slate-900',
    'danger' => 'bg-rose-600 text-white hover:bg-rose-700',
  ];

  $base = 'inline-flex items-center justify-center gap-2 font-bold active:scale-[0.99] transition';
  $sizeClass = $sizes[$size] ?? $sizes['md'];
  $variantClass = $variants[$variant] ?? $variants['primary'];
?>

<button <?php echo e($attributes->merge(['class' => "$base $sizeClass $variantClass $class"])); ?>>
  <?php echo e($slot); ?>

</button>
<?php /**PATH C:\laragon\www\ilmishop\resources\views/components/ui/button.blade.php ENDPATH**/ ?>