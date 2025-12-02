<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Platform role assign') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Platform role assign') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row">
        <div class="col-12 card">
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">{{__('Search')}}</label>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               class="form-control"
                               placeholder="{{__('Search by user, platform, or role...')}}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select wire:model.live="selectedStatus" class="form-select">
                            <option value="all">{{__('All Statuses')}}</option>
                            <option value="pending">{{__('Pending')}}</option>
                            <option value="approved">{{__('Approved')}}</option>
                            <option value="rejected">{{__('Rejected')}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('User')}}
                            </th>
                            <th>
                                {{__('Platform')}}
                            </th>
                            <th>{{__('Role')}}
                            </th>
                            <th>{{__('Status')}}
                            </th>
                            <th>{{__('Created
                                    By')}}
                            </th>
                            <th>
                                {{__('CreatedAt')}}
                            </th>
                            <th>
                                {{__('Actions')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($assignments as $assignment)
                            <tr>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0 px-3">{{ $assignment->id }}</p>
                                </td>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">
                                                {{getUserDisplayedNameFromId($assignment->user->id)}}
                                            </h6>
                                            <p class="text-xs text-secondary mb-0">{{ $assignment->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $assignment->platform->name ?? 'N/A' }}</p>
                                </td>
                                <td>
                                        <span
                                            class="badge badge-sm bg-gradient-info">{{ ucfirst(str_replace('_', ' ', $assignment->role)) }}</span>
                                </td>
                                <td>
                                    @if($assignment->status === 'pending')
                                        <span class="badge badge-sm bg-gradient-warning">{{__('Pending')}}</span>
                                    @elseif($assignment->status === 'approved')
                                        <span class="badge badge-sm bg-gradient-success">{{__('Approved')}}</span>
                                    @elseif($assignment->status === 'rejected')
                                        <span class="badge badge-sm bg-gradient-danger">{{__('Rejected')}}</span>
                                    @endif
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $assignment->creator->name ?? 'System' }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $assignment->created_at->format(config('app.date_format')) }}</p>
                                </td>
                                <td class="align-middle">
                                    @if($assignment->status === 'pending')
                                        <button wire:click="openApproveModal({{ $assignment->id }})"
                                                class="btn btn-success btn-sm mb-0 me-1"
                                                title="Approve">
                                            <i class="fas fa-check"></i> {{__('Approve')}}
                                        </button>
                                        <button wire:click="openRejectModal({{ $assignment->id }})"
                                                class="btn btn-danger btn-sm mb-0"
                                                title="Reject">
                                            <i class="fas fa-times"></i> {{__('Reject')}}
                                        </button>
                                    @elseif($assignment->status === 'rejected')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-secondary mb-0"
                                                data-bs-toggle="tooltip"
                                                title="{{ $assignment->rejection_reason }}">
                                            <i class="fas fa-info-circle"></i> {{__('View Reason')}}
                                        </button>
                                    @else
                                        <span class="text-xs text-success">
                                                    <i class="fas fa-check-circle"></i> {{__('Processed')}}
                                                </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-xs text-secondary mb-0">{{__('No role assignments found')}}.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer">
                {{ $assignments->links() }}
            </div>
        </div>
    </div>

    @if($showRejectModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1"
             role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Reject Role Assignment')}}</h5>
                        <button type="button" wire:click="closeRejectModal" class="btn-close"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="reject">
                            <div class="mb-3">
                                <label for="rejectionReason" class="form-label">{{__('Rejection Reason')}} *</label>
                                <textarea wire:model="rejectionReason"
                                          class="form-control @error('rejectionReason') is-invalid @enderror"
                                          id="rejectionReason"
                                          rows="4"
                                          placeholder="Please provide a reason for rejection (minimum 10 characters)..."
                                          required></textarea>
                                @error('rejectionReason')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeRejectModal"
                                class="btn btn-secondary">{{__('Cancel')}}</button>
                        <button type="button" wire:click="reject" class="btn btn-danger">
                            <i class="fas fa-times"></i> {{__('Reject Assignment')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showApproveModal)
        <div class="modal fade show" style="display: block; background-color: rgba(0,0,0,0.5);" tabindex="-1"
             role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success bg-gradient">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-check-circle me-2"></i>{{__('Approve Role Assignment')}}
                        </h5>
                        <button type="button" wire:click="closeApproveModal" class="btn-close btn-close-white"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center py-3">
                            <div class="avatar-md mx-auto mb-3">
                                <div class="avatar-title rounded-circle bg-success bg-soft">
                                    <i class="fas fa-check display-6 text-success"></i>
                                </div>
                            </div>
                            <h5 class="mb-3">{{__('Are you sure?')}}</h5>
                            <p class="text-muted mb-0">
                                {{__('You are about to approve this role assignment. This will update the platform with the assigned user.')}}
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" wire:click="closeApproveModal" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>{{__('Cancel')}}
                        </button>
                        <button type="button" wire:click="confirmApprove" class="btn btn-success">
                            <i class="fas fa-check me-1"></i>{{__('Yes, Approve')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

