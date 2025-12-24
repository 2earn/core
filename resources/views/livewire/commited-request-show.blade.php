<div class="container">
    @section('title')
        {{ __('Committed investor request examination') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Committed investor request examination') }}
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-header">
            {{getUserDisplayedName($commitedInvestorsRequest->user->idUser)}}
        </div>
        <div class="card-body">
            <h6 class="card-title mt-2">{{__('Details')}}</h6>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-4">
                    <img src="{{ URL::asset($userProfileImage) }}?={{Str::random(16)}}"
                         class="rounded-circle avatar-xl img-thumbnail user-profile-image"
                         alt="user-profile-image">
                </div>
                <div class="col-sm-6 col-md-6 col-lg-8">
                    <ul class="list-group">
                        @if($commitedInvestorsRequest->user->email)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>{{__('Email')}}</strong><span
                                    class="text-muted float-end">{{$commitedInvestorsRequest->user->email}}</span>
                            </li>
                        @endif
                        @if($commitedInvestorsRequest->user->idUser)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>{{__('Id User')}}</strong><span
                                    class="text-muted float-end">{{$commitedInvestorsRequest->user->idUser}}</span>
                            </li>
                        @endif
                        @if($commitedInvestorsRequest->user->name)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>{{__('Name')}}</strong><span
                                    class="text-muted float-end">{{$commitedInvestorsRequest->user->name}}</span>
                            </li>
                        @endif
                    </ul>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 mt-3">
                    <p class="card-text text-muted float-end">{{$commitedInvestorsRequest->request_date}}</p>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 mt-3">
                    <a href="{{route('user_details', ['locale' => app()->getLocale(), 'idUser' => $commitedInvestorsRequest->user_id]) }}"
                       class=" float-end" target="_blank">{{__('More Details')}}</a>
                </div>
            </div>

            <h6 class="card-title mt-2">{{__('History')}}</h6>
            <div class="flex-grow-1 ms-2">
                <ul class="list-group">
                    @forelse($commitedInvestorsRequests as $commitedRequest)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{$commitedRequest->request_date}} <span
                                class="badge {{$commitedRequest->status==\Core\Enum\RequestStatus::Rejected->value?'bg-warning':'bg-success'}} ">{{__(\Core\Enum\RequestStatus::tryFrom($commitedRequest->status)->name)}}</span>
                        </li>
                    @empty
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="card-footer">

            @if(!$rejectOpened)
                <button type="button" class="btn btn-soft-success float-end mx-2"
                        wire:click="validateRequest()">{{__('Validate')}}</button>
                <button type="button" class="btn btn-soft-danger float-end mx-2"
                        wire:click="initRejectRequest()">{{__('Reject')}}</button>

            @else
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <h6>{{__('Add reject raison')}}</h6>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                    <textarea class="form-control" maxlength="190" wire:model.live="note" id="note" rows="3">
                    </textarea>
                        @if(!empty($note_message))
                            <div class="alert alert-warning alert-borderless mt-2" role="alert">
                                {{__($note_message)}}
                            </div>
                        @endif
                    </div>
                    <div class="col-sm-12 col-md-3 col-lg-3 ">
                        <button wire:click="rejectRequest()" class="btn btn-soft-danger mt-2">
                            {{__('Reject')}}
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
