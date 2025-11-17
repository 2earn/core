<div class="{{getContainerType()}}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Edit Configuration Setting') }}
        @endslot
        @slot('li_1')
            <a href="{{ route('configuration_setting', app()->getLocale()) }}">{{ __('Configuration Settings') }}</a>
        @endslot
        @slot('li_2')
            {{ __('Edit') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Edit Setting') }}: {{ $parameterName }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="saveSetting">
                        <div class="row">
                            <!-- Parameter Name (Disabled) -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control" disabled wire:model="parameterName">
                                <div class="form-text">{{ __('Parameter name cannot be changed') }}</div>
                            </div>

                            <!-- Integer Value -->
                            @if(!is_null($IntegerValue))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('IntegerValue') }} <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('IntegerValue') is-invalid @enderror"
                                           wire:model.live="IntegerValue"
                                           placeholder="{{ __('IntegerValue') }}">
                                    @error('IntegerValue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- String Value -->
                            @if(!is_null($StringValue))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('StringValue') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('StringValue') is-invalid @enderror"
                                           wire:model.live="StringValue"
                                           placeholder="{{ __('StringValue') }}">
                                    @error('StringValue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Decimal Value -->
                            @if(!is_null($DecimalValue))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('DecimalValue') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('DecimalValue') is-invalid @enderror"
                                           wire:model.live="DecimalValue"
                                           placeholder="{{ __('DecimalValue') }}">
                                    @error('DecimalValue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Unit -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('Unit') }}</label>
                                <input maxlength="5" type="text" class="form-control @error('Unit') is-invalid @enderror"
                                       wire:model.live="Unit"
                                       placeholder="{{ __('Unit') }}">
                                @error('Unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Maximum 5 characters') }}</div>
                            </div>

                            <!-- Automatically Calculated -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('I/O') }}</label>
                                <select class="form-select" wire:model.live="Automatically_calculated">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ __('description') }}</label>
                                <textarea maxlength="250" rows="4" class="form-control @error('Description') is-invalid @enderror"
                                          wire:model.live="Description"
                                          placeholder="{{ __('description') }}"></textarea>
                                @error('Description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Maximum 250 characters') }}</div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line me-1"></i>
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i>
                                        {{ __('Save changes') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Information') }}</h6>
                    <ul class="text-muted mb-0">
                        <li>{{ __('Setting ID') }}: <strong>{{ $settingId }}</strong></li>
                        <li>{{ __('Parameter Name') }}: <strong>{{ $parameterName }}</strong></li>
                        <li>{{ __('Auto Calculated') }}:
                            @if($Automatically_calculated == 1)
                                <span class="badge bg-success">{{ __('Yes') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('No') }}</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
