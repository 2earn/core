<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

        var ChangeLanguge = '<?php echo e(Session::has('ChangeLanguge')); ?>';
        if (ChangeLanguge) {

            location.reload();
        }
        var exisPhoneUpdated = '<?php echo e(Session::has('SuccesUpdatePhone')); ?>';
        if (exisPhoneUpdated) {
            var tabChangePhone = document.querySelector('#pills-UpdatePhone-tab');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            toastr.success('<?php echo e(Session::get('SuccesUpdatePhone')); ?>');
        }
        var existSamePhone = '<?php echo e(Session::has('ErrorSamePhone')); ?>';
        if (existSamePhone) {
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorSamePhone')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(okay => {
                if (okay) {
                    var tabChangePhone = document.querySelector('#pills-UpdatePhone-tab');
                    var tab = new bootstrap.Tab(tabChangePhone);
                    tab.show();
                }
            });
        }
        var existeErrorOpt = '<?php echo e(Session::has('ErrorOptCodeUpdatePass')); ?>'
        if (existeErrorOpt) {
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorOptCodeUpdatePass')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(okay => {
                if (okay) {
                    var tabChangePass = document.querySelector('#pills-changePass-tab');
                    var tab = new bootstrap.Tab(tabChangePass);
                    tab.show();
                }
            });
        }
        var ErrorMailUsed = '<?php echo e(Session::has('ErrorMailUsed')); ?>'
        if (ErrorMailUsed) {

            Swal.fire({
                title: '<?php echo e(Session::get('ErrorMailUsed')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }

            });
        }
        var SoldeSms = '<?php echo e(Session::has('SoldeSmsInsuffisant')); ?>'
        if (SoldeSms) {
            Swal.fire({
                title: '<?php echo e(Session::get('SoldeSmsInsuffisant')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
        var MailNonValide = '<?php echo e(Session::has('MailNonValide')); ?>'
        if (MailNonValide) {
            Swal.fire({
                title: '<?php echo e(Session::get('MailNonValide')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }


        var SuccesUpdatePassword = '<?php echo e(Session::has('SuccesUpdatePassword')); ?>'
        if (SuccesUpdatePassword) {
            toastr.success('<?php echo e(Session::get('SuccesUpdatePassword')); ?>');
        }
        var ErrorOldPassWord = '<?php echo e(Session::has('ErrorOldPassWord')); ?>'
        if (ErrorOldPassWord) {
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorOldPassWord')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
        var SuccesUpdateProfile = '<?php echo e(Session::has('SuccesUpdateProfile')); ?>'
        if (SuccesUpdateProfile) {
            toastr.success('<?php echo e(Session::get('SuccesUpdateProfile')); ?>');
        }
        // ErrorPhoneUsed

        var SuccesUpdateIdentification = '<?php echo e(Session::has('SuccesUpdateIdentification')); ?>';
        if (SuccesUpdateIdentification) {

            toastr.success('<?php echo e(Session::get('SuccesUpdateIdentification')); ?>');
        }

    </script>
    
    <?php $__env->startSection('title'); ?>
        <?php echo app('translator')->get('Account'); ?>
    <?php $__env->stopSection(); ?>
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="<?php echo e(URL::asset('assets/images/profile-bg.jpg')); ?>" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                               class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> <?php echo e(__('Change_Cover')); ?></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <?php if($imageProfil): ?>
                                <img class="rounded-circle" width="70" height="70"
                                     src="<?php echo e($imageProfil->temporaryUrl()); ?>">
                                </br>
                                <?php endif; ?>
                                </br>
                                <div wire:loading wire:target="imageProfil">Uploading...</div>
                                <img
                                    src="<?php if(file_exists('uploads/profiles/profile-image-' . $user['idUser'] . '.png')): ?> <?php echo e(URL::asset('uploads/profiles/profile-image-'.$user['idUser'].'.png')); ?><?php else: ?><?php echo e(URL::asset('uploads/profiles/default.png')); ?> <?php endif; ?>"
                                    class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                    alt="user-profile-image">

                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                           wire:model="imageProfil">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                    </label>
                                </div>
                        </div>
                        <h5 class="fs-16 mb-3">josef</h5>
                        <div class="form-check form-switch" dir="ltr">
                            <input wire:model.defer="user.is_public" type="checkbox" class="form-check-input"
                                   id="customSwitchsizesm" checked="">
                            <label class="form-check-label"
                                   for="customSwitchsizesm"><?php echo e(__('I agree to receive funding requests')); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-5">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0"><?php echo e(__('Complete_Profile')); ?></h5>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="javascript:void(0);" class="badge bg-light text-primary fs-12"><i
                                    class="ri-edit-box-line align-bottom me-1"></i> <?php echo e(__('Edit')); ?></a>
                        </div>
                    </div>
                    <div class="progress animated-progress custom-progress progress-label">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 30%" aria-valuenow="30"
                             aria-valuemin="0" aria-valuemax="100">
                            <div class="label">30%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0"><?php echo e(__('Type_Profil')); ?></h5>
                        </div>
                        
                        
                        
                        
                    </div>
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>
                                <?php echo e(__('Edit_Profile')); ?>


                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="far fa-envelope"></i>
                                <?php echo e(__('Identifications')); ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab">
                                <i class="far fa-user"></i>
                                <?php echo e(__('ChangePassword')); ?>

                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                <?php echo e(__('UpdatePhoneNumber')); ?>

                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form wire:submit.prevent="saveUser" action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">
                                                اللقب</label>
                                            <input wire:model.defer="usermetta_info.arLastName" type="text"
                                                   class="form-control" id="firstnameInput"
                                                   placeholder="Enter your firstname" value="">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput" class="form-label">
                                                الاسم</label>
                                            <input wire:model.defer="usermetta_info.arFirstName" type="text"
                                                   class="form-control" id="lastnameInput"
                                                   placeholder="Enter your lastname" value="">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">Last
                                                Name </label>
                                            <input type="text" class="form-control" id=""
                                                   wire:model.defer="usermetta_info.enLastName"
                                                   placeholder="Enter your firstname" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">First
                                                Name</label>
                                            <input wire:model.defer="usermetta_info.enFirstName" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput"
                                                   class="form-label"><?php echo e(__('Your Contact number')); ?></label>
                                            <div class="input-group">
                                                <input readonly wire:model.defer="numberActif" type="text"
                                                       class="form-control inputtest" aria-label=""
                                                       placeholder="Enter your phone number">
                                                <a href="<?php echo e(route('ContactNumber', app()->getLocale())); ?>" id="update_tel"
                                                   style="cursor: pointer;" class="btn btn-primary" type="button">
                                                    <?php echo e(__('add')); ?>

                                                </a>
                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label"><?php echo e(__('Your Email')); ?></label>
                                            <div class="input-group">
                                                <input disabled wire:model.defer="user.email" type="email"
                                                       class="form-control"
                                                       name="email" placeholder="Enter your email">
                                                <button data-bs-toggle="modal" data-bs-target="#modalMail"
                                                        class="btn btn-primary"
                                                        type="button"><?php if($user['email']==""): ?> <?php echo e(__('add')); ?> <?php else: ?> <?php echo e(__('Change')); ?> <?php endif; ?></button>
                                                
                                            </div>


                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">
                                                
                                                <?php echo e(__('Date of birth')); ?>

                                            </label>
                                            <input wire:model.defer="usermetta_info.birthday" type="date"
                                                   class="form-control"
                                                   id="JoiningdatInput"
                                                   placeholder="Select date"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label"><?php echo e(__('Number Of Children')); ?></label>


                                            <div class="input-step form-control">
                                                <button type="button" class="minus">–</button>
                                                <input wire:model.defer="usermetta_info.childrenCount" type="number"
                                                       class="product-quantity form-control" value="2"
                                                       min="0"
                                                       max="100">
                                                <button type="button" class="plus">+</button>
                                            </div>


                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="designationInput"
                                                   class="form-label"><?php echo e(__('Personal Title')); ?></label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.personaltitle">
                                                <option value="">-------</option>
                                                <?php  if(isset($personaltitles)){
                                                foreach($personaltitles as $personaltitle){
                                                ?>
                                                <option value="<?php echo e($personaltitle->id); ?>"><?php echo e($personaltitle->name); ?></option>
                                                <?php  }} ?>
                                            </select>

                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1" class="form-label"><?php echo e(__('Gender')); ?></label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.gender">
                                                <
                                                <option value="">-------</option>
                                                <?php  if(isset($genders)){
                                                foreach($genders as $gender){
                                                ?>
                                                <option value="<?php echo e($gender->id); ?>"><?php echo e($gender->name); ?></option>
                                                <?php } }?>
                                            </select>

                                            
                                            
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label"><?php echo e(__('Your Preferred Language')); ?></label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.idLanguage">
                                                <option value="" selected>-------</option>
                                                <?php  if(isset($languages)){?>
                                                <?php
                                                foreach($languages as $language){
                                                ?>
                                                <option value="<?php echo e($language->name); ?>"><?php echo e($language->name); ?></option>
                                                <?php } }  ?>
                                            </select>

                                            
                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="cityInput" class="form-label"><?php echo e(__('State')); ?></label>
                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.idState">
                                                <option value=""><?php echo e(__('Choose')); ?></option>
                                                <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    $cnP = \Illuminate\Support\Facades\Lang::get($state->name);
                                                    ?>
                                                    <option value="<?php echo e($state->id); ?>"><?php echo e($cnP); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            
                                            
                                            
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label"><?php echo e(__('Country')); ?></label>
                                            <input wire:model.defer="countryUser" type="text" class="form-control"
                                                   id="countryInput"
                                                   placeholder="Country" value="United States"/>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="zipcodeInput" class="form-label"><?php echo e(__('National ID')); ?></label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   wire:model.defer="usermetta_info.nationalID"
                                                   id="zipcodeInput" placeholder="">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea"
                                                   class="form-label"><?php echo e(__('Address')); ?></label>
                                            <textarea wire:model.defer="usermetta_info.adresse" class="form-control"
                                                      id="exampleFormControlTextarea"
                                                      placeholder="Enter your description"
                                                      rows="3">
                                                    </textarea>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <?php if($paramIdUser ==""): ?>
                                                <button type="submit" class="btn btn-primary"><?php echo e(__('Save')); ?></button>
                                                
                                            <?php else: ?>
                                                <div x-data="{ open: false }">
                                                    <button x-show="!open" type="button" @click="open = true"
                                                            class="btn btn-secondary ps-5 pe-5"
                                                            id="reject"><?php echo e(__('Reject')); ?></button>
                                                    <button x-show="!open" class="btn btn-success ps-5 pe-5"
                                                            id="validate"><?php echo e(__('Validate')); ?></button>
                                                    </br>
                                                    <label x-show="open"><?php echo e(__('Libele_Note')); ?></label>
                                                    </br>
                                                    <textarea wire:model.defer="noteReject" name="Text1" cols="80"
                                                              rows="5"
                                                              x-show="open"></textarea>
                                                    </br>
                                                    <button type="button" x-show="open" wire:click="reject"
                                                            class="btn btn-secondary ps-5 pe-5"
                                                            id=""><?php echo e(__('Reject')); ?></button>
                                                    <button type="button" x-show="open" class="btn btn-danger ps-5 pe-5"
                                                            id=""
                                                            @click="open = false"><?php echo e(__('canceled !')); ?>

                                                    </button>
                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="changePassword" role="tabpanel">
                            <form action="">
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="oldpasswordInput"
                                                   class="form-label"><?php echo e(__('Current Password')); ?></label>
                                            <input wire:model.defer="oldPassword" type="password" class="form-control"
                                                   id="oldpasswordInput"
                                                   placeholder="Enter current password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="newpasswordInput"
                                                   class="form-label"><?php echo e(__('New Password')); ?></label>
                                            <input wire:model.defer="newPassword" type="password" class="form-control"
                                                   id="newpasswordInput"
                                                   placeholder="Enter new password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput"
                                                   class="form-label"><?php echo e(__('New Confirm Password')); ?></label>
                                            <input wire:model.defer="confirmedPassword" type="password"
                                                   class="form-control" id="confirmpasswordInput"
                                                   placeholder="Confirm password">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <a href="javascript:void(0);"
                                               class="link-primary text-decoration-underline">Forgot
                                                Password ?</a>
                                        </div>
                                    </div>
                                    <div style="" class="col-lg-12">
                                        <div class="form-check form-switch ms-5 me-5 mb-3" dir="ltr">
                                            <input wire:model.defer="sendPassSMS" type="checkbox" id="send"
                                                   class="form-check-input" id="flexSwitchCheckDefault" checked="">
                                            <label class="form-check-label"
                                                   for="customSwitchsizesm"><?php echo e(__('I want to receive my password by SMS')); ?>  </label>

                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button onclick="ConfirmChangePass()" type="button" class="btn btn-success">
                                                Change
                                                Password
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('identification-check', [])->html();
} elseif ($_instance->childHasBeenRendered('l4132938328-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l4132938328-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l4132938328-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l4132938328-0');
} else {
    $response = \Livewire\Livewire::mount('identification-check', []);
    $html = $response->html();
    $_instance->logRenderedChild('l4132938328-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="privacy" role="tabpanel">

                            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('edit-phone-number', [])->html();
} elseif ($_instance->childHasBeenRendered('l4132938328-1')) {
    $componentId = $_instance->getRenderedChildComponentId('l4132938328-1');
    $componentTag = $_instance->getRenderedChildComponentTagName('l4132938328-1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l4132938328-1');
} else {
    $response = \Livewire\Livewire::mount('edit-phone-number', []);
    $html = $response->html();
    $_instance->logRenderedChild('l4132938328-1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>

                        </div>
                        <!--end tab-pane-->
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->


    <div class="modal fade" id="modalMail" tabindex="-1" aria-labelledby="exampleModalgridLabel" aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel"><?php echo e(__('Are_you_sure_to_change_mail')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="emailInput" class="form-label"><?php echo e(__('Your Email')); ?></label>
                                    <input type="email" wire:model.defer="user.email" class="form-control"
                                           id="inputEmail" placeholder="<?php echo e(__('your_new_mail')); ?>">
                                </div>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                    <button type="button" id="validateMail"
                                            class="btn btn-primary"><?php echo e(__('Save_changes')); ?></button>
                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        function ConfirmChangePass() {

            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            window.livewire.emit('PreChangePass');
        }


        window.addEventListener('OptChangePass', event => {
            Swal.fire({
                title: '<?php echo e(trans('Your verification code')); ?>',
                html: '<?php echo e(__('We_will_send')); ?>' + '<br>' + event.detail.mail + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML = '<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a>';
                    timerInterval = setInterval(() => {
                        b.textContent = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('changePassword', resultat.value);
                }
                if (resultat.isDismissed) {
                    // location.reload();
                }
            })
        })


        $("#validateMail").click(function () {

            window.livewire.emit("sendVerificationMail", $('#inputEmail').val());
        });
        window.addEventListener('confirmOPTVerifMail', event => {
            Swal.fire({
                title: '<?php echo e(__('Your verification code')); ?>',
                html: '<?php echo e(__('We_will_send_Sms')); ?><br> ',
                html: '<?php echo e(__('We_will_send_Sms')); ?><br>' + event.detail.numberActif + '<br>' + '<?php echo e(__('Your OTP Code')); ?>',
                input: 'text',
                allowOutsideClick: false,
                timer: '<?php echo e(env('timeOPT')); ?>',
                timerProgressBar: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showCancelButton: true,
                cancelButtonText: '<?php echo e(trans('canceled !')); ?>',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML = '<?php echo e(trans('Dont get code?')); ?>' + ' <a OnClick="ResendMail()" >' + '<?php echo e(trans('Resend')); ?>' + '</a>';

                    timerInterval = setInterval(() => {
                        b.textContent = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>'
                    }, 100)
                },
                willClose: () => {
                    clearInterval(timerInterval)
                },
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                inputAttributes: {
                    autocapitalize: 'off'
                },
            }).then((resultat) => {
                if (resultat.value) {
                    window.livewire.emit('saveVerifiedMail', resultat.value);
                }
                if (resultat.isDismissed) {
                    location.reload();
                }
            })
        })
    </script>
    <script data-turbolinks-eval="false">
        $('#send').change(function () {
            if (this.checked && !<?php echo e($soldeSms); ?> > 0) {
                Swal.fire({
                    title: '<?php echo e(__('solde_sms_ins')); ?>',
                    confirmButtonText: '<?php echo e(trans('ok')); ?>',
                });
                return;
            }
            Swal.fire({
                title: '<?php echo e(__('upate_notification_setting')); ?>',
                showDenyButton: true,
                confirmButtonText: '<?php echo e(trans('Yes')); ?>',
                denyButtonText: '<?php echo e(trans('No')); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.livewire.emit('ParamSendChanged');
                } else if (result.isDenied) {
                }
            })
            // if (this.checked) {
            //
            // }
            // else {
            //     alert("non ckecked");
            // }
            // $('#textbox1').val(this.checked);
        });
    </script>
    
    
    
    
    
    
    
</div>
<?php /**PATH C:\xampp\htdocs\modern\resources\views/livewire/account.blade.php ENDPATH**/ ?>