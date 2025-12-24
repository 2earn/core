<div class="container">
    @section('title')
        {{ __('User details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User details') }}
        @endslot
    @endcomponent

    <div class="row g-4 mb-5">

        <div class="col-xl-4">
            <article class="card ribbon-box border-0 shadow mb-lg-0 h-100">
                <div class="card-body p-4">
                    <div class="ribbon ribbon-primary round-shape">
                        <i class="ri-user-line me-1" aria-hidden="true"></i>
                        {{$user['idUser']}}
                    </div>
                    <div class="badge bg-primary-subtle text-primary position-absolute top-0 end-0 m-3 fs-6">
                        {{__(\Core\Enum\StatusRequest::from($user->status)->name)}}
                    </div>
                    <div class="text-center mt-4">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                            <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                                 class="rounded-circle avatar-xxl img-thumbnail user-profile-image shadow-sm"
                                 alt="{{__('User profile image')}}"
                                 loading="lazy">
                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-3 border-white rounded-circle"
                                  style="width: 1.5rem; height: 1.5rem;"
                                  title="{{__('Active')}}"></span>
                        </div>
                        <h2 class="mb-1 fw-bold text-dark">
                            {{$dispalyedUserCred}}
                        </h2>
                        <p class="text-muted mb-0">
                            <i class="ri-shield-user-line me-1" aria-hidden="true"></i>
                            {{__('Member')}}
                        </p>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4">
            <article class="card border-0 shadow h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                        <i class="ri-bank-card-line me-2 fs-5" aria-hidden="true"></i>
                        {{ __('National identities cards') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border rounded overflow-hidden shadow-sm position-relative">
                                <img class="img-fluid w-100"
                                     style="height: 150px; object-fit: cover;"
                                     id="front-id-image"
                                     alt="{{__('Front id image')}}"
                                     title="{{__('Front id image')}}"
                                     src="{{asset($userNationalFrontImage)}}?={{Str::random(16)}}"
                                     loading="lazy">
                                <span class="position-absolute top-0 start-0 m-2 badge bg-primary">
                                    <i class="ri-file-text-line me-1" aria-hidden="true"></i>
                                    {{__('Front')}}
                                </span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded overflow-hidden shadow-sm position-relative">
                                <img class="img-fluid w-100"
                                     style="height: 150px; object-fit: cover;"
                                     id="back-id-image"
                                     alt="{{__('Back id image')}}"
                                     title="{{__('Back id image')}}"
                                     src="{{asset($userNationalBackImage)}}?={{Str::random(16)}}"
                                     loading="lazy">
                                <span class="position-absolute top-0 start-0 m-2 badge bg-secondary">
                                    <i class="ri-file-text-line me-1" aria-hidden="true"></i>
                                    {{__('Back')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4">
            <article class="card border-0 shadow h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                        <i class="ri-passport-line me-2 fs-5" aria-hidden="true"></i>
                        {{ __('International identity card') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="border rounded overflow-hidden shadow-sm position-relative">
                        <img class="img-fluid w-100"
                             style="height: 200px; object-fit: cover;"
                             id="international-id-image"
                             alt="{{__('International identity card')}}"
                             title="{{__('International identity card')}}"
                             src="{{asset($userInternationalImage)}}?={{Str::random(16)}}"
                             loading="lazy">
                        <span class="position-absolute top-0 start-0 m-2 badge bg-success">
                            <i class="ri-global-line me-1" aria-hidden="true"></i>
                            {{__('International')}}
                        </span>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-xl-4">
            <article class="card border-0 shadow mb-lg-0 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                        <i class="ri-information-line me-2 fs-5" aria-hidden="true"></i>
                        {{__('General data')}}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="vstack gap-2">
                        @if(!empty($user->mobile))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-phone-line text-primary me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('mobile')}}</strong>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">{{$user->mobile}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($user->email_verified))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-mail-check-line text-success me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('email_verified')}}</strong>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">{{$user->email_verified}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($user->fullphone_number))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-smartphone-line text-info me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('fullphone_number')}}</strong>
                                        </div>
                                        <span class="badge bg-info-subtle text-info">{{$user->fullphone_number}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($user->iden_notif))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-notification-line text-warning me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('iden_notif')}}</strong>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">{{$user->iden_notif}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($user->OptActivation))
                            <li class="list-group-item">
                                <strong>{{__('OPT Activation code')}}</strong>
                                <span class="float-end">{{$user->OptActivation}}</span>
                            </li>
                        @endif
                        @if(!empty($user->Upline))
                            <li class="list-group-item">
                                <strong>{{__('Upline')}}</strong>
                                <span class="float-end">{{$user->Upline}}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <article class="card border-0 shadow mb-lg-0 h-100">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                        <i class="ri-file-list-3-line me-2 fs-5" aria-hidden="true"></i>
                        {{__('Detailed data')}}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="vstack gap-2">
                        @if(!empty($metta->arFirstName))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-user-line text-primary me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('Arabic Firstname')}}</strong>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">{{$metta->arFirstName}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($metta->arLastName))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-user-3-line text-success me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('Arabic Lastname')}}</strong>
                                        </div>
                                        <span class="badge bg-success-subtle text-success">{{$metta->arLastName}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($metta->nationalID))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-shield-user-line text-info me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('National ID')}}</strong>
                                        </div>
                                        <span class="badge bg-info-subtle text-info">{{$metta->nationalID}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($metta->idLanguage))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-global-line text-warning me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('Language')}}</strong>
                                        </div>
                                        <span class="badge bg-warning-subtle text-warning">{{$metta->idLanguage}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($metta->birthday))
                            <div class="card border-0 bg-light">
                                <div class="card-body py-2 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <i class="ri-cake-2-line text-danger me-2" aria-hidden="true"></i>
                                            <strong class="text-muted small">{{__('Birth date')}}</strong>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger">{{$metta->birthday}}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </div>
    <div class="row g-4 mb-5">
        @if($activeUser)

            <div class="col-xl-6">
                <article class="card border-0 shadow mb-lg-0 h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                            <i class="ri-money-dollar-circle-line me-2 fs-5" aria-hidden="true"></i>
                            {{__('Main Balances Horizontal')}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="vstack gap-2">
                            <div class="card border-0 bg-primary-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-primary rounded fs-4">
                                                    <i class="ri-wallet-3-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-primary">{{__('Cash Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-primary">{{formatSolde($userCurrentBalanceHorisontal?->cash_balance,3)}}</h5>
                                            <small class="text-muted">{{config('app.currency')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-success-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-success rounded fs-4">
                                                    <i class="ri-shopping-cart-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-success">{{__('Balance For Shopping')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-success">{{formatSolde(\App\Services\Balances\Balances::getTotalBfs($userCurrentBalanceHorisontal),3)}}</h5>
                                            <small class="text-muted">{{config('app.currency')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-warning-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-warning rounded fs-4">
                                                    <i class="ri-percent-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-warning">{{__('Discounts Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-warning">{{formatSolde($userCurrentBalanceHorisontal?->discount_balance,3)}}</h5>
                                            <small class="text-muted">{{config('app.currency')}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-info-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-info rounded fs-4">
                                                    <i class="ri-message-3-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-info">{{__('SMS Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-info">{{formatSolde($userCurrentBalanceHorisontal?->sms_balance,3)}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-secondary-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-secondary rounded fs-4">
                                                    <i class="ri-plant-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-secondary">{{__('Tree Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-secondary">{{formatSolde($userCurrentBalanceHorisontal?->tree_balance,3)}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-danger-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-danger rounded fs-4">
                                                    <i class="ri-line-chart-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-danger">{{__('Actions (Shares)')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-danger">{{formatSolde($userCurrentBalanceHorisontal?->share_balance,3)}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 bg-dark-subtle">
                                <div class="card-body py-3 px-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm flex-shrink-0 me-3">
                                                <div class="avatar-title bg-dark rounded fs-4">
                                                    <i class="ri-trophy-line" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-semibold text-dark">{{__('Chances')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-dark">{{\App\Services\Balances\Balances::getTotalChance($userCurrentBalanceHorisontal)}}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-xl-6">
                <article class="card border-0 shadow mb-lg-0 h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                            <i class="ri-bar-chart-box-line me-2 fs-5" aria-hidden="true"></i>
                            {{__('Main Balances Vertical')}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="vstack gap-2">
                            @foreach($userCurrentBalanceVertical as $balance)
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-funds-line text-primary me-2" aria-hidden="true"></i>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">{{__(\Core\Enum\BalanceEnum::tryFrom($balance->balance_id)->name)}}</h6>
                                                    <small class="text-muted">{{$balance?->last_operation_date}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-3 justify-content-end">
                                            <div class="text-center">
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{formatSolde($balance?->previous_balance,3)}}
                                                    @if($balance->balance_id < 4) {{config('app.currency')}} @endif
                                                </span>
                                                <small class="d-block text-muted mt-1">{{__('Previous balance')}}</small>
                                            </div>
                                            <div class="text-center">
                                                <span class="badge bg-success-subtle text-success">
                                                    {{formatSolde($balance?->current_balance,3)}}
                                                    @if($balance->balance_id < 4) {{config('app.currency')}} @endif
                                                </span>
                                                <small class="d-block text-muted mt-1">{{__('Current balance')}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </div>
        @endif
        @if(isset($metta->adresse))

            <div class="col-xl-4">
                <article class="card border-0 shadow mb-lg-0 h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h3 class="card-title mb-0 h5 text-info d-flex align-items-center">
                            <i class="ri-map-pin-line me-2 fs-5" aria-hidden="true"></i>
                            {{__('Address')}}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0 d-flex align-items-start" role="status">
                            <i class="ri-home-line me-2 fs-5 flex-shrink-0" aria-hidden="true"></i>
                            <p class="mb-0">{{$metta->adresse}}</p>
                        </div>
                    </div>
                </article>
            </div>
        @endif

        @if(isset($vip))
            <div class="col-xl-4">
                <article class="card border-0 shadow mb-lg-0 h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h3 class="card-title mb-0 h5 text-info d-flex align-items-center justify-content-between">
                            <span class="d-flex align-items-center">
                                <i class="ri-vip-crown-fill me-2 fs-5" aria-hidden="true"></i>
                                {{__('VIP')}}
                            </span>
                            <span class="badge bg-info-subtle text-info">{{$vipMessage}}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="vstack gap-2">
                            @if(!empty($vip->flashCoefficient))
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-percent-line text-primary me-2" aria-hidden="true"></i>
                                                <strong class="text-muted small">{{__('Flash coefficient')}}</strong>
                                            </div>
                                            <span class="badge bg-primary-subtle text-primary">{{$vip->flashCoefficient}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($vip->flashDeadline))
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-calendar-check-line text-success me-2" aria-hidden="true"></i>
                                                <strong class="text-muted small">{{__('Flash Deadline')}}</strong>
                                            </div>
                                            <span class="badge bg-success-subtle text-success">{{$vip->flashDeadline}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($vip->flashNote))
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-start justify-content-between">
                                            <div class="d-flex align-items-center flex-shrink-0">
                                                <i class="ri-sticky-note-line text-warning me-2" aria-hidden="true"></i>
                                                <strong class="text-muted small">{{__('Flash note')}}</strong>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning text-end flex-grow-1 ms-2 text-wrap">{{$vip->flashNote}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($vip->flashMinAmount))
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-money-dollar-circle-line text-info me-2" aria-hidden="true"></i>
                                                <strong class="text-muted small">{{__('Flash min amount')}}</strong>
                                            </div>
                                            <span class="badge bg-info-subtle text-info">{{$vip->flashMinAmount}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($vip->dateFNS))
                                <div class="card border-0 bg-light">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <i class="ri-calendar-line text-danger me-2" aria-hidden="true"></i>
                                                <strong class="text-muted small">{{__('Date FNS')}}</strong>
                                            </div>
                                            <span class="badge bg-danger-subtle text-danger">{{$vip->dateFNS}}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            </div>
        @endif
    </div>
</div>
