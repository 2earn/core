<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('Cash Balance')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('Cash Balance')); ?><?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <!--    <h5 class="card-title mb-0">Alternative Pagination</h5>-->
                    </div>
                    <div class="card-body">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table" style="width: 100%">
                            <thead>
                            <tr class="head2earn">
                                <th style=" border: none "><?php echo e(__('Ref')); ?></th>
                                <th style=" border: none "><?php echo e(__('Date')); ?></th>
                                <th style=" border: none "><?php echo e(__('Operation Designation')); ?></th>
                                <th style=" border: none "><?php echo e(__('Description')); ?></th>

                                <th style=" border: none "><?php echo e(__('Value')); ?></th>
                                <th style=" border: none "><?php echo e(__('Balance')); ?></th>
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








<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/user-balance-c-b.blade.php ENDPATH**/ ?>