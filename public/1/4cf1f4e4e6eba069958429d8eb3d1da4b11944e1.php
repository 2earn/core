<div>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> <?php echo e(__('Hobbies')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>














































        <div id="listeHobbies" class="row">
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-danger">
                    <div class="card-header">

                        <h5 class="card-title">Learn</h5>
                        <h6 class="card-subtitle text-muted"><?php echo e(__('learnHobbiesDescription')); ?></h6>
                    </div>
                    <div class="card-body">

                        <?php $__currentLoopData = $hobbies->where('platform','learn'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $hobbie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check form-switch   ms-1 me-1 mb-3" dir="ltr">
                                <input wire:model.defer="hobbies.<?php echo e($key); ?>.selected" type="checkbox"
                                       class="form-check-input" id="" checked="">
                                <label class="form-check-label"
                                       for="customSwitchsizesm"><?php echo e(__('Hobbies_'. $hobbie->name )); ?>  </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-warning">
                    <div class="card-header">

                        <h5 class="card-title">Shop</h5>
                        <h6 class="card-subtitle text-muted"><?php echo e(__('shopHobbiesDescription')); ?></h6>
                    </div>
                    <div class="card-body">


                        <?php $__currentLoopData = $hobbies->where('platform','shop'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $hobbie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check form-switch   ms-1 me-1 mb-3" dir="ltr">
                                <input wire:model.defer="hobbies.<?php echo e($key); ?>.selected" type="checkbox"
                                       class="form-check-input" id="" checked="">
                                <label class="form-check-label"
                                       for="customSwitchsizesm"><?php echo e(__('Hobbies_'. $hobbie->name )); ?>  </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-primary">
                    <div class="card-header">
                        <h5 class="card-title">Move</h5>
                        <h6 class="card-subtitle text-muted"><?php echo e(__('2earnHobbiesDescription')); ?></h6>
                    </div>
                    <div class="card-body p-3">
                        <?php $__currentLoopData = $hobbies->where('platform','move'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $hobbie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-check form-switch ms-1 me-1 mb-3" dir="ltr" >
                                    <input wire:model.defer="hobbies.<?php echo e($key); ?>.selected" type="checkbox"
                                           class="form-check-input toggle-checkboxFree" id="" checked="" >
                                    <label class="form-check-label"
                                           for="customSwitchsizesm"><?php echo e(__('Hobbies_'. $hobbie->name )); ?>  </label>
                                </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>



























































































        </div>
    <script>
        $('#listeHobbies :checkbox').change(function () {
            // alert(this.checked);
            window.livewire.emit('save');
        });
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/demo.2earn.cash/resources/views/livewire/hobbies.blade.php ENDPATH**/ ?>