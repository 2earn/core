<div>


    <?php $__env->startSection('title'); ?> <?php echo e(__('Home')); ?> <?php $__env->stopSection(); ?>
    <?php $__env->startSection('css'); ?>


    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>

        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?> Home <?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?> <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/fhtaantg.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                    </lord-icon>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="javascript:void(0);" class="badge badge-soft-warning badge-border">BTC</a>
                                    <a href="javascript:void(0);" class="badge badge-soft-info badge-border">ETH</a>
                                    <a href="javascript:void(0);" class="badge badge-soft-primary badge-border">USD</a>
                                    <a href="javascript:void(0);" class="badge badge-soft-danger badge-border">EUR</a>
                                </div>
                            </div>
                            <h3 class="mb-2">$<span class="counter-value" data-target="<?php echo e((int)$cashBalance); ?>">0</span><small class="text-muted fs-13">
                                   <?php $val= explode('.', number_format($cashBalance, 2))[1]  ?> .<?php echo e($val); ?>k
                                </small></h3>
                            <h6 class="text-muted mb-0">Available Balance (USD)</h6>
                        </div>
                    </div><!--end card-->
                </div><!--end col-->







































































                    <div class="col-xxl-3 col-md-6">
                        <div class="card card-animate">
                            <div class="card-body">
                                <div class="flex-shrink-0" >
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        +29.08 %
                                    </h5>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="flex-grow-1">
                                        <lord-icon
                                            src="https://cdn.lordicon.com/qhviklyi.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                                        </lord-icon>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <a href="javascript:void(0);" class="badge badge-soft-warning badge-border">BTC</a>
                                        <a href="javascript:void(0);" class="badge badge-soft-info badge-border">ETH</a>
                                        <a href="javascript:void(0);" class="badge badge-soft-primary badge-border">USD</a>
                                        <a href="javascript:void(0);" class="badge badge-soft-danger badge-border">EUR</a>
                                    </div></br>
                                </div>
                                <h3 class="mb-2">$<span class="counter-value" data-target="<?php echo e((int)$balanceForSopping); ?>">0</span><small class="text-muted fs-13">
                                        <?php $val= explode('.', number_format($balanceForSopping, 2))[1]  ?> .<?php echo e($val); ?>k
                                    </small></h3>
                                <h6 class="text-muted mb-0">Available Balance (USD)</h6>
                            </div>
                        </div><!--end card-->
                    </div><!--end col-->


































                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        Discounts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        <i class="ri-arrow-right-up-line fs-13 align-middle"></i>
                                        +29.08 %
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span
                                            class="counter-value" data-target="<?php echo e($discountBalance); ?>">0</span>
                                    </h4>
                                    <a href="" class="text-decoration-underline">See details</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded fs-3">
                                        <i class="bx bx-user-circle text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->

                <div class="col-xl-3 col-md-6">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p
                                        class="text-uppercase fw-medium text-muted text-truncate mb-0">
                                        SMS</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-muted fs-14 mb-0">
                                        +0.00 %
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">$<span
                                            class="counter-value" data-target="<?php echo e($SMSBalance); ?>">0</span> SMS
                                    </h4>
                                    <a href="" class="text-decoration-underline">Withdraw money</a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary rounded fs-3">
                                        <i class="bx bx-wallet text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div> <!-- end row-->
    <?php $__env->stopSection(); ?>


</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/home.blade.php ENDPATH**/ ?>