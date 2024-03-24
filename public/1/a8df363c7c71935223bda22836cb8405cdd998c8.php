<div>
    <?php $__env->startSection('title'); ?><?php echo e(__('Balance For Shopping')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?><?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php echo e(__('Balance For Shopping')); ?> <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="col-lg-4">
                            <h6 class="fw-semibold"><?php echo e(__('Opération')); ?></h6>
                            <select class="select2-hidden-accessible bfs_operation_multiple" name="states[]" id="select2bfs"multiple="multiple">
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <table class="table nowrap dt-responsive align-middle table-hover table-bordered"
                                   id="ub_table_bfs" style="width: 100%">
                                <thead>
                                <tr class="head2earn">
                                    <th style=" border: none;"><?php echo e(__('Num')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Ref')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Date')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Operation Designation')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Description')); ?></th>
                                    
                                    <th style=" border: none "><?php echo e(__('Value')); ?></th>
                                    <th style=" border: none "><?php echo e(__('Balance')); ?></th>
                                    <!--<th style=" border: none;display: none ">ss</th>-->
                                </tr>
                                </thead>
                                <tbody class="body2earn">

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
            <!--end col-->
        </div>
        <!--end row-->

</div>








<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/user-balance-b-f-s.blade.php ENDPATH**/ ?>