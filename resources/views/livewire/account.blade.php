<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        var ErrorConfirmPassWord = '{{Session::has('ErrorConfirmPassWord')}}';
        if (ErrorConfirmPassWord) {
            var tabChangePhone = document.querySelector('#tabEditPass');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            Swal.fire({
                title: '{{Session::get('ErrorConfirmPassWord')}}',
                icon: 'info',
                showCloseButton: true,
                confirmButtonText: '{{trans('ok')}}',
                showCancelButton: false,
                focusConfirm: false,
            })
            {{--toastr.success('{{Session::get('ErrorConfirmPassWord')}}');--}}
        }
        var ErrorOldPassWord = '{{Session::has('ErrorOldPassWord')}}';
        if (ErrorOldPassWord) {
            var tabChangePhone = document.querySelector('#tabEditPass');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            Swal.fire({
                title: '{{Session::get('ErrorOldPassWord')}}',
                icon: 'info',
                showCloseButton: true,
                confirmButtonText: '{{trans('ok')}}',
                showCancelButton: false,
                focusConfirm: false,
            })
            {{--toastr.success('{{Session::get('ErrorConfirmPassWord')}}');--}}
        }

        {{--var exist = '{{Session::has('ErrorConfirmPassWord')}}';--}}
        {{--var exist2 = '{{Session::has('SuccesUpdatePassword')}}';--}}
        {{--var exist3 = '{{Session::has('SuccesUpdateHobbies')}}';--}}
        {{--var exist4 = '{{Session::has('SuccesUpdateIdentification')}}';--}}
        {{--var msg = "this number is already registered!";--}}
        {{--if (exist || exist2  && ("{{Session::get('ErrorConfirmPassWord')}}" != "" || "{{Session::get('SuccesUpdatePassword')}}" != ""  )) {--}}
        {{--    var someTabTriggerEl = document.querySelector('#pills-changePass-tab');--}}
        {{--    var tab = new bootstrap.Tab(someTabTriggerEl);--}}
        {{--    tab.show() ;--}}
        {{--    var local = '{{app()->getLocale()}}';--}}
        {{--    <p class="alert {{ session('ErrorConfirmPassWord') }}">{{ session('sdsdsd') }}</p>--}}
        {{--    // alert('yaan din waldik');--}}
        {{--}--}}
        {{--if (exist3   && ("{{Session::get('SuccesUpdateHobbies')}}" != ""   )) {--}}
        {{--    var someTabTriggerEl = document.querySelector('#pills-hobbies-tab');--}}
        {{--    var tab = new bootstrap.Tab(someTabTriggerEl);--}}
        {{--    tab.show() ;--}}
        {{--    var local = '{{app()->getLocale()}}';--}}
        {{--    --}}{{--<p class="alert {{ session('ErrorConfirmPassWord') }}">{{ session('sdsdsd') }}</p>--}}
        {{--    // alert('yaan din waldik');--}}
        {{--}--}}
        {{--if (exist4   && ("{{Session::get('SuccesUpdateIdentification')}}" != ""   )) {--}}
        {{--    var someTabTriggerEl = document.querySelector('#pills-contact-tab');--}}
        {{--    var tab = new bootstrap.Tab(someTabTriggerEl);--}}
        {{--    tab.show() ;--}}
        {{--    var local = '{{app()->getLocale()}}';--}}
        {{--    --}}{{--<p class="alert {{ session('ErrorConfirmPassWord') }}">{{ session('sdsdsd') }}</p>--}}
        {{--    // alert('yaan din waldik');--}}
        {{--}--}}

        var ChangeLanguge = '{{Session::has('ChangeLanguge')}}';
        if (ChangeLanguge) {

            location.reload();
        }
        var exisPhoneUpdated = '{{Session::has('SuccesUpdatePhone')}}';
        if (exisPhoneUpdated) {
            var tabChangePhone = document.querySelector('#pills-UpdatePhone-tab');
            var tab = new bootstrap.Tab(tabChangePhone);
            tab.show();
            toastr.success('{{Session::get('SuccesUpdatePhone')}}');
        }
        var existSamePhone = '{{Session::has('ErrorSamePhone')}}';
        if (existSamePhone) {
            Swal.fire({
                title: '{{Session::get('ErrorSamePhone')}}',
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
        var existeErrorOpt = '{{ Session::has('ErrorOptCodeUpdatePass')}}'
        if (existeErrorOpt) {
            Swal.fire({
                title: '{{Session::get('ErrorOptCodeUpdatePass')}}',
                confirmButtonText: '{{trans('ok')}}',
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
        var ErrorMailUsed = '{{ Session::has('ErrorMailUsed')}}'
        if (ErrorMailUsed) {

            Swal.fire({
                title: '{{Session::get('ErrorMailUsed')}}',
                confirmButtonText: '{{trans('ok')}}',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }

            });
        }
        var SoldeSms = '{{ Session::has('SoldeSmsInsuffisant')}}'
        if (SoldeSms) {
            Swal.fire({
                title: '{{Session::get('SoldeSmsInsuffisant')}}',
                confirmButtonText: '{{trans('ok')}}',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
        var MailNonValide = '{{ Session::has('MailNonValide')}}'
        if (MailNonValide) {
            Swal.fire({
                title: '{{Session::get('MailNonValide')}}',
                confirmButtonText: '{{trans('ok')}}',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }


        var SuccesUpdatePassword = '{{ Session::has('SuccesUpdatePassword')}}'
        if (SuccesUpdatePassword) {
            toastr.success('{{Session::get('SuccesUpdatePassword')}}');
        }

        var SuccesUpdateProfile = '{{ Session::has('SuccesUpdateProfile')}}'
        if (SuccesUpdateProfile) {
            toastr.success('{{Session::get('SuccesUpdateProfile')}}');
        }
        // ErrorPhoneUsed

        var SuccesUpdateIdentification = '{{Session::has('SuccesUpdateIdentification')}}';
        if (SuccesUpdateIdentification) {

            toastr.success('{{Session::get('SuccesUpdateIdentification')}}');
        }

    </script>
    {{--    @extends('layouts.master')--}}
    {{--    @section('title')--}}
    {{--        @lang('Account')--}}
    {{--    @endsection--}}
    {{--    <div class="position-relative mx-n4 mt-n4">--}}
    {{--        <div class="profile-wid-bg profile-setting-img">--}}
    {{--            <img src="{{ URL::asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">--}}
    {{--            <div class="overlay-content">--}}
    {{--                <div class="text-end p-3">--}}
    {{--                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">--}}
    {{--                        <input id="profile-foreground-img-file-input" type="file"--}}
    {{--                               class="profile-foreground-img-file-input">--}}
    {{--                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">--}}
    {{--                            <i class="ri-image-edit-line align-bottom me-1"></i> {{__('Change_Cover')}}</label>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--    @section('title'){{ __('Balance For Shopping') }} @endsection--}}
    {{--    @section('content')--}}

    @component('components.breadcrumb')
        @slot('title') {{ __('Profile') }} @endslot
    @endcomponent


    <div class="row">
        <div class="col-xxl-3">
            <div class="card  ">
                <div class="card-body p-4">

                    <div class="text-center">

                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <label>{{__('MaxTaillePhoto')}}</label>
                            @if ($imageProfil)
                                <img class="rounded-circle" width="70" height="70"
                                     src="{{ $imageProfil->temporaryUrl() }}">
                                </br>
                                @endif
                                </br>
                                <div wire:loading wire:target="imageProfil">Uploading...</div>
                                <img
                                    src="@if (file_exists('uploads/profiles/profile-image-' . $user['idUser'] . '.png')) {{ URL::asset('uploads/profiles/profile-image-'.$user['idUser'].'.png') }}@else{{ URL::asset('uploads/profiles/default.png') }} @endif"
                                    class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                    alt="user-profile-image">

                                {{--                            <?php--}}
                                {{--                            if (file_exists('uploads/profiles/profile-image-' . $user['idUser'] . '.png')) { ?>--}}
                                {{--                            <img  class="  rounded-circle avatar-xl img-thumbnail user-profile-image"--}}
                                {{--                                 src="{{asset('uploads/profiles/profile-image-'.$user['idUser'].'.png')}}"--}}
                                {{--                                 width="70"--}}
                                {{--                                 height="70" alt=""--}}
                                {{--                                 id="myImg" src="prof6ile-image.jpg" style="width:70%;max-width:70px">--}}
                                {{--                            <?php }else{ ?>--}}
                                {{--                            <img  class="  rounded-circle avatar-xl img-thumbnail user-profile-image"--}}
                                {{--                                 src="{{asset('uploads/profiles/default.png')}}" width="70" height="70"--}}
                                {{--                                 alt="">--}}
                                {{--                            <?php } ?>--}}
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
                            @if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
                                @if(isset($usermetta_info['arFirstName']) && isset($usermetta_info['arLastName']) && !empty($usermetta_info['arFirstName']) && !empty($usermetta_info['arLastName']))
                                    {{$usermetta_info['arFirstName']}} {{$usermetta_info['arLastName']}}
                                @else
                                    @if((isset($usermetta_info['arFirstName'])&&!empty($usermetta_info['arFirstName'])) || (isset($usermetta_info['arLastName'])&&!empty($usermetta_info['arLastName'])))
                                        @if(isset($usermetta_info['arFirstName'])&& !empty($usermetta_info['arFirstName']))
                                            {{$usermetta_info['arFirstName']}}
                                        @endif
                                        @if(isset($usermetta_info['arLastName'])&& !empty($usermetta_info['arLastName']))
                                            {{$usermetta_info['arLastName']}}
                                        @endif
                                    @else
                                        {{$user['fullphone_number']}}
                                    @endif
                                @endif
                            @else
                                @if(isset($usermetta_info['enFirstName']) && isset($usermetta_info['enLastName']) && !empty($usermetta_info['enFirstName']) && !empty($usermetta_info['enLastName']))
                                    {{$usermetta_info['enFirstName']}} {{$usermetta_info['enLastName']}}
                                @else
                                    @if(   (isset($usermetta_info['enFirstName'])&&!empty($usermetta_info['enFirstName'])) || (isset($usermetta_info['enLastName'])&&!empty($usermetta_info['enLastName'])))
                                        @if(isset($usermetta_info['enFirstName']) && !empty($usermetta_info['enFirstName']))
                                            {{$usermetta_info['enFirstName']}}
                                        @endif
                                        @if(isset($usermetta_info['enLastName'])&& !empty($usermetta_info['enLastName']))
                                            {{$usermetta_info['enLastName']}}
                                        @endif
                                    @else
                                        {{$user['fullphone_number']}}
                                    @endif
                                @endif
                            @endif

                        </h5>
                        <div class="form-check form-switch" dir="ltr">
                            <input wire:model.defer="user.is_public" type="checkbox" class="form-check-input"
                                   id="customSwitchsizesm" checked="">
                            <label class="form-check-label"
                                   for="customSwitchsizesm">{{ __('I agree to receive funding requests') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-5">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{ __('Complete_Profile') }}</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <a style="color: #009fe3!important" data-bs-toggle="modal" data-bs-target="#modalEditProf" href="javascript:void(0);"
                               class="badge bg-light text-primary fs-12"><i
                                    class="ri-edit-box-line align-bottom me-1" ></i> {{__('Edit')}}</a>
                        </div>
                    </div>
                    <div class="progress animated-progress custom-progress progress-label">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: {{$PercentComplete}}%"
                             aria-valuenow="1"
                             aria-valuemin="0" aria-valuemax="100">
                            <div style="background-color: #009fe3!important" class="label">{{$PercentComplete}}%</div>
                        </div>
                    </div>
                    @if($PercentComplete==100)
                        <br>
                        @if($hasRequest)
                            <h6>{{__('voter_demande_déja_en_cours')}}</h6>
                        @else
                            @if($user['status'] == 1)
                                <h6>{{__('votre_compte_est_déja_validé')}}</h6>
                            @else
                                <button style="background-color: #009fe3!important" onclick="sendRequest()"
                                        class="btn btn-primary"
                                        type="button"> {{__('Send Request')}}</button>
                            @endif
                        @endif
                    @else
                        <br>
                        @if(!empty($errors_array))
                            @foreach ($errors_array as $error)
                                <p class="text-danger">{{ $error }}</p>
                            @endforeach
                        @endif
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-0">{{__('Type_Profil')}}</h5>
                        </div>
                        {{--                            <div class="flex-shrink-0">--}}
                        {{--                                <a href="javascript:void(0);" class="badge bg-light text-primary fs-12"><i--}}
                        {{--                                            class="ri-add-fill align-bottom me-1"></i> Add</a>--}}
                        {{--                            </div>--}}
                    </div>
                    {{--                    <div class="mb-3 d-flex">--}}
                    {{--                        <div class="avatar-xs d-block flex-shrink-0 me-3">--}}
                    {{--                            <span class="avatar-title rounded-circle fs-16 bg-dark text-light">--}}
                    {{--                                <i class="ri-github-fill"></i>--}}
                    {{--                            </span>--}}
                    {{--                        </div>--}}
                    {{--                        <input type="email" class="form-control" id="gitUsername" placeholder="Username"--}}
                    {{--                               value="">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="mb-3 d-flex">--}}
                    {{--                        <div class="avatar-xs d-block flex-shrink-0 me-3">--}}
                    {{--                            <span class="avatar-title rounded-circle fs-16 bg-primary">--}}
                    {{--                                <i class="ri-global-fill"></i>--}}
                    {{--                            </span>--}}
                    {{--                        </div>--}}
                    {{--                        <input type="text" class="form-control" id="websiteInput" placeholder="www.example.com"--}}
                    {{--                               value="www.velzon.com">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="mb-3 d-flex">--}}
                    {{--                        <div class="avatar-xs d-block flex-shrink-0 me-3">--}}
                    {{--                            <span class="avatar-title rounded-circle fs-16 bg-success">--}}
                    {{--                                <i class="ri-dribbble-fill"></i>--}}
                    {{--                            </span>--}}
                    {{--                        </div>--}}
                    {{--                        <input type="text" class="form-control" id="dribbleName" placeholder="Username"--}}
                    {{--                               value="@dave_adame">--}}
                    {{--                    </div>--}}
                    {{--                    <div class="d-flex">--}}
                    {{--                        <div class="avatar-xs d-block flex-shrink-0 me-3">--}}
                    {{--                            <span class="avatar-title rounded-circle fs-16 bg-danger">--}}
                    {{--                                <i class="ri-pinterest-fill"></i>--}}
                    {{--                            </span>--}}
                    {{--                        </div>--}}
                    {{--                        <input type="text" class="form-control" id="pinterestName" placeholder="Username"--}}
                    {{--                               value="Advance Dave">--}}
                    {{--                    </div>--}}
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
                                {{__('Edit_Profile')}}

                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="far fa-envelope"></i>
                                {{__('Identifications')}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab" id="tabEditPass">
                                <i class="far fa-user"></i>
                                {{__('ChangePassword')}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                {{__('UpdatePhoneNumber')}}
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
                                                   class="form-label">{{ __('Your Contact number') }}</label>
                                            <div class="input-group form-icon">
                                                <input readonly wire:model.defer="numberActif" type="text"
                                                       class="form-control inputtest form-control-icon" aria-label=""
                                                       placeholder="">
                                                <i style="font-size: 20px;" class="ri-phone-line"></i>

                                                <a href="{{route('ContactNumber', app()->getLocale())}}" id="update_tel"
                                                   style="cursor: pointer;background-color: #009fe3!important" class="btn btn-primary" type="button">
                                                    {{__('add')}}
                                                </a>
                                            </div>
                                            {{--                                                <input type="text" class="form-control" id="phonenumberInput"--}}
                                            {{--                                                       placeholder="Enter your phone number" value="+(966) 987 6543">--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label">{{ __('Your Email') }}</label>
                                            <div class="input-group form-icon">

                                                <input disabled wire:model.defer="user.email" type="email"
                                                       class="form-control form-control-icon"
                                                       name="email" placeholder="">
                                                <i style="font-size: 20px;" class="ri-mail-unread-line"></i>
                                                <button style="background-color: #009fe3!important" data-bs-toggle="modal" data-bs-target="#modalMail"
                                                        class="btn btn-primary"
                                                        type="button">@if($user['email']=="") {{__('add')}} @else {{__('Change')}} @endif</button>
                                                {{--                                                    <button class="btn btn-success" type="button">Button</button>--}}
                                            </div>


                                            {{--                                                <input type="email" class="form-control" id="emailInput"--}}
                                            {{--                                                       placeholder="Enter your email" value="">--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">
                                                {{--                                                    Joining Date--}}
                                                {{__('Date of birth')  }}
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
                                                   class="form-label">{{ __('Number Of Children') }}</label>


                                            <div class="input-step form-control full-width light">
                                                <button id="btnMinus" type="button" class="minus">–</button>
                                                <input wire:model.defer="usermetta_info.childrenCount" type="number"
                                                       class="product-quantity form-control" value="2"
                                                       min="0"
                                                       max="100" id="inputChild" readonly>
                                                <button id="btnPlus" type="button" class="plus">+</button>
                                            </div>


                                            {{--                                                <input type="text" class="form-control" id="websiteInput1"--}}
                                            {{--                                                       placeholder="www.example.com" value="" />--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                {{--                                        <div class="col-lg-12">--}}
                                {{--                                            <div class="mb-3">--}}
                                {{--                                                <label for="skillsInput" class="form-label">Skills</label>--}}
                                {{--                                                <select class="form-control" name="skillsInput" data-choices--}}
                                {{--                                                        data-choices-text-unique-true multiple id="skillsInput">--}}
                                {{--                                                    <option value="illustrator">Illustrator</option>--}}
                                {{--                                                    <option value="photoshop">Photoshop</option>--}}
                                {{--                                                    <option value="css">CSS</option>--}}
                                {{--                                                    <option value="html">HTML</option>--}}
                                {{--                                                    <option value="javascript" selected>Javascript</option>--}}
                                {{--                                                    <option value="python">Python</option>--}}
                                {{--                                                    <option value="php">PHP</option>--}}
                                {{--                                                </select>--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="designationInput"
                                                   class="form-label">{{ __('Personal Title') }}</label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.personaltitle">
                                                <option value="">-------</option>
                                                <?php  if(isset($personaltitles)){
                                                foreach($personaltitles as $personaltitle){
                                                ?>
                                                <option
                                                    value="{{$personaltitle->id}}">{{__($personaltitle->name)}}</option>
                                                <?php  }} ?>
                                            </select>

                                            {{--                                            <input type="text" class="form-control" id="designationInput"--}}
                                            {{--                                                   placeholder="Designation" value="Lead Designer / Developer">--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1" class="form-label">{{ __('Gender') }}</label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.gender">
                                                <
                                                <option value="">-------</option>
                                                <?php  if(isset($genders)){
                                                foreach($genders as $gender){
                                                ?>
                                                <option value="{{$gender->id}}">{{ __( $gender->name)  }}</option>
                                                <?php } }?>
                                            </select>

                                            {{--                                            <input type="text" class="form-control" id="websiteInput1"--}}
                                            {{--                                                   placeholder="www.example.com" value="www.velzon.com"/>--}}
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label">{{ __('Your Preferred Language') }}</label>

                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.idLanguage">
                                                <option value="" selected>-------</option>
                                                <?php  if(isset($languages)){?>
                                                <?php
                                                foreach($languages as $language){
                                                ?>
                                                <option
                                                    value="{{$language->name}}"> {{ __('lang'.$language->PrefixLanguage)  }}</option>
                                                <?php } }  ?>
                                            </select>

                                            {{--                                            --}}
                                            {{--                                            <input type="text" class="form-control" id="websiteInput1"--}}
                                            {{--                                                   placeholder="www.example.com" value="www.velzon.com"/>--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="cityInput" class="form-label">{{ __('State') }}</label>
                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model.defer="usermetta_info.idState">
                                                <option value="">{{__('Choose')}}</option>
                                                @foreach($states as $state)
                                                    <?php
                                                    $cnP = \Illuminate\Support\Facades\Lang::get($state->name);
                                                    ?>
                                                    <option value="{{$state->id}}">{{$cnP}}</option>
                                                @endforeach
                                            </select>
                                            {{--                                            <input type="text" class="form-control" id="cityInput"--}}
                                            {{--                                                   placeholder="City"--}}
                                            {{--                                                   value="California"/>--}}
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label">{{ __('Country') }}</label>
                                            <input readonly wire:model.defer="countryUser" type="text"
                                                   class="form-control"
                                                   id="countryInput"
                                                   placeholder="" value="United States"/>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="zipcodeInput" class="form-label">{{ __('National ID') }}</label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   wire:model.defer="usermetta_info.nationalID"
                                                   id="zipcodeInput" placeholder="">
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea"
                                                   class="form-label">{{ __('Address') }}</label>
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
                                            @if($paramIdUser =="")
                                                <button type="button" id="btnsaveUser"
                                                        class="btn btn-primary btn2earn">{{ __('Save') }}</button>
                                                {{--                                                <button type="button" class="btn btn-soft-success">Cancel</button>--}}
                                            @else
                                                <div x-data="{ open: false }">
                                                    <button x-show="!open" type="button" @click="open = true"
                                                            class="btn btn-secondary ps-5 pe-5"
                                                            id="reject">{{ __('Reject') }}</button>
                                                    <button x-show="!open" class="btn btn-success ps-5 pe-5"
                                                            id="validate">{{ __('Approve') }}</button>
                                                    </br>
                                                    <label x-show="open">{{ __('Libele_Note') }}</label>
                                                    </br>
                                                    <textarea wire:model.defer="noteReject" name="Text1" cols="80"
                                                              rows="5"
                                                              x-show="open"></textarea>
                                                    </br>
                                                    <button type="button" x-show="open" wire:click="reject"
                                                            class="btn btn-secondary ps-5 pe-5"
                                                            id="">{{ __('Reject') }}</button>
                                                    <button type="button" x-show="open" class="btn btn-danger ps-5 pe-5"
                                                            id=""
                                                            @click="open = false">{{ __('canceled !') }}
                                                    </button>
                                                </div>
                                            @endif

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
                                               class="form-label">{{ __('Current Password') }}</label>
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


                                        {{--                                            <label for="oldpasswordInput"--}}
                                        {{--                                                   class="form-label">{{ __('Current Password') }}</label>--}}
                                        {{--                                            <input wire:model.defer="oldPassword" type="password" class="form-control"--}}
                                        {{--                                                   id="oldpasswordInput"--}}
                                        {{--                                                   placeholder="********">--}}

                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <label for="newpasswordInput"
                                               class="form-label">{{ __('New Password') }}</label>


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
                                                   class="form-label">{{ __('New Confirm Password') }}</label>


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
{{--                                    <div class="col-lg-12">--}}
{{--                                        <div class="mb-3">--}}
{{--                                            <a href="javascript:void(0);"--}}
{{--                                               class="link-primary text-decoration-underline">{{ __('Forgot Password?') }}</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div style="" class="col-lg-12">
                                        <div class="form-check form-switch ms-5 me-5 mb-3" dir="ltr">
                                            <input wire:model.defer="sendPassSMS" type="checkbox" id="send"
                                                   class="form-check-input" id="flexSwitchCheckDefault" checked="">
                                            <label class="form-check-label"
                                                   for="customSwitchsizesm">{{ __('I want to receive my password by SMS') }}  </label>

                                        </div>
                                        {{--                                        <label  class="toggle">--}}
                                        {{--                                            --}}{{--                    <input class="toggle-checkbox" type="checkbox" id="send" name="send" @if($setting_notif->change_pwd_sms == 1) checked @endif>--}}
                                        {{--                                            <input class="toggle-checkbox" type="checkbox" id="send" name="send" wire:model="sendPassSMS">--}}
                                        {{--                                            <div class="toggle-switch"></div>--}}
                                        {{--                                            <span  class="toggle-label">{{ __('I want to receive my password by SMS') }}</span>--}}
                                        {{--                                        </label>--}}
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button onclick="ConfirmChangePass()" type="button" class="btn btn-success btn2earn">
                                                {{ __('Save') }}
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                            {{--                            <div class="mt-4 mb-3 border-bottom pb-2">--}}
                            {{--                                <div class="float-end">--}}
                            {{--                                    <a href="javascript:void(0);" class="link-primary">All Logout</a>--}}
                            {{--                                </div>--}}
                            {{--                                <h5 class="card-title">Login History</h5>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="d-flex align-items-center mb-3">--}}
                            {{--                                <div class="flex-shrink-0 avatar-sm">--}}
                            {{--                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">--}}
                            {{--                                        <i class="ri-smartphone-line"></i>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="flex-grow-1 ms-3">--}}
                            {{--                                    <h6>iPhone 12 Pro</h6>--}}
                            {{--                                    <p class="text-muted mb-0">Los Angeles, United States - March 16 at--}}
                            {{--                                        2:47PM</p>--}}
                            {{--                                </div>--}}
                            {{--                                <div>--}}
                            {{--                                    <a href="javascript:void(0);">Logout</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="d-flex align-items-center mb-3">--}}
                            {{--                                <div class="flex-shrink-0 avatar-sm">--}}
                            {{--                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">--}}
                            {{--                                        <i class="ri-tablet-line"></i>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="flex-grow-1 ms-3">--}}
                            {{--                                    <h6>Apple iPad Pro</h6>--}}
                            {{--                                    <p class="text-muted mb-0">Washington, United States - November 06--}}
                            {{--                                        at 10:43AM</p>--}}
                            {{--                                </div>--}}
                            {{--                                <div>--}}
                            {{--                                    <a href="javascript:void(0);">Logout</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="d-flex align-items-center mb-3">--}}
                            {{--                                <div class="flex-shrink-0 avatar-sm">--}}
                            {{--                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">--}}
                            {{--                                        <i class="ri-smartphone-line"></i>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="flex-grow-1 ms-3">--}}
                            {{--                                    <h6>Galaxy S21 Ultra 5G</h6>--}}
                            {{--                                    <p class="text-muted mb-0">Conneticut, United States - June 12 at--}}
                            {{--                                        3:24PM</p>--}}
                            {{--                                </div>--}}
                            {{--                                <div>--}}
                            {{--                                    <a href="javascript:void(0);">Logout</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                            <div class="d-flex align-items-center">--}}
                            {{--                                <div class="flex-shrink-0 avatar-sm">--}}
                            {{--                                    <div class="avatar-title bg-light text-primary rounded-3 fs-18">--}}
                            {{--                                        <i class="ri-macbook-line"></i>--}}
                            {{--                                    </div>--}}
                            {{--                                </div>--}}
                            {{--                                <div class="flex-grow-1 ms-3">--}}
                            {{--                                    <h6>Dell Inspiron 14</h6>--}}
                            {{--                                    <p class="text-muted mb-0">Phoenix, United States - July 26 at--}}
                            {{--                                        8:10AM</p>--}}
                            {{--                                </div>--}}
                            {{--                                <div>--}}
                            {{--                                    <a href="javascript:void(0);">Logout</a>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <livewire:identification-check/>
                        </div>
                        <!--end tab-pane-->
                        <div class="tab-pane" id="privacy" role="tabpanel">

                            <livewire:edit-phone-number/>

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
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('Are_you_sure_to_change_mail')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="emailInput" class="form-label">{{ __('Your Email') }}</label>
                                    <input type="email" wire:model.defer="user.email" class="form-control"
                                           id="inputEmail" placeholder="{{ __('your_new_mail')}}">

                                </div>
                            </div><!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">{{ __('Close')}}</button>
                                    <button type="button" id="validateMail"
                                            class="btn btn-primary">{{ __('Save_changes')}}</button>
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
                    <h5 class="modal-title" id="exampleModalgridLabel">{{ __('CompleteProfil')}}</h5>
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
                                        {{--                                                    Joining Date--}}
                                        {{__('Date of birth')  }}
                                    </label>
                                    <input wire:model.defer="usermetta_info.birthday" type="date"
                                           class="form-control"
                                           id=""
                                           placeholder=""/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="zipcodeInput" class="form-label">{{ __('National ID') }}</label>
                                    <input type="text" class="form-control" minlength="5" maxlength="50"
                                           wire:model.defer="usermetta_info.nationalID"
                                           id="zipcodeInput" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="emailInput"
                                           class="form-label">{{ __('Your Email') }}</label>
                                    <div class="input-group form-icon">

                                        <input disabled wire:model.defer="user.email" type="email"
                                               class="form-control form-control-icon"
                                               name="email" placeholder="">
                                        <i style="font-size: 20px" class="ri-mail-unread-line"></i>
                                        {{--                                        <button data-bs-toggle="modal" data-bs-target="#modalMail"--}}
                                        {{--                                                class="btn btn-primary"--}}
                                        {{--                                                type="button">@if($user['email']=="") {{__('add')}} @else {{__('Change')}} @endif</button>--}}
                                    </div>

                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="form-label">{{ __('Front ID') }}</label>
                                </div>
                                <div>
                                    @if(file_exists(public_path('/uploads/profiles/front-id-image'.$user['idUser'].'.png')))
                                        <img width="150" height="100"
                                             src={{asset(('/uploads/profiles/front-id-image'.$user['idUser'].'.png'))}} >
                                    @endif
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
                                    <label class="form-label">{{ __('Back ID') }}</label>
                                </div>
                                <div>
                                    @if(file_exists(public_path('/uploads/profiles/back-id-image'.$user['idUser'].'.png')))
                                        <img width="150" height="100"
                                             src={{asset(('/uploads/profiles/back-id-image'.$user['idUser'].'.png'))}} >
                                    @endif
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
                                            data-bs-dismiss="modal">{{ __('Close')}}</button>
                                    <button onclick="SaveChangeEdit()" type="button" id="SaveCahngeEdit"
                                            class="btn btn-primary">{{ __('Save_changes')}}</button>
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

            {{--if ($('#password').val() === '' || $('#new_password').val() === '' || $('#new_confirm_password').val() === '') {--}}
            {{--    Swal.fire('{{trans('Invalid_field')}}', '', 'error');--}}
            {{--    return;--}}
            {{--}--}}
            {{--if ($('#new_password').val() != $('#new_confirm_password').val()) {--}}
            {{--    Swal.fire({--}}
            {{--        title: '{{trans('Password_not_Confirmed')}}',--}}
            {{--        icon: 'error',--}}
            {{--        showDenyButton: false,--}}
            {{--        showCancelButton: false,--}}
            {{--        confirmButtonText: '{{trans('ok')}}',--}}
            {{--    });--}}
            {{--    Swal.fire('{{trans('Password_not_Confirmed')}}', '', 'error');--}}
            {{--    return;--}}
            {{--}--}}
            window.livewire.emit('PreChangePass');
        }
        window.addEventListener('OptChangePass', event => {
            Swal.fire({
                title: '{{trans('Your verification code')}}',
                html: '{{ __('We_will_send') }}' + '<br>' + event.detail.mail + '<br>' + '{{__('Your OTP Code')}}',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT') }}',
                timerProgressBar: true,
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('ok')}}',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML =  '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                    timerInterval = setInterval(() => {
                        b.innerHTML = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}'
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
                title: '{{ __('Your verification code') }}',
                html: '{{ __('We_will_send_Sms') }}<br> ',
                html: '{{ __('We_will_send_Sms') }}<br>' + event.detail.numberActif + '<br>' + '{{ __('Your OTP Code') }}',
                input: 'text',
                allowOutsideClick: false,
                timer: '{{ env('timeOPT') }}',
                timerProgressBar: true,
                confirmButtonText: '{{trans('ok')}}',
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                footer: ' <i></i><div class="footerOpt"></div>',
                didOpen: () => {
                    // Swal.showLoading()
                    const b = Swal.getFooter().querySelector('i')
                    const p22 = Swal.getFooter().querySelector('div')
                    p22.innerHTML = '{{trans('Dont get code?') }}' + ' <a OnClick="ResendMail()" >' + '{{trans('Resend')}}' + '</a>';

                    timerInterval = setInterval(() => {
                        b.textContent = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}'
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
            {{--alert( {{$nbrChild}});--}}
        });
        $("#btnMinus").click(function () {
            var child = parseInt($("#inputChild").val());
            child = child - 1;
            if (child >= 0)
                $("#inputChild").val(child);
            else
                $("#inputChild").val(0);
            {{--alert( {{$nbrChild}});--}}
        });

        $('#send').change(function () {
            if (this.checked && !{{$soldeSms}} > 0) {
                Swal.fire({
                    title: '{{ __('solde_sms_ins') }}',
                    confirmButtonText: '{{trans('ok')}}',
                });
                return;
            }
            Swal.fire({
                title: '{{ __('upate_notification_setting') }}',
                showDenyButton: true,
                confirmButtonText: '{{trans('Yes')}}',
                denyButtonText: '{{trans('No')}}'
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
    {{--    <script  src="{{ URL::asset('/assets/js/app.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js') }}"></script>--}}
    {{--    <script src="{{ URL::asset('assets/js/plugins.min.js') }}"></script>--}}
</div>
