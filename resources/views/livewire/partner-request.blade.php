<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Partner Requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Partner Requests Management') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">{{ __('Partner Requests') }}</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text"
                               class="form-control"
                               wire:model.live="searchTerm"
                               placeholder="{{ __('Search by company name or user...') }}">
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" wire:model.live="statusFilter">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="1">{{ __('In Progress') }}</option>
                            <option value="2">{{ __('Validated 2earn') }}</option>
                            <option value="3">{{ __('Validated') }}</option>
                            <option value="4">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    @forelse($partnerRequests as $request)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">
                                            <strong>{{ $request->company_name ?? 'N/A' }}</strong>
                                        </h5>
                                        @if($request->status == \Core\Enum\BePartnerRequestStatus::InProgress->value)
                                            <span class="badge bg-warning">{{ __('In Progress') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Validated->value)
                                            <span class="badge bg-success">{{ __('Validated') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Validated2earn->value)
                                            <span class="badge bg-info">{{ __('Validated 2earn') }}</span>
                                        @elseif($request->status == \Core\Enum\BePartnerRequestStatus::Rejected->value)
                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                        @endif
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">{{ __('User') }}:</small>
                                        <p class="mb-0">{{ $request->user?->name }}</p>
                                        <small class="text-muted">{{ $request->user?->email }}</small>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">{{ __('Business Sector') }}:</small>
                                        <p class="mb-0">{{ $request->businessSector?->name ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-2">
                                        <small class="text-muted">{{ __('Request Date') }}:</small>
                                        <p class="mb-0">{{ $request->request_date?->format('Y-m-d H:i') ?? 'N/A' }}</p>
                                    </div>

                                    <div class="mb-3">
                                        <a href="{{ $request->platform_url }}" target="_blank" class="btn btn-sm btn-info w-100 mb-2">
                                            <i class="fas fa-link"></i> {{ __('Visit Platform') }}
                                        </a>
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('requests_partner_show', [$request->id], false) }}"
                                           class="btn btn-primary w-100">
                                            <i class="fas fa-eye"></i> {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <span>{{ __('No partner requests found') }}</span>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $partnerRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

