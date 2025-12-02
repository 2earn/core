<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Shares Sold: Recent transaction') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')@endslot
        @slot('title')
            {{ __('Shares Sold: Recent transaction') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-12 card shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <div class="row g-3 align-items-center">
                    <div class="col-lg-8 col-md-6">
                        <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="{{ __('Search by value or description...') }}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 text-md-end">
                        <label class="me-2 text-muted small">{{ __('Show') }}</label>
                        <select wire:model.live="perPage" class="form-select form-select-sm d-inline-block w-auto">
                            <option value="10">10</option>
                            <option value="30">30</option>
                            <option value="50">50</option>
                        </select>
                        <span class="ms-2 text-muted small">{{ __('entries') }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Header Row -->
                <div class="row g-0 fw-semibold text-uppercase small bg-light border-bottom mx-0 py-3 px-3">
                    <div class="col-md-3 col-12 mb-2 mb-md-0">
                            <span wire:click="sortBy('value')" class="text-primary user-select-none" role="button">
                                <i class="mdi mdi-currency-usd me-1"></i>
                                {{__('value')}}
                                @if($sortField === 'value')
                                    <i class="mdi mdi-menu-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="mdi mdi-unfold-more-horizontal text-muted opacity-50"></i>
                                @endif
                            </span>
                    </div>
                    <div class="col-md-6 col-12 mb-2 mb-md-0">
                            <span wire:click="sortBy('description')" class="text-primary user-select-none"
                                  role="button">
                                <i class="mdi mdi-text-box-outline me-1"></i>
                                {{__('Description')}}
                                @if($sortField === 'description')
                                    <i class="mdi mdi-menu-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="mdi mdi-unfold-more-horizontal text-muted opacity-50"></i>
                                @endif
                            </span>
                    </div>
                    <div class="col-md-3 col-12">
                            <span wire:click="sortBy('created_at')" class="text-primary user-select-none" role="button">
                                <i class="mdi mdi-clock-outline me-1"></i>
                                {{__('formatted_created_at')}}
                                @if($sortField === 'created_at')
                                    <i class="mdi mdi-menu-{{ $sortDirection === 'asc' ? 'up' : 'down' }} text-success"></i>
                                @else
                                    <i class="mdi mdi-unfold-more-horizontal text-muted opacity-50"></i>
                                @endif
                            </span>
                    </div>
                </div>

                <!-- Data Rows -->
                @forelse($transactions as $transaction)
                    <div class="row g-0 border-bottom mx-0 py-3 px-3 align-items-center bg-hover-light transition">
                        <div class="col-md-3 col-12 mb-2 mb-md-0">
                                <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                    {{ number_format($transaction->value, 2) }}
                                </span>
                        </div>
                        <div class="col-md-6 col-12 mb-2 mb-md-0">
                            <div class="text-dark">{{ $transaction->description }}</div>
                        </div>
                        <div class="col-md-3 col-12">
                            <small class="text-muted">
                                <i class="mdi mdi-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('Y-m-d') }}
                                <br class="d-md-none">
                                <i class="mdi mdi-clock-outline me-1 ms-md-2"></i>
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('H:i:s') }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="row g-0 mx-0">
                        <div class="col-12 text-center py-5">
                            <div class="mb-3">
                                <i class="mdi mdi-folder-open-outline display-4 text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-1">{{ __('No transactions found') }}</h5>
                            <p class="text-muted small mb-0">{{ __('Try adjusting your search criteria') }}</p>
                        </div>
                    </div>
                @endforelse


                @if($transactions->hasPages())
                    <div class="row g-0 mx-0 border-top bg-light">
                        <div class="col-12 p-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted small">
                                    {{ __('Showing') }}
                                    <strong>{{ $transactions->firstItem() ?? 0 }}</strong>
                                    {{ __('to') }}
                                    <strong>{{ $transactions->lastItem() ?? 0 }}</strong>
                                    {{ __('of') }}
                                    <strong>{{ $transactions->total() }}</strong>
                                    {{ __('results') }}
                                </div>
                                <div>
                                    {{ $transactions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
