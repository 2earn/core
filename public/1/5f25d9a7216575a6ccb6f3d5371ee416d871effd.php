<div class="container ">
    <style>
        .card-header
        {
            text-align: center!important;
            justify-content: center !important;
        }
        element.style {
        }
        #btnsubmitchange {
            background-image: linear-gradient(to right, #009fe3, #673bb7, #bc34b6) !important;
            border-color: #f6f8fe !important;
            border-radius: 20px !important;
            color: white;
        }
        .container {

            vertical-align: bottom;
        }
        .center-div {
        }
    </style>
    <div class="row text-center  " style="  height: 80px">
        <div class=" ">
            <a href=" "><img class="imgminilogo" src="<?php echo e(asset('assets/images/2earn.png')); ?>" alt=""></a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card center-div " id="security-form" style="width: 600px"   >
            <div class="card-header">
                <h4 class="card-title"><?php echo e(__('Security')); ?></h4>
            </div>
            <div class="card-body" >
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if(session()->has('ErrorConfirmPassWord')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(Session::get('ErrorConfirmPassWord')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <script>
                        var exist = '<?php echo e(Session::has('ErrorConfirmPassWord')); ?>';
                        if (exist && "<?php echo e(Session::get('ErrorConfirmPassWord')); ?>" != "") {
                            var local = '<?php echo e(app()->getLocale()); ?>';
                            $(".alert-danger").fadeTo(3000, 500).slideUp(500, function () {
                                $(".alert-danger").alert('close');
                                // $(this).remove();
                            });
                        }
                    </script>
                <?php endif; ?>
                <?php if(session()->has('ErrorOldPassWord')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(Session::get('ErrorOldPassWord')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <script>
                        var exist = '<?php echo e(Session::has('ErrorOldPassWord')); ?>';
                        if (exist && "<?php echo e(Session::get('ErrorOldPassWord')); ?>" != "") {

                            var local = '<?php echo e(app()->getLocale()); ?>';
                            $(".alert-danger").fadeTo(3000, 500).slideUp(500, function () {
                                $(".alert-danger").alert('close');
                                // $(this).remove();
                            });
                        }
                    </script>
                <?php endif; ?>
                <?php if(session()->has('SuccesUpdatePassword')): ?>
                    <div class="alert alert-dismissible alert-success d-flex align-items-center" role="alert">

                        <div>
                            <?php echo e(Session::get('SuccesUpdatePassword')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                <?php endif; ?>
                <?php echo csrf_field(); ?>
                <div class="form-group mb-3 row">
                    <label for="password"
                           class="col-md-4 col-form-label text-md-right me-sm-2"><?php echo e(__('New Password')); ?></label>
                    <div class="col-md-6">
                        <input id="new_password" type="password"
                               class="form-control" name="new_password"
                               autocomplete="current-password" placeholder="********" wire:model.defer="newPassword"
                               style="display:inline-block;">
                        <span class="eye" >
                        <i class="fas fa-eye" style="color: #6c757d" onclick="newPassWord()" id="hide1"></i>
                            <i class="fas fa-eye-slash" style="color: #6c757d" onclick="newPassWord()" id="hide2"></i>
                        </span>
                    </div>
                </div>
                <div class="form-group mb-3 row">
                    <label for="password"
                           class="col-md-4 col-form-label text-md-right me-sm-2"><?php echo e(__('New Confirm Password')); ?></label>

                    <div class="col-md-6">
                        <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password"
                               autocomplete="current-password" placeholder="********" wire:model.defer="confirmedPassword"
                               style="display:inline-block;">
                        <span class="eye" >
                        <i class="fa fa-eye" style="color: #6c757d" onclick="confirmNewPassWord()" id="eye1"></i>
                            <i class="fa fa-eye-slash"  style="color: #6c757d" onclick="confirmNewPassWord()" id="eye2"></i>
                        </span>
                    </div>
                </div>
                <div class="text-center" style="margin-top: 20px;">
                    <button wire:click="change"  type="submit" class="btn ps-5 pe-5"
                            id="btnsubmitchange"><?php echo e(__('Save')); ?></button>
                    
                    
                </div>

            </div>
            <!-- </form> -->
        </div>
    </div>
    <script>
        function confirmNewPassWord(){
            var a = document.getElementById("new_confirm_password");
            var b = document.getElementById("eye1");
            var c = document.getElementById("eye2");
            if(a.type === 'password'){
                a.type = "text";
                b.style.display="block";
                c.style.display="none";
            }
            else {
                a.type = "password";
                b.style.display="none";
                c.style.display="block";
            }
        }
    </script>
    <script>
        function newPassWord(){
            var x = document.getElementById("new_password");
            var y = document.getElementById("hide1");
            var z = document.getElementById("hide2");
            if(x.type === 'password'){
                x.type = "text";
                y.style.display="block";
                z.style.display="none";
            }
            else {
                x.type = "password";
                y.style.display="none";
                z.style.display="block";
            }
        }
    </script>
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/dev.2earn.cash/resources/views/livewire/change-password.blade.php ENDPATH**/ ?>