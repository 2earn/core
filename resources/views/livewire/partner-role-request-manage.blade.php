<div class="container-fluid">
    @section('title')
        {{ __('Partner Role Requests') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Partner Role Requests Management') }}
        @endslot
        @slot('li_1')
            {{ __('Partner') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Total Requests') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $statistics['total'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Pending') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $statistics['pending'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                <i class="ri-time-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Approved') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $statistics['approved'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                <i class="ri-checkbox-circle-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-stats shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title text-uppercase text-muted mb-0">{{ __('Rejected') }}</h5>
                            <span class="h2 font-weight-bold mb-0">{{ $statistics['rejected'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                <i class="ri-close-circle-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">{{ __('Status') }}</label>
                            <select wire:model.live="statusFilter" id="statusFilter" class="form-select">
                                <option value="all">{{ __('All Statuses') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="approved">{{ __('Approved') }}</option>
                                <option value="rejected">{{ __('Rejected') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label for="searchUser" class="form-label">{{ __('Search User') }}</label>
                            <input type="text"
                                   wire:model.live.debounce.300ms="searchUser"
                                   id="searchUser"
                                   class="form-control"
                                   placeholder="{{ __('Search by user name or email...') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0 text-white">
                        <i class="ri-user-settings-line me-2"></i>
                        {{ __('Partner Role Requests') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('ID') }}</th>
                                    <th>{{ __('Partner') }}</th>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('Requested By') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($requests as $request)
                                    <tr>
                                        <td>{{ $request->id }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $request->partner->company_name ?? 'N/A' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $request->user->name ?? 'N/A' }}</span>
                                                <small class="text-muted">{{ $request->user->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $request->role_name }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span>{{ $request->requestedBy->name ?? 'N/A' }}</span>
                                                <small class="text-muted">{{ $request->requestedBy->email ?? '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $this->getStatusBadgeClass($request->status) }}">
                                                {{ __(ucfirst($request->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ $request->created_at->format('Y-m-d H:i') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                @if($request->isPending())
                                                    <button wire:click="openApproveModal({{ $request->id }})"
                                                            class="btn btn-sm btn-success"
                                                            title="{{ __('Approve') }}">
                                                        <i class="ri-check-line"></i>
                                                    </button>
                                                    <button wire:click="openRejectModal({{ $request->id }})"
                                                            class="btn btn-sm btn-danger"
                                                            title="{{ __('Reject') }}">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                    <button wire:click="cancelRequest({{ $request->id }})"
                                                            class="btn btn-sm btn-secondary"
                                                            title="{{ __('Cancel') }}"
                                                            onclick="return confirm('{{ __('Are you sure you want to cancel this request?') }}')">
                                                        <i class="ri-forbid-line"></i>
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-info"
                                                            title="{{ __('View Details') }}"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#details-{{ $request->id }}">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Collapsible Details Row -->
                                    <tr class="collapse" id="details-{{ $request->id }}">
                                        <td colspan="8" class="bg-light">
                                            <div class="p-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <strong>{{ __('Reason:') }}</strong>
                                                        <p>{{ $request->reason ?? __('No reason provided') }}</p>
                                                    </div>
                                                    @if($request->isRejected())
                                                        <div class="col-md-6">
                                                            <strong>{{ __('Rejection Reason:') }}</strong>
                                                            <p>{{ $request->rejection_reason }}</p>
                                                            <small class="text-muted">
                                                                {{ __('Reviewed by:') }} {{ $request->reviewedBy->name ?? 'N/A' }}
                                                                {{ __('on') }} {{ $request->reviewed_at?->format('Y-m-d H:i') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                    @if($request->isApproved())
                                                        <div class="col-md-6">
                                                            <strong>{{ __('Approved by:') }}</strong>
                                                            <p>{{ $request->reviewedBy->name ?? 'N/A' }}</p>
                                                            <small class="text-muted">
                                                                {{ __('on') }} {{ $request->reviewed_at?->format('Y-m-d H:i') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                    @if($request->isCancelled())
                                                        <div class="col-md-6">
                                                            <strong>{{ __('Cancelled by:') }}</strong>
                                                            <p>{{ $request->cancelledBy->name ?? 'N/A' }}</p>
                                                            <small class="text-muted">
                                                                {{ __('on') }} {{ $request->cancelled_at?->format('Y-m-d H:i') }}
                                                            </small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-inbox-line" style="font-size: 3rem;"></i>
                                                <p class="mt-2">{{ __('No partner role requests found') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve/Reject Modal -->
    @if($showModal && $selectedRequest)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header {{ $modalType === 'approve' ? 'bg-success' : 'bg-danger' }} text-white">
                        <h5 class="modal-title text-white">
                            @if($modalType === 'approve')
                                <i class="ri-checkbox-circle-line me-2"></i>{{ __('Approve Request') }}
                            @else
                                <i class="ri-close-circle-line me-2"></i>{{ __('Reject Request') }}
                            @endif
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>{{ __('Partner:') }}</strong> {{ $selectedRequest->partner->company_name ?? 'N/A' }}
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('User:') }}</strong> {{ $selectedRequest->user->name ?? 'N/A' }}
                            <br>
                            <small class="text-muted">{{ $selectedRequest->user->email ?? '' }}</small>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('Role:') }}</strong>
                            <span class="badge bg-primary">{{ $selectedRequest->role_name }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('Requested By:') }}</strong> {{ $selectedRequest->requestedBy->name ?? 'N/A' }}
                        </div>
                        @if($selectedRequest->reason)
                            <div class="mb-3">
                                <strong>{{ __('Reason:') }}</strong>
                                <p class="text-muted">{{ $selectedRequest->reason }}</p>
                            </div>
                        @endif

                        @if($modalType === 'reject')
                            <div class="mb-3">
                                <label for="rejectionReason" class="form-label required">{{ __('Rejection Reason') }}</label>
                                <textarea wire:model="rejectionReason"
                                          id="rejectionReason"
                                          class="form-control @error('rejectionReason') is-invalid @enderror"
                                          rows="4"
                                          placeholder="{{ __('Please provide a reason for rejection...') }}"></textarea>
                                @error('rejectionReason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            {{ __('Cancel') }}
                        </button>
                        @if($modalType === 'approve')
                            <button type="button" class="btn btn-success" wire:click="approve">
                                <i class="ri-check-line me-1"></i>{{ __('Approve') }}
                            </button>
                        @else
                            <button type="button" class="btn btn-danger" wire:click="reject">
                                <i class="ri-close-line me-1"></i>{{ __('Reject') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
