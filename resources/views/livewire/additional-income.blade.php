<div>
    @section('title')
        {{ __('Additional income') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Additional income') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 mt-2 mb-2">
            @include('layouts.flash-messages')
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/2earn.png') }}" alt="logo 2earn"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-center mx-auto">
                        <input type="checkbox" class="form-check-input" wire:model="isCommitedInvestor"
                               wire:click="sendCommitedInvestorRequest()" id="be_commited_investor"
                               @if($isCommitedInvestorDisabled) disabled @endif>
                        <label class="form-check-label"
                               for="be_commited_investor">{{__('Be commited Investor')}}</label>

                    </div>
                    <br>
                    @if(auth()->user()->commited_investor)
                        <div class="alert alert-success material-shadow text-center" role="alert">
                            {{__('You are committed investor')}}
                        </div>
                    @else
                        @if($soldesAction >= $beCommitedInvestorMinActions)
                            @if(is_null($lastCommittedInvestorRequest)||$lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::Rejected->value)
                                <div class="alert alert-danger material-shadow text-center" role="alert">
                                    {{__('To benefit from this privilege please activate the option')}}
                                    @if(!is_null($lastCommittedInvestorRequest))
                                        @if(!is_null($lastCommittedInvestorRequest?->note||$lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::Rejected->value))
                                            <hr class="text-muted">
                                            <span class="mt-2 text-muted">
                                        <strong>{{__('Latest request rejection raison')}} :</strong> {{$lastCommittedInvestorRequest?->note}}
                                    </span>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            @if($lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::InProgress->value)
                                <div class="alert alert-warning material-shadow text-center" role="alert">
                                    {{__('Your request is currently being processes...')}}
                                </div>
                            @endif
                        @else
                            <div class="alert alert-danger material-shadow text-center" role="alert">
                                {{__('You must hold a minimum of')}} {{formatSolde($beCommitedInvestorMinActions,0)}} {{__('shares to be considered a committed investor')}}
                                <a href="{{route('home',app()->getLocale() )}}">{{__('Go to home, To buy more actions')}}</a>
                            </div>
                        @endif
                    @endif

                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-commited-investor.png') }}"
                         alt="be-commited-investor"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/learn.png') }}" alt="logo learn"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch form-switch-lg form-switch-success d-block img-fluid  text-center mx-auto">

                        <input type="checkbox" class="form-check-input" id="be_instructor" wire:model="isInstructor"
                               wire:click="sendInstructorRequest()" @if($isInstructorDisabled) disabled @endif>
                        <label class="form-check-label"
                               for="be_instructor">{{__('Be Instructor')}}</label>
                    </div>
                    <br>

                    @if(auth()->user()->instructor==\Core\Enum\BeInstructorRequestStatus::Validated->value)
                        <div class="alert alert-success material-shadow text-center" role="alert">
                            {{__('You are Instructor')}}
                        </div>
                    @endif

                    @if(auth()->user()->instructor==\Core\Enum\BeInstructorRequestStatus::Validated2earn->value)
                        <div class="alert alert-info material-shadow text-center" role="alert">
                            {{__('Waiting for Learn2earn platform validation')}}
                        </div>
                    @endif

                    @if(auth()->user()->instructor<\Core\Enum\BeInstructorRequestStatus::Validated2earn->value)
                        @if($validatedUser)
                            @if(is_null($lastInstructorRequest)||$lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::Rejected->value)
                                <div class="alert alert-danger material-shadow  text-center" role="alert">
                                    {{__('To benefit from this privilege please activate the option')}}

                                    @if(!is_null($lastInstructorRequest))
                                        @if(!is_null($lastInstructorRequest?->note||$lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::Rejected->value))
                                            <hr class="text-muted">
                                            <span class="mt-2 text-muted">
                                        <strong>{{__('Latest request rejection raison')}} :</strong> {{$lastInstructorRequest?->note}}
                                    </span>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            @if($lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::InProgress->value)
                                <div class="alert alert-warning material-shadow text-center" role="alert">
                                    {{__('Your request is currently being processes...')}}
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info material-shadow text-center" role="alert">
                                {{__('You must be validated user')}}
                                <hr>
                                <a href="{{route('account',app()->getLocale() )}}">{{__('Go to Account, To validate your account')}}</a>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-instructor.png') }}" alt="be-instructor"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/move.png') }}" alt="logo move"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch  form-switch-lg form-switch-success d-block img-fluid  text-center mx-auto">

                        <input type="checkbox" class="form-check-input" id="be_PHV_driver" disabled>
                        <label class="form-check-label"
                               for="be_PHV_driver">{{__('Be PHV (Private Hire Vehicle)')}}</label>

                    </div>
                    <br>
                    <div class="alert alert-info material-shadow  text-center" role="alert">
                        {{__('Comming soon')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-phv.png') }}" alt="be-phv"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/belegant.png') }}" alt="logo belegant"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch  form-switch-lg form-switch-success d-block img-fluid  text-center mx-auto">
                        <input type="checkbox" class="form-check-input" id="be_seller" disabled>
                        <label class="form-check-label"
                               for="be_seller">{{__('Be Seller')}}</label>
                    </div>
                    <br>
                    <div class="alert alert-info material-shadow  text-center" role="alert">
                        {{__('Comming soon')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-seller-be.png') }}" alt="be-seller"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/shop.png') }}" alt="logo shop"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch  form-switch-lg form-switch-success d-block img-fluid  text-center mx-auto">
                        <input type="checkbox" class="form-check-input" id="be_seller" disabled>
                        <label class="form-check-label"
                               for="be_seller">{{__('Be Seller')}}</label>
                    </div>
                    <br>
                    <div class="alert alert-info material-shadow  text-center" role="alert">
                        {{__('Comming soon')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-seller-s.png') }}" alt="be-seller"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3 align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/logos/takecare.png') }}" alt="logo takecare"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6 my-2">
                    <div
                        class="form-check form-switch  form-switch-lg form-switch-success d-block img-fluid  text-center mx-auto">
                        <input type="checkbox" class="form-check-input" id="be_seller" disabled>
                        <label class="form-check-label"
                               for="be_seller">{{__('Be Seller')}}</label>
                    </div>
                    <br>
                    <div class="alert alert-info material-shadow  text-center" role="alert">
                        {{__('Comming soon')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3 d-flex align-items-center justify-content-center">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-seller-tc.png') }}" alt="be-seller"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
</div>
