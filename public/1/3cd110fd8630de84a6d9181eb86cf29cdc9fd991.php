<div data-turbolinks='false'>

    <style>
        .iti {
            width: 100% !important;
        }


    </style>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <script>
                var existLogout = '<?php echo e(Session::has('FromLogOut')); ?>';
                if (existLogout) {
                    location.reload();
                }
                var existmessageLogin = '<?php echo e(Session::has('message')); ?>';
                if (existmessageLogin) {
                    var msgMsgLogin = '<?php echo e(Session::get('message')); ?>';
                    var local = '<?php echo e(Session::has('locale')); ?>';
                    if (local == 'ar') {
                        msg = "هاتفك أو كلمة المرور الخاصة بك غير صحيحة !";
                    }
                    Swal.fire({
                        title: ' ',
                        text: msgMsgLogin,
                        icon: 'error',
                        confirmButtonText: '<?php echo e(trans('ok')); ?>'
                    }).then(okay => {
                        if (okay) {
                            window.location.reload();
                        }
                    });
                }
            </script>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index" class="d-block">
                                                    <img src="<?php echo e(URL::asset('assets/images/logo-light.png')); ?>" alt=""
                                                         height="18">
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-success"></i>
                                                </div>

                                                <div id="qoutescarouselIndicators" class="carousel slide"
                                                     data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="0" class="active" aria-current="true"
                                                                aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="2" aria-label="Slide 3"></button>

                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">
                                                                <?php echo e(__('slide1')); ?>


                                                            </p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">
                                                                <?php echo e(__('slide2')); ?></p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">
                                                                <?php echo e(__('slide3')); ?></p>
                                                        </div>


                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary"> <?php echo e(__('Welcome_Back')); ?></h5>
                                            <p class="text-muted"> <?php echo e(__('continueTo2earn')); ?> </p>
                                        </div>

                                        <div class="mt-4">
                                            

                                            
                                            
                                            
                                            
                                            

                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            

                                            
                                            
                                            
                                            
                                            
                                            

                                            
                                            
                                            

                                            
                                            
                                            
                                            

                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            

                                            


                                            <form>
                                                <?php echo csrf_field(); ?>
                                                
                                                
                                                <div dir="ltr" class="mb-3">
                                                    <label for="username"
                                                           class="float-start form-label"><?php echo e(__('Mobile Number')); ?></label>
                                                    <br>
                                                    <input type="tel" name="mobile" id="phone"
                                                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                           value=""
                                                           placeholder="<?php echo e(__('PH_MobileNumber')); ?>">
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
                                                    <input type="hidden" name="ccodelog" id="ccodelog">
                                                    <input type="hidden" name="isoCountryLog" id="isoCountryLog">
                                                </div>

                                                <div class="mb-3">
                                                    <label
                                                        class="float-end">
                                                        <a href="<?php echo e(route('forgetpassword',app()->getLocale())); ?>"
                                                           class="text-muted"><?php echo e(__('Forgot Password?')); ?></a>
                                                    </label>
                                                    <label class="form-label"
                                                           for="password-input"><?php echo e(__('Password')); ?></label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password"
                                                               class="form-control pe-5 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                               name="password" placeholder="<?php echo e(__('PH_Password')); ?>"
                                                               id="password-input">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                            type="button" id="togglePassword"><i
                                                                class="ri-eye-fill align-middle"></i></button>
                                                        <?php $__errorArgs = ['password'];
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
                                                    </div>
                                                </div>

                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="auth-remember-check">
                                                    <label class="form-check-label"
                                                           for="auth-remember-check"><?php echo e(__('Remember me')); ?></label>
                                                </div>

                                                <div class="mt-4">
                                                    <button onclick="functionLogin()" class="btn btn-success w-100"
                                                            type="button"><?php echo e(__('Sign in')); ?>

                                                    </button>
                                                </div>
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
                                                                <a href="<?php echo e(route($var, ['locale'=> $locale ])); ?> "
                                                                   class="dropdown-item notify-item language py-2"
                                                                   data-lang="en"
                                                                   title="<?php echo e(__('lang'.$locale)); ?>"
                                                                   data-turbolinks="false">
                                                                    <img
                                                                        src="<?php echo e(URL::asset('assets/images/flags/'.$value['flag'].'.svg')); ?>"
                                                                        alt="user-image" class="me-2 rounded"
                                                                        height="20">
                                                                    <span
                                                                        class="align-middle"><?php echo e(__('lang'.$locale)); ?></span>
                                                                </a>

                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                        </div>
                                                    </div>
                                                </div>

                                            </form>
                                            
                                            
                                            
                                            

                                            

                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            
                                            


                                            
                                            

                                            
                                            
                                            
                                            
                                            
                                        </div>

                                        <div class="mt-5 text-center">
                                            <p class="mb-0"><?php echo e(__('Dont have an account?')); ?> <a
                                                    href="<?php echo e(route('registre', app()->getLocale())); ?>"
                                                    class="fw-semibold text-primary text-decoration-underline">
                                                    <?php echo e(__('Sign up')); ?></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

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
                            <script>

                            </script>
                            &#169 2023 Created by 2earn.cash</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->


    
    
    


    <script>
        document.querySelector("#phone").addEventListener("keypress", function (evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });
        var togglePasswordLogin = document.querySelector("#togglePassword");
        var passwordLogin = document.querySelector("#password-input");
        togglePasswordLogin.addEventListener("click", function () {
            // toggle the type attribute
            var type = passwordLogin.getAttribute("type") === "password" ? "text" : "password";
            passwordLogin.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        function changeLanguage() {
            // session('changeL'=>'true') ;
            const ss = '<?php echo e(Session::put('changeL', 'false' )); ?>';
            // window.livewire.emit('changeLanguage');
            ;
        }

        // $(".Langchange").change(function(){
        //     window.location.href = url + "?lang="+ $(this).val();
        // });
        function functionLogin(dd) {

            window.livewire.emit('login', $("#phone").val(), $("#ccodelog").val(), $("#password-input").val(), $("#isoCountryLog").val());
        }

        // $('.dropdown-menu a').click(function(){
        //    alert('dd');
        // });
    </script>
    
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/login.blade.php ENDPATH**/ ?>