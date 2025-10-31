<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Commited investors requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Commited investors requests') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        @forelse($commitedInvestorsRequests as $commitedRequest)
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 position-relative">
                                <img src="{{\App\Models\User::getUserProfileImage($commitedRequest->user->idUser)}}"
                                     alt="{{getUserDisplayedName($commitedRequest->user->idUser)}}"
                                     class="avatar-lg rounded-circle border border-3 border-light shadow-sm"
                                     onerror="this.src='{{asset('images/users/default-avatar.png')}}'"/>
                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle">
                                    <span class="visually-hidden">{{__('Active')}}</span>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="card-title mb-1 fw-semibold">
                                    {{getUserDisplayedName($commitedRequest->user->idUser)}}
                                </h5>
                                <p class="text-muted mb-0 small">
                                    <i class="ri-user-line me-1"></i>{{__('Investor')}}
                                </p>
                            </div>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="ri-phone-line text-primary me-2"></i>
                                <span class="text-muted small">{{__('Phone')}}</span>
                                <span class="ms-auto fw-medium">{{$commitedRequest->fullphone_number}}</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="ri-calendar-line text-primary me-2"></i>
                                <span class="text-muted small">{{__('Request Date')}}</span>
                                <span class="ms-auto fw-medium">{{$commitedRequest->request_date}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-grid">
                            <a href="{{route('requests_commited_investors_show', ['locale' => app()->getLocale(), 'id' => $commitedRequest->id]) }}"
                               class="btn btn-primary btn-sm waves-effect waves-light">
                                <i class="ri-eye-line me-1 align-bottom"></i>
                                {{__('See Details')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="ri-inbox-line display-4 text-muted"></i>
                        </div>
                        <h5 class="mb-2">{{__('No Commited investors requests found')}}</h5>
                        <p class="text-muted mb-0">{{__('There are no committed investor requests at the moment.')}}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
