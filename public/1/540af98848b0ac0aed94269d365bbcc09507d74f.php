<div>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e(__('Home')); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
<style>
    .logoExterne {
        display: inline-block;
        max-width: 40%;
        height: auto;
        width: 6%;
        margin: 3%;
    }
</style>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0"><?php echo e(__('Cash Balance')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                <?php if($cashBalance - $arraySoldeD[0] > 0): ?>
                                    <p class="text-success" style="max-height: 5px">+<?php echo e($cashBalance - $arraySoldeD[0]); ?>  <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                <?php elseif($cashBalance - $arraySoldeD[0] < 0): ?>
                                    <p class="text-danger" style="max-height: 5px"><?php echo e($cashBalance - $arraySoldeD[0]); ?>  <i class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>



                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">$<span class="counter-value" data-target="<?php echo e((int)$cashBalance); ?>">0</span><small class="text-muted fs-13">
                                    <?php $val= explode('.', number_format($cashBalance, 2))[1]  ?> .<?php echo e($val); ?>k
                                </small></h3>
                            <a href="<?php echo e(route('user_balance_cb' , app()->getLocale() )); ?> " class="text-decoration-underline"><?php echo e(__('see_details')); ?></a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="<?php echo e(URL::asset('assets/icons/298-coins-gradient-edited.json')); ?>" trigger="loop"
                                colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                            </lord-icon>
                        </div>

                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0"><?php echo e(__('Balance For Shopping')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <?php if($balanceForSopping - $arraySoldeD[1] > 0): ?>
                                    <p class="text-success" style="max-height: 5px">+<?php echo e($balanceForSopping - $arraySoldeD[1]); ?>  <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                <?php elseif($balanceForSopping - $arraySoldeD[1] < 0): ?>
                                    <p class="text-danger" style="max-height: 5px"><?php echo e($balanceForSopping - $arraySoldeD[1]); ?>  <i class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>



                            <h3 class="mb-4 fs-22 fw-semibold ff-secondary">$<span class="counter-value" data-target="<?php echo e((int)$balanceForSopping); ?>">0</span><small class="text-muted fs-13">
                                    <?php $val= explode('.', number_format($balanceForSopping, 2))[1]  ?> .<?php echo e($val); ?>k
                                </small></h3>
                            <a href="<?php echo e(route('user_balance_bfs' , app()->getLocale() )); ?> " class="text-decoration-underline"><?php echo e(__('see_details')); ?></a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="<?php echo e(URL::asset('assets/icons/146-basket-trolley-shopping-card-gradient-edited.json')); ?>" trigger="loop"
                                colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate  mb-0"><?php echo e(__('Discounts Balance')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                <?php if($discountBalance - $arraySoldeD[2] > 0): ?>
                                    <p class="text-success"  style="max-height: 5px">+<?php echo e($discountBalance - $arraySoldeD[2]); ?>  <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                <?php elseif($discountBalance - $arraySoldeD[2] < 0): ?>
                                    <p class="text-danger"  style="max-height: 5px" ><?php echo e($discountBalance - $arraySoldeD[2]); ?>  <i class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>



                            <h4 class="mb-4 fs-22 fw-semibold ff-secondary"><?php echo e(__('DPC')); ?><span class="counter-value" data-target="<?php echo e((int)$discountBalance); ?>">0</span><small class="text-muted fs-13">
                                    <?php $val= explode('.', number_format($discountBalance, 2))[1]  ?> .<?php echo e($val); ?>k
                                </small></h4>
                            <a href="<?php echo e(route('user_balance_db' , app()->getLocale() )); ?> " class="text-decoration-underline"><?php echo e(__('see_details')); ?></a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="<?php echo e(URL::asset('assets/icons/501-free-0-morph-gradient-edited.json')); ?>" trigger="loop"
                                colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate   mb-0"><?php echo e(__('SMS Solde')); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">

                                <?php if($discountBalance - $arraySoldeD[2] > 0): ?>
                                    <p class="text-success" style="max-height: 5px">+<?php echo e($discountBalance - $arraySoldeD[2]); ?>  <i class="ri-arrow-right-up-line fs-13 align-middle"></i></p>
                                <?php elseif($discountBalance - $arraySoldeD[2] < 0): ?>
                                    <p class="text-danger" style="max-height: 5px"><?php echo e($discountBalance - $arraySoldeD[2]); ?>  <i class="ri-arrow-right-down-line fs-13 align-middle"></i></p>
                                <?php endif; ?>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?php echo e(__('DPC')); ?><span
                                    class="counter-value" data-target="<?php echo e($SMSBalance); ?>">0</span>
                            </h4>
                            <a href="<?php echo e(route('user_balance_cb' , app()->getLocale() )); ?> " class="text-decoration-underline"><?php echo e(__('see_details')); ?></a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <lord-icon
                                src="<?php echo e(URL::asset('assets/icons/981-consultation-gradient-edited.json')); ?>" trigger="loop"
                                colors="primary:#405189,secondary:#0ab39c" style="width:55px;height:55px">
                            </lord-icon>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div>
    </div>
        <!-- end row-->





















































































        <div class="row" style="margin-top:0px ; display: flex;flex-direction: row">
            <div class="col-12" style="display: flex;justify-content:center">
               <div>
                <img src="<?php echo e(URL::asset('assets/images/arbre.gif')); ?>" class="img-fluid" alt="Responsive image" >
               </div>


               </div>
        </div>










</div>
<!--end card-->
</div>

</div>

</div>



</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/home.blade.php ENDPATH**/ ?>