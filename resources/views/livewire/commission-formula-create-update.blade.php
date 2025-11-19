<div class="{{getContainerType()}}">
    @section('title')
        {{ $isEditMode ? __('Edit Commission Formula') : __('Create Commission Formula') }}
    @endsection

    @component('components.breadcrumb')
        @slot('title')
            {{ $isEditMode ? __('Edit Commission Formula') : __('Create Commission Formula') }}
        @endslot
        @slot('li_1')
            <a href="{{ route('commission_formula_index', ['locale' => app()->getLocale()]) }}">
                {{ __('Commission Formulas') }}
            </a>
        @endslot
    @endcomponent


    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ri-percent-line align-middle me-2"></i>
                {{ $isEditMode ? __('Edit Commission Formula') : __('Create New Commission Formula') }}
            </h5>
        </div>

        <div class="card-body">
            {{-- Error Messages --}}
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ri-error-warning-line align-middle me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form wire:submit.prevent="save">
                {{-- Name Field --}}
                <div class="mb-3">
                    <label for="name" class="form-label">
                        {{ __('Formula Name') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           wire:model.blur="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="{{ __('Enter formula name (e.g., Premium Plan)') }}">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">{{ __('A descriptive name for this commission formula.') }}</div>
                </div>

                {{-- Commission Fields Row --}}
                <div class="row">
                    {{-- Initial Commission --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="initial_commission" class="form-label">
                                {{ __('Initial Commission (%)') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       wire:model.blur="initial_commission"
                                       id="initial_commission"
                                       class="form-control @error('initial_commission') is-invalid @enderror"
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       max="100">
                                <span class="input-group-text">%</span>
                                @error('initial_commission')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-text">{{ __('Starting commission percentage (0-100)') }}</div>
                        </div>
                    </div>

                    {{-- Final Commission --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="final_commission" class="form-label">
                                {{ __('Final Commission (%)') }} <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number"
                                       wire:model.blur="final_commission"
                                       id="final_commission"
                                       class="form-control @error('final_commission') is-invalid @enderror"
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       max="100">
                                <span class="input-group-text">%</span>
                                @error('final_commission')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div
                                class="form-text">{{ __('Ending commission percentage (must be greater than initial)') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Commission Range Preview --}}
                @if($initial_commission && $final_commission && $final_commission > $initial_commission)
                    <div class="alert alert-info border-0 mb-3" role="alert">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <strong>{{ __('Commission Range:') }}</strong>
                                {{ number_format($initial_commission, 2) }}% - {{ number_format($final_commission, 2) }}
                                %
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Description Field --}}
                <div class="mb-3">
                    <label for="description" class="form-label">
                        {{ __('Description') }}
                    </label>
                    <textarea wire:model.blur="description"
                              id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="{{ __('Enter a detailed description of this commission formula...') }}"></textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div
                        class="form-text">{{ __('Optional detailed description of when and how this formula should be used.') }}</div>
                </div>

                {{-- Active Status --}}
                <div class="mb-4">
                    <div class="form-check form-switch form-switch-lg">
                        <input type="checkbox"
                               wire:model.live="is_active"
                               class="form-check-input"
                               id="is_active">
                        <label class="form-check-label" for="is_active">
                            {{ __('Active Status') }}
                        </label>
                    </div>
                    <div class="form-text">
                        @if($is_active)
                            <span class="badge badge-soft-success">
                                        <i class="ri-checkbox-circle-line align-middle"></i> {{ __('This formula is active and can be used') }}
                                    </span>
                        @else
                            <span class="badge badge-soft-danger">
                                        <i class="ri-close-circle-line align-middle"></i> {{ __('This formula is inactive and cannot be used') }}
                                    </span>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" wire:click="cancel" class="btn btn-light">
                        <i class="ri-close-line align-middle me-1"></i>
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save">
                                    <i class="ri-save-line align-middle me-1"></i>
                                    {{ $isEditMode ? __('Update Formula') : __('Create Formula') }}
                                </span>
                        <span wire:loading wire:target="save">
                                    <span class="spinner-border spinner-border-sm me-1" role="status"
                                          aria-hidden="true"></span>
                                    {{ __('Saving...') }}
                                </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h6 class="card-title mb-0">
                <i class="ri-question-line align-middle me-1"></i>
                {{ __('Help & Guidelines') }}
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">{{ __('Commission Range') }}</h6>
                    <ul class="list-unstyled text-muted mb-3">
                        <li>
                            <i class="ri-arrow-right-s-line text-success"></i> {{ __('Initial commission must be between 0% and 100%') }}
                        </li>
                        <li>
                            <i class="ri-arrow-right-s-line text-success"></i> {{ __('Final commission must be greater than initial') }}
                        </li>
                        <li>
                            <i class="ri-arrow-right-s-line text-success"></i> {{ __('Both values support 2 decimal places') }}
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">{{ __('Best Practices') }}</h6>
                    <ul class="list-unstyled text-muted mb-3">
                        <li>
                            <i class="ri-arrow-right-s-line text-info"></i> {{ __('Use descriptive names (e.g., "Premium Partner Plan")') }}
                        </li>
                        <li>
                            <i class="ri-arrow-right-s-line text-info"></i> {{ __('Add detailed descriptions for clarity') }}
                        </li>
                        <li><i class="ri-arrow-right-s-line text-info"></i> {{ __('Set inactive when not in use') }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="alert alert-warning border-0 mb-0" role="alert">
                <i class="ri-error-warning-line align-middle me-1"></i>
                <strong>{{ __('Note:') }}</strong> {{ __('All fields marked with') }} <span
                    class="text-danger">*</span> {{ __('are required.') }}
            </div>
        </div>
    </div>

</div>

