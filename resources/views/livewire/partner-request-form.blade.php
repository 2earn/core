<div class="{{getContainerType()}}">
    @section('title')
        {{ __('Become a Partner') }}
    @endsection
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('business_hub_additional_income', app()->getLocale()) }}">
                {{ __('Additional income') }}
            </a>
        @endslot
        @slot('title')
            {{ __('Become a Partner') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="col-12 col-md-8 offset-md-2 card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">{{ __('Partnership Request Form') }}</h5>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="submitForm">
                    <!-- Company Name Field -->
                    <div class="form-group mb-3">
                        <label for="companyName" class="form-label">
                            {{ __('Company Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               id="companyName"
                               class="form-control @error('companyName') is-invalid @enderror"
                               wire:model.defer="companyName"
                               placeholder="{{ __('Enter your company name') }}"
                               required>
                        @error('companyName')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Business Sector Field -->
                    <div class="form-group mb-3">
                        <label for="businessSectorId" class="form-label">
                            {{ __('Business Sector') }} <span class="text-danger">*</span>
                        </label>
                        <select id="businessSectorId"
                                class="form-control @error('businessSectorId') is-invalid @enderror"
                                wire:model.defer="businessSectorId"
                                required>
                            <option value="">{{ __('Select a business sector') }}</option>
                            @foreach($businessSectors as $sector)
                                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                            @endforeach
                        </select>
                        @error('businessSectorId')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Platform URL Field -->
                    <div class="form-group mb-3">
                        <label for="platformUrl" class="form-label">
                            {{ __('Platform URL') }} <span class="text-danger">*</span>
                        </label>
                        <input type="url"
                               id="platformUrl"
                               class="form-control @error('platformUrl') is-invalid @enderror"
                               wire:model.defer="platformUrl"
                               placeholder="{{ __('https://example.com') }}"
                               required>
                        @error('platformUrl')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Platform Description Field -->
                    <div class="form-group mb-3">
                        <label for="platformDescription" class="form-label">
                            {{ __('Platform Description') }} <span class="text-danger">*</span>
                        </label>
                        <textarea id="platformDescription"
                                  class="form-control @error('platformDescription') is-invalid @enderror"
                                  wire:model.defer="platformDescription"
                                  rows="4"
                                  placeholder="{{ __('Describe your platform...') }}"
                                  required></textarea>
                        @error('platformDescription')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Partnership Reason Field -->
                    <div class="form-group mb-3">
                        <label for="partnershipReason" class="form-label">
                            {{ __('Reason for Partnership Request') }} <span class="text-danger">*</span>
                        </label>
                        <textarea id="partnershipReason"
                                  class="form-control @error('partnershipReason') is-invalid @enderror"
                                  wire:model.defer="partnershipReason"
                                  rows="4"
                                  placeholder="{{ __('Explain why you want to partner with us...') }}"
                                  required></textarea>
                        @error('partnershipReason')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>{{ __('Submit Partnership Request') }}
                        </button>
                    </div>

                    <!-- Back Button -->
                    <div class="form-group mt-2">
                        <a href="{{ route('business_hub_additional_income', app()->getLocale()) }}"
                           class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Additional Income') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

