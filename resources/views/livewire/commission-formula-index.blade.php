<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Commission Formulas') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Commission Formulas') }}
        @endslot
        @slot('li_1')
            {{ __('Management') }}
        @endslot
    @endcomponent


    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Total Formulas') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-list-check align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $statistics['total'] }}">{{ $statistics['total'] }}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Active Formulas') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-success fs-14 mb-0">
                                <i class="ri-checkbox-circle-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                <span class="counter-value" data-target="{{ $statistics['active'] }}">{{ $statistics['active'] }}</span>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Avg Initial Commission') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-info fs-14 mb-0">
                                <i class="ri-percent-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                {{ number_format($statistics['avg_initial'], 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0">{{ __('Avg Final Commission') }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <h5 class="text-primary fs-14 mb-0">
                                <i class="ri-percent-line align-middle"></i>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">
                                {{ number_format($statistics['avg_final'], 2) }}%
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-header border-0">
            <div class="row g-4 align-items-center">
                <div class="col-sm">
                    <div>
                        <h5 class="card-title mb-0">{{ __('Commission Formulas List') }}</h5>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex flex-wrap align-items-start gap-2">
                        @if(\App\Models\User::isSuperAdmin())
                            <a href="{{ route('commission_formula_create', ['locale' => app()->getLocale()]) }}"
                               class="btn btn-success add-btn">
                                <i class="ri-add-line align-bottom me-1"></i> {{ __('Add Formula') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row g-3">

                <div class="col-xxl-4 col-sm-6">
                    <div class="search-box">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               class="form-control search"
                               placeholder="{{ __('Search by name or description...') }}">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>


                <div class="col-xxl-2 col-sm-4">
                    <div>
                        <select wire:model.live="filterActive" class="form-control">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="1">{{ __('Active') }}</option>
                            <option value="0">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>


                <div class="col-xxl-2 col-sm-4">
                    <div>
                        <button wire:click="clearFilters" class="btn btn-light w-100">
                            <i class="ri-filter-off-line align-bottom me-1"></i> {{ __('Clear Filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

            <div wire:loading class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                </div>
            </div>
            <div class="row">
                    @include('layouts.flash-messages')
            </div>

            <div wire:loading.remove >
                @if($formulas->count() > 0)
                    <div class="row g-3">
                        @foreach($formulas as $formula)
                            <div class="col-12">
                                <div class="card border shadow-none mb-0 {{ $formula->is_active ? '' : 'bg-light' }}">
                                    <div class="card-body">
                                        <div class="row align-items-center">

                                            <div class="col-lg-3 col-md-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-16">
                                                                <i class="ri-percent-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-15 mb-1">
                                                            {{ $formula->name ?: __('No Name') }}
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            <small>{{ __('ID') }}: #{{ $formula->id }}</small>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-5 col-md-4">
                                                <div class="row g-2">
                                                    <div class="col-auto">
                                                        <div class="text-center">
                                                            <p class="text-muted mb-1 fs-11 text-uppercase">{{ __('Initial') }}</p>
                                                            <span class="badge badge-soft-info fs-13">
                                                                {{ number_format($formula->initial_commission, 2) }}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto d-flex align-items-center">
                                                        <i class="ri-arrow-right-line text-muted"></i>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="text-center">
                                                            <p class="text-muted mb-1 fs-11 text-uppercase">{{ __('Final') }}</p>
                                                            <span class="badge badge-soft-success fs-13">
                                                                {{ number_format($formula->final_commission, 2) }}%
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="vr h-100"></div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="text-center">
                                                            <p class="text-muted mb-1 fs-11 text-uppercase">{{ __('Range') }}</p>
                                                            <span class="badge badge-soft-primary fs-12">
                                                                {{ $formula->getCommissionRange() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-lg-4 col-md-4">
                                                <div class="d-flex align-items-center justify-content-end gap-3">

                                                    <div class="text-center">
                                                        @if($formula->is_active)
                                                            <span class="badge badge-soft-success">
                                                                <i class="ri-checkbox-circle-line align-middle me-1"></i>{{ __('Active') }}
                                                            </span>
                                                        @else
                                                            <span class="badge badge-soft-danger">
                                                                <i class="ri-close-circle-line align-middle me-1"></i>{{ __('Inactive') }}
                                                            </span>
                                                        @endif
                                                        <p class="text-muted mb-0 mt-1">
                                                            <small>{{ $formula->created_at->format(config('app.date_format')) }}</small>
                                                        </p>
                                                    </div>


                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <div class="flex-shrink-0">
                                                            <div class="hstack gap-1">

                                                                <button wire:click="toggleActive({{ $formula->id }})"
                                                                        class="btn btn-sm btn-soft-{{ $formula->is_active ? 'warning' : 'success' }}"
                                                                        title="{{ $formula->is_active ? __('Deactivate') : __('Activate') }}">
                                                                    <i class="ri-{{ $formula->is_active ? 'pause' : 'play' }}-circle-line"></i>
                                                                </button>


                                                                <a href="{{ route('commission_formula_edit', ['locale' => app()->getLocale(), 'id' => $formula->id]) }}"
                                                                   class="btn btn-sm btn-soft-info"
                                                                   title="{{ __('Edit') }}">
                                                                    <i class="ri-edit-2-line"></i>
                                                                </a>


                                                                <button wire:click="confirmDelete({{ $formula->id }})"
                                                                        class="btn btn-sm btn-soft-danger"
                                                                        title="{{ __('Delete') }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>


                                        @if($formula->description)
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="border-top pt-3">
                                                        <p class="text-muted mb-0">
                                                            <i class="ri-information-line align-middle me-1"></i>
                                                            {{ Str::limit($formula->description, 150) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ri-file-list-3-line display-4 text-muted"></i>
                        </div>
                        <h5 class="mt-2">{{ __('No commission formulas found') }}</h5>
                        <p class="text-muted">{{ __('Try adjusting your search or filter to find what you are looking for.') }}</p>
                        @if(\App\Models\User::isSuperAdmin())
                            <a href="{{ route('commission_formula_create', ['locale' => app()->getLocale()]) }}"
                               class="btn btn-success mt-3">
                                <i class="ri-add-line align-bottom me-1"></i> {{ __('Add First Formula') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>


    @if($showDeleteModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="ri-alert-line display-5 text-warning"></i>
                            <div class="mt-4">
                                <h4 class="mb-3">{{ __('Are you sure?') }}</h4>
                                <p class="text-muted mb-4">{{ __('This will soft delete the commission formula. You can restore it later if needed.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="cancelDelete">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteFormula">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
