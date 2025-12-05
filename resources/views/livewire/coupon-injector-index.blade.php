<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Coupon') }}
    @endsection
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Coupon') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 card">
            <div class="card-header border-info">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <button wire:click="deleteSelected"
                                wire:confirm="{{__('Are you sure you want to delete the selected rows?')}}"
                                class="btn btn-soft-danger material-shadow-none mt-1">
                            {{__('Delete')}} <span class="badge bg-danger"
                                                   wire:loading.remove>{{ count($selectedIds) }}</span>
                        </button>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <a href="{{route('coupon_injector_create',['locale'=>app()->getLocale()])}}"
                           class="btn btn-soft-info material-shadow-none mt-1 float-end"
                           id="create-btn">
                            {{__('Add Coupons list')}}
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Search and Filter Bar -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="form-control"
                                   placeholder="{{__('Search by pin, sn, value, or category...')}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <label class="form-check-label me-2">
                                <input type="checkbox" wire:model.live="selectAll"
                                       class="form-check-input"> {{__('Select All')}}
                            </label>
                            <div class="btn-group" role="group">
                                <button type="button" wire:click="sortBy('created_at')"
                                        class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-calendar"></i> {{__('Date')}}
                                    @if($sortField === 'created_at')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </button>
                                <button type="button" wire:click="sortBy('value')"
                                        class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-currency-usd"></i> {{__('Value')}}
                                    @if($sortField === 'value')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </button>
                                <button type="button" wire:click="sortBy('consumed')"
                                        class="btn btn-sm btn-outline-secondary">
                                    <i class="mdi mdi-check-circle"></i> {{__('Status')}}
                                    @if($sortField === 'consumed')
                                        <i class="mdi mdi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coupons Cards Layer -->
                <div class="row">
                    @forelse($coupons as $coupon)
                        <div class="col-12 mb-3" wire:key="coupon-{{ $coupon->id }}">
                            <div
                                class="card shadow-sm border-start border-4 {{ $coupon->consumed ? 'border-success' : 'border-warning' }}">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <input type="checkbox"
                                                       wire:model.live="selectedIds"
                                                       value="{{ $coupon->id }}"
                                                       class="form-check-input"
                                                       id="check-{{ $coupon->id }}">
                                                <label class="form-check-label" for="check-{{ $coupon->id }}"></label>
                                            </div>
                                        </div>

                                        <div class="col-lg-5 col-md-4">
                                            <div class="d-flex flex-column">
                                                <h5 class="mb-1">
                                                    <i class="mdi mdi-ticket-confirmation text-primary"></i>
                                                    {{ $coupon->pin }}
                                                </h5>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-barcode"></i> SN: {{ $coupon->sn }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="mdi mdi-calendar"></i> {{ $coupon->created_at->format(config('app.date_format')) }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Category Section -->
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="mb-2">
                                                <small class="text-muted d-block">{{__('Category')}}</small>
                                                @include('parts.datatable.coupon-category', ['coupon' => $coupon])
                                            </div>
                                        </div>

                                        <!-- Value Section -->
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <div class="mb-2">
                                                <small class="text-muted d-block">{{__('Value')}}</small>
                                                @include('parts.datatable.coupon-value', ['coupon' => $coupon])
                                            </div>
                                        </div>

                                        <!-- Status Section -->
                                        <div class="col-lg-2 col-md-3 col-sm-6">
                                            <div class="mb-2">
                                                <small class="text-muted d-block">{{__('Consumed')}}</small>
                                                @include('parts.datatable.coupon-consumed', ['coupon' => $coupon])
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-lg-1 col-md-12">
                                            <div class="d-flex gap-2 justify-content-end flex-wrap">
                                                <button wire:click="delete({{ $coupon->id }})"
                                                        wire:confirm="{{__('Are you sure to delete this Coupon')}}? {{ $coupon->pin }}"
                                                        class="btn btn-sm btn-danger">
                                                    <i class="mdi mdi-delete"></i> {{__('Delete')}}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="p-3 bg-light rounded">
                                                <h6 class="mb-3">
                                                    <i class="mdi mdi-calendar-clock text-primary"></i> {{__('Dates')}}
                                                </h6>
                                                @include('parts.datatable.coupon-injector-dates', ['coupon' => $coupon])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body text-center py-5">
                                    <i class="mdi mdi-ticket-outline display-1 text-muted"></i>
                                    <h4 class="mt-3">{{__('No coupons found')}}</h4>
                                    <p class="text-muted">{{__('Try adjusting your search or filters')}}</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>


                <div class="mt-3">
                    {{ $coupons->links() }}
                </div>
            </div>
        </div>
    </div>
    <div wire:loading class="position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">{{__('Loading...')}}</span>
        </div>
    </div>
</div>
