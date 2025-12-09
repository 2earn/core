<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Plan label') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Plan label') }}
        @endslot
        @slot('li_1')
            {{ __('Management') }}
        @endslot
    @endcomponent
    <div class="row">
        @include('layouts.flash-messages')
    </div>
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 card card-animate">
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
                                <span class="counter-value"
                                      data-target="{{ $statistics['total'] }}">{{ $statistics['total'] }}</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 card card-animate">
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
                                <span class="counter-value"
                                      data-target="{{ $statistics['active'] }}">{{ $statistics['active'] }}</span>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 card card-animate">
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
        <div class="col-xl-3 col-md-6 card card-animate">
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
    <div class="col-12 card">
        <div class="card-header border-0">
            <div class="row g-4 align-items-center">
                <div class="col-sm">
                    <div>
                        <h5 class="card-title mb-0">{{ __('Plan label List') }}</h5>
                    </div>
                </div>
                <div class="col-sm-auto">
                    <div class="d-flex flex-wrap align-items-start gap-2">
                        @if(\App\Models\User::isSuperAdmin())
                            <a href="{{ route('plan_label_create', ['locale' => app()->getLocale()]) }}"
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


            <div wire:loading.remove>
                @if($labels->count() > 0)
                    <div class="row g-3">
                        @foreach($labels as $formula)
                            <div class="col-12">
                                <div class="card border mb-0">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-lg-8">
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-title bg-soft-primary text-primary rounded fs-20">
                                                                <i class="ri-price-tag-3-line"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            <h5 class="fs-15 mb-0 me-2">{{ $formula->name ?: __('No Name') }}</h5>
                                                            <span class="badge badge-soft-secondary me-2">#{{ $formula->id }}</span>
                                                            @if($formula->is_active)
                                                                <span class="badge badge-soft-success">
                                                                    <i class="ri-checkbox-circle-line align-middle me-1"></i>{{ __('Active') }}
                                                                </span>
                                                            @else
                                                                <span class="badge badge-soft-danger">
                                                                    <i class="ri-close-circle-line align-middle me-1"></i>{{ __('Inactive') }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        @if($formula->description)
                                                            <p class="text-muted mb-3">{{ Str::limit($formula->description, 100) }}</p>
                                                        @endif

                                                        <div class="row g-2">
                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <i class="ri-percent-line text-info"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0 fs-12">{{ __('Initial') }}</p>
                                                                        <span class="badge badge-soft-info fs-11">{{ number_format($formula->initial_commission, 2) }}%</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <i class="ri-percent-line text-success"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0 fs-12">{{ __('Final') }}</p>
                                                                        <span class="badge badge-soft-success fs-11">{{ number_format($formula->final_commission, 2) }}%</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0 me-2">
                                                                        <i class="ri-price-tag-line text-primary"></i>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <p class="text-muted mb-0 fs-12">{{ __('Range') }}</p>
                                                                        <span class="badge badge-soft-primary fs-11">{{ $formula->getCommissionRange() }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @if($formula->step)
                                                                <div class="col-auto">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <i class="ri-sort-asc text-warning"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <p class="text-muted mb-0 fs-12">{{ __('Step') }}</p>
                                                                            <span class="badge badge-soft-warning fs-11">{{ $formula->step }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($formula->rate)
                                                                <div class="col-auto">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <i class="ri-dashboard-line text-danger"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <p class="text-muted mb-0 fs-12">{{ __('Rate') }}</p>
                                                                            <span class="badge badge-soft-danger fs-11">{{ number_format($formula->rate, 2) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            @if($formula->stars)
                                                                <div class="col-auto">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="flex-shrink-0 me-2">
                                                                            <i class="ri-star-fill text-warning"></i>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <p class="text-muted mb-0 fs-12">{{ __('Stars') }}</p>
                                                                            <div>
                                                                                @for($i = 1; $i <= 5; $i++)
                                                                                    <i class="ri-star-{{ $i <= $formula->stars ? 'fill' : 'line' }} text-warning fs-12"></i>
                                                                                @endfor
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="d-flex flex-column align-items-lg-end">
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="ri-calendar-line align-middle me-1"></i>
                                                            {{ $formula->created_at->format('Y-m-d') }}
                                                        </small>
                                                    </div>

                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <div class="hstack gap-2">
                                                            <button wire:click="toggleActive({{ $formula->id }})"
                                                                    class="btn btn-sm btn-soft-{{ $formula->is_active ? 'warning' : 'success' }}"
                                                                    title="{{ $formula->is_active ? __('Deactivate') : __('Activate') }}">
                                                                <i class="ri-{{ $formula->is_active ? 'pause' : 'play' }}-circle-line align-middle me-1"></i>
                                                                {{ $formula->is_active ? __('Deactivate') : __('Activate') }}
                                                            </button>

                                                            <a href="{{ route('plan_label_edit', ['locale' => app()->getLocale(), 'id' => $formula->id]) }}"
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
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
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
                        <h5 class="mt-2">{{ __('No Plan label found') }}</h5>
                        <p class="text-muted">{{ __('Try adjusting your search or filter to find what you are looking for.') }}</p>
                        @if(\App\Models\User::isSuperAdmin())
                            <a href="{{ route('plan_label_create', ['locale' => app()->getLocale()]) }}"
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
                        <button type="button" class="btn btn-light"
                                wire:click="cancelDelete">{{ __('Cancel') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteLabel">
                            <i class="ri-delete-bin-line align-bottom me-1"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

