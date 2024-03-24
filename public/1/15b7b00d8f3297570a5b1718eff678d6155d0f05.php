<div>
    <style>

    </style>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?> <?php echo e(__('Manage my notifications')); ?> <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row">

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"><?php echo e(__('email_notification')); ?></h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3" src="<?php echo e(asset('assets\icons/eMail Icon'.'.png')); ?>" width="30" height="30" alt="">
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <h4 class="mb-3 fs-15"><?php echo e(__( 'Je souhaite recevoir un E-mail :' )); ?></h4>

                        <?php $__currentLoopData = $setting_notif->where('type','b'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if($setting->typeNotification=='m'): ?>
                                <div class="form-check form-switch <?php if($setting->payer==0): ?> form-switch-success <?php else: ?> form-switch-danger <?php endif; ?> ms-5 me-5 mb-3" dir="ltr" >
                                    <input wire:model.defer="setting_notif.<?php echo e($key); ?>.value" type="checkbox"
                                           class="form-check-input"  id="flexSwitchCheckDefault" checked="">

                                    <label class="form-check-label"
                                           for="customSwitchsizesm"><?php echo e(__( $setting->libelle )); ?>  </label>

                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"><?php echo e(__('Discount_plan')); ?></h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3" src="<?php echo e(asset('assets\icons/discount'.'.png')); ?>" width="30" height="30" alt="">
                    </div>
                </div>
                <div class="card-body">



                    <div class="row mt-5">
                        <?php
                            $i = 1 ;
                        ?>
                        <?php $__currentLoopData = $setting_notif->where('type','v'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if($setting->id != '19'): ?>
                                <?php
                                    $idSlider = 'slider' . $i ;
                                    $classPct = 'pct'.$i;
                                ?>
                                <div class="mb-4 col-md-4 col-12" style="text-align: center">
                                    <div class="circular-progress">
                                        <span class="toggle-label"><?php echo e(__( $setting->libelle )); ?>  </span>
                                        <p class="<?php echo e($classPct); ?>"><?php echo e($setting->value); ?>%</p>
                                    </div>
                                    <div class="">
                                        <label for="<?php echo e($idSlider); ?>" class='sr-only'>range slider</label>
                                        <input type="range" orient="" class="custom-range"
                                               id='<?php echo e($idSlider); ?>'
                                               wire:model.defer="setting_notif.<?php echo e($key); ?>.value"
                                               name="discount_email_p">
                                    </div>
                                </div>
                                <?php
                                    $i= $i + 1 ;
                                ?>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="live-preview mt-5 ">

                            <div class="d-flex justify-content-center gap-2">
                                <div class="alert alert-success alert-dismissible alert-solid alert-label-icon fade show" role="alert">
                                    <i class="ri-notification-off-line label-icon"></i><strong><?php echo e(__('Gratuit')); ?></strong>
                                </div>
                                <div class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" role="alert">
                                    <i class="ri-notification-off-line label-icon"></i><strong><?php echo e(__('Payant')); ?></strong>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <!-- end card -->
        </div>



        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1"><?php echo e(__('sms_notification')); ?></h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3" src="<?php echo e(asset('assets\icons/SMS Icon'.'.png')); ?>" width="30" height="30" alt="">
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row">
                        <h4 class="mb-3 fs-15"><?php echo e(__( 'Je souhaite recevoir un SMS :' )); ?></h4>

                        <?php $__currentLoopData = $setting_notif->where('type','b'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php if($setting->typeNotification=='s'): ?>
                                <div class="form-check form-switch <?php if($setting->payer==0): ?> form-switch-success <?php else: ?> form-switch-danger <?php endif; ?> ms-5 me-5 mb-3" dir="ltr" >
                                    <input wire:model.defer="setting_notif.<?php echo e($key); ?>.value" type="checkbox"
                                           class="form-check-input"  id="flexSwitchCheckDefault" checked="">
                                    <label class="form-check-label" for="customSwitchsizesm"><?php echo e(__( $setting->libelle )); ?>  </label>

                                </div>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="d-flex flex-row" style="margin-top: 30px;gap: 10px;">
                        <div><label for=""><?php echo e(__('accepte_recevoir')); ?></label></div>
                        <div>
                            <select style="width: 60px" id="nbrSms" wire:model.defer="nbrSms">
                                <?php for($i=0; $i<= $nbrSmsPossible ;$i++): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div><label for=""><?php echo e(__('SMS_by_week')); ?></label></div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">

                <div class="card-body">


                    <!-- Buttons Grid -->
                    <div class="d-grid gap-2" >
                        <button class="btn btn-primary d-inline-block" type="button" wire:click="save"><?php echo e(__('Validate')); ?></button>
                    </div>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>


    <script>
        var slider1 = document.querySelector('#slider1');
        var pct1 = document.querySelector('.pct1');
        slider1.oninput = () => {
            pct1.textContent = `${slider1.value}%`
            // alert(pct1.textContent);
            // percent for dashoffset
            const p = (1 - slider1.value / 100) * (2 * (22 / 7) * 40);
        }
        var slider2 = document.querySelector('#slider2');
        var pct2 = document.querySelector('.pct2');
        slider2.oninput = () => {
            pct2.textContent = `${slider2.value}%`
            const p = (1 - slider2.value / 100) * (2 * (22 / 7) * 40);
        }
        var slider3 = document.querySelector('#slider3');
        var pct3 = document.querySelector('.pct3');
        slider3.oninput = () => {
            pct3.textContent = `${slider3.value}%`
            const p = (1 - slider3.value / 100) * (2 * (22 / 7) * 40);
        }
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/notification-settings.blade.php ENDPATH**/ ?>