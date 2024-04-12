<div>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> <?php echo e(__('Evolution_arbre')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
</div>
<div class="row" style="margin-top:0px ; display: flex;flex-direction: row">
    <div class="col-12" style="display: flex;justify-content:center">
        <div>
            <img src="<?php echo e(URL::asset('assets/images/arbre.gif')); ?>" class="img-fluid" alt="Responsive image">
        </div>
    </div>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/httpdocs/resources/views/livewire/evolution-arbre.blade.php ENDPATH**/ ?>