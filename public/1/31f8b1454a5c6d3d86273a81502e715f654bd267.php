<div>

    <?php $__env->startSection('title'); ?>
        <?php echo app('translator')->get('translation.team'); ?>
    <?php $__env->stopSection(); ?>
    <?php $__env->startSection('content'); ?>
        <?php $__env->startComponent('components.breadcrumb'); ?>
            <?php $__env->slot('li_1'); ?>
                Pages
            <?php $__env->endSlot(); ?>
            <?php $__env->slot('title'); ?>
                Team
            <?php $__env->endSlot(); ?>
        <?php echo $__env->renderComponent(); ?>

        <div class="row">
            <div class="col-12">
                <div class="justify-content-between d-flex align-items-center mt-3 mb-4">
                </div>
                <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-1">
                    <?php $__currentLoopData = $requestIdentif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col">
                            <div class="card card-body">
                                <div class="d-flex mb-4 align-items-center">
                                    <div class="flex-shrink-0">
                                        <img
                                            src="<?php if(file_exists('uploads/profiles/profile-image-' . $req->idUser . '.png')): ?> <?php echo e(URL::asset('uploads/profiles/profile-image-'.$req->idUser.'.png')); ?><?php else: ?><?php echo e(URL::asset('uploads/profiles/default.png')); ?> <?php endif; ?>"
                                            alt=""
                                            class="avatar-sm rounded-circle"/>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h5 class="card-title mb-1"><?php echo e($req->fullphone_number); ?></h5>
                                        <p class="text-muted mb-0"><?php echo e($req->enName); ?></p>
                                    </div>
                                </div>
                                <h6 class="mb-1"><?php echo e($req->nationalID); ?></h6>
                                <p class="card-text text-muted"><?php echo e($req->DateCreation); ?></p>
                                <a href=" <?php echo e(route('validate_account', ['locale' => app()->getLocale(), 'paramIdUser' => $req->id])); ?>"
                                   class="btn btn-primary btn-sm">See Details</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

    <?php $__env->stopSection(); ?>


</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/identification-request.blade.php ENDPATH**/ ?>
