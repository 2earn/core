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
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/logos/2earn.png') }}" alt="logo2earn"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div
                        class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business w-75 m-auto">
                        <label class="form-check-label  "
                               for="be_commited_investor">{{__('Be commited Investor')}}</label>
                        <input type="checkbox" class="form-check-input" wire:model="isCommitedInvestor"
                               wire:click="sendCommitedInvestorRequest()" id="be_commited_investor"
                               @if($isCommitedInvestorDisabled) disabled @endif>
                    </div>

                    @if($soldesAction >= $beCommitedInvestorMinActions)

                        @if(is_null($lastCommittedInvestorRequest)||$lastCommittedInvestorRequest?->status == \Core\Enum\CommittedInvestorRequestStatus::Rejected->value)
                            <div class="alert alert-danger material-shadow" role="alert">
                                {{__('To benefit from this privilege please activate the option')}}
                                @if(!is_null($lastCommittedInvestorRequest))
                                    @if(!is_null($lastCommittedInvestorRequest?->note||$lastCommittedInvestorRequest?->status == \Core\Enum\CommittedInvestorRequestStatus::Rejected->value))
                                        <hr class="text-muted">
                                        <span class="mt-2 text-muted">
                                        <strong>{{__('Latest request rejection raison')}} :</strong> {{$lastCommittedInvestorRequest?->note}}
                                    </span>
                                    @endif
                                @endif
                            </div>
                        @endif

                        @if($lastCommittedInvestorRequest?->status == \Core\Enum\CommittedInvestorRequestStatus::InProgress->value)
                            <div class="alert alert-warning material-shadow" role="alert">
                                {{__('Your request is currently being processes...')}}
                            </div>
                        @endif
                        @if($lastCommittedInvestorRequest?->status == \Core\Enum\CommittedInvestorRequestStatus::Validated->value)
                            <div class="alert alert-success material-shadow" role="alert">
                                {{__('You are committed investor')}}
                            </div>
                        @endif

                    @else
                        <div class="alert alert-danger material-shadow" role="alert">
                            {{__('You must hold a minimum of')}} {{formatSolde($beCommitedInvestorMinActions,0)}} {{__('shares to be considered a committed investor')}}
                            <a href="{{route('home',app()->getLocale() )}}">{{__('Go to home, To buy more actions')}}</a>
                        </div>
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
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/logos/learn.png') }}" alt="logolearn"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div
                        class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business w-75 m-auto">
                        <label class="form-check-label  "
                               for="be_instructor">{{__('Be Instructor')}}</label>
                        <input type="checkbox" class="form-check-input" id="be_instructor" wire:model="isInstructor"
                               @if($isInstructorDisabled) disabled @endif>
                    </div>

                    <div class="alert alert-warning material-shadow  d-none" role="alert">
                        {{__('Your request is currently being processes...')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-instructor.png') }}" alt="be-instructor"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/logos/move.png') }}" alt="logomove"
                         class="d-block img-fluid img-business mx-auto rounded float-left">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div
                        class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business w-75 m-auto">
                        <label class="form-check-label  "
                               for="be_commited_investor">{{__('Be PHV (Private Hire Vehicle)')}}</label>
                        <input type="checkbox" class="form-check-input" id="be_commited_investor" disabled>
                    </div>
                    <div class="alert alert-success material-shadow d-none" role="alert">
                        {{__('Congratulation You are anow a PHV driver')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-phv.png') }}" alt="be-phv"
                         class="d-block img-fluid img-business-square mx-auto rounded float-left">
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/logos/belegant.png') }}" alt="logobelegant"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                    <img src="{{ Vite::asset('resources/images/logos/shop.png') }}" alt="logoshop"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                    <img src="{{ Vite::asset('resources/images/logos/takecare.png') }}" alt="logotakecare"
                         class="d-block img-fluid img-business mx-auto rounded float-left my-1">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div
                        class="form-check form-switch form-switch-lg  form-switch-success d-block img-fluid text-business w-75 m-auto">
                        <label class="form-check-label  "
                               for="be_commited_investor">{{__('Be Seller')}}</label>
                        <input type="checkbox" class="form-check-input" id="be_commited_investor" disabled>
                    </div>
                    <div class="alert alert-success material-shadow  d-none" role="alert">
                        {{__('Congratulation You are anow a PHV driver')}}
                    </div>
                </div>
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <img src="{{ Vite::asset('resources/images/business-hub/be-seller.png') }}" alt="be-seller"
                         class="d-block img-fluid img-business-square mx-auto rounded float-right my-1 my-auto">
                </div>
            </div>
        </div>
    </div>
</div>
