<div class="{{getContainerType()}}">
    @section('title')
        {{ __('User Details') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('User Details') }}
        @endslot
    @endcomponent
    <div class="row mb-5">
        <div class="col-xl-4">
            <div class="card ribbon-box border shadow-none mb-lg-0 material-shadow">
                <div class="card-body">
                    <div class="ribbon ribbon-primary round-shape">[{{$user['idUser']}}]</div>
                    <h5 class="fs-14 text-end">{{__(\Core\Enum\StatusRequest::from($user->status)->name)}}</h5>
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img
                                src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                                class="  rounded-circle avatar-xl img-thumbnail user-profile-image"
                                alt="user-profile-image">
                        </div>
                        <h2>
                            {{$dispalyedUserCred}}
                        </h2>
                        <h4>
                            <span class="badge text-bg-secondary"></span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('National identities cards') }}</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td>
                                    <img class="img-thumbnail" width="150" height="100" id="front-id-image"
                                         title="{{__('Front id image')}}"
                                         src="{{asset($userNationalFrontImage)}}?={{Str::random(16)}}"
                                    >

                                </td>
                                <td>
                                    <img class="img-thumbnail" width="150" height="100" id="back-id-image"
                                         title="{{__('Back id image')}}"
                                         src="{{asset($userNationalBackImage)}}?={{Str::random(16)}}"
                                    >

                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-2 text-info">{{ __('International identity card') }}</h5>
                </div>
                <div class="card-body row">
                    <div class="col-12">
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Identity card') }}</th>
                                <td>
                                    <img class="img-thumbnail" width="150" height="100"
                                         id="international-id-image"
                                         title="{{__('International identity card')}}"
                                         src="{{asset($userInternationalImage)}}?={{Str::random(16)}}">
                                </td>
                            <tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-4">
            <div class="card border shadow-none mb-lg-0 material-shadow">
                <h5 class="card-header text-info">
                    {{__('General data')}}
                </h5>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if(!empty($user->mobile))
                            <li class="list-group-item">
                                <strong>{{__('mobile')}}</strong>
                                <span class="float-end">{{$user->mobile}}</span>
                            </li>
                        @endif
                        @if(!empty($user->email_verified))
                            <li class="list-group-item">
                                <strong>{{__('email_verified')}}</strong>
                                <span class="float-end">{{$user->email_verified}}</span>
                            </li>
                        @endif
                        @if(!empty($user->fullphone_number))
                            <li class="list-group-item">
                                <strong>{{__('fullphone_number')}}</strong>
                                <span class="float-end">{{$user->fullphone_number}}</span>
                            </li>
                        @endif
                        @if(!empty($user->iden_notif))
                            <li class="list-group-item">
                                <strong>{{__('iden_notif')}}</strong>
                                <span class="float-end">{{$user->iden_notif}}</span>
                            </li>
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
            <div class="card border shadow-none mb-lg-0 material-shadow">
                <h5 class="card-header text-info">
                    {{__('Detailed data')}}
                </h5>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if(!empty($metta->arFirstName))
                            <li class="list-group-item">
                                <strong>{{__('Arabic Firstname')}}</strong>
                                <span class="float-end">{{$metta->arFirstName}}</span>
                            </li>
                        @endif
                        @if(!empty($metta->arLastName))
                            <li class="list-group-item">
                                <strong>{{__('Arabic Lastname')}}</strong>
                                <span class="float-end">{{$metta->arLastName}}</span>
                            </li>
                        @endif
                        @if(!empty($metta->nationalID))
                            <li class="list-group-item">
                                <strong>{{__('National ID')}}</strong>
                                <span class="float-end">{{$metta->nationalID}}</span>
                            </li>
                        @endif
                        @if(!empty($metta->idLanguage))
                            <li class="list-group-item">
                                <strong>{{__('Language')}}</strong>
                                <span class="float-end">{{$metta->idLanguage}}</span>
                            </li>
                        @endif
                        @if(!empty($metta->birthday))
                            <li class="list-group-item">
                                <strong>{{__('Birth date')}}</strong>
                                <span class="float-end">{{$metta->birthday}}</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        @if($activeUser)
            <div class="col-xl-6">
                <div class="card border shadow-none mb-lg-0 material-shadow">
                    <h5 class="card-header text-info">
                        {{__('Main Balances Horizontal')}}
                    </h5>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Cash Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde($userCurrentBalanceHorisontal?->cash_balance,3)}}  {{config('app.currency')}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Balance For Shopping')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde(\App\Services\Balances\Balances::getTotalBfs($userCurrentBalanceHorisontal),3)}}  {{config('app.currency')}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Discounts Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde($userCurrentBalanceHorisontal?->discount_balance,3)}}  {{config('app.currency')}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('SMS Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde($userCurrentBalanceHorisontal?->sms_balance,3)}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Tree Balance')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde($userCurrentBalanceHorisontal?->tree_balance,3)}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Actions (Shares)')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{formatSolde($userCurrentBalanceHorisontal?->share_balance,3)}}</span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 ms-2">
                                                <h6 class="fs-14 mb-0">{{__('Chances')}}</h6>
                                                <small class="text-muted">{{now()}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                    <span
                                        class="text-danger">{{\App\Services\Balances\Balances::getTotalChance($userCurrentBalanceHorisontal)}}</span>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card border shadow-none mb-lg-0 material-shadow">
                    <h5 class="card-header text-info">
                        {{__('Main Balances Vertical')}}
                    </h5>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($userCurrentBalanceVertical as $balance)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 ms-2">
                                                    <h6 class="fs-14 mb-0">{{__(\Core\Enum\BalanceEnum::tryFrom($balance->balance_id)->name)}}</h6>
                                                    <small class="text-muted">{{$balance?->last_operation_date}}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 mx-2 ">
                                            <h5 class="fs-12 my-1 fw-normal"><span
                                                    class="badge bg-danger-subtle text-danger">{{formatSolde($balance?->previous_balance,3)}} @if($balance->balance_id<4)
                                                        {{config('app.currency')}}
                                                    @endif </span></h5>
                                            <span class="text-muted">{{__('Previous balance')}}</span>
                                        </div>
                                        <div class="flex-shrink-0 mx-2 ">
                                            <h5 class="fs-14 my-1 fw-normal"><span
                                                    class="badge bg-danger-subtle text-danger">{{formatSolde($balance?->current_balance,3)}} @if($balance->balance_id<4)
                                                        {{config('app.currency')}}
                                                    @endif</span></h5>
                                            <span class="text-muted">{{__('Current balance')}}</span>
                                        </div>

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
        @if(isset($metta->adresse))
            <div class="col-xl-4">
                <div class="card border shadow-none mb-lg-0 material-shadow">
                    <div class="card border shadow-none mb-lg-0 material-shadow">
                        <h5 class="card-header text-info">
                            {{__('Address')}}
                        </h5>
                        <div class="card-body">
                            <p class="text-muted">
                                {{$metta->adresse}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(isset($vip))
            <div class="col-xl-4">
                <div class="card border shadow-none mb-lg-0 material-shadow">
                    <div class="card border shadow-none mb-lg-0 material-shadow">
                        <h5 class="card-header text-info">
                            {{__('VIP')}} <span class="text-info badge-info float-end">{{$vipMessage}}</span>
                        </h5>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @if(!empty($vip->flashCoefficient))
                                    <li class="list-group-item">
                                        <strong>{{__('Flash coefficient')}}</strong>
                                        <span class="float-end">{{$vip->flashCoefficient}}</span>
                                    </li>
                                @endif
                                @if(!empty($vip->flashDeadline))
                                    <li class="list-group-item">
                                        <strong>{{__('Flash Deadline')}}</strong>
                                        <span class="float-end">{{$vip->flashDeadline}}</span>
                                    </li>
                                @endif

                                @if(!empty($vip->flashNote))
                                    <li class="list-group-item">
                                        <strong>{{__('Flash note')}}</strong>
                                        <span class="float-end">{{$vip->flashNote}}</span>
                                    </li>
                                @endif

                                @if(!empty($vip->flashMinAmount))
                                    <li class="list-group-item">
                                        <strong>{{__('Flash min amount')}}</strong>
                                        <span class="float-end">{{$vip->flashMinAmount}}</span>
                                    </li>
                                @endif

                                @if(!empty($vip->dateFNS))
                                    <li class="list-group-item">
                                        <strong>{{__('Date FNS')}}</strong>
                                        <span class="float-end">{{$vip->dateFNS}}</span>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
