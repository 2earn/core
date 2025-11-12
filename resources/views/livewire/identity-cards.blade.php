<div class="row">
    <div class="col mx-1 card shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <div class="d-flex align-items-center">
                <i class="ri-bank-card-line fs-4 text-info me-2"></i>
                <h5 class="card-title mb-0 text-info">{{ __('National identities cards') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12">
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-id-card-line me-2 text-primary"></i>{{ __('Front ID') }}
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    data-toggle="modal"
                                    id="show-identity-front"
                                    data-target=".bd-example-modal-lg"
                                    aria-label="{{ __('View full size front ID') }}">
                                <i class="ri-eye-line me-1"></i>{{__('Show Identity')}}
                            </button>
                        </div>
                        <div class="text-center">
                            <img class="img-thumbnail shadow-sm" width="200" height="auto" id="front-id-image"
                                 title="{{__('Front id image')}}"
                                 src="{{asset($userNationalFrontImage)}}?={{Str::random(16)}}"
                                 alt="{{__('Front ID card')}}">
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="ri-id-card-line me-2 text-primary"></i>{{ __('Back ID') }}
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-primary"
                                    data-toggle="modal"
                                    id="show-identity-back"
                                    data-target=".bd-example-modal-lg"
                                    aria-label="{{ __('View full size back ID') }}">
                                <i class="ri-eye-line me-1"></i>{{__('Show Identity')}}
                            </button>
                        </div>
                        <div class="text-center">
                            <img class="img-thumbnail shadow-sm" width="200" height="auto" id="back-id-image"
                                 title="{{__('Back id image')}}"
                                 src="{{asset($userNationalBackImage)}}?={{Str::random(16)}}"
                                 alt="{{__('Back ID card')}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col mx-1 card shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="ri-passport-line fs-4 text-info me-2"></i>
                    <h5 class="card-title mb-0 text-info">{{ __('International identity card') }}</h5>
                </div>
            </div>
            @if($user->status == 2 && $justExpired)
                <div class="alert alert-danger d-inline-flex align-items-center mt-3 mb-0" role="alert">
                    <i class="ri-error-warning-line fs-5 me-2"></i>
                    <div>
                        <strong>{{__('Your International identity is expired')}}</strong>
                        <button type="button" id="soonExpireIIC" class="btn btn-sm btn-danger ms-2">
                            {{__('Update Now')}}
                        </button>
                    </div>
                </div>
            @elseif($user->status == 4 && $lessThanSixMonths)
                <div class="alert alert-warning d-inline-flex align-items-center mt-3 mb-0" role="alert">
                    <i class="ri-alarm-warning-line fs-5 me-2"></i>
                    <div>
                        <strong>{{__('Your International identity will soon expire')}}</strong>
                        <button type="button" id="soonExpireIIC" class="btn btn-sm btn-warning ms-2">
                            {{__('Renew Now')}}
                        </button>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-body">
            <div class="col-12">
                @if($user->internationalID)
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="ri-passport-line me-2 text-primary"></i>{{ __('Identity card') }}
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-toggle="modal"
                                            id="show-identity-international"
                                            data-target=".bd-example-modal-lg"
                                            aria-label="{{ __('View full size international ID') }}">
                                        <i class="ri-eye-line me-1"></i>{{__('Show Identity')}}
                                    </button>
                                </div>
                                <div class="text-center">
                                    <img class="img-thumbnail shadow-sm" width="200" height="auto"
                                         id="international-id-image"
                                         title="{{__('International identity card')}}"
                                         src="{{asset($userInternationalImage)}}?={{Str::random(16)}}"
                                         alt="{{__('International identity card')}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border bg-light mb-0">
                                <div class="card-body p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="ri-profile-line fs-5 text-primary me-2 mt-1"></i>
                                                <div>
                                            <label class="text-muted small mb-1">{{__('InternationalId ID identificatdion modal')}}</label>
                                                    <p class="mb-0 fw-semibold">{{$user->internationalID}}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="ri-calendar-line fs-5 text-primary me-2 mt-1"></i>
                                                <div>
                                                    <label class="text-muted small mb-1">{{__('Expiry date identificatdion modal')}}</label>
                                                    <p class="mb-0 fw-semibold">{{$user->expiryDate}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <i class="ri-alert-line fs-3 align-middle"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">
                                    <i class="ri-information-line me-1"></i>{{__('No international identities data information')}}
                                </h5>
                                <p class="mb-3">{{__('Please log in to benefit from many advantages')}}</p>
                                <button class="btn btn-warning" id="goToIdentification">
                                    <i class="ri-arrow-right-line me-1"></i>{{__('Open identification tab')}}
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
