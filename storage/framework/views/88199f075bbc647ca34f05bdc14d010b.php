<?php $__env->startSection('content'); ?>
<h1 class="text-2xl font-black mb-4">Flash Sale</h1>

<div class="flex justify-between items-center mb-4">
  <div class="text-slate-600 text-sm">Flash sale aktif akan tampil di homepage selama periodenya.</div>
  <a class="px-4 py-2 rounded-xl bg-fuchsia-600 text-white font-bold" href="<?php echo e(route('admin.flash-sales.create')); ?>">+ Flash Sale</a>
</div>

<div class="bg-white border rounded-2xl overflow-hidden">
  <table class="w-full text-sm">
    <thead class="bg-slate-50">
      <tr>
        <th class="text-left p-3">Nama</th>
        <th class="text-left p-3">Periode</th>
        <th class="text-left p-3">Aktif</th>
        <th class="text-right p-3">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y">
      <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <tr>
          <td class="p-3">
            <div class="font-semibold"><?php echo e($s->name); ?></div>
            <div class="text-xs text-slate-500">ID: <?php echo e($s->id); ?></div>
          </td>
          <td class="p-3 text-slate-600">
            <div><?php echo e($s->starts_at->format('Y-m-d H:i')); ?></div>
            <div><?php echo e($s->ends_at->format('Y-m-d H:i')); ?></div>
          </td>
          <td class="p-3">
            <span class="px-2 py-1 rounded-full text-xs <?php echo e($s->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-600 border border-slate-200'); ?>">
              <?php echo e($s->is_active ? 'aktif' : 'nonaktif'); ?>

            </span>
          </td>
          <td class="p-3 text-right">
            <a class="px-3 py-2 rounded-xl bg-slate-900 text-white" href="<?php echo e(route('admin.flash-sales.edit',$s)); ?>">Kelola</a>
            <form class="inline" method="POST" action="<?php echo e(route('admin.flash-sales.destroy',$s)); ?>" onsubmit="return confirm('Hapus flash sale?')">
              <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
              <button class="px-3 py-2 rounded-xl bg-rose-600 text-white">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <tr><td colspan="4" class="p-6 text-slate-600">Belum ada flash sale.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="mt-4"><?php echo e($sales->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/admin/flash_sales/index.blade.php ENDPATH**/ ?>