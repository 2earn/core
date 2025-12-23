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
                <!-- Search and Filter -->
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

                <!-- Cards Grid -->
                <div class="row g-3">
                    @forelse($partnerRequests as $request)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border shadow-sm">
                                <!-- Card Header with Status -->
                                <div class="card-header bg-light d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="card-title mb-1">{{ $request->company_name ?? 'N/A' }}</h6>
                                        <small class="text-muted d-block">{{ $request->user?->name }}</small>
                                        <small class="text-muted">{{ $request->user?->email }}</small>
                                    </div>
                                    <div>
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
                                </div>

                                <!-- Card Body -->
                                <div class="card-body">
                                    <!-- Business Sector -->
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted d-block">{{ __('Business Sector') }}</label>
                                        <p class="mb-0">{{ $request->businessSector?->name ?? 'N/A' }}</p>
                                    </div>

                                    <!-- Platform URL -->
                                    <div class="mb-3">
                                        <label class="small fw-bold text-muted d-block">{{ __('Platform URL') }}</label>
                                        <a href="{{ $request->platform_url }}" target="_blank" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-link"></i> {{ __('Visit Platform') }}
                                        </a>
                                    </div>

                                    <!-- Request Date -->
                                    <div>
                                        <label class="small fw-bold text-muted d-block">{{ __('Request Date') }}</label>
                                        <p class="mb-0 small">
                                            @if($request->request_date)
                                                @if(is_object($request->request_date))
                                                    {{ $request->request_date->format('Y-m-d H:i') }}
                                                @else
                                                    {{ \Carbon\Carbon::parse($request->request_date)->format('Y-m-d H:i') }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Card Footer with Action -->
                                <div class="card-footer bg-light">
                                    @if($request?->id)
                                        <a href="{{ route('requests_partner_show', ['locale'=> app()->getLocale(),'id'=>$request->id], false) }}"
                                           class="btn btn-sm btn-primary w-100">
                                            <i class="fas fa-eye"></i> {{ __('View Details') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center py-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <p class="mb-0">{{ __('No partner requests found') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-end mt-3">
                    {{ $partnerRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

