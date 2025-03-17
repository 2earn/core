<div>


    @php
        $justExpired=$lessThanSixMonths = false;
        if (!is_null(auth()->user()->expiryDate)) {
            $daysNumber = getDiffOnDays(auth()->user()->expiryDate);
            $lessThanSixMonths = $daysNumber < 180 ? true : false;
            $justExpired = $daysNumber < 1 ? true : false;
        }
    @endphp
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
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="row">
        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('Your Profile Picture') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <span
                                class="text-muted">{{__('The photo must be in PNG, JPG or JPEG format and must not exceed 8 Mb in size')}}</span>
                            @if ($imageProfil)
                                <img class="rounded-circle" width="70" height="70"
                                     src="{{ $imageProfil->temporaryUrl() }}?={{Str::random(16)}}">
                                </br>
                                @endif
                                </br>
                                <div wire:loading wire:target="imageProfil">{{__('Uploading')}}...</div>
                                <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                                     class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                                     alt="user-profile-image">

                                <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                                    <input id="profile-img-file-input" type="file" class="profile-img-file-input"
                                           accept="image/png, image/jpeg"
                                           wire:model.live="imageProfil">
                                    <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                    <span class="avatar-title rounded-circle bg-light text-body">
                                        <i class="ri-camera-fill"></i>
                                    </span>
                                    </label>
                                </div>
                        </div>
                        <h2>
                            {{$dispalyedUserCred}}
                        </h2>
                        <h4>
                            <span class="badge text-bg-secondary">[{{$user['idUser']}}]</span>
                        </h4>
                        <div class="form-check form-switch mt-3" dir="ltr">
                            <input wire:model="user.is_public" type="checkbox" class="form-check-input"
                                   id="customSwitchsizesm" checked="">
                            <label class="form-check-label" for="customSwitchsizesm">
                                {{ __('I agree to receive funding requests') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('National identities cards') }}</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Front ID') }}</th>
                                <td>
                                    <img class="img-thumbnail" width="150" height="100" id="front-id-image"
                                         title="{{__('Front id image')}}"
                                         src="{{asset($userNationalFrontImage)}}?={{Str::random(16)}}">
                                    <button type="button" class="btn btn-outline-primary mt-1"
                                            data-toggle="modal"
                                            id="show-identity-front"
                                            data-target=".bd-example-modal-lg">{{__('Show Identity')}}</button>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                        <span class="align-middle">{{ __('Back ID') }}
                                        </span>
                                </th>
                                <td>
                                    <img class="img-thumbnail" width="150" height="100" id="back-id-image"
                                         title="{{__('Back id image')}}"
                                         src="{{asset($userNationalBackImage)}}?={{Str::random(16)}}">
                                    <button type="button" class="btn btn-outline-primary mt-1"
                                            data-toggle="modal"
                                            id="show-identity-back"
                                            data-target=".bd-example-modal-lg">{{__('Show Identity')}}</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('International identity card') }}</h5>
                    @if(auth()->user()->status == 2 && $justExpired)
                        <button type="button" id="soonExpireIIC"
                                class="btn btn-danger mt-2">{{__('Your International identity is expired')}}</button>
                    @elseif(auth()->user()->status == 4 && $lessThanSixMonths)
                        <button type="button" id="soonExpireIIC"
                                class="btn btn-warning mt-2">{{__('Your International identity will soon expire')}}</button>
                    @endif
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        @if($user['internationalID'])
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th scope="row">{{ __('Identity card') }}</th>
                                    <td>
                                        <img class="img-thumbnail" width="150" height="100"
                                             id="international-id-image"
                                             title="{{__('International identity card')}}"
                                             src="{{asset($userInternationalImage)}}?={{Str::random(16)}}">
                                        <button type="button" class="btn btn-outline-primary mt-1"
                                                data-toggle="modal"
                                                id="show-identity-international"
                                                data-target=".bd-example-modal-lg">
                                            {{__('Show Identity')}}
                                        </button>
                                    </td>
                                <tr>
                                <tr>
                                    <th scope="row">{{__('InternationalId ID identificatdion modal')}}</th>
                                    <td>
                                        @if($user['internationalID'])
                                            {{$user['internationalID']}}
                                        @endif
                                    </td>
                                <tr>
                                <tr>
                                    <th scope="row">{{__('Expiry date identificatdion modal')}}</th>
                                    <td>
                                        @if($user['internationalID'])
                                            {{$user['expiryDate']}}
                                        @endif
                                    </td>
                                <tr>
                            </table>
                        @else
                            <div
                                class="alert alert-warning alert-dismissible alert-additional fade show mb-0 material-shadow"
                                role="alert">
                                <div class="alert-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="ri-alert-line fs-16 align-middle"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="alert-heading">{{__('No international identities data information')}}</h5>
                                            <p class="mb-0">{{__('Please log in to benefit from many advantages')}} </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="my-2 float-end btn btn-info" id="goToIdentification"
                            >{{__('Open identification tab')}}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-8">
            @if(!$disabled)
                <div class="card @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif">
                    <div class="card-header">
                        <h5 class="card-title mb-2 text-info">{{ __('Complete_Profile') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div
                                class="flex-shrink-0 @if(Route::getCurrentRoute()->getName()!="validate_account") d-none   @endif">
                                <a style="color: #009fe3!important" data-bs-toggle="modal"
                                   data-bs-target="#modalEditProf"
                                   href="javascript:void(0);"
                                   class="badge bg-light text-primary fs-12"><i
                                        class="ri-edit-box-line align-bottom me-1"></i> {{__('Edit')}}</a>
                            </div>
                        </div>
                        <div class="progress progress-label" style="height: 20px;">
                            @if($PercentComplete>=20)
                                <div class="progress-bar progress-bar-striped progress-bar-animated   bg-danger"
                                     role="progressbar"
                                     style="width: 20%" aria-valuenow="10" aria-valuemin="0"
                                     aria-valuemax="20">
                                    @if($PercentComplete==20)
                                        {{$PercentComplete}}%
                                    @endif
                                </div>
                            @endif
                            @if($PercentComplete>=40)
                                <div class="progress-bar progress-bar-striped progress-bar-animated  bg-danger"
                                     role="progressbar"
                                     style="width:20%" aria-valuenow="25" aria-valuemin="0"
                                     aria-valuemax="40">
                                    @if($PercentComplete==40)
                                        {{$PercentComplete}}%
                                    @endif
                                </div>
                            @endif
                            @if($PercentComplete>=60)
                                <div class="progress-bar progress-bar-striped progress-bar-animated  bg-warning"
                                     role="progressbar"
                                     style="width: 20%" aria-valuenow="60" aria-valuemin="0"
                                     aria-valuemax="60">
                                    @if($PercentComplete==60)
                                        {{$PercentComplete}}%
                                    @endif
                                </div>
                            @endif
                            @if($PercentComplete>=80)
                                <div class="progress-bar progress-bar-striped progress-bar-animated  bg-warning"
                                     role="progressbar" style="width: 20%"
                                     aria-valuenow="80" aria-valuemin="0" aria-valuemax="80">
                                    @if($PercentComplete==80)
                                        {{$PercentComplete}}%
                                    @endif

                                </div>
                            @endif
                            @if($PercentComplete==100)
                                <div class="progress-bar progress-bar-striped progress-bar-animated  bg-success"
                                     role="progressbar"
                                     style="width: 20%" aria-valuenow="100" aria-valuemin="0"
                                     aria-valuemax="100">
                                    @if($PercentComplete==100)
                                        {{$PercentComplete}}%
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($PercentComplete==100)
                            <br>
                            @if($hasRequest)
                                <button class="btn btn-outline-warning" type="button" disabled>
                                        <span class="spinner-grow spinner-grow-sm" role="status"
                                              aria-hidden="true"></span>
                                    {{__('voter_demande_déja_en_cours')}}...
                                </button>
                            @else
                                @if($user['status'] == 2)
                                    <h6>{{__('Your account is already national validated')}}</h6>
                                @elseif($user['status'] == 4)
                                    <h6>{{__('your account is already international validated')}}</h6>
                                @endif
                            @endif
                        @else
                            @if(!empty($errors_array))
                                <div class="alert alert-warning material-shadow mt-2" role="alert">
                                    <h4 class="alert-heading"> {{ __('Please fill in the missing fields profile') }}
                                        :</h4>
                                    <div class="mx-4">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($errors_array as $error)
                                                <li>{{ $error }}.</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0"
                        role="tablist">
                        <li class="nav-item" id="personalDetailsTab">
                            <a class="nav-link active" data-bs-toggle="tab"
                               href="#personalDetails" role="tab">
                                <i class="fas fa-home-user"></i>
                                {{__('Edit profile')}}
                            </a>
                        </li>
                        <li id="identificationsTab"
                            class="nav-item @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="fas fa-contact-card"></i>
                                {{__('Identifications')}}
                            </a>
                        </li>
                        <li class="nav-item @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif">
                            <a class="nav-link" data-bs-toggle="tab" href="#changePassword" role="tab"
                               id="tabEditPass">
                                <i class="fas fa-user"></i>
                                {{__('Change password')}}
                            </a>
                        </li>
                        <li class="nav-item  @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif d-none ">
                            <a class="nav-link disabled" data-bs-toggle="tab" href="#privacy" role="tab">
                                <i class="far fa-envelope"></i>
                                {{__('Update phone number')}}
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
                                            <label for="firstnameInput"
                                                   class="form-label">{{__('Enter your ar firstname label')}}</label>
                                            <input wire:model="usermetta_info.arLastName" type="text"
                                                   class="form-control" id="firstnameInput"
                                                   placeholder="{{__('Enter your ar firstname')}}" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="lastnameInput"
                                                   class="form-label">{{__('Enter your ar last label')}}</label>
                                            <input wire:model="usermetta_info.arFirstName" type="text"
                                                   class="form-control" id="lastnameInput"
                                                   placeholder="{{__('Enter your ar last')}}" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput" class="form-label">
                                                {{__('Last name label')}}
                                            </label>
                                            <input type="text" class="form-control"
                                                   {{ $disabled ? 'disabled' : ''  }}
                                                   wire:model="usermetta_info.enLastName"
                                                   placeholder="{{__('Last Name')}}" value="">
                                            <div class="form-text">{{__('Required for account validation')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstnameInput"
                                                   class="form-label">{{__('First name label')}}</label>
                                            <input
                                                {{ $disabled ? 'disabled' : ''  }}
                                                wire:model="usermetta_info.enFirstName"
                                                placeholder="{{__('First name')}}" class="form-control">
                                            <div class="form-text">{{__('Required for account validation')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput"
                                                   class="form-label">{{ __('Your Contact number') }}</label>
                                            <div class="input-group form-icon">
                                                <input disabled wire:model="numberActif" type="text"
                                                       class="form-control inputtest form-control-icon"
                                                       aria-label=""
                                                       placeholder="">
                                                <i style="font-size: 20px;" class="ri-phone-line"></i>

                                                <a href="{{ !empty($user['email']) ? route('contact_number', app()->getLocale()) : '#' }}"
                                                   id="update_tel" class="btn btn-info" type="button"
                                                   @if(empty($user['email']))
                                                       data-bs-toggle="modal"
                                                   data-bs-target="#topmodal" @endif>
                                                    {{ __('Change') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="emailInput"
                                                   class="form-label">{{ __('Your Email') }}</label>
                                            <div class="input-group form-icon">
                                                <input disabled wire:model="user.email" type="email"
                                                       class="form-control form-control-icon"
                                                       name="email" placeholder="">
                                                <i style="font-size: 20px;" class="ri-mail-unread-line"></i>
                                                <button data-bs-toggle="modal" data-bs-target="#modalMail"
                                                        class="btn btn-info"
                                                        type="button">
                                                    @if($user['email']=="")
                                                        {{__('add')}}
                                                    @else
                                                        {{__('Change')}}
                                                    @endif
                                                </button>
                                            </div>
                                            <div class="form-text">{{__('Required for account validation')}}</div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="JoiningdatInput" class="form-label">
                                                {{__('Date of birth')  }}
                                            </label>
                                            <input
                                                {{ $disabled ? 'disabled' : ''  }}

                                                wire:model="usermetta_info.birthday" type="date"
                                                class="form-control" id="JoiningdatInput"/>
                                            <div class="form-text">{{__('Required for account validation')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label">{{ __('Number Of Children') }}</label>
                                            <div class="input-step form-control full-width light">
                                                <button id="btnMinus" type="button" class="minus">–</button>
                                                <input wire:model="usermetta_info.childrenCount" type="number"
                                                       class="product-quantity form-control" value="2"
                                                       min="0"
                                                       max="100" id="inputChild" readonly>
                                                <button id="btnPlus" type="button" class="plus">+</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="designationInput"
                                                   class="form-label">{{ __('Personal Title') }}</label>
                                            <select class="form-select mb-3"
                                                    wire:model="usermetta_info.personaltitle">
                                                <option value="">{{__('no selected value')}}</option>
                                                <?php if (isset($personaltitles)){
                                                foreach ($personaltitles as $personaltitle){
                                                    ?>
                                                <option
                                                    value="{{$personaltitle->id}}">{{__($personaltitle->name)}}</option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1" class="form-label">{{ __('Gender') }}</label>
                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model="usermetta_info.gender">
                                                <
                                                <option value="">{{__('no selected value')}}</option>
                                                <?php if (isset($genders)){
                                                foreach ($genders as $gender){
                                                    ?>
                                                <option value="{{$gender->id}}">{{ __( $gender->name)  }}</option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="websiteInput1"
                                                   class="form-label">{{ __('Your Preferred Language') }}</label>
                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model="usermetta_info.idLanguage">
                                                <option value="" selected>{{__('no selected value')}}</option>
                                                <?php if (isset($languages)){ ?>
                                                    <?php
                                                foreach ($languages as $language){
                                                    ?>
                                                <option
                                                    value="{{$language->name}}"> {{ __('lang'.$language->PrefixLanguage)  }}</option>
                                                <?php }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="cityInput" class="form-label">{{ __('State') }}</label>
                                            <select class="form-select mb-3" aria-label=" "
                                                    wire:model="usermetta_info.idState">
                                                <option value="">{{__('Choose')}}</option>
                                                @foreach($states as $state)
                                                        <?php
                                                        $cnP = \Illuminate\Support\Facades\Lang::get($state->name);
                                                        ?>
                                                    <option value="{{$state->id}}">{{$cnP}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="countryInput" class="form-label">{{ __('Country') }}</label>
                                            <input readonly wire:model="countryUser" type="text"
                                                   class="form-control"
                                                   id="countryInput"
                                                   {{ $disabled ? 'disabled' : ''  }}

                                                   value="United States"/>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="mb-3">
                                            <label for="zipcodeInput1"
                                                   class="form-label">{{ __('National ID') }}</label>
                                            <input type="text" class="form-control" minlength="5" maxlength="50"
                                                   wire:model="usermetta_info.nationalID"
                                                   id="zipcodeInput1" {{ $disabled ? 'disabled' : ''  }}
                                            >
                                            <div class="form-text">{{__('Required for account validation')}}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3 pb-2">
                                            <label for="exampleFormControlTextarea"
                                                   class="form-label">{{ __('Address') }}</label>
                                            <textarea wire:model="usermetta_info.adresse" class="form-control"
                                                      id="exampleFormControlTextarea"
                                                      placeholder="{{__('Address')}}"
                                                      rows="3">
                                                    </textarea>
                                        </div>
                                    </div>
                                </div>
                                @if($paramIdUser =="")
                                    <div class="col-lg-12">
                                        <button type="button" id="btnsaveUser"
                                                class="btn btn-soft-primary float-end">{{ __('Save') }}</button>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-inline" x-data="{ open: false }">
                                                <div class="form-group mb-2">
                                                    <button x-show="!open" type="button" @click="open = true"
                                                            class="btn btn-secondary ps-5 pe-5" id="reject">
                                                        {{ __('Reject') }}
                                                    </button>
                                                    <button x-show="!open" class="btn btn-success ps-5 pe-5"
                                                            wire:click="approuve({{$paramIdUser}})"
                                                            id="validate">
                                                        <div wire:loading wire:target="approuve({{$paramIdUser}})">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                            <span class="sr-only">{{__('Loading')}}...</span>
                                                        </div>
                                                        {{ __('Approve') }}
                                                    </button>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group mb-2">
                                                        <label x-show="open">{{ __('Libele_Note') }}</label>
                                                        <textarea class="form-control" wire:model="noteReject"
                                                                  name="Text1" cols="80"
                                                                  rows="5"
                                                                  x-show="open">
                                                        </textarea>
                                                    </div>
                                                    <div class="form-group mb-2">
                                                        <button type="button" x-show="open"
                                                                wire:click="reject({{$paramIdUser}})"
                                                                class="btn btn-secondary ps-5 pe-5">
                                                            <div wire:loading
                                                                 wire:target="reject({{$paramIdUser}})">
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                                <span class="sr-only">{{__('Loading')}}...</span>
                                                            </div>
                                                            {{ __('Reject') }}
                                                        </button>
                                                        <button type="button" x-show="open"
                                                                class="btn btn-danger ps-5 pe-5"
                                                                @click="open = false">
                                                            {{ __('canceled !') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                        <div
                            class="tab-pane @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif"
                            id="changePassword" role="tabpanel">
                            <form action="">
                                <div class="row g-2">
                                    <div class="col-lg-4">
                                        <label for="oldpasswordInput" class="form-label">
                                            {{ __('Current Password') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative auth-p
                                        ass-inputgroup mb-3">
                                            <input wire:model="oldPassword" type="password"
                                                   class="form-control pe-5" name="password"
                                                   placeholder="{{__('Old password')}}"
                                                   id="oldpasswordInput">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                type="button" id="toggleOldPassword"><i
                                                    class="ri-eye-fill align-middle"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="newpasswordInput" class="form-label">
                                            {{ __('New Password') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input wire:model="newPassword" type="password"
                                                   class="form-control pe-5  "
                                                   name="password" placeholder="{{__('New password please')}}"
                                                   id="newpasswordInput">
                                            <button
                                                class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                type="button" id="toggleNewPassword">
                                                <i class="ri-eye-fill align-middle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">
                                                {{ __('New Confirm Password') }}
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input wire:model="confirmedPassword" type="password"
                                                       class="form-control" id="confirmpasswordInput"
                                                       placeholder="{{__('Confirm password')}}">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                    type="button" id="toggleConfirmPassword">
                                                    <i class="ri-eye-fill align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="" class="col-lg-12">
                                        <div class="form-check form-switch ms-5 me-5 mb-3" dir="ltr">
                                            <input wire:model="sendPassSMS" type="checkbox" id="send"
                                                   class="form-check-input" id="flexSwitchCheckDefault" checked="">
                                            <label class="form-check-label" for="customSwitchsizesm">
                                                {{ __('I want to receive my password by SMS') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="text-end">
                                            <button wire:click="PreChangePass" type="button"
                                                    class="btn btn-soft-success">
                                                {{ __('Change password') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div
                            class="tab-pane @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif"
                            id="experience" role="tabpanel">
                            <livewire:identification-check/>
                        </div>
                        <div
                            class="tab-pane @if(Route::getCurrentRoute()->getName()=="validate_account") d-none   @endif d-none "
                            id="privacy" role="tabpanel">
                            <livewire:edit-phone-number/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                    <input wire:model="usermetta_info.birthday" {{ $disabled ? 'disabled' : ''  }}
                                    type="date" class="form-control"/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="zipcodeInput2" class="form-label">{{ __('National ID') }}</label>
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
                                               class="form-control form-control-icon" name="email" placeholder="">
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
         aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header mb-2">
                    <h3 class="modal-title" id="identies-viewer-title"></h3>
                </div>
                <div class="modal-content p-3" id="identies-viewer-content">

                </div>
            </div>
        </div>
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
            $(document).on('turbolinks:load', function () {
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
                    cancelButtonText: '{{trans('canceled !')}}',
                    confirmButtonText: '{{trans('ok')}}',
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
                    cancelButtonText: '{{trans('canceled !')}}',
                    confirmButtonText: '{{trans('ok')}}',
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
                    if (resultat.isConfirmed && resultat.value) {
                        window.Livewire.dispatch('checkUserEmail',[ resultat.value]);
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
                if (event.detail[0].emailValidation) {
                    Swal.fire({
                        title: event.detail[0].title,
                        html: event.detail[0].html,
                        allowOutsideClick: false,
                        timer: '{{ env('timeOPT',180000) }}',
                        timerProgressBar: true,
                        showCancelButton: true,
                        cancelButtonText: '{{trans('canceled !')}}',
                        confirmButtonText: '{{trans('ok')}}',
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
                            window.Livewire.dispatch('saveVerifiedMail', resultat.value);
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
            $(document).on('turbolinks:load', function () {
                $("#soonExpireIIC, #goToIdentification").click(function () {
                    console.log('Hi')
                    $('#personalDetailsTab a').removeClass('active')
                    $('#personalDetails').removeClass('active')
                    $('#personalDetailsTab a').attr('aria-selected', false)

                    $('#identificationsTab a').addClass('active')
                    $('#experience').addClass('active')
                    $('#identificationsTab a').attr('aria-selected', true)

                    $('#identificationModalbtn').trigger('click');
                });
            });
        </script>

        <script type="module">
            $(document).on('turbolinks:load', function () {
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
                            window.Livewire.dispatch('ParamSendChanged');
                        } else if (result.isDenied) {
                        }
                    })
                });
            });

            var toggleOldPassword = document.querySelector("#toggleOldPassword");
            var Oldpassword = document.querySelector("#oldpasswordInput");
            toggleOldPassword.addEventListener("click", function () {
                var type = Oldpassword.getAttribute("type") === "password" ? "text" : "password";
                Oldpassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });

            var toggleNewPassword = document.querySelector("#toggleNewPassword");
            var Newpassword = document.querySelector("#newpasswordInput");
            toggleNewPassword.addEventListener("click", function () {
                var type = Newpassword.getAttribute("type") === "password" ? "text" : "password";
                Newpassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });

            var toggleConfirmPassword = document.querySelector("#toggleConfirmPassword");
            var confirmPassword = document.querySelector("#confirmpasswordInput");
            toggleConfirmPassword.addEventListener("click", function () {
                var type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
                confirmPassword.setAttribute("type", type);
                this.classList.toggle("bi-eye");
            });
        </script>
    </div>
    <div id="topmodal" class="modal fade" tabindex="-1" aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <h4 class="mb-3">{{ __('Email Needed') }}</h4>
                    <p class="text-muted mb-4">{{ __('Please enter your email to proceed with the update of your contact number.') }}</p>
                    <div class="hstack gap-2 justify-content-center">
                        <a href="javascript:void(0);"
                           class="btn btn-link link-success fw-medium"
                           data-bs-dismiss="modal"><i
                                class="ri-close-line me-1 align-middle"></i>
                            {{ __('Close')}}</a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
