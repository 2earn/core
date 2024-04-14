<div  data-turbolinks='false'>
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
    <meta name="turbolinks-visit-control" content="reload">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>

        // $(document).on('ready turbolinks:load', function () {
        var exist = '<?php echo e(Session::has('ErrorOptCodeForget')); ?>';

        if (exist) {
            var msg = '<?php echo e(Session::get('ErrorOptCodeForget')); ?>';
            Swal.fire({
                title: ' ',
                text: msg,
                icon: 'error',
                confirmButtonText: '<?php echo e(trans('ok')); ?>'
            }).then(okay => {
                if (okay) {
                    window.location.reload();
                }
            });
        }
        var exist = '<?php echo e(Session::has('ErrorUserFound')); ?>';
        if (exist) {
            
            var msg =  '<?php echo e(Session::get('ErrorUserFound')); ?>' ;
            
            
            
            
            Swal.fire({
                title: ' ',
                text: msg,
                icon: 'error',
                confirmButtonText: '<?php echo e(trans('ok')); ?>'
            }).then(okay => {
                if (okay) {
                    window.location.reload();
                }
            });
        }

    </script>

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
                                    <h5 class="text-primary"><?php echo e(__('Forgot Password?')); ?></h5>
                                    <p class="text-muted"><?php echo e(__('Reset password with 2earn')); ?></p>

                                    <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                               colors="primary:#0ab39c" class="avatar-xl">
                                    </lord-icon>

                                </div>

                                <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                                    <?php echo e(__('Enter your mobile  will be sent to you!')); ?>

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

                                        <div   class="">
                                            <button style="width: 100%" onclick="sendSmsEvent()" class="btn btn-primary w-md waves-effect waves-light btn2earn" type="button">
                                                <?php echo e(__('Send')); ?>

                                            </button>
                                        </div>

                                    </form><!-- end form -->
                                </div>
                            </div>

                            <div class=" text-center mt-4" style="background-color: #FFFFFF">
                                <nav class="">
                                    <ul style="display: inline-block;" class="logoLogin ">
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
                            <div class="mt-4 text-center">
                                <div class="center" style=" display: flex;  justify-content: center;">
                                    <div class="dropdown ms-1 topbar-head-dropdown header-item  ">
                                        <button type="button"
                                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false">
                                            <img
                                                src="<?php echo e(URL::asset('/assets/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg')); ?>"
                                                class="rounded" alt="Header Language"
                                                height="20">
                                            <span
                                                style="margin: 10px"><?php echo e(__('lang'.app()->getLocale())); ?></span>
                                        </button>
                                        <?php
                                            $var = \Illuminate\Support\Facades\Route::currentRouteName() ;

                                        ?>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <?php $__currentLoopData = config('app.available_locales'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $locale => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e(route('forgetpassword', ['locale'=>app()->getLocale()])); ?> "
                                                   class="dropdown-item notify-item language py-2"
                                                   data-lang="en"
                                                   title="<?php echo e(__('lang'.$locale)); ?>"
                                                   data-turbolinks="false">
                                                    <img
                                                        src="<?php echo e(URL::asset('assets/images/flags/'.$value['flag'].'.svg')); ?>"
                                                        alt="user-image" class="me-2 rounded" height="20">
                                                    <span
                                                        class="align-middle"><?php echo e(__('lang'.$locale)); ?></span>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0"><?php echo e(__('remember my password')); ?><a href="<?php echo e(route('login',['locale'=>app()->getLocale()])); ?>" class="fw-semibold text-primary text-decoration-underline">
                                  <?php echo e(__('Click_here')); ?> </a></p>
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

                              <p>@ Created by 2Earn.cash</p>
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
            console.log($("#outputforget").val());
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
<?php /**PATH C:\wamp64\www\2earn\resources\views/livewire/forgot-password.blade.php ENDPATH**/ ?>