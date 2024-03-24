<div>


        <style>
            .iti {
                width: 100% !important;
            }
        </style>
        <script>
            var existSucess = '<?php echo e(Session::has('successRegistre')); ?>';
            var msgsuccess = "success !";
            if (existSucess && "<?php echo e(Session::get('successRegistre')); ?>" != ""  ) {
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
            var exist = '<?php echo e(Session::has('errorPhoneExiste')); ?>';
            var msg = "this number is already registered!";
            if (exist && "<?php echo e(Session::get('errorPhoneExiste')); ?>" != ""  ) {
                var local = '<?php echo e(app()->getLocale()); ?>';
                if (local == 'ar') {
                    msg = "هذا الرقم مسجل!";
                }
                Swal.fire({
                    title: ' ',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                }).then(okay => {
                    if (okay) {
                        window.location.reload();
                    }
                });
            }
            var existn = '<?php echo e(Session::has('errorPhoneValidation')); ?>';
            var msg = "This number is not valid!";
            if (existn && "<?php echo e(Session::get('errorPhoneValidation')); ?>" != ""  ) {
                var local = '<?php echo e(app()->getLocale()); ?>';
                if (local == 'ar') {
                    msg = "هذا الرقم غير صالح!";
                }
                Swal.fire({
                    title: ' ',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
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
                                        <h5 class="text-primary">Create New Account</h5>
                                        <p class="text-muted">Get your free 2Earn account now</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form
                                             >
                                            <?php echo csrf_field(); ?>
                                            <div class="mb-3">
                                                <label for="userPhone" class="form-label"><?php echo e(__('Mobile Number')); ?> <span
                                                        class="text-danger">*</span></label>
                                                <input wire:model.defer="phoneNumber" type="tel" name="mobile" id="phonereg"   class="form-control <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                         value=" "
                                                       placeholder="Enter email address" required>
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
                                                <div class="invalid-feedback">
                                                    Please enter email
                                                </div>
                                                <input type="hidden"   name="fullnumber" id="output"
                                                       value=""
                                                       class="form-control">
                                                <input type="hidden"    name="ccode" id="ccode" >
                                                <input type="hidden"    name="iso2Country" id="iso2Country" >
                                            </div>
                                            <div class="mb-4">
                                                <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the
                                                    2Earn cash <a href=""
                                                              class="text-primary text-decoration-underline fst-normal fw-medium">Terms
                                                        of Use</a></p>
                                            </div>

                                            <div class="mt-4">
                                                <button onclick="signupEvent()" class="btn btn-success w-100" type="button">Sign Up</button>
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



















                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="mt-4 text-center">
                                <p class="mb-0">Already have an account ? <a href="<?php echo e(route('login',app()->getLocale())); ?>"
                                                                             class="fw-semibold text-primary text-decoration-underline"> Signin </a> </p>
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
                                @Created by 2Earn.cash
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->
        <script>
            // document.querySelector("#phonereg").addEventListener("keypress", function (evt) {
            //     if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            //         evt.preventDefault();
            //     }
            // });
            function signupEvent()
            {

                window.livewire.emit('changefullNumber',$("#output").val(),$("#ccode").val(),$("#iso2Country").val());
            }
        </script>



</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/registre.blade.php ENDPATH**/ ?>