<div>
    <div>
        @php
            $moreThanSixMonths = false;
           if (!is_null(auth()->user()->expiryDate)) {
               $daysNumber = getDiffOnDays(auth()->user()->expiryDate);
               $moreThanSixMonths = $daysNumber > 180 ? true : false;
           }
        @endphp

        {{-- Main KYC Card --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary-subtle">
                                    <i class="ri-shield-check-line fs-3 text-primary d-flex align-items-center justify-content-center" style="height: 40px;"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1 fw-semibold">{{__('KYC_Verification')}}</h4>
                                <p class="text-muted mb-0 small">{{__('Complete your identity verification')}}</p>
                            </div>
                        </div>

                        <p class="text-muted mb-4">
                            {{__('Txt_KYC_Verification')}}
                        </p>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button"
                                    id="identificationModalbtn"
                                    class="btn btn-primary btn-label"
                                    data-bs-toggle="modal"
                                    @if(!$usermetta_info2['enFirstName'] || !$usermetta_info2['enLastName'] || !$usermetta_info2['birthday'] || !$usermetta_info2['nationalID'] || !$userF['email'])
                                        disabled
                                        title="{{__('Please fill in required information first')}}"
                                    @endif

                                    @if($userAuth->status== 4 && $moreThanSixMonths)
                                        disabled
                                        title="{{__('Verification not available at this time')}}"
                                    @endif

                                    @if($hasRequest) data-bs-target="#accountValidationModal"
                                    @else data-bs-target="#identificationModal" @endif>
                                <i class="ri-shield-check-line label-icon align-middle fs-16 me-2"></i>
                                {{__('Click here for verification')}}
                            </button>

                            @if(!$usermetta_info2['enFirstName'] || !$usermetta_info2['enLastName'] || !$usermetta_info2['birthday'] || !$usermetta_info2['nationalID'] || !$userF['email'])
                                <a href="{{route('account',app()->getLocale())}}" class="btn btn-outline-secondary btn-label">
                                    <i class="ri-edit-line label-icon align-middle fs-16 me-2"></i>
                                    {{__('Complete Profile')}}
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5 text-center mt-4 mt-lg-0">
                        <img src="{{ Vite::asset('resources/images/verification-img.png') }}"
                             alt="{{__('KYC_Verification')}}"
                             class="img-fluid"
                             style="max-height: 200px;"/>
                    </div>
                </div>
            </div>
        </div>

        {{-- Missing Fields Alert --}}
        @if(!empty($errors_array))
            <div class="alert alert-warning alert-border-left alert-dismissible fade show shadow-sm mt-3" role="alert">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <i class="ri-error-warning-line fs-1 align-middle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading">
                            <i class="ri-information-line me-1"></i>
                            {{ __('Please fill in the missing fields identification') }}
                        </h5>
                        <hr class="my-2">
                        <ul class="mb-0">
                            @foreach ($errors_array as $error)
                                <li class="py-1">
                                    <i class="ri-arrow-right-s-line text-warning"></i>
                                    <strong>{{ $error }}</strong>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <a href="{{route('account',app()->getLocale())}}" class="btn btn-sm btn-warning">
                                <i class="ri-edit-box-line me-1"></i>
                                {{__('Go to form data')}}
                            </a>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- Request Already Exists Modal --}}
        <div class="modal fade" id="accountValidationModal" tabindex="-1" aria-labelledby="accountValidationModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-warning-subtle border-0">
                        <h5 class="modal-title d-flex align-items-center" id="modalRequestExisteLabel">
                            <i class="ri-time-line fs-4 me-2 text-warning"></i>
                            {{__('Validation Compte')}}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <div class="avatar-lg mx-auto mb-4">
                            <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                <i class="ri-hourglass-line fs-1"></i>
                            </div>
                        </div>
                        <h5 class="mb-3">{{__('Request In Progress')}}</h5>
                        <p class="text-muted mb-4">{{__('your request already in progress')}}...</p>
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">{{__('Loading')}}...</span>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>
                            {{__('Close')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Identification Modal --}}
        <div wire:ignore class="modal fade" id="identificationModal" tabindex="-1"
             aria-labelledby="identificationModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-primary-subtle border-0 p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                    <i class="ri-shield-user-line fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="modal-title text-primary mb-0" id="identificationModalLabel">
                                    {{__('Entering personal information')}}
                                </h5>
                                <small class="text-muted">{{__('Complete all steps for verification')}}</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" class="checkout-tab">
                        <div class="modal-body p-0">
                            <div class="step-arrow-nav border-bottom">
                                <ul class="nav nav-pills nav-justified custom-nav mb-0" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link p-3 active d-flex align-items-center justify-content-center"
                                                id="pills-bill-info-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-bill-info"
                                                type="button"
                                                role="tab"
                                                aria-controls="pills-bill-info"
                                                aria-selected="true">
                                            <i class="ri-user-line fs-5 me-2"></i>
                                            <span class="d-none d-sm-inline">{{__('Personal info')}}</span>
                                            <span class="d-inline d-sm-none">{{__('Step')}} 1</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link p-3 d-flex align-items-center justify-content-center"
                                                id="pills-identities-card-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-identities-card"
                                                type="button"
                                                role="tab"
                                                aria-controls="pills-identities-card"
                                                aria-selected="false">
                                            <i class="ri-bank-card-line fs-5 me-2"></i>
                                            <span class="d-none d-sm-inline">{{__('National ID')}}</span>
                                            <span class="d-inline d-sm-none">{{__('Step')}} 2</span>
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link p-3 d-flex align-items-center justify-content-center"
                                                id="pills-inter-identities-card-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#pills-inter-identities-card"
                                                type="button"
                                                role="tab"
                                                aria-controls="pills-inter-identities-card"
                                                aria-selected="false">
                                            <i class="ri-passport-line fs-5 me-2"></i>
                                            <span class="d-none d-sm-inline">{{__('International ID')}}</span>
                                            <span class="d-inline d-sm-none">{{__('Step')}} 3</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="tab-content">
                                {{-- Step 1: Personal Information --}}
                                <div class="tab-pane fade show active" id="pills-bill-info" role="tabpanel"
                                     aria-labelledby="pills-bill-info-tab">
                                    <div class="alert alert-info border-0 mb-4 d-none"
                                         id="personalInformationMessage"
                                         role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-information-line fs-4 me-3"></i>
                                            <div class="flex-grow-1">
                                                {{__('Please check form data')}}
                                            </div>
                                            <a href="{{route('account',app()->getLocale())}}"
                                               class="btn btn-sm btn-outline-info ms-3">
                                                <i class="ri-edit-line me-1"></i>
                                                {{__('Go to form data')}}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="card border mb-4">
                                        <div class="card-header bg-light border-bottom">
                                            <h6 class="mb-0 d-flex align-items-center">
                                                <i class="ri-user-line fs-5 me-2 text-primary"></i>
                                                {{__('Verify Your Information')}}
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-nowrap mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-semibold text-muted" style="width: 40%;">
                                                                <i class="ri-user-3-line me-2 text-primary"></i>
                                                                {{__('First name identificatdion modal')}}
                                                            </td>
                                                            <td class="fw-medium">
                                                                {{$usermetta_info2['enFirstName']}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-muted">
                                                                <i class="ri-user-3-line me-2 text-primary"></i>
                                                                {{__('Last name identificatdion modal')}}
                                                            </td>
                                                            <td class="fw-medium">
                                                                {{$usermetta_info2['enLastName']}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-muted">
                                                                <i class="ri-calendar-line me-2 text-primary"></i>
                                                                {{__('Date of birth identificatdion modal')}}
                                                            </td>
                                                            <td class="fw-medium">
                                                                {{$usermetta_info2['birthday']}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-muted">
                                                                <i class="ri-bank-card-2-line me-2 text-primary"></i>
                                                                {{__('National ID identificatdion modal')}}
                                                            </td>
                                                            <td class="fw-medium forceltr">
                                                                {{$usermetta_info2['nationalID']}}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-semibold text-muted">
                                                                <i class="ri-mail-line me-2 text-primary"></i>
                                                                {{__('Email identificatdion modal')}}
                                                            </td>
                                                            <td class="fw-medium forceltr">
                                                                {{$userF['email']}}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="form-check form-switch" dir="ltr">
                                                <input wire:model="notify"
                                                       type="checkbox"
                                                       class="form-check-input"
                                                       id="smsNotification"
                                                       checked>
                                                <label class="form-check-label" for="smsNotification">
                                                    <i class="ri-message-2-line me-1"></i>
                                                    {{ __('I want to receive an SMS when my identification completed successfully') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4">
                                        <button id="btn-next-identities-card"
                                                type="button"
                                                class="btn btn-primary btn-label right">
                                            {{__('Next step :  check your identities card')}}
                                            <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                {{-- Step 2: National Identity Cards --}}
                                <div class="tab-pane fade" id="pills-identities-card" role="tabpanel"
                                     aria-labelledby="pills-identities-card-tab">
                                    <div class="alert alert-info border-0 alert-border-left mb-4" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-information-line fs-4 me-3"></i>
                                            <div>
                                                <strong>{{__('Note')}}: </strong>
                                                {{__('The photo must be in PNG, JPG or JPEG format and must not exceed 8 Mb in size')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="card border shadow-sm h-100">
                                                <div class="card-header bg-light border-bottom">
                                                    <h6 class="mb-0 d-flex align-items-center">
                                                        <i class="ri-bank-card-line fs-5 me-2 text-primary"></i>
                                                        {{ __('Front ID') }}
                                                        <span class="badge bg-danger-subtle text-danger ms-2">
                                                            <i class="ri-star-fill"></i> {{__('Required')}}
                                                        </span>
                                                    </h6>
                                                </div>
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <img class="img-thumbnail shadow-sm rounded"
                                                             style="max-width: 100%; height: auto;"
                                                             src="{{asset($userNationalFrontImage)}}"
                                                             alt="{{__('Front ID')}}">
                                                    </div>
                                                    @if(!$disabled)
                                                        <div class="wrap-custom-file mt-3">
                                                            <input wire:model="photoFront"
                                                                   type="file"
                                                                   name="photoFront"
                                                                   id="photoFront"
                                                                   accept="image/png, image/jpeg"/>
                                                            <label for="photoFront">
                                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json"
                                                                           trigger="loop"
                                                                           delay="1000"
                                                                           style="width:100px;height:100px">
                                                                </lord-icon>
                                                                <span> <i class="ri-camera-fill"></i> </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card border shadow-sm h-100">
                                                <div class="card-header bg-light border-bottom">
                                                    <h6 class="mb-0 d-flex align-items-center">
                                                        <i class="ri-bank-card-line fs-5 me-2 text-primary"></i>
                                                        {{ __('Back ID') }}
                                                        <span class="badge bg-danger-subtle text-danger ms-2">
                                                            <i class="ri-star-fill"></i> {{__('Required')}}
                                                        </span>
                                                    </h6>
                                                </div>
                                                <div class="card-body text-center p-4">
                                                    <div class="mb-3">
                                                        <img class="img-thumbnail shadow-sm rounded"
                                                             style="max-width: 100%; height: auto;"
                                                             src="{{asset($userNationalBackImage)}}"
                                                             alt="{{__('Back ID')}}">
                                                    </div>
                                                    @if(!$disabled)
                                                        <div class="wrap-custom-file mt-3">
                                                            <input wire:model="photoBack"
                                                                   type="file"
                                                                   name="photoBack"
                                                                   id="photoBack"
                                                                   accept="image/png, image/jpeg"/>
                                                            <label for="photoBack">
                                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json"
                                                                           trigger="loop"
                                                                           delay="1000"
                                                                           style="width:100px;height:100px">
                                                                </lord-icon>
                                                                <span> <i class="ri-camera-fill"></i> </span>
                                                            </label>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button"
                                                class="btn btn-light btn-label"
                                                data-previous="pills-bill-info-tab">
                                            <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                            {{__('back to personal info')}}
                                        </button>
                                        <button id="btn-next-inter-identities-card"
                                                type="button"
                                                class="btn btn-primary btn-label right">
                                            {{__('Next Step: Check your International Identiies card')}}
                                            <i class="ri-arrow-right-line label-icon align-middle fs-16 ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                                {{-- Step 3: International Identity Card --}}
                                <div class="tab-pane fade" id="pills-inter-identities-card" role="tabpanel"
                                     aria-labelledby="pills-inter-identities-card-tab">
                                    <div class="alert alert-warning border-0 alert-border-left mb-4" role="alert">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-information-line fs-4 me-3"></i>
                                            <div>
                                                <strong>{{__('Note')}}: </strong>
                                                {{__('International identity is essential for non-Saudis who want to buy shares')}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card border mb-4">
                                        <div class="card-body">
                                            <div class="form-check form-switch form-switch-lg">
                                                <input class="form-check-input"
                                                       wire:model.live="internationalCard"
                                                       type="checkbox"
                                                       @if($userAuth->status==\Core\Enum\StatusRequest::ValidInternational)
                                                           disabled
                                                           title="{{__('Already validated')}}"
                                                       @endif
                                                       id="international-card">
                                                <label for="international-card" class="form-check-label fw-semibold">
                                                    <i class="ri-passport-line me-2 text-primary"></i>
                                                    {{__('I want to submit my international identitie card')}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="international-card-block" class="@if(!$internationalCard) d-none @endif">
                                        <div class="alert alert-info border-0 alert-border-left mb-4" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-information-line fs-4 me-3"></i>
                                                <div>
                                                    <strong>{{__('Note')}}: </strong>
                                                    {{__('The photo must be in PNG, JPG or JPEG format and must not exceed 8 Mb in size')}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-lg-5">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-header bg-light border-bottom">
                                                        <h6 class="mb-0 d-flex align-items-center">
                                                            <i class="ri-passport-line fs-5 me-2 text-primary"></i>
                                                            {{ __('International ID') }}
                                                            <span class="badge bg-danger-subtle text-danger ms-2">
                                                                <i class="ri-star-fill"></i> {{__('Required')}}
                                                            </span>
                                                        </h6>
                                                    </div>
                                                    <div class="card-body text-center p-4">
                                                        <div class="mb-3">
                                                            <img class="img-thumbnail shadow-sm rounded"
                                                                 style="max-width: 100%; height: auto;"
                                                                 src="{{asset($userInternationalImage)}}"
                                                                 alt="{{__('International ID')}}">
                                                        </div>
                                                        <div class="wrap-custom-file mt-3">
                                                            <input wire:model="photoInternational"
                                                                   type="file"
                                                                   name="photoInternational"
                                                                   id="photoInternational"
                                                                   accept="image/png, image/jpeg"/>
                                                            <label for="photoInternational">
                                                                <lord-icon src="https://cdn.lordicon.com/vixtkkbk.json"
                                                                           trigger="loop"
                                                                           delay="1000"
                                                                           style="width:100px;height:100px">
                                                                </lord-icon>
                                                                <span> <i class="ri-camera-fill"></i> </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-7">
                                                <div class="card border shadow-sm h-100">
                                                    <div class="card-header bg-light border-bottom">
                                                        <h6 class="mb-0 d-flex align-items-center">
                                                            <i class="ri-file-text-line fs-5 me-2 text-primary"></i>
                                                            {{__('Document Information')}}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-4">
                                                        <div class="mb-4">
                                                            <label for="internationalId" class="form-label fw-semibold">
                                                                <i class="ri-profile-line me-1 text-primary"></i>
                                                                {{ __('InternationalId ID identificatdion modal') }}
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text"
                                                                   class="form-control form-control-lg forceltr"
                                                                   minlength="5"
                                                                   maxlength="50"
                                                                   wire:model="userF.internationalID"
                                                                   id="internationalId"
                                                                   placeholder="{{__('InternationalId ID')}}">
                                                            <div class="form-text">
                                                                <i class="ri-information-line me-1"></i>
                                                                {{__('Enter your international ID number')}}
                                                            </div>
                                                        </div>

                                                        <div class="mb-0">
                                                            <label for="expiryDate" class="form-label fw-semibold">
                                                                <i class="ri-calendar-line me-1 text-primary"></i>
                                                                {{__('Expiry date identificatdion modal')}}
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input wire:model="userF.expiryDate"
                                                                   type="date"
                                                                   onkeydown="return false"
                                                                   min="{{ now()->format('Y-m-d') }}"
                                                                   class="form-control form-control-lg"
                                                                   id="expiryDate"/>
                                                            <div class="form-text">
                                                                <i class="ri-information-line me-1"></i>
                                                                {{__('Select document expiry date')}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button"
                                                class="btn btn-light btn-label"
                                                data-previous="pills-identities-card-tab">
                                            <i class="ri-arrow-left-line label-icon align-middle fs-16 me-2"></i>
                                            {{__('back to identity card')}}
                                        </button>
                                        <button id="sendIndentificationRequest"
                                                type="button"
                                                class="btn btn-success btn-label right">
                                            <div wire:loading wire:target="sendIndentificationRequest">
                                                <span class="spinner-border spinner-border-sm me-1"
                                                      role="status"
                                                      aria-hidden="true"></span>
                                            </div>
                                            <i class="ri-send-plane-fill label-icon align-middle fs-16 me-2"></i>
                                            {{__('Send identification request')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="module">
            document.addEventListener("DOMContentLoaded", function () {


                var errorMail = document.querySelector("#error-mail");
                var checkOpt = false;
                var canUseEmail = false;
                var sendEmailNotification = false;


                function doneVerify() {
                    window.location.reload();
                }

                function showIdentificationModal() {
                    $('#identificationModal').modal('show');
                }

                function hideIdentificationModal() {
                    $('#identificationModal').modal('hide');
                }


                window.addEventListener('load', () => {
                    function sendMailNotification() {
                        $("#inputEmailUser").css('border-color', 'green');
                        $.ajax({
                            method: "GET",
                            url: "/sendMailNotification",
                            async: false,
                            success: (result) => {
                                $("#inputEmailUser").css('border-color', 'green');
                                sendEmailNotification = true;
                            }
                        });
                    }

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


            });

            function checkRequiredFieldInfo(idInput) {
                if ($("#" + idInput).val().trim() === "") {
                    $("#" + idInput).css('border-color', 'red');
                    return false;
                } else {
                    $("#" + idInput).css('border-color', 'green');
                }
                return true;
            }

            function checkRequiredFieldsInfo() {
                if ($('#international-card').is(":checked")) {
                    return checkRequiredFieldInfo('internationalId') && checkRequiredFieldInfo('expiryDate');
                } else {
                    return true;
                }
            }

            function validateEmail(email) {
                return String(email)
                    .toLowerCase()
                    .match(
                        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
            }

            function sendIndentificationRequest(event) {
                if (checkRequiredFieldsInfo()) {
                    window.Livewire.dispatch('sendIndentificationRequest');
                }
            }

            $("#sendIndentificationRequest").on("click", function () {
                sendIndentificationRequest();
            });

            $('#btn-next-identities-card').click(function (e) {
                $('#myTab li:nth-child(2) button').trigger("click");
            });

            $('#btn-next-inter-identities-card').click(function (e) {
                $('#myTab li:nth-child(3) button').trigger("click");
            });

            $('#international-card').change(function () {
                if (this.checked) {
                    $('#international-card-block').removeClass("d-none");
                    $("#internationalId, #expiryDate, #photoInternational").val('');
                } else {
                    $('#international-card-block').addClass("d-none")
                }
            });

            document.getElementById('identificationModal').addEventListener('shown.bs.modal', function (event) {
                if ('{{$user->status}}' == 2 || '{{$user->status}}' == 4) {
                    $('#pills-inter-identities-card-tab').trigger('click');
                }
            });

            window.addEventListener('IdentificationRequestMissingInformation', event => {
                Swal.fire({
                    title: event.detail[0].title,
                    text: event.detail[0].text,
                    icon: 'error',
                    cancelButtonText: '{{__('Cancel')}}',
                    confirmButtonText: '{{__('Confirm')}}',
                })
            });
        </script>
    </div>
</div>
