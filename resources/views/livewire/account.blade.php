<div class="container">
    <div class="row">
        <div>
            @component('components.breadcrumb')
                @slot('title')
                    @if(Route::getCurrentRoute()->getName()!="validate_account")
                        {{ __('Profile') }}
                    @else
                        {{ __('Validate account') }} :  {{$dispalyedUserCred}} [{{ $user['idUser']}}]
                    @endif
                @endslot
            @endcomponent
            <div class="row">
                @include('layouts.flash-messages')
            </div>
            <div class="row">
                @if(!$disabled)
                    <div  class="col-sm-12 card shadow-sm @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif">
                        <div class="card-header bg-transparent border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-user-line fs-4 text-info me-2"></i>
                                    <h5 class="card-title mb-0 text-info">{{ __('Complete_Profile') }}</h5>
                                </div>
                                <div
                                        class="@if(Route::getCurrentRoute()->getName()!="validate_account") d-none   @endif">
                                    <a style="color: #009fe3!important" data-bs-toggle="modal"
                                       data-bs-target="#modalEditProf"
                                       href="javascript:void(0);"
                                       class="btn btn-sm btn-outline-primary"
                                       aria-label="{{ __('Edit profile') }}">
                                        <i class="ri-edit-box-line align-bottom me-1"></i> {{__('Edit')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">
                                            <i class="ri-user-settings-line me-1"></i>{{ __('Profile Completion') }}
                                        </span>
                                    <span class="badge bg-primary-subtle text-primary">{{$PercentComplete}}%</span>
                                </div>
                                <div class="progress" style="height: 24px;">
                                    @if($PercentComplete>=20)
                                        <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                                role="progressbar"
                                                style="width: 20%"
                                                aria-valuenow="20"
                                                aria-valuemin="0"
                                                aria-valuemax="100"
                                                aria-label="{{ __('Profile completion progress') }}">
                                            @if($PercentComplete==20)
                                                <span class="fw-semibold">{{$PercentComplete}}%</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($PercentComplete>=40)
                                        <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-danger"
                                                role="progressbar"
                                                style="width:20%" aria-valuenow="40" aria-valuemin="0"
                                                aria-valuemax="100">
                                            @if($PercentComplete==40)
                                                <span class="fw-semibold">{{$PercentComplete}}%</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($PercentComplete>=60)
                                        <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                                                role="progressbar"
                                                style="width: 20%" aria-valuenow="60" aria-valuemin="0"
                                                aria-valuemax="100">
                                            @if($PercentComplete==60)
                                                <span class="fw-semibold">{{$PercentComplete}}%</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($PercentComplete>=80)
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                             role="progressbar" style="width: 20%"
                                             aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                            @if($PercentComplete==80)
                                                <span class="fw-semibold">{{$PercentComplete}}%</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($PercentComplete==100)
                                        <div
                                                class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar"
                                                style="width: 20%" aria-valuenow="100" aria-valuemin="0"
                                                aria-valuemax="100">
                                                <span class="fw-semibold">
                                                    <i class="ri-check-line me-1"></i>{{$PercentComplete}}%
                                                </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($PercentComplete==100)
                                <div class="alert alert-success border-0 mb-0" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-checkbox-circle-line fs-3 me-3"></i>
                                        <div>
                                            @if($hasRequest)
                                                <h6 class="alert-heading mb-1">
                                                        <span class="spinner-grow spinner-grow-sm me-2" role="status"
                                                              aria-hidden="true"></span>
                                                    {{__('voter_demande_déja_en_cours')}}
                                                </h6>
                                                <p class="mb-0 small">{{ __('Your request is being processed') }}</p>
                                            @else
                                                @if($user['status'] == 2)
                                                    <h6 class="alert-heading mb-0">
                                                        <i class="ri-verified-badge-line me-1"></i>
                                                        {{__('Your account is already national validated')}}
                                                    </h6>
                                                @elseif($user['status'] == 4)
                                                    <h6 class="alert-heading mb-0">
                                                        <i class="ri-verified-badge-line me-1"></i>
                                                        {{__('your account is already international validated')}}
                                                    </h6>
                                                @else
                                                    <h6 class="alert-heading mb-0">
                                                        <i class="ri-verified-badge-line me-1"></i>
                                                        {{__('Your account is not validated , you can send a request')}}
                                                    </h6>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                @if(!empty($errors_array))
                                    <div class="alert alert-warning border-0 mb-0" role="alert">
                                        <div class="d-flex">
                                            <i class="ri-error-warning-line fs-3 me-3 flex-shrink-0"></i>
                                            <div class="flex-grow-1">
                                                <h6 class="alert-heading mb-2">
                                                    <i class="ri-information-line me-1"></i>
                                                    {{ __('Please fill in the missing fields profile') }}
                                                </h6>
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($errors_array as $error)
                                                        <li class="mb-1">
                                                            <i class="ri-arrow-right-s-line text-warning"></i>
                                                            <span class="fw-medium">{{ $error }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                @if(Route::getCurrentRoute()->getName()=="validate_account")
                    <livewire:user-form-content :paramIdUser="$user['idUser']"/>
                @else
                    <div class="col mx-1 card shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-user-line fs-4 text-info me-2"></i>
                                    <h5 class="card-title mb-0 text-info">{{ __('User Information Form') }}</h5>
                                </div>
                                <div>
                                    <a href="{{ route('user_form', app()->getLocale()) }}"
                                       class="btn btn-sm btn-primary"
                                       aria-label="{{ __('Go to User Form') }}">
                                        <i class="ri-edit-box-line align-bottom me-1"></i> {{__('Open Form')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-0 mb-0" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="ri-information-line fs-5 me-2"></i>
                                    <div>
                                        <p class="mb-0">{{ __('Click the "Open Form" button to access the detailed user information form where you can update your personal details.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col mx-1 card shadow-sm" id="profile">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ri-user-3-line fs-4 text-info me-2"></i>
                            <h5 class="card-title mb-0 text-info">{{ __('Your Profile Picture') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center">
                            <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                                @if ($imageProfil)
                                    <div class="mb-2">
                                        <img class="rounded-circle shadow-sm" width="70" height="70"
                                             src="{{ $imageProfil->temporaryUrl() }}?={{Str::random(16)}}"
                                             alt="{{ __('Preview image') }}">
                                    </div>
                                @endif
                                <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                                     class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow"
                                     alt="{{ __('Profile picture of') }} {{$dispalyedUserCred}}">

                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit"
                                     title="{{ __('Change profile picture') }}">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                           accept="image/png, image/jpeg, image/jpg"
                                           wire:model="imageProfil"
                                           aria-label="{{ __('Upload profile picture') }}">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                            <span class="avatar-title rounded-circle bg-light text-body">
                                                <i class="ri-camera-fill"></i>
                                            </span>
                                    </label>
                                </div>
                            </div>

                            <div wire:loading wire:target="imageProfil" class="alert alert-info border-0 py-2 mb-2"
                                 role="alert">
                                <i class="ri-upload-cloud-line me-1"></i>
                                <small>{{__('Uploading')}}...</small>
                            </div>

                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="ri-information-line"></i>
                                    {{__('The photo must be in PNG, JPG or JPEG format and must not exceed 8 Mb in size')}}
                                </small>
                            </div>

                            <h2 class="mb-2 fw-semibold">
                                {{$dispalyedUserCred}}
                            </h2>
                            <div class="mb-2">
                                    <span
                                            class="badge bg-secondary-subtle text-secondary fs-6">{{ __('ID') }}: {{$user['idUser']}}</span>
                            </div>

                            @if($user['status']==\Core\Enum\StatusRequest::ValidNational->value||$user['status']==\Core\Enum\StatusRequest::ValidInternational->value)
                                <div class="mb-3">
                                        <span class="badge bg-success-subtle text-success fs-6">
                                            <i class="ri-verified-badge-line me-1"></i>{{__('Identified')}}
                                        </span>
                                </div>
                            @endif

                            @if($imageProfil || (bool)($user['is_public'] ?? 0) !== (bool)($originalIsPublic ?? 0))
                                <div class="mt-3">
                                    <button wire:click="saveProfileSettings"
                                            wire:loading.attr="disabled"
                                            class="btn btn-success w-100"
                                            aria-label="{{ __('Save Changes') }}">
                                            <span wire:loading.remove wire:target="saveProfileSettings">
                                                <i class="ri-save-line me-1"></i>{{ __('Save Changes') }}
                                            </span>
                                        <span wire:loading wire:target="saveProfileSettings">
                                                <span class="spinner-border spinner-border-sm me-1" role="status"
                                                      aria-hidden="true"></span>
                                                {{ __('Saving') }}...
                                            </span>
                                    </button>
                                </div>
                            @endif

                            @if(Route::getCurrentRoute()->getName()!="validate_account")
                                <div class="mt-3">
                                    <a href="{{ route('change_password', app()->getLocale()) }}"
                                       class="btn btn-primary w-100"
                                       aria-label="{{ __('Change password') }}">
                                        <i class="ri-lock-password-line me-1"></i>
                                        {{ __('Change password') }}
                                    </a>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('identification', app()->getLocale()) }}"
                                       class="btn btn-info w-100"
                                       aria-label="{{ __('Identifications') }}">
                                        <i class="ri-shield-check-line me-1"></i>
                                        {{ __('Identifications') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <livewire:identity-cards :userId="$user['idUser']"/>
                </div>
            </div>
            <div class="modal fade" id="modalMail" tabindex="-1" aria-labelledby="exampleModalgridLabel"
                 aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"
                                id="exampleModalgridLabel">{{ __('Are_you_sure_to_change_mail')}}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="javascript:void(0);">
                                <div class="row g-3">
                                    <div class="col-xxl-12">
                                        <div>
                                            <label for="emailInput" class="form-label">{{ __('Your Email') }}</label>
                                            <input type="email" wire:model="user.email" class="form-control"
                                                   id="inputEmail" placeholder="{{ __('your_new_mail')}}">
                                            <div class="form-text">{{__('Required for account validation')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                {{ __('Close')}}
                                            </button>
                                            <button type="button" wire:loading.attr="disabled" id="validateMail"
                                                    class="btn btn-primary">
                                                {{ __('Change Email')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
                                            <label for="lastName" class="form-label">{{__('First Name')}}</label>
                                            <input wire:model="usermetta_info.enFirstName" type="text"
                                                   class="form-control" {{ $disabled ? 'disabled' : ''  }}
                                                   placeholder="{{__('Enter your name')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div>
                                            <label for="phoneNumber" class="form-label">{{__('Last Name')}}AA</label>
                                            <input wire:model="usermetta_info.enLastName" type="text"
                                                   class="form-control" {{ $disabled ? 'disabled' : ''  }}
                                                   placeholder="{{__('Enter your lastname')}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">
                                                {{__('Date of birth')  }}
                                            </label>
                                            <input wire:model="usermetta_info.birthday"
                                                   {{ $disabled ? 'disabled' : ''  }}
                                                   type="date" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="zipcodeInput2"
                                                   class="form-label">{{ __('National ID') }}</label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   {{ $disabled ? 'disabled' : ''  }}
                                                   wire:model="usermetta_info.nationalID"
                                                   id="zipcodeInput2">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label">{{ __('Your Email') }}</label>
                                            <div class="input-group form-icon">
                                                <input disabled wire:model="user.email" type="email"
                                                       class="form-control form-control-icon" name="email"
                                                       placeholder="">
                                                <i style="font-size: 20px" class="ri-mail-unread-line"></i>
                                                <div class="form-text">{{__('Required for account validation')}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label">{{ __('Front ID') }}</label>
                                        </div>
                                        <div>
                                            <img class="img-thumbnail" width="150" height="100"
                                                 src="{{asset($userNationalFrontImage)}}?={{Str::random(16)}}">
                                        </div>
                                        @if(!$disabled)
                                            <div class="wrap-custom-file mt-2 ">
                                                <input wire:model="photoFront" type="file" name="image55" id="image55"
                                                       {{ $disabled ? 'disabled' : ''  }}

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
                                        @endif

                                    </div>
                                    <div class="col-6">
                                        <div>
                                            <label class="form-label">{{ __('Back ID') }}</label>
                                        </div>
                                        <div>
                                            <img width="150" height="100"
                                                 src="{{asset($userNationalBackImage)}}?={{Str::random(16)}}">
                                        </div>
                                        @if(!$disabled)
                                            <div class="wrap-custom-file mt-2">
                                                <input wire:model="photoBack" type="file" name="image44" id="image44"
                                                       {{ $disabled ? 'disabled' : ''  }}
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
                                        @endif
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                {{ __('Close')}}
                                            </button>
                                            <button wire:click="SaveChangeEdit" type="button" id="SaveCahngeEdit"
                                                    class="btn btn-primary">
                                                <div wire:loading wire:target="SaveChangeEdit">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                    <span class="sr-only">{{__('Loading')}}...</span>
                                                </div>
                                                {{ __('Save_changes')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade bd-example-modal-lg" tabindex="-1" id="identies-viewer" role="dialog"
                 aria-labelledby="identies-viewer-title"
                 aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <i class="ri-id-card-line fs-4 text-primary me-2"></i>
                                <h5 class="modal-title text-primary fw-semibold mb-0"
                                    id="identies-viewer-title">{{ __('Identity Document') }}</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body p-4" id="identies-viewer-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('Loading') }}...</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-top">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>{{ __('Close') }}
                            </button>
                        </div>
                    </div>
                </div>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
                <script>
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
                    }


                    var SuccesUpdatePassword = '{{ Session::has('SuccesUpdatePassword')}}'
                    if (SuccesUpdatePassword) {
                        toastr.success('{{Session::get('SuccesUpdatePassword')}}');
                    }

                </script>
                <script type="module">
                    var timerInterval;
                    document.addEventListener("DOMContentLoaded", function () {

                        $("#btnsaveUser").click(function () {
                            window.Livewire.dispatch('saveUser', [parseInt($("#inputChild").val())]);
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
                                    $label.addClass('file-ok').css('background-image', 'url(' + tmppath + ')');
                                    $labelText.text(fileName);
                                } else {
                                    $label.removeClass('file-ok');
                                    $labelText.text(labelDefault);
                                }
                            });
                        });
                    });


                    window.addEventListener('OptChangePass', event => {
                        Swal.fire({
                            title: '{{trans('Your verification code by email')}}',
                            html: '{{ __('We_will_send') }}' + '<br>' + event.detail[0].mail + '<br>' + '{{__('Your OTP Code')}}',
                            allowOutsideClick: false,
                            timer: '{{ env('timeOPT',180000) }}',
                            timerProgressBar: true,
                            showCancelButton: true,
                            cancelButtonText: '{{trans('cancel')}}',
                            confirmButtonText: '{{trans('Confirm changes')}}',
                            footer: '<i></i><div class="footerOpt"></div>',
                            didOpen: () => {
                                const b = Swal.getFooter().querySelector('i');
                                const p22 = Swal.getFooter().querySelector('div');
                                p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';

                                timerInterval = setInterval(() => {
                                    let timerLeft = Swal.getTimerLeft();
                                    if (timerLeft !== null) {
                                        b.innerHTML = '{{trans('It will close in')}}' + (timerLeft / 1000).toFixed(0) + '{{trans('secondes')}}';
                                    } else {
                                        clearInterval(timerInterval);
                                    }
                                }, 1000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            },
                            input: 'text',
                            inputAttributes: {autocapitalize: 'off'},
                        }).then((resultat) => {
                            if (resultat.value) {
                                window.Livewire.dispatch('changePasswordWithOPTValidation', [resultat.value]);
                            }
                        }).catch((error) => {
                            console.error('SweetAlert Error:', error);
                        });
                    });
                    window.addEventListener('confirmOPTVerifMail', event => {
                        Swal.fire({
                            title: '{{trans('Your verification code by phone number')}}',
                            html: '{{ __('We_will_send') }}' + '<br>' + event.detail[0].numberActif + '<br>' + '{{__('Your OTP Code')}}',
                            allowOutsideClick: false,
                            timer: '{{ env('timeOPT',180000) }}',
                            timerProgressBar: true,
                            showCancelButton: true,
                            cancelButtonText: '{{trans('cancel')}}',
                            confirmButtonText: '{{trans('confirm OPT')}}',
                            footer: '<i></i><div class="footerOpt"></div>',
                            didOpen: () => {
                                const b = Swal.getFooter().querySelector('i');
                                const p22 = Swal.getFooter().querySelector('div');
                                p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                                timerInterval = setInterval(() => {
                                    let timerLeft = Swal.getTimerLeft();
                                    if (timerLeft !== null) {
                                        b.innerHTML = '{{trans('It will close in')}}' + (timerLeft / 1000).toFixed(0) + '{{trans('secondes')}}';
                                    } else {
                                        clearInterval(timerInterval);
                                    }
                                }, 1000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            },
                            input: 'text',
                            inputAttributes: {autocapitalize: 'off'},
                        }).then((resultat) => {
                            if (resultat.isConfirmed) {
                                window.Livewire.dispatch('checkUserEmail', [resultat.value]);
                            } else if (resultat.isDismissed && resultat.dismiss == 'cancel') {
                                window.Livewire.dispatch('cancelProcess', ["{{__('confirm OPT Verif Mail canceled')}}"]);
                            }
                        }).catch((error) => {
                            console.error('SweetAlert Error:', error);
                        });
                    });


                    $("#validateMail").click(function (validateMailEvent) {
                        validateMailEvent.preventDefault();
                        validateMailEvent.stopImmediatePropagation();
                        window.Livewire.dispatch("sendVerificationMail", [$('#inputEmail').val()]);
                    });

                    function showIdentitiesModal(typeIdentitie) {
                        $('#identies-viewer-title').empty().append($('#' + typeIdentitie + '-id-image').attr('title'));
                        $('#identies-viewer-content').empty().append($('#' + typeIdentitie + '-id-image').clone().width('100%').height('200%'));
                        var myModal = new bootstrap.Modal(document.getElementById('identies-viewer'))
                        myModal.show();
                    }

                    $("#show-identity-front").click(function () {
                        showIdentitiesModal('front')
                    });
                    $("#show-identity-back").click(function () {
                        showIdentitiesModal('back')
                    });
                    $("#show-identity-international").click(function () {
                        showIdentitiesModal('international')
                    });
                    window.addEventListener('profilePhotoError', event => {
                        Swal.fire({
                            title: event.detail[0].title,
                            text: event.detail[0].text,
                            icon: 'error',
                            confirmButtonText: "{{__('ok')}}"
                        })
                    })

                    window.addEventListener('EmailCheckUser', event => {
                        console.log(event.detail[0])
                        if (event.detail[0].emailValidation) {
                            Swal.fire({
                                title: event.detail[0].title,
                                html: event.detail[0].html,
                                allowOutsideClick: false,
                                timer: '{{ env('timeOPT',180000) }}',
                                timerProgressBar: true,
                                showCancelButton: true,
                                cancelButtonText: '{{trans('Cancel')}}',
                                confirmButtonText: '{{trans('Confirm OPT')}}',
                                footer: '<i></i><div class="footerOpt"></div>',
                                didOpen: () => {
                                    const b = Swal.getFooter().querySelector('i');
                                    const p22 = Swal.getFooter().querySelector('div');
                                    p22.innerHTML = '<br>' + '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                                    var timerInterval = setInterval(() => {
                                        let timerLeft = Swal.getTimerLeft();
                                        if (timerLeft !== null) {
                                            b.innerHTML = '{{trans('It will close in')}}' + (timerLeft / 1000).toFixed(0) + '{{trans('secondes')}}';
                                        } else {
                                            clearInterval(timerInterval);
                                        }
                                    }, 1000);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                },
                                input: 'text',
                                inputAttributes: {autocapitalize: 'off'},
                            }).then((resultat) => {
                                if (resultat.isConfirmed) {
                                    window.Livewire.dispatch('saveVerifiedMail', [resultat.value]);

                                } else if (resultat.isDismissed && resultat.dismiss == 'cancel') {
                                    window.Livewire.dispatch('cancelProcess', ["{{__('confirm Email Check User canceled')}}"]);
                                }
                            }).catch((error) => {
                                console.error('SweetAlert Error:', error);
                            });
                        } else {
                            $('.modal-backdrop').remove();
                            Swal.fire({
                                title: event.detail[0].title,
                                text: event.detail[0].text,
                                icon: 'error',
                                confirmButtonText: "{{__('ok')}}"
                            })
                        }
                    })
                </script>
                <script type="module">
                    document.addEventListener("DOMContentLoaded", function () {

                        $("#soonExpireIIC, #goToIdentification").click(function () {
                            window.location.href = "{{ route('identification', app()->getLocale()) }}";
                        });

                    });
                </script>
                <script type="module">
                    document.addEventListener("DOMContentLoaded", function () {

                        $("#btnPlus").click(function () {
                            var child = parseInt($("#inputChild").val()) || 0;
                            child = child + 1;
                            if (child <= 20)
                                $("#inputChild").val(child);
                            else
                                $("#inputChild").val(20);
                        });

                        $("#btnMinus").click(function () {
                            var child = parseInt($("#inputChild").val()) || 0;
                            child = child - 1;
                            if (child >= 0)
                                $("#inputChild").val(child);
                            else
                                $("#inputChild").val(0);
                        });
                    });
                </script>
                <script type="module">
                    var ipPhone = document.getElementById("inputPhoneUpdate");
                    var errorMap = ['{{trans('Invalid number')}}', '{{trans('Invalid country code')}}', '{{trans('Too shortsss')}}', '{{trans('Too long')}}', '{{trans('Invalid number')}}'];
                    document.addEventListener("DOMContentLoaded", function () {
                        if (ipPhone !== null) {
                            ipPhone.innerHTML =
                                "<input type='tel'  placeholder= '{{ __("PH_EditPhone") }}' id='initIntlTelInput' class='form-control' onpaste='handlePaste(event)'>" +
                                "  <span id='valid-msg'   class='invisible'>✓ Valid</span><span id='error-msg' class='hide'></span>" +
                                " <input type='hidden' name='fullnumberUpPhone' id='outputUpPhone' value='hidden' class='form-control'> " +
                                " <input type='hidden' name='ccodeUpPhone' id='ccodeUpPhone'  ><input type='hidden' name='isoUpPhone' id='isoUpPhone'  >";
                        }

                        var countryDataUpPhone = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                            inputUpPhone = document.querySelector("#initIntlTelInput");
                        var itiUpPhone = window.intlTelInput(inputUpPhone, {
                            initialCountry: "auto",
                            autoFormat: true,
                            separateDialCode: true,
                            useFullscreenPopup: false,
                            geoIpLookup: function (callback) {
                                $.get('https://ipinfo.io', function () {
                                }, "jsonp").always(function (resp) {
                                    var countryCode = (resp && resp.country) ? resp.country : "TN";
                                    callback(countryCode);
                                });
                            },
                            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
                        });

                        function initIntlTelInput() {
                            inputUpPhone.classList.remove("error");
                            errorMsg.innerHTML = "";
                            errorMsg.classList.add("invisible");
                            validMsg.classList.add("invisible");
                            $("#submit_phone").prop("disabled", true);
                            var phone = itiUpPhone.getNumber();
                            document.createTextNode(phone);
                            phone = phone.replace('+', '00');
                            var mobile = $("#initIntlTelInput").val();
                            var countryData = itiUpPhone.getSelectedCountryData();
                            phone = '00' + countryData.dialCode + phone;
                            $("#outputUpPhone").val(phone);
                            $("#ccodeUpPhone").val(countryData.dialCode);
                            $("#isoUpPhone").val(countryData.iso2);

                            var fullphone = $("#outputUpPhone").val();
                            if (inputUpPhone.value.trim()) {
                                if (itiUpPhone.isValidNumber()) {
                                    errorMsg.classList.add("invisible");
                                    $("#submit_phone").prop("disabled", false);
                                } else {
                                    $("#submit_phone").prop("disabled", true);
                                    inputUpPhone.classList.add("error");
                                    var errorCode = itiUpPhone.getValidationError();
                                    errorMsg.classList.remove("invisible");
                                    if (errorCode == '-99') {
                                        errorMsg.innerHTML = errorMap[2];
                                    } else {
                                        errorMsg.innerHTML = errorMap[errorCode];
                                    }
                                }
                            } else {
                                $("#submit_phone").prop("disabled", true);
                                inputUpPhone.classList.remove("error");
                                var errorCode = itiUpPhone.getValidationError();
                                errorMsg.innerHTML = errorMap[errorCode];
                                errorMsg.classList.add("invisible");
                            }
                        };


                        inputUpPhone.addEventListener('keyup', initIntlTelInput);
                        inputUpPhone.addEventListener('countrychange', initIntlTelInput);
                        for (var i = 0; i < countryDataUpPhone.length; i++) {
                            var country = countryDataUpPhone[i];
                            var optionNode = document.createElement("option");
                            optionNode.value = country.iso2;
                        }
                        document.querySelector("#initIntlTelInput").addEventListener("keypress", function (evt) {
                            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                                evt.preventDefault();
                            }
                        });
                        var validMsg = document.querySelector("#valid-msg");
                        var errorMsg = document.querySelector("#error-msg")
                        inputUpPhone.addEventListener('blur', function () {
                            if (inputUpPhone.value.trim()) {
                                if (itiUpPhone.isValidNumber()) {
                                    $("#submit_phone").prop("disabled", false);
                                } else {
                                    $("#submit_phone").prop("disabled", true);
                                    inputUpPhone.classList.add("error");
                                    var errorCode = itiUpPhone.getValidationError();
                                    errorMsg.innerHTML = errorMap[errorCode];
                                    errorMsg.classList.remove("invisible");
                                }
                            }
                        });
                        initIntlTelInput();
                    });

                </script>


            </div>
        </div>
    </div>
</div>
