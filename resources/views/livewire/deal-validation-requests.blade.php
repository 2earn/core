<div class="{{getContainerType()}}">
    @if($currentRouteName=="deals_validation_requests")
        @section('title')
            {{ __('Deal Validation Requests') }}
        @endsection
        @component('components.breadcrumb')
            @slot('li_1')
                <a href="{{route('deals_index', app()->getLocale())}}">{{ __('Deals') }}</a>
            @endslot
            @slot('title')
                {{ __('Validation Requests') }}
            @endslot
        @endcomponent

        <div class="row">
            @include('layouts.flash-messages')
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-12 card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="position-relative">
                            <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-3 text-muted fs-5"></i>
                            <input type="text"
                                   class="form-control form-control ps-5 pe-3 border-0 bg-light"
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="{{__('Search by deal name, requester name or email...')}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <select class="form-select border-0 bg-light" wire:model.live="statusFilter">
                            <option value="all">{{__('All Statuses')}}</option>
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="approved">{{__('Approved')}}</option>
                            <option value="rejected">{{__('Rejected')}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($requests as $request)
            <div class="col-12 card border-0 shadow-sm hover-shadow">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <!-- Deal Info -->
                        <div class="col-lg-4 col-md-4 mb-3 mb-md-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-md me-3">
                                    <div class="avatar-title rounded bg-soft-primary text-primary fs-4">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{$request->deal->name ?? 'N/A'}}</h6>
                                    <span class="badge badge-soft-secondary">ID: {{$request->deal_id}}</span>
                                    @if($request->deal?->platform)
                                        <div class="mt-1">
                                            <small class="text-muted">
                                                <i class="fas fa-desktop me-1"></i>
                                                {{$request->deal->platform->name}}
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Requester Info -->
                        <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                            <div>
                                <p class="text-muted mb-1 small">{{__('Requested By')}}</p>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <div
                                            class="fw-semibold">{{getUserDisplayedName($request->requestedBy->idUser) ?? 'N/A'}}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Date -->
                        <div class="col-lg-2 col-md-4 mb-3 mb-md-0 text-center">
                            <div>
                                @if($request->status === 'pending')
                                    <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2">
                                            <i class="ri-time-line align-middle me-1"></i>{{__('Pending')}}
                                        </span>
                                @elseif($request->status === 'approved')
                                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                            <i class="ri-checkbox-circle-line align-middle me-1"></i>{{__('Approved')}}
                                        </span>
                                @elseif($request->status === 'rejected')
                                    <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                                            <i class="ri-close-circle-line align-middle me-1"></i>{{__('Rejected')}}
                                        </span>
                                @endif
                                <p class="text-muted mb-0 mt-2 small">
                                    <i class="ri-calendar-line me-1"></i>
                                    {{$request->created_at->format('M d, Y')}}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-lg-3 col-md-12 text-end">
                            @if($request->status === 'pending')
                                <div class="d-flex gap-2 justify-content-end">
                                    <button wire:click="openApproveModal({{$request->id}})"
                                            class="btn btn-success btn-sm">
                                        <i class="ri-check-line align-middle me-1"></i>{{__('Approve')}}
                                    </button>
                                    <button wire:click="openRejectModal({{$request->id}})"
                                            class="btn btn-danger btn-sm">
                                        <i class="ri-close-line align-middle me-1"></i>{{__('Reject')}}
                                    </button>
                                </div>
                            @else
                                <div class="text-muted small">
                                    <i class="ri-information-line me-1"></i>{{__('Request processed')}}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="row mt-3 pt-3 border-top">
                        <div class="col-md-4">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>{{__('Type')}}:</strong>
                                <span class="badge bg-info-subtle text-info">
                                        {{__(\Core\Enum\DealTypeEnum::from($request->deal->type)->name)}}
                                    </span>
                            </small>
                        </div>
                        <div class="col-md-4 text-center">
                            <small class="text-muted">
                                <i class="fas fa-circle-notch me-1"></i>
                                <strong>{{__('Status')}}:</strong>
                                <span class="badge bg-primary-subtle text-primary">
                                        {{__(strtoupper(\Core\Enum\DealStatus::from($request->deal->status)->name))}}
                                    </span>
                            </small>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <small class="text-muted">
                                <i class="ri-time-line me-1"></i>
                                <strong>{{__('Request ID')}}:</strong> #{{$request->id}}
                            </small>
                        </div>
                        @if($request->notes)
                            <div class="col-12 mt-3">
                                <div class="alert alert-info mb-0" role="alert">
                                    <strong><i class="ri-information-line me-1"></i>{{__('Notes')}}:</strong>
                                    <p class="mb-0 mt-2">{{$request->notes}}</p>
                                </div>
                            </div>
                        @endif
                        @if($request->status === 'rejected' && $request->rejection_reason)
                            <div class="col-12 mt-3">
                                <div class="alert alert-danger mb-0" role="alert">
                                    <strong><i class="ri-error-warning-line me-1"></i>{{__('Rejection Reason')}}
                                        :</strong>
                                    <p class="mb-0 mt-2">{{$request->rejection_reason}}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="avatar-xl mx-auto mb-4">
                        <div class="avatar-title bg-soft-info text-info rounded-circle">
                            <i class="ri-file-list-line display-4"></i>
                        </div>
                    </div>
                    <h4 class="mb-2">{{__('No requests found')}}</h4>
                    <p class="text-muted mb-4">
                        @if($search)
                            {{__('No requests match your search criteria')}}
                        @else
                            {{__('There are no deal validation requests at the moment')}}
                        @endif
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    @if($requests->hasPages())
        <div class="row mt-2">
            <div class="col-12 card border-0 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            <i class="ri-file-list-line me-1"></i>
                            {{__('Showing')}}
                            <span class="fw-semibold text-dark">{{$requests->firstItem() ?? 0}}</span>
                            {{__('to')}}
                            <span class="fw-semibold text-dark">{{$requests->lastItem() ?? 0}}</span>
                            {{__('of')}}
                            <span class="fw-semibold text-dark">{{$requests->total()}}</span>
                            {{__('results')}}
                        </div>
                        <div>
                            {{$requests->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($showApproveModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-checkbox-circle-line me-2"></i>{{__('Approve Deal Validation')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeApproveModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            <i class="ri-information-line me-2"></i>
                            {{__('Are you sure you want to approve this deal validation request?')}}
                        </div>
                        <p class="text-muted mb-0">
                            {{__('This action will validate the deal and mark it as approved. This cannot be undone.')}}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeApproveModal">
                            <i class="ri-close-line me-1"></i>{{__('Cancel')}}
                        </button>
                        <button type="button" class="btn btn-success" wire:click="approveRequest">
                            <i class="ri-check-double-line me-1"></i>{{__('Yes, Approve Deal')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($showRejectModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-close-circle-line me-2"></i>{{__('Reject Deal Validation')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeRejectModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="ri-alert-line me-2"></i>
                            {{__('Please provide a reason for rejecting this deal validation request.')}}
                        </div>
                        <div class="mb-3">
                            <label for="rejectionReason" class="form-label">
                                {{__('Rejection Reason')}} <span class="text-danger">*</span>
                            </label>
                            <textarea
                                wire:model="rejectionReason"
                                class="form-control @error('rejectionReason') is-invalid @enderror"
                                id="rejectionReason"
                                rows="5"
                                placeholder="{{__('Enter the reason for rejecting this validation (minimum 10 characters)...')}}"
                                required></textarea>
                            @error('rejectionReason')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                {{__('Minimum 10 characters, maximum 1000 characters')}}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeRejectModal">
                            <i class="ri-close-line me-1"></i>{{__('Cancel')}}
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="rejectRequest">
                            <i class="ri-close-circle-line me-1"></i>{{__('Reject Validation')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

