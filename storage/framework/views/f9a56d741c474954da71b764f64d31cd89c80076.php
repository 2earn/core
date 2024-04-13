<div>
    <script>

        var existSucess = '<?php echo e(Session::has('ErrorExpirationCode')); ?>';

        if (existSucess && "<?php echo e(Session::get('ErrorExpirationCode')); ?>" != ""  ) {
            var msgsuccess =  "<?php echo e(Session::get('ErrorExpirationCode')); ?>" ;
            // "Opt code expired !";
            var local = '<?php echo e(app()->getLocale()); ?>';
            if (local == 'ar') {
                msgsuccess = "هذا  !";
            }
            Swal.fire({
                title: ' ',
                text: msgsuccess,
                icon: 'error',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
            })
        }
        var existSucess2 = '<?php echo e(Session::has('ErrorOptCode')); ?>';
        if (existSucess2 && "<?php echo e(Session::get('ErrorOptCode')); ?>" != ""  ) {
            var msgsuccess2 = "Invalid Opt code !";
            var local = '<?php echo e(app()->getLocale()); ?>';
            if (local == 'ar') {
                msgsuccess = "هذا  !";
            }
            Swal.fire({
                title: ' ',
                text: msgsuccess2,
                icon: 'error',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
            })
        }

    </script>
    <div id="main-wrapper">
        <div class="authincation section-padding">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-xl-5 col-md-6">
                        <div class="mini-logo text-center my-5">
                            <a href="<?php echo e(route('registre',app()->getLocale())); ?>"><img
                                    src="<?php echo e(asset('assets/images/2earn.png')); ?>"
                                    alt=""></a>
                        </div>
                        <div class="auth-form card">
                            <div class="card-body">
                                <a class="page-back text-muted"
                                   href="<?php echo e(route('registre',app()->getLocale())); ?>"><span><i
                                            class="fa fa-angle-left"></i></span><?php echo e(__('Back')); ?> </a>
                                <h3 class="text-center"><?php echo e(__('OTP Verification')); ?></h3>
                                <p class="text-center"><?php echo e(__('We will send one time code on this number')); ?> </br> <?php echo e($numPhone); ?></p>
                                <form method=" " action="javascript:void(0)">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label><?php echo e(__('Your OTP Code')); ?></label>
                                        <input type="text" class="form-control text-center font-weight-bold"
                                               name="activationCodeValue" wire:model.defer="code">
                                        
                                        
                                    </div>
                                    <div class="text-center"style="margin-top:10px">
                                        <?php if($message = Session::get('error')): ?>
                                            <p class="text-danger"><?php echo e($message); ?></p>
                                        <?php endif; ?>
                                        <button type="button" wire:click="verifCodeOpt"
                                                class="btn btn-success w-100 btnlogin"><?php echo e(__('Verify')); ?></button>
                                    </div>
                                </form>
                            </div>
                            <div style="background-color: #FFFFFF ". class="card-body">
                                <div class="footerOpt"><?php echo e(__('Dont get code?')); ?> <a><?php echo e(__('Resend')); ?> </a></div>
                            </div>
                            <div class="card-footer text-center " style="background-color: #FFFFFF">
                                <nav class="">
                                    <ul class="logoLogin ">
                                        <li class="active active-underline">
                                            <div>
                                                <a href="<?php echo e(env('SHOP_LIEN')); ?>">
                                                    <img src="<?php echo e(asset('assets/images/icon-shop.png')); ?>" width="70" height="70">
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <a href="<?php echo e(env('LEARN_LIEN')); ?>">
                                                    <img src="<?php echo e(asset('assets/images/icon-learn.png')); ?>" width="70" height="70">
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <a href="<?php echo e(env('LEARN_LIEN')); ?>"><img
                                                        <?php if(isset($plateforme)): ?> <?php if($plateforme==1): ?> style="box-shadow: 0 0 30px #004dcede;
                                                border-radius: 39px;"
                                                        <?php endif; ?> <?php endif; ?> src="<?php echo e(asset('assets/images/Move2earn Icon.png')); ?>"
                                                        width="70" height="70"></a>
                                            </div>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\ghazi\Documents\GitHub\2earnprod\resources\views/livewire/check-opt-code.blade.php ENDPATH**/ ?>