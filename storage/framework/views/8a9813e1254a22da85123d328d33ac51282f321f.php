
<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('history')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('history')); ?> <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>
            <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                    </div>
                    <div class="card-body table-responsive">
                        <table id="userPurchase_table" class="table nowrap dt-responsive align-middle table-hover table-bordered" style="width:100%">
                            <thead  class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
                                <th style=" border: none;"><?php echo e(__('Date')); ?></th>
                                <th style=" border: none ;text-align: center; "><?php echo e(__('Ref')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Item')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('Quantity')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Amout')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('invitation to purchase')); ?></th>
                                <th style=" border: none ;text-align: center; "><?php echo e(__('Visit')); ?></th>
                                <th style=" border: none ; text-align: center; "><?php echo e(__('Proactive BFS')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('Proactive CB')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Cash back BFS')); ?></th>
                                <th style=" border: none;text-align: center; "><?php echo e(__('Cash back CB')); ?></th>
                                <th style=" border: none ;text-align: center;"><?php echo e(__('Economy')); ?></th>
                            </tr>
                            </thead>
                            <tbody class="body2earn">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end col-->
        </div>
        <!--end row-->

</div>
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/user-purchase-history.blade.php ENDPATH**/ ?>