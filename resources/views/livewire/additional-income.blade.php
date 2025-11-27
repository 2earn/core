<div class="{{getContainerType()}}">
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
        <div class="col-12">
            @include('layouts.flash-messages')
        </div>

        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/2earn.png') }}"
                             alt="2Earn Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   wire:model.live="isCommitedInvestor"
                                   wire:click="sendCommitedInvestorRequest()"
                                   id="be_committed_investor"
                                   aria-describedby="committed_investor_status"
                                   @if($isCommitedInvestorDisabled) disabled @endif>
                            <label class="form-check-label" for="be_committed_investor">
                                {{ __('Be committed Investor') }}
                            </label>
                        </div>

                        <div id="committed_investor_status">
                            @if(auth()->user()->commited_investor)
                                <div class="alert alert-success material-shadow text-center" role="status">
                                    <strong>✓</strong> {{ __('You are committed investor') }}
                                </div>
                            @else
                                @if($soldesAction >= $beCommitedInvestorMinActions)
                                    @if(is_null($lastCommittedInvestorRequest) || $lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::Rejected->value)
                                        <div class="alert alert-danger material-shadow text-center" role="alert">
                                            <p class="mb-2">{{ __('To benefit from this privilege please activate the option') }}</p>
                                            @if(!is_null($lastCommittedInvestorRequest))
                                                @if(!is_null($lastCommittedInvestorRequest?->note) || $lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::Rejected->value)
                                                    <hr>
                                                    <small class="d-block mt-2">
                                                        <strong>{{ __('Latest request rejection reason') }}:</strong>
                                                        {{ $lastCommittedInvestorRequest?->note }}
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    @endif

                                    @if($lastCommittedInvestorRequest?->status == \Core\Enum\RequestStatus::InProgress->value)
                                        <div class="alert alert-warning material-shadow text-center" role="status">
                                            <strong>⏳</strong> {{ __('Your request is currently being processed...') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-danger material-shadow text-center" role="alert">
                                        <p class="mb-2">
                                            {{ __('You must hold a minimum of') }}
                                            <strong>{{ formatSolde($beCommitedInvestorMinActions, 0) }}</strong>
                                            {{ __('shares to be considered a committed investor') }}
                                        </p>
                                        <a href="{{ route('business_hub_trading', app()->getLocale()) }}"
                                           class="btn btn-sm btn-danger">
                                            {{ __('Go to trading page, To buy more actions') }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-commited-investor.png') }}"
                             alt="Committed Investor Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/learn.png') }}"
                             alt="Learn2Earn Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="be_instructor"
                                   wire:model.live="isInstructor"
                                   wire:click="sendInstructorRequest()"
                                   aria-describedby="instructor_status"
                                   @if($isInstructorDisabled) disabled @endif>
                            <label class="form-check-label" for="be_instructor">
                                {{ __('Be Instructor') }}
                            </label>
                        </div>

                        <div id="instructor_status">
                            @if(auth()->user()->instructor == \Core\Enum\BeInstructorRequestStatus::Validated->value)
                                <div class="alert alert-success material-shadow text-center" role="status">
                                    <strong>✓</strong> {{ __('You are Instructor') }}
                                </div>
                            @endif

                            @if(auth()->user()->instructor == \Core\Enum\BeInstructorRequestStatus::Validated2earn->value)
                                <div class="alert alert-info material-shadow text-center" role="status">
                                    <strong>⏳</strong> {{ __('Waiting for Learn2earn platform validation') }}
                                </div>
                            @endif

                            @if(auth()->user()->instructor < \Core\Enum\BeInstructorRequestStatus::Validated2earn->value)
                                @if($validatedUser)
                                    @if(is_null($lastInstructorRequest) || $lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::Rejected->value)
                                        <div class="alert alert-danger material-shadow text-center" role="alert">
                                            <p class="mb-2">{{ __('To benefit from this privilege please activate the option') }}</p>
                                            @if(!is_null($lastInstructorRequest))
                                                @if(!is_null($lastInstructorRequest?->note) || $lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::Rejected->value)
                                                    <hr>
                                                    <small class="d-block mt-2">
                                                        <strong>{{ __('Latest request rejection reason') }}:</strong>
                                                        {{ $lastInstructorRequest?->note }}
                                                    </small>
                                                @endif
                                            @endif
                                        </div>
                                    @endif

                                    @if($lastInstructorRequest?->status == \Core\Enum\BeInstructorRequestStatus::InProgress->value)
                                        <div class="alert alert-warning material-shadow text-center" role="status">
                                            <strong>⏳</strong> {{ __('Your request is currently being processed...') }}
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-info material-shadow text-center" role="alert">
                                        <p class="mb-2">{{ __('You must be validated user') }}</p>
                                        <a href="{{ route('account', app()->getLocale()) }}"
                                           class="btn btn-sm btn-info">
                                            {{ __('Go to Account, To validate your account') }}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-instructor.png') }}"
                             alt="Instructor Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/move.png') }}"
                             alt="Move2Earn Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="be_phv_driver"
                                   disabled
                                   aria-describedby="phv_status">
                            <label class="form-check-label" for="be_phv_driver">
                                {{ __('Be PHV (Private Hire Vehicle)') }}
                            </label>
                        </div>

                        <div id="phv_status" class="alert alert-info material-shadow text-center" role="status">
                            <strong>🚀</strong> {{ __('Coming soon') }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-phv.png') }}"
                             alt="PHV Driver Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/belegant.png') }}"
                             alt="BeElegant Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="be_seller_belegant"
                                   disabled
                                   aria-describedby="belegant_seller_status">
                            <label class="form-check-label" for="be_seller_belegant">
                                {{ __('Be Seller') }}
                            </label>
                        </div>

                        <div id="belegant_seller_status" class="alert alert-info material-shadow text-center"
                             role="status">
                            <strong>🚀</strong> {{ __('Coming soon') }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-seller-be.png') }}"
                             alt="BeElegant Seller Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/shop.png') }}"
                             alt="Shop2Earn Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="be_seller_shop"
                                   disabled
                                   aria-describedby="shop_seller_status">
                            <label class="form-check-label" for="be_seller_shop">
                                {{ __('Be Seller') }}
                            </label>
                        </div>

                        <div id="shop_seller_status" class="alert alert-info material-shadow text-center" role="status">
                            <strong>🚀</strong> {{ __('Coming soon') }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-seller-s.png') }}"
                             alt="Shop2Earn Seller Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 card">
            <div class="card-body">
                <div class="row mt-2 align-items-center">
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mb-3 mb-md-0">
                        <img src="{{ Vite::asset('resources/images/logos/takecare.png') }}"
                             alt="TakeCare Logo"
                             class="img-fluid img-business rounded"
                             loading="lazy">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-check form-switch form-switch-lg form-switch-success text-center mb-3">
                            <input type="checkbox"
                                   class="form-check-input"
                                   id="be_seller_takecare"
                                   disabled
                                   aria-describedby="takecare_seller_status">
                            <label class="form-check-label" for="be_seller_takecare">
                                {{ __('Be Seller') }}
                            </label>
                        </div>

                        <div id="takecare_seller_status" class="alert alert-info material-shadow text-center"
                             role="status">
                            <strong>🚀</strong> {{ __('Coming soon') }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 text-center mt-3 mt-md-0">
                        <img src="{{ Vite::asset('resources/images/business-hub/be-seller-tc.png') }}"
                             alt="TakeCare Seller Badge"
                             class="img-fluid img-business-square rounded"
                             loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
