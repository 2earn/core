<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        var ErrorConfirmPassWord = '<?php echo e(Session::has('ErrorConfirmPassWord')); ?>';
        if (ErrorConfirmPassWord) {
            var tabChangePhone = document.querySelector('#tabEditPass');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorConfirmPassWord')); ?>',
                icon: 'info',
                showCloseButton: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showCancelButton: false,
                focusConfirm: false,
            })
            
        }
        var ErrorOldPassWord = '<?php echo e(Session::has('ErrorOldPassWord')); ?>';
        if (ErrorOldPassWord) {
            var tabChangePhone = document.querySelector('#tabEditPass');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            Swal.fire({
                title: '<?php echo e(Session::get('ErrorOldPassWord')); ?>',
                icon: 'info',
                showCloseButton: true,
                confirmButtonText: '<?php echo e(trans('ok')); ?>',
                showCancelButton: false,
                focusConfirm: false,
            })
            
        }

        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        

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
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    

    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('title'); ?> <?php echo e(__('Profile')); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>


    <div class="row">
        <div class="col-xxl-3">
            <div class="card  ">
                <div class="card-body p-4">

                    <div class="text-center">

                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <label><?php echo e(__('MaxTaillePhoto')); ?></label>
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
                        <h5 class="fs-16 mb-3">
                            <?php if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl'): ?>
                                <?php if(isset($usermetta_info['arFirstName']) && isset($usermetta_info['arLastName']) && !empty($usermetta_info['arFirstName']) && !empty($usermetta_info['arLastName'])): ?>
                                    <?php echo e($usermetta_info['arFirstName']); ?> <?php echo e($usermetta_info['arLastName']); ?>

                                <?php else: ?>
                                    <?php if((isset($usermetta_info['arFirstName'])&&!empty($usermetta_info['arFirstName'])) || (isset($usermetta_info['arLastName'])&&!empty($usermetta_info['arLastName']))): ?>
                                        <?php if(isset($usermetta_info['arFirstName'])&& !empty($usermetta_info['arFirstName'])): ?>
                                            <?php echo e($usermetta_info['arFirstName']); ?>

                                        <?php endif; ?>
                                        <?php if(isset($usermetta_info['arLastName'])&& !empty($usermetta_info['arLastName'])): ?>
                                            <?php echo e($usermetta_info['arLastName']); ?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e($user['fullphone_number']); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if(isset($usermetta_info['enFirstName']) && isset($usermetta_info['enLastName']) && !empty($usermetta_info['enFirstName']) && !empty($usermetta_info['enLastName'])): ?>
                                    <?php echo e($usermetta_info['enFirstName']); ?> <?php echo e($usermetta_info['enLastName']); ?>

                                <?php else: ?>
                                    <?php if(   (isset($usermetta_info['enFirstName'])&&!empty($usermetta_info['enFirstName'])) || (isset($usermetta_info['enLastName'])&&!empty($usermetta_info['enLastName']))): ?>
                                        <?php if(isset($usermetta_info['enFirstName']) && !empty($usermetta_info['enFirstName'])): ?>
                                            <?php echo e($usermetta_info['enFirstName']); ?>

                                        <?php endif; ?>
                                        <?php if(isset($usermetta_info['enLastName'])&& !empty($usermetta_info['enLastName'])): ?>
                                            <?php echo e($usermetta_info['enLastName']); ?>

                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php echo e($user['fullphone_number']); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>

                        </h5>
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
                            <a style="color: #009fe3!important" data-bs-toggle="modal" data-bs-target="#modalEditProf" href="javascript:void(0);"
                               class="badge bg-light text-primary fs-12"><i
                                    class="ri-edit-box-line align-bottom me-1" ></i> <?php echo e(__('Edit')); ?></a>
                        </div>
                    </div>
                    <div class="progress animated-progress custom-progress progress-label">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo e($PercentComplete); ?>%"
                             aria-valuenow="1"
                             aria-valuemin="0" aria-valuemax="100">
                            <div style="background-color: #009fe3!important" class="label"><?php echo e($PercentComplete); ?>%</div>
                        </div>
                    </div>
                    <?php if($PercentComplete==100): ?>
                        <br>
                        <?php if($hasRequest): ?>
                            <h6><?php echo e(__('voter_demande_déja_en_cours')); ?></h6>
                        <?php else: ?>
                            <?php if($user['status'] == 1): ?>
                                <h6><?php echo e(__('votre_compte_est_déja_validé')); ?></h6>
                            <?php else: ?>
                                <button style="background-color: #009fe3!important" onclick="sendRequest()"
                                        class="btn btn-primary"
                                        type="button"> <?php echo e(__('Send Request')); ?></button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <br>
                        <?php if(!empty($errors_array)): ?>
                            <?php $__currentLoopData = $errors_array; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="text-danger"><?php echo e($error); ?></p>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
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
            <div class="card  ">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0 tab2earn" role="tablist">
                        <li class="nav-item">
                            <a style="color: #f02602" class="nav-link active" data-bs-toggle="tab"
                               href="#personalDetails" role="tab" >
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
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" id="tabEditPass">
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
                            <form action="javascript:void(0);">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">اللقب
                                                (الإسم العائلي) </label>
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
                                                   placeholder="" value="">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">Last
                                                Name </label>
                                            <input type="text" class="form-control" id=""
                                                   wire:model.defer="usermetta_info.enLastName"
                                                   placeholder="" value="">
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
                                            <div class="input-group form-icon">
                                                <input readonly wire:model.defer="numberActif" type="text"
                                                       class="form-control inputtest form-control-icon" aria-label=""
                                                       placeholder="">
                                                <i style="font-size: 20px;" class="ri-phone-line"></i>

                                                <a href="<?php echo e(route('ContactNumber', app()->getLocale())); ?>" id="update_tel"
                                                   style="cursor: pointer;background-color: #009fe3!important" class="btn btn-primary" type="button">
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
                                            <div class="input-group form-icon">

                                                <input disabled wire:model.defer="user.email" type="email"
                                                       class="form-control form-control-icon"
                                                       name="email" placeholder="">
                                                <i style="font-size: 20px;" class="ri-mail-unread-line"></i>
                                                <button style="background-color: #009fe3!important" data-bs-toggle="modal" data-bs-target="#modalMail"
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
                                                   placeholder=""/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label"><?php echo e(__('Number Of Children')); ?></label>


                                            <div class="input-step form-control full-width light">
                                                <button id="btnMinus" type="button" class="minus">–</button>
                                                <input wire:model.defer="usermetta_info.childrenCount" type="number"
                                                       class="product-quantity form-control" value="2"
                                                       min="0"
                                                       max="100" id="inputChild" readonly>
                                                <button id="btnPlus" type="button" class="plus">+</button>
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
                                                <option
                                                    value="<?php echo e($personaltitle->id); ?>"><?php echo e(__($personaltitle->name)); ?></option>
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
                                                <option value="<?php echo e($gender->id); ?>"><?php echo e(__( $gender->name)); ?></option>
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
                                                <option
                                                    value="<?php echo e($language->name); ?>"> <?php echo e(__('lang'.$language->PrefixLanguage)); ?></option>
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
                                            <input readonly wire:model.defer="countryUser" type="text"
                                                   class="form-control"
                                                   id="countryInput"
                                                   placeholder="" value="United States"/>
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
                                                      placeholder=""
                                                      rows="3">
                                                    </textarea>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <?php if($paramIdUser ==""): ?>
                                                <button type="button" id="btnsaveUser"
                                                        class="btn btn-primary btn2earn"><?php echo e(__('Save')); ?></button>
                                                
                                            <?php else: ?>
                                                <div x-data="{ open: false }">
                                                    <button x-show="!open" type="button" @click="open = true"
                                                            class="btn btn-secondary ps-5 pe-5"
                                                            id="reject"><?php echo e(__('Reject')); ?></button>
                                                    <button x-show="!open" class="btn btn-success ps-5 pe-5"
                                                            id="validate"><?php echo e(__('Approve')); ?></button>
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
                                        <label for="oldpasswordInput"
                                               class="form-label"><?php echo e(__('Current Password')); ?></label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input wire:model.defer="oldPassword" type="password"
                                                   class="form-control pe-5  "
                                                   name="password" placeholder="********"
                                                   id="oldpasswordInput">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                type="button" id="toggleOldPassword"><i
                                                    class="ri-eye-fill align-middle"></i></button>

                                        </div>


                                        
                                        
                                        
                                        
                                        

                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <label for="newpasswordInput"
                                               class="form-label"><?php echo e(__('New Password')); ?></label>


                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input wire:model.defer="newPassword" type="password"
                                                   class="form-control pe-5  "
                                                   name="password" placeholder="********"
                                                   id="newpasswordInput">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                type="button" id="toggleNewPassword"><i
                                                    class="ri-eye-fill align-middle"></i></button>

                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput"
                                                   class="form-label"><?php echo e(__('New Confirm Password')); ?></label>


                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input wire:model.defer="confirmedPassword" type="password"
                                                       class="form-control" id="confirmpasswordInput"
                                                       placeholder="********">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                    type="button" id="toggleConfirmPassword"><i
                                                        class="ri-eye-fill align-middle"></i></button>

                                            </div>


                                        </div>
                                    </div>
                                    <!--end col-->






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
                                            <button onclick="ConfirmChangePass()" type="button" class="btn btn-success btn2earn">
                                                <?php echo e(__('Save')); ?>

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
} elseif ($_instance->childHasBeenRendered('l2513439014-0')) {
    $componentId = $_instance->getRenderedChildComponentId('l2513439014-0');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2513439014-0');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2513439014-0');
} else {
    $response = \Livewire\Livewire::mount('identification-check', []);
    $html = $response->html();
    $_instance->logRenderedChild('l2513439014-0', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="privacy" role="tabpanel">

                            <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('edit-phone-number', [])->html();
} elseif ($_instance->childHasBeenRendered('l2513439014-1')) {
    $componentId = $_instance->getRenderedChildComponentId('l2513439014-1');
    $componentTag = $_instance->getRenderedChildComponentTagName('l2513439014-1');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('l2513439014-1');
} else {
    $response = \Livewire\Livewire::mount('edit-phone-number', []);
    $html = $response->html();
    $_instance->logRenderedChild('l2513439014-1', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
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
    <div wire:ignore class="modal fade" id="modalEditProf" tabindex="-1" aria-labelledby="exampleModalgridLabel"
         aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalgridLabel"><?php echo e(__('CompleteProfil')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div>
                                    <label for="lastName" class="form-label">First Name</label>
                                    <input wire:model.defer="usermetta_info.enFirstName" type="text"
                                           class="form-control" id=""
                                           placeholder="Enter your lastname">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-6">
                                <div>
                                    <label for="phoneNumber" class="form-label">Last Name</label>
                                    <input wire:model.defer="usermetta_info.enLastName" type="text"
                                           class="form-control" id=""
                                           placeholder="Enter your phone number">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="JoiningdatInput" class="form-label">
                                        
                                        <?php echo e(__('Date of birth')); ?>

                                    </label>
                                    <input wire:model.defer="usermetta_info.birthday" type="date"
                                           class="form-control"
                                           id=""
                                           placeholder=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="zipcodeInput" class="form-label"><?php echo e(__('National ID')); ?></label>
                                    <input type="text" class="form-control" minlength="5" maxlength="50"
                                           wire:model.defer="usermetta_info.nationalID"
                                           id="zipcodeInput" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="emailInput"
                                           class="form-label"><?php echo e(__('Your Email')); ?></label>
                                    <div class="input-group form-icon">

                                        <input disabled wire:model.defer="user.email" type="email"
                                               class="form-control form-control-icon"
                                               name="email" placeholder="">
                                        <i style="font-size: 20px" class="ri-mail-unread-line"></i>
                                        
                                        
                                        
                                    </div>

                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="form-label"><?php echo e(__('Front ID')); ?></label>
                                </div>
                                <div>
                                    <?php if(file_exists(public_path('/uploads/profiles/front-id-image'.$user['idUser'].'.png'))): ?>
                                        <img width="150" height="100"
                                             src=<?php echo e(asset(('/uploads/profiles/front-id-image'.$user['idUser'].'.png'))); ?> >
                                    <?php endif; ?>
                                </div>
                                <div class="wrap-custom-file" style="margin-top: 10px">
                                    <input wire:model.defer="photoFront" type="file" name="image55" id="image55"
                                           accept=".png"/>
                                    <label for="image55">
                                        <lord-icon
                                            src="https://cdn.lordicon.com/vixtkkbk.json"
                                            trigger="loop" delay="1000"
                                            colors="primary:#464fed,secondary:#bc34b6"
                                            style="width:100px;height:100px">
                                        </lord-icon>
                                        <span> <i class="ri-camera-fill"></i> </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="form-label"><?php echo e(__('Front ID')); ?></label>
                                </div>
                                <div>
                                    <?php if(file_exists(public_path('/uploads/profiles/back-id-image'.$user['idUser'].'.png'))): ?>
                                        <img width="150" height="100"
                                             src=<?php echo e(asset(('/uploads/profiles/back-id-image'.$user['idUser'].'.png'))); ?> >
                                    <?php endif; ?>
                                </div>
                                <div class="wrap-custom-file" style="margin-top: 10px">
                                    <input wire:model.defer="backback" type="file" name="image44" id="image44"
                                           accept=".png"/>
                                    <label for="image44">
                                        <lord-icon
                                            src="https://cdn.lordicon.com/vixtkkbk.json"
                                            trigger="loop" delay="1000"
                                            colors="primary:#464fed,secondary:#bc34b6"
                                            style="width:100px;height:100px">
                                        </lord-icon>
                                        <span> <i class="ri-camera-fill"></i> </span>
                                    </label>
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                                    <button onclick="SaveChangeEdit()" type="button" id="SaveCahngeEdit"
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

    </script>
    <script>

        $("#btnsaveUser").click(function () {
            window.livewire.emit('saveUser', parseInt($("#inputChild").val()));
        });
        $('input[type="file"]').each(function () {
            var $file = $(this),
                $label = $file.next('label'),
                $labelText = $label.find('span'),
                labelDefault = $labelText.text();
            $file.on('change', function (event) {

                var fileName = $file.val().split('\\').pop(),

                    tmppath = URL.createObjectURL(event.target.files[0]);

                if (fileName) {

                    $label

                        .addClass('file-ok')

                        .css('background-image', 'url(' + tmppath + ')');

                    $labelText.text(fileName);

                } else {

                    $label.removeClass('file-ok');

                    $labelText.text(labelDefault);

                }

            });

        });

        function SaveChangeEdit() {
            window.livewire.emit('SaveChangeEdit');
        }

        function sendRequest() {
            window.livewire.emit('sendIdentificationRequest');
        }

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
                    p22.innerHTML =  '<br>' + '<?php echo e(trans('Dont get code?')); ?>' + ' <a>' + '<?php echo e(trans('Resend')); ?>' + '</a>';
                    timerInterval = setInterval(() => {
                        b.innerHTML = '<?php echo e(trans('It will close in')); ?>' + (Swal.getTimerLeft() / 1000).toFixed(0) + '<?php echo e(trans('secondes')); ?>'
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
        $("#btnPlus").click(function () {
            var child = parseInt($("#inputChild").val());
            child = child + 1;
            if (child <= 20)
                $("#inputChild").val(child);
            else
                $("#inputChild").val(20);
            
        });
        $("#btnMinus").click(function () {
            var child = parseInt($("#inputChild").val());
            child = child - 1;
            if (child >= 0)
                $("#inputChild").val(child);
            else
                $("#inputChild").val(0);
            
        });

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

        var toggleOldPassword = document.querySelector("#toggleOldPassword");
        var Oldpassword = document.querySelector("#oldpasswordInput");
        toggleOldPassword.addEventListener("click", function () {
            // toggle the type attribute
            var type = Oldpassword.getAttribute("type") === "password" ? "text" : "password";
            Oldpassword.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });


        var toggleNewPassword = document.querySelector("#toggleNewPassword");
        var Newpassword = document.querySelector("#newpasswordInput");
        toggleNewPassword.addEventListener("click", function () {
            // toggle the type attribute
            var type = Newpassword.getAttribute("type") === "password" ? "text" : "password";
            Newpassword.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        var toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
        var confirmPassword = document.querySelector("#confirmpasswordInput");
        toggleConfirmPassword.addEventListener("click", function () {
            // toggle the type attribute
            var type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
            confirmPassword.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });


    </script>
    
    
    
    
    
    
    
</div>
<?php /**PATH /var/www/vhosts/2earn.cash/httpdocs/resources/views/livewire/account.blade.php ENDPATH**/ ?>