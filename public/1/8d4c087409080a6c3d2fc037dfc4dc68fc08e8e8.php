<nav class="pagination" wire:key="pagination-next-<?php echo e($paginator->currentPage()); ?>">
    <div class="previous">
        <?php if(!$paginator->onFirstPage()): ?>
        <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><use href="#icon-arrow-left" /></svg>
        </button>
        <?php endif; ?>
    </div>
    <div class="pages">
        <?php
            $links = $paginator->toArray()['links'];
            // To get rid of the "previous" and "next" links
            array_shift($links);
            array_pop($links);
        ?>
        <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($link['active']): ?>
                <button class="border-emerald-500 text-emerald-600 dark:border-emerald-600 dark:text-emerald-500" aria-current="page">
                    <?php echo e(number_format($link['label'])); ?>

                </button>
            <?php elseif($link['label'] === '...'): ?>
                <span><?php echo e($link['label']); ?></span>
            <?php else: ?>
                <button wire:click="gotoPage(<?php echo e(intval($link['label'])); ?>)" class="border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300 dark:hover:border-gray-400">
                    <?php echo e(number_format($link['label'])); ?>

                </button>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="next">
        <?php if($paginator->hasMorePages()): ?>
        <button wire:click="nextPage" wire:loading.attr="disabled" rel="next">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><use href="#icon-arrow-right" /></svg>
        </button>
        <?php endif; ?>
    </div>
</nav>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/vendor/opcodesio/log-viewer/src/../resources/views/pagination.blade.php ENDPATH**/ ?>