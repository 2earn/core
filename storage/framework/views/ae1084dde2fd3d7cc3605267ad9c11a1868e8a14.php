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
                        <div class="row g-4">
                            <div class="col-sm-auto">
                                <div>

                               <img src=" <?php echo e(asset('assets/images/qr_code.jpg')); ?>" class="rounded avatar-lg">
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="d-flex justify-content-sm-end">
                                    <div class="search-box ms-2">
                                        <p><?php echo e(__('Cash Balance description')); ?></p> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="ub_table" style="width: 100%">
                            <thead class="table-light">
                            <tr class="head2earn  tabHeader2earn" >
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








<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/user-balance-c-b.blade.php ENDPATH**/ ?>