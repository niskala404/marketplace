<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
  <div class="sticky top-0 z-10 bg-white/85 backdrop-blur border rounded-t-2xl px-4 py-3 flex items-center gap-3">
    <div class="w-10 h-10 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center">
      <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'store','class' => 'w-5 h-5 text-rose-700']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'store','class' => 'w-5 h-5 text-rose-700']); ?>
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
    <div class="flex-1 min-w-0">
      <div class="font-black truncate"><?php echo e($conversation->shop->name); ?></div>
      <div class="text-xs text-slate-500 truncate">Chat aktif setelah kamu bertransaksi dengan toko.</div>
    </div>
    <a href="<?php echo e(route('messages.index')); ?>" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-sm font-semibold">Kembali</a>
  </div>

  <div class="bg-white border border-t-0 rounded-b-2xl overflow-hidden">
    <div id="chatBox" class="px-4 py-4 space-y-2 h-[68vh] overflow-y-auto bg-gradient-to-b from-slate-50 to-white">
      <?php $prevSender = null; ?>
      <?php $__currentLoopData = $conversation->messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
          $mine = $m->sender_id === auth()->id();
          $newGroup = $prevSender !== $m->sender_id;
          $prevSender = $m->sender_id;
        ?>

        <div class="flex <?php echo e($mine ? 'justify-end' : 'justify-start'); ?>" data-mid="<?php echo e($m->id); ?>">
          <div class="max-w-[82%]">
            <?php if($newGroup): ?>
              <div class="text-[11px] mb-1 <?php echo e($mine ? 'text-right text-slate-500' : 'text-left text-slate-500'); ?>">
                <?php echo e($mine ? 'Kamu' : $conversation->shop->name); ?>

              </div>
            <?php endif; ?>

            <div class="relative rounded-2xl px-4 py-2 shadow-sm <?php echo e($mine ? 'bg-rose-600 text-white rounded-tr-md' : 'bg-white border text-slate-900 rounded-tl-md'); ?>">
              <div class="text-sm leading-relaxed whitespace-pre-wrap break-words"><?php echo e($m->body); ?></div>
              <div class="mt-1 text-[11px] opacity-70 <?php echo e($mine ? 'text-right' : 'text-left'); ?>"><?php echo e($m->created_at->format('d M H:i')); ?></div>
              <span class="absolute bottom-2 <?php echo e($mine ? '-right-1' : '-left-1'); ?> w-3 h-3 rotate-45 <?php echo e($mine ? 'bg-rose-600' : 'bg-white border border-slate-200'); ?>"></span>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <div class="h-3"></div>
    </div>

    <div class="border-t bg-white px-3 py-3">
      <form method="POST" action="<?php echo e(route('messages.send',$conversation)); ?>" class="flex items-end gap-2" id="sendForm">
        <?php echo csrf_field(); ?>
        <textarea name="body" id="msgInput" rows="1" class="w-full resize-none rounded-2xl border-slate-200 focus:border-rose-400 focus:ring-rose-200 px-4 py-3 text-sm bg-slate-50" placeholder="Tulis pesan..." required autocomplete="off"></textarea>

        <button type="submit" class="shrink-0 w-12 h-12 rounded-full bg-slate-900 hover:bg-slate-800 text-white flex items-center justify-center font-bold shadow" aria-label="Kirim">
          <?php if (isset($component)) { $__componentOriginal16783dc90daf260581c0ddf14436b31a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal16783dc90daf260581c0ddf14436b31a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ic','data' => ['name' => 'send','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ic'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'send','class' => 'w-5 h-5']); ?>
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
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  const chatBox = document.getElementById('chatBox');
  const pollUrl = <?php echo json_encode(route('messages.poll', $conversation), 512) ?>;
  const myId = <?php echo json_encode(auth()->id(), 15, 512) ?>;
  const input = document.getElementById('msgInput');
  const form = document.getElementById('sendForm');

  function lastId(){
    const nodes = chatBox.querySelectorAll('[data-mid]');
    if(!nodes.length) return 0;
    return parseInt(nodes[nodes.length-1].getAttribute('data-mid') || '0', 10);
  }
  function isNearBottom(){
    return (chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight) < 120;
  }
  function scrollToBottom(){ chatBox.scrollTop = chatBox.scrollHeight; }
  scrollToBottom();

  function autosize(){
    input.style.height = 'auto';
    input.style.height = Math.min(input.scrollHeight, 140) + 'px';
  }
  input.addEventListener('input', autosize);
  autosize();

  input.addEventListener('keydown', (e) => {
    if(e.key === 'Enter' && !e.shiftKey){
      e.preventDefault();
      form.requestSubmit();
    }
  });

  function renderMessage(m){
    const mine = m.sender_id === myId;

    const wrap = document.createElement('div');
    wrap.className = 'flex ' + (mine ? 'justify-end' : 'justify-start');
    wrap.setAttribute('data-mid', m.id);

    const outer = document.createElement('div');
    outer.className = 'max-w-[82%]';

    const bubble = document.createElement('div');
    bubble.className = 'relative rounded-2xl px-4 py-2 shadow-sm ' +
      (mine ? 'bg-rose-600 text-white rounded-tr-md' : 'bg-white border text-slate-900 rounded-tl-md');

    const body = document.createElement('div');
    body.className = 'text-sm leading-relaxed whitespace-pre-wrap break-words';
    body.textContent = m.body;

    const time = document.createElement('div');
    time.className = 'mt-1 text-[11px] opacity-70 ' + (mine ? 'text-right' : 'text-left');
    time.textContent = m.created_at;

    const tail = document.createElement('span');
    tail.className = 'absolute bottom-2 ' + (mine ? '-right-1' : '-left-1') + ' w-3 h-3 rotate-45 ' +
      (mine ? 'bg-rose-600' : 'bg-white border border-slate-200');

    bubble.appendChild(body);
    bubble.appendChild(time);
    bubble.appendChild(tail);
    outer.appendChild(bubble);
    wrap.appendChild(outer);
    return wrap;
  }

  async function poll(){
    try{
      const stick = isNearBottom();
      const after = lastId();
      const res = await fetch(pollUrl + '?after_id=' + after, { headers: { 'Accept': 'application/json' } });
      if(!res.ok) return;
      const data = await res.json();
      if(!data.messages || !data.messages.length) return;

      data.messages.forEach(m => chatBox.appendChild(renderMessage(m)));
      if(stick) scrollToBottom();
    }catch(e){}
  }

  form.addEventListener('submit', () => setTimeout(scrollToBottom, 50));
  setInterval(poll, 2500);
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.market', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\ilmishop\resources\views/messages/show.blade.php ENDPATH**/ ?>