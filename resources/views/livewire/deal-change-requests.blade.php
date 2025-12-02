<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Deal Change Requests') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{route('deals_index', app()->getLocale())}}">{{ __('Deals') }}</a>
        @endslot
        @slot('title')
            {{ __('Deal Change Requests') }}
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
                                       placeholder="{{__('Search by deal name or ID...')}}">
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

    <!-- Requests List -->
    <div class="row g-3">
        @forelse($requests as $request)
            <div class="col-12">
                <div class="card border-0 shadow-sm hover-shadow">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Deal Info -->
                            <div class="col-lg-3 col-md-4 mb-3 mb-md-0">
                                <div>
                                    <h6 class="mb-1">{{$request->deal->name ?? 'N/A'}}</h6>
                                    <span class="badge badge-soft-secondary">ID: {{$request->deal_id}}</span>
                                    @if($request->deal->platform)
                                        <p class="text-muted mb-0 mt-1 small">
                                            <i class="ri-store-2-line me-1"></i>{{$request->deal->platform->name}}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Change Info -->
                            <div class="col-lg-4 col-md-4 mb-3 mb-md-0">
                                <div class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">
                                            <i class="ri-file-edit-line me-1"></i>
                                            {{ count($request->changes ?? []) }} {{__('field(s) changed')}}
                                        </span>
                                        <button wire:click="openChangesModal({{$request->id}})"
                                                class="btn btn-outline-primary btn-sm">
                                            <i class="ri-eye-line me-1"></i>{{__('View Details')}}
                                        </button>
                                    </div>
                                    @if($request->requestedBy)
                                        <p class="text-muted mb-0 mt-2 small">
                                            <i class="ri-user-line me-1"></i>{{__('Requested by')}}: {{getUserDisplayedName($request->requestedBy->idUser) ?? 'N/A'}}
                                        </p>
                                    @endif
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
                                        @if($request->reviewedBy)
                                            <br>
                                            <span class="text-muted">{{__('by')}} {{$request->reviewedBy->name}}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>


                        @if($request->status === 'rejected' && $request->rejection_reason)
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-12">
                                    <div class="alert alert-danger mb-0" role="alert">
                                        <strong><i class="ri-error-warning-line me-1"></i>{{__('Rejection Reason')}}:</strong>
                                        <p class="mb-0 mt-2">{{$request->rejection_reason}}</p>
                                    </div>
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
                                {{__('There are no deal change requests at the moment')}}
                            @endif
                        </p>
                    </div>
                </div>
        @endforelse
    </div>


    @if($requests->hasPages())
        <div class="row mt-2">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
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
        </div>
    @endif

    <!-- View Changes Modal -->
    @if($showChangesModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="ri-file-edit-line me-2"></i>{{__('Change Request Details')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeChangesModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6 class="text-muted">
                                {{__('Deal')}}: <strong class="text-dark">{{$viewChangesData['deal_name'] ?? 'N/A'}}</strong>
                            </h6>
                            <p class="text-muted mb-1 small">{{__('Platform')}}: {{$viewChangesData['platform_name'] ?? 'N/A'}}</p>
                            <p class="text-muted mb-0 small">{{__('Requested at')}}: {{$viewChangesData['requested_at'] ?? 'N/A'}}</p>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;">{{__('Field')}}</th>
                                        <th style="width: 35%;">{{__('Current Value')}}</th>
                                        <th style="width: 35%;">{{__('New Value')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($viewChangesData['changes']) && is_array($viewChangesData['changes']))
                                        @foreach($viewChangesData['changes'] as $field => $change)
                                            <tr>
                                                <td>
                                                    <strong>{{$this->getFieldLabel($field)}}</strong>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        {{ is_bool($change['old'] ?? null) ? ($change['old'] ? 'Yes' : 'No') : ($change['old'] ?? 'N/A') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success-subtle text-success">
                                                        {{ is_bool($change['new'] ?? null) ? ($change['new'] ? 'Yes' : 'No') : ($change['new'] ?? 'N/A') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">{{__('No changes available')}}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeChangesModal">
                            <i class="ri-close-line me-1"></i>{{__('Close')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if($showApproveModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ri-checkbox-circle-line me-2"></i>{{__('Approve Change Request')}}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeApproveModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" role="alert">
                            <i class="ri-information-line me-2"></i>
                            {{__('Are you sure you want to approve this change request?')}}
                        </div>

                        <h6 class="mb-3">{{__('Changes to be applied')}}:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{__('Field')}}</th>
                                        <th>{{__('Current')}}</th>
                                        <th>{{__('New')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approveRequestChanges as $field => $change)
                                        <tr>
                                            <td><strong>{{$this->getFieldLabel($field)}}</strong></td>
                                            <td><span class="text-muted">{{ is_bool($change['old'] ?? null) ? ($change['old'] ? 'Yes' : 'No') : ($change['old'] ?? 'N/A') }}</span></td>
                                            <td><span class="text-success fw-bold">{{ is_bool($change['new'] ?? null) ? ($change['new'] ? 'Yes' : 'No') : ($change['new'] ?? 'N/A') }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <p class="text-muted mb-0 mt-3">
                            {{__('This action will update the deal with the new values and cannot be undone.')}}
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
                            <i class="ri-close-circle-line me-2"></i>{{__('Reject Change Request')}}
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

