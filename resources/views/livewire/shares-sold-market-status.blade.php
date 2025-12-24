<div class="container">
    @section('title')
        {{ __('Shares Sold : market status') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold : market status') }}
        @endslot
    @endcomponent

    <div class="row mb-3">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12 card" id="marketList">
            <div class="card-body">
                <!-- Search and Filters -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                               placeholder="{{ __('Search by mobile or name...') }}">
                    </div>
                    <div class="col-md-6 text-end">
                        <select wire:model.live="perPage" class="form-select d-inline-block w-auto my-1">
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="1000">200</option>
                        </select>
                    </div>
                </div>

                <!-- Sort Controls -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="sortBy('id')">
                                {{__('Sort by ID')}}
                                @if($sortField === 'id')
                                    <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                @endif
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                    wire:click="sortBy('created_at')">
                                {{__('Sort by Date')}}
                                @if($sortField === 'created_at')
                                    <i class="ri-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}-s-line"></i>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Cards -->
                <div class="shares-list">
                    @forelse($sharesSoldes as $share)
                        @php
                            $totalPrice = number_format($share->unit_price * $share->raw_value, 2);
                            $sellPriceNow = number_format(actualActionValue(getSelledActions(true)) * $share->raw_value, 2);
                            $gain = number_format(actualActionValue(getSelledActions(true)) * $share->raw_value - $share->unit_price * $share->raw_value, 2);
                            $totalShares = number_format($share->raw_value, 0);
                            $flagUrl = Vite::asset('resources/images/flags/'.strtolower($share->apha2).'.svg');
                        @endphp
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- User Info Section -->
                                    <div class="col-md-3 border-end">
                                        <div class="d-flex align-items-center mb-2">
                                            <img
                                                src="{{ $flagUrl }}"
                                                class="rounded m-2"
                                                height="32px">
                                            <div>
                                                <h6 class="mb-0">{{ $share->Name }}</h6>
                                                <small class="text-muted">{{ $share->mobile }}</small>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($share->created_at)->format(config('app.date_format')) }}</small>
                                        </div>
                                        <div>
                                            @if($share->payed == 1)
                                                <span class="badge bg-success fs-12" style="cursor: pointer;"
                                                      wire:click="openModal({{ $share->id }}, '{{ $share->mobile }}', '{{ $totalPrice }}', '{{ $flagUrl }}')">
                                                        {{__('Transfert Made')}}
                                                    </span>
                                            @elseif($share->payed == 0)
                                                <span class="badge bg-danger fs-12" style="cursor: pointer;"
                                                      wire:click="openModal({{ $share->id }}, '{{ $share->mobile }}', '{{ $totalPrice }}', '{{ $flagUrl }}')">
                                                        {{__('Free')}}
                                                    </span>
                                            @elseif($share->payed == 2)
                                                <span class="badge bg-warning fs-12" style="cursor: pointer;"
                                                      wire:click="openModal({{ $share->id }}, '{{ $share->mobile }}', '{{ $totalPrice }}', '{{ $flagUrl }}')">
                                                        {{__('Mixed')}}
                                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Shares Info Section -->
                                    <div class="col-md-4 border-end">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('total_shares')}}</div>
                                                <div class="fw-semibold">{{ $totalShares }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('number_of_shares')}}</div>
                                                <div class="fw-semibold">{{ $share->value }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('share_price')}}</div>
                                                <div class="fw-semibold">${{ $share->unit_price }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('total_price')}}</div>
                                                <div class="fw-semibold">${{ $totalPrice }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Financial Info Section -->
                                    <div class="col-md-4">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('sell_price_now')}}</div>
                                                <div class="fw-semibold text-primary">${{ $sellPriceNow }}</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-muted small">{{__('gains')}}</div>
                                                <div
                                                    class="fw-semibold {{ floatval(str_replace(',', '', $gain)) >= 0 ? 'text-success' : 'text-danger' }}">
                                                    ${{ $gain }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-muted small">{{__('Real_Sold_amount')}}</div>
                                                <div class="fw-semibold">${{ $share->current_balance }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Button -->
                                    <div class="col-md-1 text-center">
                                        <button class="btn btn-primary btn-sm"
                                                wire:click="openModal({{ $share->id }}, '{{ $share->mobile }}', '{{ $totalPrice }}', '{{ $flagUrl }}')">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="ri-file-list-3-line display-4 text-muted"></i>
                                <h5 class="mt-3">{{ __('No data available') }}</h5>
                            </div>
                        </div>
                    @endforelse
                </div>


                <div class="mt-3">
                    {{ $sharesSoldes->links() }}
                </div>
            </div>
        </div>
        @if($showModal)
            <div class="modal fade show" id="realsoldmodif" tabindex="-1" style="display: block;" aria-modal="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Transfert Cash') }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-xxl-6">
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                                 <img
                                                                     src="{{ Vite::asset('resources/images/flags/'.$selectedAsset.'.svg') }}"
                                                                     class="rounded"
                                                                     height="22">

                                            </span>
                                        <input type="text" class="form-control" disabled
                                               value="{{ $selectedPhone }}">
                                    </div>
                                </div>
                                <div class="col-xxl-6">
                                    <div class="input-group">
                                        <input type="number" wire:model="selectedAmount" class="form-control">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="button" class="btn btn-light" wire:click="closeModal">
                                            {{ __('Cancel') }}
                                        </button>
                                        <button type="button" class="btn btn-primary" wire:click="updateBalance">
                                            {{ __('Submit') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif
    </div>
</div>
