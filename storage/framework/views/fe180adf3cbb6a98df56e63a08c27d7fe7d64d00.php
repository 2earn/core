<div>
    <script>
        var existLogout = '<?php echo e(Session::has('FromLogOut')); ?>';
        if (existLogout) {
            // alert('er');
            location.reload();
        }
        var existmessageLogin = '<?php echo e(Session::has('message')); ?>';
        if (existmessageLogin) {
            var msgMsgLogin = '<?php echo e(Session::get('message')); ?>';
            
            
            
            
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
    <style>
        .iti {
            width: 100% !important;
        }
    </style>
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
                                <a href="#" class="d-inline-block auth-logo">
                                    <img src="<?php echo e(URL::asset('assets/images/2Earn.png')); ?>" alt="" height="60">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium"></p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="text-muted">Sign in to continue to 2Earn.cash.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <form>
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="username"
                                                   class="form-label"><?php echo e(__('Mobile Number')); ?></label><br>
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
                                            <div
                                                class="<?php if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl'): ?> float-start <?php else: ?> float-end <?php endif; ?> ">
                                                <a href="<?php echo e(route('forgetpassword',app()->getLocale())); ?>"
                                                   class="text-muted"><?php echo e(__('Forgot Password?')); ?></a>
                                            </div>
                                            <label class="form-label" for="password-input"><?php echo e(__('Password')); ?></label>
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
                                                    type="button" id="password-addon"><i
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
                                                                alt="user-image" class="me-2 rounded" height="20">
                                                            <span
                                                                class="align-middle"><?php echo e(__('lang'.$locale)); ?></span>
                                                        </a>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                        <div class="mt-4 text-center">
                            <p class="mb-0"><?php echo e(__('Dont have an account?')); ?> <a
                                    href="<?php echo e(route('registre',app()->getLocale())); ?>"
                                    class="fw-semibold text-primary text-decoration-underline">
                                    <?php echo e(__('Sign up')); ?> </a></p>
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
                            <p class="mb-0 text-muted">&copy;
                                Created by 2Earn.cash</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <script>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        function functionLogin(dd) {

            window.livewire.emit('login', $("#phone").val(), $("#ccodelog").val(), $("#password-input").val(), $("#isoCountryLog").val());
        }
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/login.blade.php ENDPATH**/ ?>