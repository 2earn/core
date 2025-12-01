<div class="{{getContainerType()}}">
    @section('title')
        {{ __('history') }}
    @endsection

    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Notification history') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    
    <div class="row">
            <div class="col-12 card shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <div class="avatar-title bg-primary bg-opacity-25 text-primary rounded">
                                    <i class="ri-notification-3-line fs-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ __('Notification history') }}</h5>
                            <p class="text-muted mb-0 small">{{ __('View and filter notification history') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <label class="form-label text-muted small mb-2">
                                <i class="ri-list-check align-middle me-1"></i>{{ __('Items per page') }}
                            </label>
                            <select wire:model.live="pageCount" class="form-select form-select-sm shadow-sm" aria-label="Items per page">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>

                        <div class="col-lg-8 col-md-8 col-sm-6">
                            <label class="form-label text-muted small mb-2">
                                <i class="ri-search-line align-middle me-1"></i>{{ __('Search') }}
                            </label>
                            <div class="input-group input-group-sm shadow-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="ri-search-2-line text-muted"></i>
                                </span>
                                <input wire:model.live.debounce.500ms="search"
                                       type="search"
                                       class="form-control border-start-0 ps-0"
                                       placeholder="{{ __('Search in all fields') }}..."
                                       aria-label="Search"/>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-12">
                            <label class="form-label text-muted small mb-2 d-none d-lg-block">&nbsp;</label>
                            <button wire:click="clearFilters" class="btn btn-outline-primary btn-sm w-100 shadow-sm">
                                <i class="ri-refresh-line align-middle me-1"></i>{{ __('Clear Filters') }}
                            </button>
                        </div>
                    </div>

                    
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-light py-2">
                            <div class="d-flex align-items-center">
                                <i class="ri-filter-3-line me-2 text-primary"></i>
                                <strong class="small">{{ __('Advanced Filters') }}</strong>
                            </div>
                        </div>
                        <div class="card-body bg-light bg-opacity-50">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-hashtag align-middle"></i> {{ __('Reference') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterReference"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by reference') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-send-plane-line align-middle"></i> {{ __('Source') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterSource"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by source') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-user-received-line align-middle"></i> {{ __('Receiver') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterReceiver"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by receiver') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-flashlight-line align-middle"></i> {{ __('Actions') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterActions"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by actions') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-calendar-line align-middle"></i> {{ __('Date') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterDate"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by date') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-price-tag-3-line align-middle"></i> {{ __('Type') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterType"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by type') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <label class="form-label small text-muted mb-1">
                                        <i class="ri-message-3-line align-middle"></i> {{ __('Response') }}
                                    </label>
                                    <input wire:model.live.debounce.500ms="filterResponse"
                                           type="text"
                                           class="form-control form-control-sm"
                                           placeholder="{{ __('Filter by response') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div wire:loading.delay class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">{{__('Loading...')}}</span>
                        </div>
                        <p class="text-muted">{{__('Loading notifications')}}...</p>
                    </div>

                    
                    <div wire:loading.remove.delay>
                        @forelse($notifications as $index => $notification)
                            <div class="card border mb-3 shadow-sm">
                                <div class="card-body p-0">
                                    <div class="row g-0">
                                        
                                        <div class="col-md-6 border-end">
                                            <div class="p-4 h-100">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded">
                                                                <i class="ri-notification-4-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1 text-dark">{{ __('Notification Details') }}</h6>
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                            #{{ $index + 1 + (($notifications->currentPage() - 1) * $notifications->perPage()) }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="vstack gap-2">
                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 90px;">
                                                            <i class="ri-hashtag text-primary"></i> {{__('Reference')}}:
                                                        </span>
                                                        <span class="ms-2 small fw-medium text-break">
                                                            {{ $notification->reference ?? 'N/A' }}
                                                        </span>
                                                    </div>

                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 90px;">
                                                            <i class="ri-send-plane-2-line text-info"></i> {{__('Source')}}:
                                                        </span>
                                                        <span class="ms-2 small">
                                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                                {{ $notification->send ?? 'N/A' }}
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 90px;">
                                                            <i class="ri-user-received-2-line text-success"></i> {{__('Receiver')}}:
                                                        </span>
                                                        <span class="ms-2 small">
                                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                                {{ $notification->receiver ?? 'N/A' }}
                                                            </span>
                                                        </span>
                                                    </div>

                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 90px;">
                                                            <i class="ri-flashlight-line text-warning"></i> {{__('Action')}}:
                                                        </span>
                                                        <span class="ms-2 small text-break">
                                                            {{ $notification->action ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="p-4 h-100 bg-light bg-opacity-50">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-success bg-opacity-10 text-success rounded">
                                                                <i class="ri-information-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-0 text-dark">{{ __('Status & Response') }}</h6>
                                                    </div>
                                                </div>

                                                <div class="vstack gap-2">
                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 80px;">
                                                            <i class="ri-calendar-event-line text-primary"></i> {{__('Date')}}:
                                                        </span>
                                                        <span class="ms-2 small fw-medium">
                                                            {{ $notification->date ?? 'N/A' }}
                                                        </span>
                                                    </div>

                                                    <div class="d-flex">
                                                        <span class="text-muted small flex-shrink-0" style="min-width: 80px;">
                                                            <i class="ri-price-tag-3-line text-info"></i> {{__('Type')}}:
                                                        </span>
                                                        <span class="ms-2">
                                                            <span class="badge bg-primary">{{ $notification->type ?? 'N/A' }}</span>
                                                        </span>
                                                    </div>

                                                    <div>
                                                        <div class="text-muted small mb-2">
                                                            <i class="ri-message-3-line text-success"></i> {{__('Response')}}:
                                                        </div>
                                                        <div class="p-3 bg-white rounded border border-opacity-50">
                                                            <small class="text-muted text-break d-block" style="max-height: 100px; overflow-y: auto;">
                                                                {{ $notification->responce ?: __('No response available') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-4">
                                    <div class="avatar-xl mx-auto">
                                        <div class="avatar-title bg-light text-primary rounded-circle">
                                            <i class="ri-notification-off-line display-4"></i>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="text-muted mb-2">{{__('No notifications found')}}</h4>
                                <p class="text-muted mb-4">{{__('Try adjusting your search or filter criteria')}}</p>
                                <button wire:click="clearFilters" class="btn btn-primary btn-sm">
                                    <i class="ri-refresh-line align-middle me-1"></i>{{__('Reset Filters')}}
                                </button>
                            </div>
                        @endforelse

                        
                        @if($notifications->hasPages())
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    {{ __('Showing') }}
                                    <strong>{{ $notifications->firstItem() }}</strong>
                                    {{ __('to') }}
                                    <strong>{{ $notifications->lastItem() }}</strong>
                                    {{ __('of') }}
                                    <strong>{{ $notifications->total() }}</strong>
                                    {{ __('results') }}
                                </div>
                                <div>
                                    {{ $notifications->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
    </div>
</div>
