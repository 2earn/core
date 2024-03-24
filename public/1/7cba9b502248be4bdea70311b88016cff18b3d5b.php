<div>
    
    <div class="card">
        <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('account',[ 'tovalidate' =>"1", 'paramIdUser'=> $paramIdUser ])->html();
} elseif ($_instance->childHasBeenRendered('l2268121216-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l2268121216-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2268121216-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2268121216-0');
} else {
    $response = \Livewire\Livewire::mount('account',[ 'tovalidate' =>"1", 'paramIdUser'=> $paramIdUser ]);
    $html = $response->html();
    $_instance->logRenderedChild('l2268121216-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
    </div>

</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/validate-account.blade.php ENDPATH**/ ?>