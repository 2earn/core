<div class="container">
    @section('title')
        {{ __('Platform Type Change Requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{route('platform_index', app()->getLocale())}}">{{ __('Platforms') }}</a>
        @endslot
        @slot('title')
            {{ __('Type Change Requests') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

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
                                   placeholder="{{__('Search by platform name or ID...')}}">
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
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-shadow">
                    <div class="card-body p-4">
                        <div class="row align-items-center">

                            <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    @if($request->platform?->logoImage)
                                        <img src="{{ asset('uploads/' . $request->platform->logoImage->url) }}"
                                             alt="{{ $request->platform->name }}"
                                             class="img-fluid rounded me-3"
                                             style="max-width: 60px; max-height: 60px; object-fit: contain;">
                                    @else
                                        <div class="avatar-md me-3">
                                            <div class="avatar-title rounded bg-soft-primary text-primary fs-4">
                                                {{strtoupper(substr($request->platform->name ?? 'P', 0, 1))}}
                                            </div>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="mb-1">{{$request->platform->name ?? 'N/A'}}</h6>
                                        <span class="badge badge-soft-secondary">ID: {{$request->platform_id}}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Type Change Info -->
                            <div class="col-lg-4 col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <div>
                                            <p class="text-muted mb-1 small">{{__('From')}}</p>
                                            <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">
                                                {{__($this->getTypeName($request->old_type))}}
                                            </span>
                                        </div>
                                        <div>
                                            <i class="ri-arrow-right-line fs-3 text-muted"></i>
                                        </div>
                                        <div>
                                            <p class="text-muted mb-1 small">{{__('To')}}</p>
                                            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                                {{__($this->getTypeName($request->new_type))}}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


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
                                        {{$request->created_at->format(config('app.date_format'))}}
                                    </p>
                                </div>
                            </div>


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


                        <div class="row mt-3 pt-3 border-top">
                            @if(isset($request->platform?->owner_id))
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="ri-user-line me-1"></i>
                                        <strong>{{__('Owner')}}:</strong>
                                        {{getUserDisplayedNameFromId($request->platform->owner_id) ?? 'N/A'}}
                                    </small>
                                </div>
                            @endif
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">
                                    <i class="ri-time-line me-1"></i>
                                    <strong>{{__('Request ID')}}:</strong> #{{$request->id}}
                                </small>
                            </div>
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
                                {{__('There are no platform type change requests at the moment')}}
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
                            <i class="ri-checkbox-circle-line me-2"></i>{{__('Approve Type Change Request')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeApproveModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            <i class="ri-information-line me-2"></i>
                            {{__('Are you sure you want to approve this type change request?')}}
                        </div>
                        <p class="text-muted mb-0">
                            {{__('This action will change the platform type and cannot be undone. The platform type will be updated immediately.')}}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeApproveModal">
                            <i class="ri-close-line me-1"></i>{{__('Cancel')}}
                        </button>
                        <button type="button" class="btn btn-success" wire:click="approveRequest">
                            <i class="ri-check-double-line me-1"></i>{{__('Yes, Approve Request')}}
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
                            <i class="ri-close-circle-line me-2"></i>{{__('Reject Type Change Request')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeRejectModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning" role="alert">
                            <i class="ri-alert-line me-2"></i>
                            {{__('Please provide a reason for rejecting this request.')}}
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
                                placeholder="{{__('Enter the reason for rejecting this request (minimum 10 characters)...')}}"
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
                            <i class="ri-close-circle-line me-1"></i>{{__('Reject Request')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

