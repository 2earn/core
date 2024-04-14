<div>
    <style>
        .custom-range::-webkit-slider-thumb {
            background: #f02602;
        }
        .custom-range::-webkit-slider-thumb:active {
            background-color: red;
        }

        .custom-range::-webkit-slider-thumb::-ms-fill {
            background-color: red;
        }
        .custom-range::-moz-range-thumb {
            background: #3595f6;
        }

        .custom-range::-ms-thumb {
            background: #89e8ba;
        }
        /*.form-range {*/
        /*    width: 100%;*/
        /*    height: 1.5rem;*/
        /*    padding: 0;*/
        /*    background-color: transparent;*/
        /*    -webkit-appearance: none;*/
        /*    -moz-appearance: none;*/
        /*    appearance: none;*/
        /*}*/
        /*.form-range:focus {*/
        /*    outline: 0;*/
        /*}*/
        /*.form-range:focus::-webkit-slider-thumb {*/
        /*    box-shadow: 0 0 0 1px #fff, 0 0 0 0.25rem rgba(13, 110, 253, 0.25);*/
        /*}*/
        /*.form-range:focus::-moz-range-thumb {*/
        /*    box-shadow: 0 0 0 1px #fff, 0 0 0 0.25rem rgba(13, 110, 253, 0.25);*/
        /*}*/
        /*.form-range::-moz-focus-outer {*/
        /*    border: 0;*/
        /*}*/
        /*.form-range::-webkit-slider-thumb {*/
        /*    width: 1rem;*/
        /*    height: 1rem;*/
        /*    margin-top: -0.25rem;*/
        /*    background-color: #0d6efd;*/
        /*    border: 0;*/
        /*    border-radius: 1rem;*/
        /*    -webkit-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;*/
        /*    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;*/
        /*    -webkit-appearance: none;*/
        /*    appearance: none;*/
        /*}*/
        /*@media (prefers-reduced-motion: reduce) {*/
        /*    .form-range::-webkit-slider-thumb {*/
        /*        -webkit-transition: none;*/
        /*        transition: none;*/
        /*    }*/
        /*}*/
        /*.form-range::-webkit-slider-thumb:active {*/
        /*    background-color: #b6d4fe;*/
        /*}*/
        /*.form-range::-webkit-slider-runnable-track {*/
        /*    width: 100%;*/
        /*    height: 0.5rem;*/
        /*    color: #51A351;*/
        /*    cursor: pointer;*/
        /*    background-color: #f02602;*/
        /*    border-color: transparent;*/
        /*    border-radius: 1rem;*/
        /*}*/
        /*.form-range::-moz-range-thumb {*/
        /*    width: 1rem;*/
        /*    height: 1rem;*/
        /*    background-color: #0d6efd;*/
        /*    border: 0;*/
        /*    border-radius: 1rem;*/
        /*    -moz-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;*/
        /*    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;*/
        /*    -moz-appearance: none;*/
        /*    appearance: none;*/
        /*}*/
        /*@media (prefers-reduced-motion: reduce) {*/
        /*    .form-range::-moz-range-thumb {*/
        /*        -moz-transition: none;*/
        /*        transition: none;*/
        /*    }*/
        /*}*/
        /*.form-range::-moz-range-thumb:active {*/
        /*    background-color: #b6d4fe;*/
        /*}*/
        /*.form-range::-moz-range-track {*/
        /*    width: 100%;*/
        /*    height: 0.5rem;*/
        /*    color: transparent;*/
        /*    cursor: pointer;*/
        /*    background-color: #51A351;*/
        /*    border-color: transparent;*/
        /*    border-radius: 1rem;*/
        /*}*/
        /*.form-range:disabled {*/
        /*    pointer-events: none;*/
        /*}*/
        /*.form-range:disabled::-webkit-slider-thumb {*/
        /*    background-color: #f02602;*/
        /*}*/
        /*.form-range:disabled::-moz-range-thumb {*/
        /*    background-color: #0d6efd;*/
        /*}*/
        /*.form-range::-webkit-slider-thumb {*/

        /*    background: #6ada7d;*/

        /*}*/
        /*.form-range::-moz-range-thumb {*/
        /*    background: #FFE1FF;*/
        /*    background-color: #f02602;*/
        /*}*/

        /*.form-range::-ms-thumb {*/
        /*    background: #FFE1FF;*/
        /*    background-color: #f02602;*/
        /*}*/
        /*.form-range::-webkit-slider-thumb:active {*/
        /*    background-color: #FF8000;*/
        /*}*/
        /*.form-range::-ms-fill-lower {*/
        /*     background-color: #3595f6;*/

        /* }*/
        /*.form-range::-ms-track {*/

        /*     color: #3595f6;*/
        /*      background-color: #f02602;*/
        /*     border-color: #1a202c;*/

        /* }*/
        /*.form-range::-ms-fill-upper {*/

        /* background-color:#2d3748;*/

        /* }*/
        /*.form-range::-moz-range-track {*/

        /*     color: #f02602;*/
        /*      background-color:#3595f6;*/
        /*     border-color: #f02602; // Firefox specific?*/

        /* }*/
        /*.form-range::-webkit-slider-runnable-track {*/
        /*    width: 100%;*/
        /*    height: 0.5rem;*/
        /*    color: transparent;*/
        /*    cursor: pointer;*/
        /*    background-color: #6ada7d;*/
        /*    border-color: transparent;*/
        /*    border-radius: 1rem;*/

        /*}*/

    </style>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> <?php echo e(__('Manage my notifications')); ?> <?php $__env->endSlot(); ?>
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
                        <h4 class="mb-3 m-0 fs-15"><?php echo e(__( 'Je souhaite recevoir un E-mail :' )); ?></h4>

                        <?php $__currentLoopData = $setting_notif->where('type','b'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($setting->typeNotification=='m'): ?>
                                <div class="form-check form-switch <?php if($setting->payer==0): ?> toggle-checkboxFree <?php else: ?> toggle-checkboxPay <?php endif; ?> m-2 col-12   mb-3" dir="ltr" >
                                    <input wire:model.defer="setting_notif.<?php echo e($key); ?>.value" type="checkbox"
                                           class="form-check-input <?php if($setting->payer==0): ?> toggle-checkboxFree <?php else: ?> toggle-checkboxPay <?php endif; ?>"  id="flexSwitchCheckDefault" checked="">
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


                    <div style="margin: 0;padding: 0" class="row">

                        <?php
                            $i = 1 ;
                        ?>
                        <?php $__currentLoopData = $setting_notif->where('type','v'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($setting->id != '19'): ?>
                                <?php
                                    $idSlider = 'slider' . $i ;
                                    $classPct = 'pct'.$i;
                                ?>
                                <div class=" col-md-4 col-12" style="text-align: center">
                                    <div style=" height: 90px" class="row">
                                        <span class="toggle-label"><?php echo e(__( $setting->libelle )); ?>  </span>
                                        <p class="<?php echo e($classPct); ?>"><?php echo e($setting->value); ?>%</p>
                                    </div>
                                    <div class="" style="margin: 0;padding: 0">
                                        <input type="range" value="0" id='<?php echo e($idSlider); ?>'  wire:model.defer="setting_notif.<?php echo e($key); ?>.value"
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
                                <div style="background-color: #009fe3" class="alert   alert-dismissible alert-solid alert-label-icon fade show" role="alert">
                                    <i class="ri-notification-off-line label-icon"></i><strong><?php echo e(__('Gratuit')); ?></strong>
                                </div>
                                <div style="background-color: #bc34b6" class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" role="alert">
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
                                    <div class="form-check form-switch <?php if($setting->payer==0): ?> toggle-checkboxFree <?php else: ?> toggle-checkboxPay <?php endif; ?>  m-2 mb-3" dir="ltr" >
                                        <input wire:model.defer="setting_notif.<?php echo e($key); ?>.value" type="checkbox"
                                               class="form-check-input <?php if($setting->payer==0): ?> toggle-checkboxFree <?php else: ?> toggle-checkboxPay <?php endif; ?>"   id="flexSwitchCheckDefault" checked="">
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
                        <button class="btn btn-primary d-inline-block btn2earn" type="button" wire:click="save"><?php echo e(__('Validate')); ?></button>
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
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/notification-settings.blade.php ENDPATH**/ ?>