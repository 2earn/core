<div  data-turbolinks='false'>
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
    <meta name="turbolinks-visit-control" content="reload">
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="<?php echo e(route('login',app()->getLocale())); ?>" class="d-inline-block auth-logo">
                                    <img src="<?php echo e(URL::asset('assets/images/2Earn.png')); ?>" alt="" height="60">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium"> </p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Forgot Password?</h5>
                                    <p class="text-muted">Reset password with 2Earn.cash</p>

                                    <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                               colors="primary:#0ab39c" class="avatar-xl">
                                    </lord-icon>

                                </div>

                                <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                                    Enter your mobile  will be sent to you!
                                </div>
                                <div class="p-2">
                                    <form  >
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="useremail" class="form-label"><?php echo e(__('Your phone number')); ?></label>
                                            <input type="tel" name="mobile" id="phoneforget"
                                                   class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    placeholder="<?php echo e(__('PH_MobileNumber')); ?>"
                                                   value=""  >
                                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-feedback" role="alert">
                                                <strong><?php echo e($message); ?></strong>
                                            </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <input type="text"   hidden name="fullnumber" id="outputforget"
                                                   value=""
                                                   class="form-control">
                                            <input type="text" hidden   name="ccodeforget" id="ccodeforget" >
                                        </div>

                                        <div class="text-end">
                                            <button onclick="sendSmsEvent()" class="btn btn-primary w-md waves-effect waves-light" type="button">
                                                Reset
                                            </button>
                                        </div>

                                    </form><!-- end form -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Wait, I remember my password... <a href="<?php echo e(route('login',app()->getLocale())); ?>"
                                                                               class="fw-semibold text-primary text-decoration-underline">
                                    Click here </a></p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">

                              @ Created by 2Earn.cash</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <script>
        document.querySelector("#phoneforget").addEventListener("keypress", function (evt) {

            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });
        function sendSmsEvent()
        {
            window.livewire.emit('Presend',$("#ccodeforget").val(),$("#outputforget").val());
        }

        window.addEventListener('OptForgetPass',event=>{
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: '<?php echo e(__('We_will_send')); ?><br> ',
                html :  '<?php echo e(__('We_will_send')); ?><br>'+event.detail.FullNumber +'<br>'+'<?php echo e(__('Your OTP Code')); ?>',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                timer: '<?php echo e(env('timeOPT')); ?>',
                confirmButtonText: 'Confirm' ,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML='<?php echo e(trans('Dont get code?')); ?>'   +' <a>'+  '<?php echo e(trans('Resend')); ?>' + '</a>' ;

                    timerInterval = setInterval(() => {
                        b.textContent = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },

            }).then((resultat)=>{
                if(resultat.value){
                    window.livewire.emit('sendSms',resultat.value,$("#outputforget").val());
                }
                if(resultat.isDismissed)
                {
                    location.reload();
                }
            })
        })

        // function checkopt()
        // {
        //     window.livewire.emit('checkopt',$("#ccodeforget").val(),$("#outputforget").val());
        // }
        // function signupEvent()
        // {
        //     // alert("sdfsf");
        //     window.livewire.emit('changefullNumber',$("#output").val(),$("#ccode").val());
        // }

    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/forgot-password.blade.php ENDPATH**/ ?>