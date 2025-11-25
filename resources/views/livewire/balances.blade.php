<div class="{{getContainerType()}}">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Balance operations') }}
            @endslot
        @endcomponent

        <div class="row">
            <div class="col-12">
                @include('layouts.flash-messages')
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ri-search-line"></i>
                                    </span>
                                    <input type="text"
                                           wire:model.live.debounce.300ms="search"
                                           class="form-control border-start-0 ps-0"
                                           placeholder="{{ __('Search by ID, Operation, Note, Balance or Parent...') }}">
                                </div>
                            </div>
                            <div class="col-md-5 text-md-end">
                                <div class="d-inline-flex align-items-center">
                                    <label class="me-2 mb-0 text-muted small">{{ __('Show') }}</label>
                                    <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span class="ms-2 text-muted small">{{ __('entries') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item bg-light">
                                <div class="row fw-semibold text-muted small text-uppercase">
                                    <div class="col-md-1">{{ __('ID') }}</div>
                                    <div class="col-md-3">{{ __('Details') }}</div>
                                    <div class="col-md-2">{{ __('Balance') }}</div>
                                    <div class="col-md-2">{{ __('Parent') }}</div>
                                    <div class="col-md-2">{{ __('Operation category') }}</div>
                                    <div class="col-md-2">{{ __('Amount') }}</div>
                                </div>
                            </div>

                            @forelse($operations as $operation)
                                <div class="list-group-item list-group-item-action">
                                    <div class="row align-items-center">
                                        <div class="col-md-1">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill">
                                                #{{ $operation->id }}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-1">
                                                <strong class="text-dark">{{ $operation->operation ?? '-' }}</strong>
                                            </div>
                                            @if($operation->note)
                                                <div class="small text-muted">
                                                    <i class="ri-file-text-line me-1"></i>
                                                    {{ Str::limit($operation->note, 50) }}
                                                </div>
                                            @endif
                                            <div class="small">
                                                <span class="badge bg-info-subtle text-info">
                                                    {{ $operation->io ?? '-' }}
                                                </span>
                                                <span class="badge bg-secondary-subtle text-secondary ms-1">
                                                    {{ $operation->source ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            @if($operation->balance_id)
                                                <span class="badge bg-success-subtle text-success">
                                                    Balance #{{ $operation->balance_id }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            @if($operation->parent_id)
                                                <span class="badge bg-warning-subtle text-warning">
                                                    Parent #{{ $operation->parent_id }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            @if($operation->operation_category_id)
                                                <span class="badge bg-purple-subtle text-purple">
                                                    {{ $operation->opeartionCategory->name ?? 'Category #'.$operation->operation_category_id }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                        <div class="col-md-2">
                                            @if($operation->modify_amount)
                                                <strong class="text-dark">
                                                    {{ number_format($operation->modify_amount, 2) }}
                                                </strong>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item">
                                    <div class="text-center py-5">
                                        <i class="ri-file-list-3-line display-4 text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">{{ __('No balance operations found') }}</h5>
                                        <p class="text-muted mb-0">{{ __('Try adjusting your search criteria') }}</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    @if($operations->hasPages())
                        <div class="card-footer bg-white border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    {{ __('Showing') }}
                                    <strong>{{ $operations->firstItem() ?? 0 }}</strong>
                                    {{ __('to') }}
                                    <strong>{{ $operations->lastItem() ?? 0 }}</strong>
                                    {{ __('of') }}
                                    <strong>{{ $operations->total() }}</strong>
                                    {{ __('entries') }}
                                </div>
                                <div>
                                    {{ $operations->links('vendor.livewire.bootstrap') }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
