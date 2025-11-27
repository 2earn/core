<div class="{{getContainerType()}}">
    <div>
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

        <div class="row card">
            <div class="card-body">
                {{-- Search and Filter Controls --}}
                <div class="card-header border-0 bg-white">
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-sm-6 col-lg-3">
                            <label class="form-label mb-1 fw-semibold">{{ __('Item per page') }}</label>
                            <select wire:model.live="pageCount" class="form-select"
                                    aria-label="Items per page">
                                <option @if($pageCount=="10") selected @endif value="10">10</option>
                                <option @if($pageCount=="25") selected @endif value="25">25</option>
                                <option @if($pageCount=="50") selected @endif value="50">50</option>
                                <option @if($pageCount=="100") selected @endif value="100">100</option>
                            </select>
                        </div>

                        <div class="col-sm-6 col-lg-7">
                            <label class="form-label mb-1 fw-semibold">{{ __('Search') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="ri-search-line"></i></span>
                                <input wire:model.live.debounce.500ms="search" type="search"
                                       class="form-control"
                                       placeholder="{{ __('Search in all fields') }}..." aria-label="Search"/>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-2 d-flex align-items-end">
                            <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                                <i class="ri-refresh-line"></i> {{ __('Clear Filters') }}
                            </button>
                        </div>
                    </div>

                    {{-- Column Filters --}}
                    <div class="row g-2 mb-2">
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterReference" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by reference') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterSource" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by source') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterReceiver" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by receiver') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterActions" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by actions') }}">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterDate" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by date') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterType" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by type') }}">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <input wire:model.live.debounce.500ms="filterResponse" type="text" class="form-control form-control-sm" placeholder="{{ __('Filter by response') }}">
                        </div>
                    </div>
                </div>

                {{-- Loading Indicator --}}
                <div wire:loading.delay class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{__('Loading...')}}</span>
                    </div>
                </div>

                {{-- Notification Items --}}
                <div wire:loading.remove.delay class="mt-3">
                    @forelse($notifications as $notification)
                        <div class="card border shadow-sm mb-3 hover-shadow">
                            <div class="card-body">
                                <div class="row g-3">
                                    {{-- Left Column --}}
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="text-primary fs-14 mb-3">
                                                <i class="ri-notification-line align-middle me-1"></i>
                                                {{__('Notification Details')}}
                                            </h6>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12 fw-medium">{{__('reference')}}:</span>
                                                <span class="ms-2 fs-13">{{ $notification->reference ?? 'N/A' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12 fw-medium">{{__('source')}}:</span>
                                                <span class="ms-2 fs-13">{{ $notification->send ?? 'N/A' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12 fw-medium">{{__('receiver')}}:</span>
                                                <span class="ms-2 fs-13">{{ $notification->receiver ?? 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted fs-12 fw-medium">{{__('Actions')}}:</span>
                                                <span class="ms-2 fs-13">{{ $notification->action ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Right Column --}}
                                    <div class="col-md-6">
                                        <div class="p-3 bg-light rounded h-100">
                                            <h6 class="text-primary fs-14 mb-3">
                                                <i class="ri-information-line align-middle me-1"></i>
                                                {{__('Status & Response')}}
                                            </h6>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12 fw-medium">{{__('date')}}:</span>
                                                <span class="ms-2 fs-13">{{ $notification->date ?? 'N/A' }}</span>
                                            </div>
                                            <div class="mb-2">
                                                <span class="text-muted fs-12 fw-medium">{{__('Type')}}:</span>
                                                <span class="badge bg-info ms-2">{{ $notification->type ?? 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-muted fs-12 fw-medium">{{__('response')}}:</span>
                                                <div class="ms-2 mt-1 p-2 bg-white rounded border">
                                                    <small class="text-break">{{ $notification->responce ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="avatar-sm mx-auto mb-3">
                                <div class="avatar-title bg-light text-primary rounded-circle fs-2">
                                    <i class="ri-notification-off-line"></i>
                                </div>
                            </div>
                            <h5 class="text-muted">{{__('No notifications found')}}</h5>
                            <p class="text-muted">{{__('Try adjusting your search or filter criteria')}}</p>
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($notifications->hasPages())
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow {
        transition: box-shadow 0.3s ease-in-out;
    }
    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
</style>
