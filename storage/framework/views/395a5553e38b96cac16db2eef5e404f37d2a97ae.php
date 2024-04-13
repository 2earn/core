<div>
    
    <div class="card">
        <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('account',[ 'tovalidate' =>"1", 'paramIdUser'=> $paramIdUser ])->html();
} elseif ($_instance->childHasBeenRendered('l1884589072-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l1884589072-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l1884589072-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l1884589072-0');
} else {
    $response = \Livewire\Livewire::mount('account',[ 'tovalidate' =>"1", 'paramIdUser'=> $paramIdUser ]);
    $html = $response->html();
    $_instance->logRenderedChild('l1884589072-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/validate-account.blade.php ENDPATH**/ ?>