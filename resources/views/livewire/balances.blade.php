<div class="container">
    <div>
        @component('components.breadcrumb')
        @slot('title')
        {{ __('Balance operations') }}
        @endslot
        @endcomponent

        <div class="row">
            @include('layouts.flash-messages')
        </div>

        <div class="row">
            <div class="col-12 card">
                <div class="card-header">
                    <div class="row g-3 align-items-center">
                        <div class="col-lg-8">
                            <div class="input-group input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="ri-search-line text-primary"></i>
                                </span>
                                <input type="text" wire:model.live.debounce.300ms="search"
                                    class="form-control bg-white border-start-0 ps-0 shadow-none"
                                    placeholder="{{ __('Search by ID, Operation, Note, Balance or Parent...') }}">
                            </div>
                        </div>
                        <div class="col-lg-4 text-end">
                            <div class="d-inline-flex align-items-center bg-light rounded-pill px-3 py-2">
                                <i class="ri-file-list-line text-muted me-2"></i>
                                <select wire:model.live="perPage"
                                    class="form-select form-select-sm border-0 bg-transparent shadow-none pe-4"
                                    style="width: 70px;">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <span class="text-muted small ms-1">{{ __('entries') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <div class="d-none d-md-block sticky-top">
                            <div class="row g-0 px-3 py-3">
                                <div class="col-md-1 fw-semibold text-muted small text-uppercase">
                                    <i class="ri-hashtag me-1"></i>{{ __('ID') }}
                                </div>
                                <div class="col-md-3 fw-semibold text-muted small text-uppercase">
                                    <i class="ri-information-line me-1"></i>{{ __('Details') }}
                                </div>
                                <div class="col-md-2 fw-semibold text-muted small text-uppercase">
                                    <i class="ri-wallet-line me-1"></i>{{ __('Balance') }}
                                </div>
                                <div class="col-md-2 fw-semibold text-muted small text-uppercase">
                                    <i class="ri-parent-line me-1"></i>{{ __('Parent') }}
                                </div>
                                <div class="col-md-2 fw-semibold text-muted small text-uppercase">
                                    <i class="ri-folder-line me-1"></i>{{ __('Category') }}
                                </div>
                                <div class="col-md-2 fw-semibold text-muted small text-uppercase text-end">
                                    <i class="ri-money-dollar-circle-line me-1"></i>{{ __('Amount') }}
                                </div>
                            </div>
                        </div>

                        @forelse($operations as $operation)
                            <div class="border-bottom hover-bg-light transition">
                                <div class="row g-0 px-3 py-3 align-items-center">
                                    <div class="col-md-1 mb-2 mb-md-0">
                                        <span class="badge bg-primary text-white rounded-pill px-3 py-2 fs-6">
                                            #{{ $operation->id }}
                                        </span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="d-flex flex-column gap-2">
                                            <div>
                                                <span class="badge bg-dark text-white px-2 py-1 fs-6">
                                                    {{ $operation->operation ?? '-' }}
                                                </span>
                                            </div>
                                            @if($operation->note)
                                                <div class="small text-muted d-flex align-items-start">
                                                    <i class="ri-sticky-note-line me-1 mt-1 text-info"></i>
                                                    <span>{{ Str::limit($operation->note, 60) }}</span>
                                                </div>
                                            @endif
                                            <div class="d-flex gap-1 flex-wrap">
                                                @if($operation->io)
                                                    <span
                                                        class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                                        <i class="ri-arrow-left-right-line me-1"></i>{{ $operation->io }}
                                                    </span>
                                                @endif
                                                @if($operation->source)
                                                    <span
                                                        class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                                        <i class="ri-links-line me-1"></i>{{ $operation->source }}
                                                    </span>
                                                @endif
                                                @if($operation->mode)
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                                                        <i class="ri-settings-3-line me-1"></i>{{ $operation->mode }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        @if($operation->balance_id)
                                            <span class="badge bg-success text-white px-3 py-2">
                                                <i class="ri-wallet-3-line me-1"></i>{{ $operation->balance_id }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="ri-close-circle-line"></i> {{ __('N/A') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        @if($operation->parent_id)
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="ri-git-branch-line me-1"></i>#{{ $operation->parent_id }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="ri-close-circle-line"></i> {{ __('N/A') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        @if($operation->operation_category_id && $operation->operationCategory)
                                            <span class="badge bg-purple text-white px-3 py-2">
                                                <i
                                                    class="ri-price-tag-3-line me-1"></i>{{ Str::limit($operation->operationCategory->name, 20) }}
                                            </span>
                                        @elseif($operation->operation_category_id)
                                            <span class="badge bg-purple-subtle text-purple border border-purple px-2 py-1">
                                                <i class="ri-price-tag-3-line me-1"></i>ID:
                                                {{ $operation->operation_category_id }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="ri-close-circle-line"></i> {{ __('N/A') }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-2 text-md-end">
                                        @if($operation->modify_amount)
                                            <div class="d-flex flex-column align-items-md-end">
                                                <span
                                                    class="badge {{ $operation->modify_amount > 0 ? 'bg-success' : 'bg-danger' }} text-white px-3 py-2 fs-6">
                                                    <i class="ri-money-dollar-circle-line me-1"></i>
                                                    {{ number_format($operation->modify_amount, 2) }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="ri-close-circle-line"></i> {{ __('N/A') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-4">
                                    <i class="ri-file-list-3-line display-1 text-primary opacity-25"></i>
                                </div>
                                <h4 class="text-muted fw-semibold mb-2">{{ __('No balance operations found') }}</h4>
                                <p class="text-muted mb-4">{{ __('Try adjusting your search criteria or filters') }}</p>
                                <button wire:click="$set('search', '')" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-refresh-line me-1"></i>{{ __('Clear Search') }}
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($operations->hasPages())
                    <div class="card-footer">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div class="text-muted small order-2 order-md-1">
                                <i class="ri-file-list-line me-1"></i>
                                {{ __('Showing') }}
                                <strong class="text-primary">{{ $operations->firstItem() ?? 0 }}</strong>
                                {{ __('to') }}
                                <strong class="text-primary">{{ $operations->lastItem() ?? 0 }}</strong>
                                {{ __('of') }}
                                <strong class="text-primary">{{ $operations->total() }}</strong>
                                {{ __('entries') }}
                            </div>
                            <div class="order-1 order-md-2">
                                {{ $operations->links('vendor.livewire.bootstrap') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>