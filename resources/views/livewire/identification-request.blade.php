<div class="container">
    @section('title')
        {{ __('Identification requests') }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')
            {{ __('Pages') }}
        @endslot
        @slot('title')
            {{ __('Identification requests') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        @forelse($identificationRequests as $identificationRequest)
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            {{ $identificationRequest->enName }}
                        </h5>
                        <span title="{{ __('Status') }}: {{ $identificationRequest->status }}">
                            @if($identificationRequest->status == 1)
                                <span class="badge bg-info">{{ __('National') }}</span>
                            @elseif($identificationRequest->status == 5)
                                <span class="badge bg-info">{{ __('International') }}</span>
                            @elseif($identificationRequest->status == 6)
                                <span class="badge bg-info">{{ __('Global') }}</span>
                            @else
                                <span class="badge bg-warning">{{ __('Old data') }}</span>
                            @endif
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="d-flex mb-4 align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset(\App\Models\User::getUserProfileImage($identificationRequest->idUser)) }}"
                                     class="avatar-sm rounded-circle"
                                     alt="{{ $identificationRequest->enName }}"
                                     loading="lazy">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $identificationRequest->fullphone_number }}</h6>
                                <p class="text-muted mb-0 small">{{ $identificationRequest->nationalID }}</p>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="ri-calendar-line me-1"></i>
                                {{ $identificationRequest->DateCreation }}
                            </small>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent">
                        <a href="{{ route('validate_account', ['locale' => app()->getLocale(), 'paramIdUser' => $identificationRequest->id]) }}"
                           class="btn btn-primary w-100"
                           aria-label="{{ __('See Details') }} - {{ $identificationRequest->enName }}">
                            <i class="ri-file-list-3-line me-1"></i>
                            {{ __('See Details') }}
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info material-shadow d-flex align-items-center" role="alert">
                    <i class="ri-information-line me-2 fs-4"></i>
                    <div>
                        <strong>{{ __('No identification request found') }}</strong>
                        <p class="mb-0 mt-1 small">{{ __('There are currently no pending identification requests to review.') }}</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
