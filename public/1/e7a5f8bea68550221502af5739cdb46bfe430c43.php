<div wire:ignore.self class="search" x-bind:class="{'has-error': $store.search.error}"
     x-init='$store.search.update(<?php echo json_encode($query, 15, 512) ?>, <?php echo json_encode($queryError, 15, 512) ?>, <?php echo json_encode(route('blv.search-more'), 15, 512) ?>, <?php echo json_encode($hasMoreResults, 15, 512) ?>, <?php echo json_encode($percentScanned, 15, 512) ?>)'
>
    <div class="prefix-icon">
        <label for="query" class="sr-only">Search</label>
        <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class="hidden" wire:target="query" class="h-4 w-4" x-bind:class="{'hidden': $store.search.searching}" viewBox="0 0 20 20" fill="currentColor"><use href="#icon-search" /></svg>
        <svg xmlns="http://www.w3.org/2000/svg" wire:loading.class.remove="hidden" wire:target="query" class="spin w-5 h-5 -mr-1" x-bind:class="{'hidden': !$store.search.searching}" viewBox="0 0 24 24" fill="currentColor"><use href="#icon-spinner" /></svg>
    </div>
    <div class="relative flex-1 m-1">
        <input wire:model.lazy="query" name="query" id="query" type="text" />
        <div x-show="$store.search.query !== ''" class="clear-search">
            <button wire:click="clearQuery">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><use href="#icon-cross" /></svg>
            </button>
        </div>
    </div>
    <div class="submit-search">
        <?php if($hasMoreResults): ?>
        <button disabled="disabled">
            <span>Searching <?php echo e(isset($file) ? $file->name : 'all files'); ?>...</span>
        </button>
        <?php else: ?>
        <button wire:click="submitSearch">
            <span>Search <?php echo e(isset($file) ? 'in "' . \Illuminate\Support\Str::limit($file->name, 30).'"' : 'all files'); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><use href="#icon-arrow-right" /></svg>
        </button>
        <?php endif; ?>
    </div>
</div>
<div class="relative h-0 w-full overflow-visible">
    <div class="search-progress-bar" x-show="$store.search.searching" x-bind:style="{ width: $store.search.percentScanned + '%' }"></div>
</div>
<script wire:key="<?php echo e('search-key-'.md5($query)); ?>" x-init='$store.search.update(<?php echo json_encode($query, 15, 512) ?>, <?php echo json_encode($queryError, 15, 512) ?>, <?php echo json_encode(route('blv.search-more'), 15, 512) ?>, <?php echo json_encode($hasMoreResults, 15, 512) ?>, <?php echo json_encode($percentScanned, 15, 512) ?>)'>
</script>
<p class="mt-1 text-red-600 text-xs" x-show="$store.search.error" x-html="$store.search.error"></p>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/vendor/opcodesio/log-viewer/src/../resources/views/partials/search-input.blade.php ENDPATH**/ ?>