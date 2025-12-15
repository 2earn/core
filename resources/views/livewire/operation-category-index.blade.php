<div class="{{getContainerType()}}">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Operation category') }}
            @endslot
        @endcomponent

        <div class="row">
                @include('layouts.flash-messages')
        </div>

        <div class="row">
            <div class="col-12 card shadow-sm">
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
                                       placeholder="{{ __('Search by ID, Code, Name or Description...') }}">
                            </div>
                        </div>
                        <div class="col-md-5 text-md-end">
                            <div class="d-inline-flex align-items-center">
                                <label class="me-2 mb-0 text-muted small">{{ __('Show') }}</label>
                                <select wire:model.live="perPage" class="form-select form-select-sm per-page-width">
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
                                <div class="col-md-2">{{ __('Code') }}</div>
                                <div class="col-md-3">{{ __('Name') }}</div>
                                <div class="col-md-4">{{ __('Description') }}</div>
                                <div class="col-md-2 text-center">{{ __('Actions') }}</div>
                            </div>
                        </div>

                        @forelse($categories as $category)
                            <div class="list-group-item list-group-item-action">
                                <div class="row align-items-center">
                                    <div class="col-md-1">
                                            <span class="badge bg-primary-subtle text-primary rounded-pill">
                                                #{{ $category->id }}
                                            </span>
                                    </div>
                                    <div class="col-md-2">
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $category->code }}
                                            </span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong class="text-dark">{{ $category->name }}</strong>
                                    </div>
                                    <div class="col-md-4">
                                            <span class="text-muted">
                                                {{ $category->description ? Str::limit($category->description, 60) : '-' }}
                                            </span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <a href="{{route('balances_categories_create_update',['locale'=>app()->getLocale(),'idCategory'=>$category->id])}}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="ri-edit-line me-1"></i>
                                            {{__('Edit')}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item">
                                <div class="text-center py-5">
                                    <i class="ri-folder-open-line display-4 text-muted mb-3 d-block"></i>
                                    <h5 class="text-muted">{{ __('No operation categories found') }}</h5>
                                    <p class="text-muted mb-0">{{ __('Try adjusting your search criteria') }}</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($categories->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                {{ __('Showing') }}
                                <strong>{{ $categories->firstItem() ?? 0 }}</strong>
                                {{ __('to') }}
                                <strong>{{ $categories->lastItem() ?? 0 }}</strong>
                                {{ __('of') }}
                                <strong>{{ $categories->total() }}</strong>
                                {{ __('entries') }}
                            </div>
                            <div>
                                {{ $categories->links('vendor.livewire.bootstrap') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

