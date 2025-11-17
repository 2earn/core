<div class="{{getContainerType()}}">
    <div class="row">
        @php
            $isValidateAccountRoute = Route::getCurrentRoute()->getName() == "validate_account";
        @endphp
        @if(!$isValidateAccountRoute)
            @component('components.breadcrumb')
                @slot('title')
                    {{ __('User Form') }}
                @endslot
            @endcomponent
        @endif
                @include('layouts.flash-messages')
                <div class="card shadow-sm">
                    <div class="card-header bg-transparent border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="ri-file-user-line fs-4 text-info me-2"></i>
                            <h5 class="card-title mb-0 text-info">{{ __('User Information Form') }}</h5>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="user-form-content">
                            <div class="tab-pane {{ $activeTab == 'personalDetails' ? 'show active' : '' }}"
                                 id="personalDetails" role="tabpanel">

                                <form action="javascript:void(0);">
                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <div class="alert alert-info border-0 mb-0" role="alert">
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-information-line fs-5 me-2"></i>
                                                    <small>{{ __('Fields marked with') }} <span
                                                            class="text-danger fw-bold">*</span> {{ __('are required for account validation') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="arLastNameInput" class="form-label fw-semibold">
                                                    <i class="ri-user-line text-primary me-1"></i>
                                                    {{__('Enter your ar firstname label')}}
                                                </label>
                                                <input wire:model="usermetta_info.arLastName" type="text"
                                                       class="form-control" id="arLastNameInput"
                                                       {{ $isValidateAccountRoute || $disabled ? 'disabled' : '' }}
                                                       placeholder="{{__('Enter your ar firstname')}}"
                                                       aria-label="{{__('Enter your ar firstname label')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="arFirstNameInput" class="form-label fw-semibold">
                                                    <i class="ri-user-line text-primary me-1"></i>
                                                    {{__('Enter your ar last label')}}
                                                </label>
                                                <input wire:model="usermetta_info.arFirstName" type="text"
                                                       class="form-control" id="arFirstNameInput"
                                                       {{ $isValidateAccountRoute || $disabled ? 'disabled' : '' }}
                                                       placeholder="{{__('Enter your ar last')}}"
                                                       aria-label="{{__('Enter your ar last label')}}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="enLastNameInput" class="form-label fw-semibold">
                                                    <i class="ri-user-3-line text-primary me-1"></i>
                                                    {{__('Last name label')}}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                       {{ $isValidateAccountRoute || $disabled ? 'disabled' : ''  }}
                                                       wire:model="usermetta_info.enLastName"
                                                       id="enLastNameInput"
                                                       placeholder="{{__('Last Name')}}"
                                                       aria-label="{{__('Last name label')}}"
                                                       aria-required="true">
                                                <div class="form-text">
                                                    <i class="ri-information-line"></i>
                                                    {{__('Required for account validation')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="enFirstNameInput" class="form-label fw-semibold">
                                                    <i class="ri-user-3-line text-primary me-1"></i>
                                                    {{__('First name label')}}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input
                                                    {{ $isValidateAccountRoute || $disabled ? 'disabled' : ''  }}
                                                    wire:model="usermetta_info.enFirstName"
                                                    id="enFirstNameInput"
                                                    placeholder="{{__('First name')}}"
                                                    class="form-control"
                                                    aria-label="{{__('First name label')}}"
                                                    aria-required="true">
                                                <div class="form-text">
                                                    <i class="ri-information-line"></i>
                                                    {{__('Required for account validation')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="phonenumberInput" class="form-label fw-semibold">
                                                    <i class="ri-phone-line text-primary me-1"></i>
                                                    {{ __('Your Contact number') }}
                                                </label>
                                                <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ri-phone-fill text-muted"></i>
                                                        </span>
                                                    <input disabled wire:model="numberActif" type="text"
                                                           class="form-control"
                                                           aria-label="{{ __('Contact number') }}"
                                                           placeholder="{{ __('Your phone number') }}">

                                                    <a href="{{ !empty($user['email']) ? route('contact_number', app()->getLocale()) : '#' }}"
                                                       id="update_tel"
                                                       class="btn btn-outline-info"
                                                       type="button"
                                                       @if(empty($user['email']))
                                                           data-bs-toggle="modal"
                                                       data-bs-target="#topmodal"
                                                       @endif
                                                       aria-label="{{ __('Change phone number') }}">
                                                        <i class="ri-pencil-line me-1"></i>{{ __('Change') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="emailInput" class="form-label fw-semibold">
                                                    <i class="ri-mail-line text-primary me-1"></i>
                                                    {{ __('Your Email') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group">
                                                        <span class="input-group-text bg-light">
                                                            <i class="ri-mail-fill text-muted"></i>
                                                        </span>
                                                    <input disabled wire:model="user.email" type="email"
                                                           class="form-control"
                                                           id="emailInput"
                                                           name="email"
                                                           placeholder="{{ __('your@email.com') }}"
                                                           aria-label="{{ __('Email address') }}"
                                                           style="cursor: pointer;"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#topmodal"
                                                           title="{{ __('Click to update email') }}">
                                                </div>
                                                <div class="form-text">
                                                    <i class="ri-information-line"></i>
                                                    {{__('Required for account validation')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="JoiningdatInput" class="form-label fw-semibold">
                                                    <i class="ri-calendar-line text-primary me-1"></i>
                                                    {{__('Date of birth')}}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input
                                                    {{ $isValidateAccountRoute || $disabled ? 'disabled' : ''  }}
                                                    wire:model="usermetta_info.birthday"
                                                    type="date"
                                                    class="form-control"
                                                    id="JoiningdatInput"
                                                    aria-label="{{__('Date of birth')}}"
                                                    aria-required="true"/>
                                                <div class="form-text">
                                                    <i class="ri-information-line"></i>
                                                    {{__('Required for account validation')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="mb-3">
                                                <label for="websiteInput1" class="form-label fw-semibold">
                                                    <i class="ri-parent-line text-primary me-1"></i>
                                                    {{ __('Number Of Children') }}
                                                </label>
                                                <div class="input-group input-step">
                                                    <button id="btnMinus" type="button"
                                                            class="btn btn-outline-secondary minus"
                                                            {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                            aria-label="{{ __('Decrease children count') }}">
                                                        <i class="ri-subtract-line"></i>
                                                    </button>
                                                    <input wire:model="usermetta_info.childrenCount"
                                                           type="number"
                                                           class="form-control text-center"
                                                           min="0"
                                                           max="100"
                                                           id="inputChild"
                                                           {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                           readonly
                                                           aria-label="{{ __('Number Of Children') }}">
                                                    <button id="btnPlus" type="button"
                                                            class="btn btn-outline-secondary plus"
                                                            {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                            aria-label="{{ __('Increase children count') }}">
                                                        <i class="ri-add-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="designationInput" class="form-label fw-semibold">
                                                    <i class="ri-user-star-line text-primary me-1"></i>
                                                    {{ __('Personal Title') }}
                                                </label>
                                                <select class="form-select"
                                                        wire:model="usermetta_info.personaltitle"
                                                        id="designationInput"
                                                        {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                        aria-label="{{ __('Personal Title') }}">
                                                    <option value="">{{__('no selected value')}}</option>
                                                    @foreach($personaltitles as $personaltitle)
                                                        <option
                                                            value="{{$personaltitle->id}}">{{__($personaltitle->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="genderInput" class="form-label fw-semibold">
                                                    <i class="ri-user-heart-line text-primary me-1"></i>
                                                    {{ __('Gender') }}
                                                </label>
                                                <select class="form-select"
                                                        id="genderInput"
                                                        wire:model="usermetta_info.gender"
                                                        {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                        aria-label="{{ __('Gender') }}">
                                                    <option value="">{{__('no selected value')}}</option>
                                                    @foreach($genders as $gender)
                                                        <option
                                                            value="{{$gender->id}}">{{__($gender->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="languageInput" class="form-label fw-semibold">
                                                    <i class="ri-global-line text-primary me-1"></i>
                                                    {{ __('Your Preferred Language') }}
                                                </label>
                                                <select class="form-select"
                                                        id="languageInput"
                                                        wire:model="usermetta_info.idLanguage"
                                                        {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                        aria-label="{{ __('Your Preferred Language') }}">
                                                    <option value="">{{__('no selected value')}}</option>
                                                    @foreach($languages as $language)
                                                        <option
                                                            value="{{$language->id}}">{{__($language->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="cityInput" class="form-label fw-semibold">
                                                    <i class="ri-map-pin-line text-primary me-1"></i>
                                                    {{ __('State') }}
                                                </label>
                                                <select class="form-select"
                                                        id="cityInput"
                                                        wire:model="usermetta_info.idState"
                                                        {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                        aria-label="{{ __('State') }}">
                                                    <option value="">{{__('Choose')}}</option>
                                                    @foreach($states as $state)
                                                        <option value="{{$state->id}}">{{__($state->name)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="countryInput" class="form-label fw-semibold">
                                                    <i class="ri-earth-line text-primary me-1"></i>
                                                    {{ __('Country') }}
                                                </label>
                                                <input readonly
                                                       wire:model="countryUser"
                                                       type="text"
                                                       class="form-control bg-light"
                                                       id="countryInput"
                                                       {{ $isValidateAccountRoute || $disabled ? 'disabled' : ''  }}
                                                       value="United States"
                                                       aria-label="{{ __('Country') }}"/>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label for="zipcodeInput1" class="form-label fw-semibold">
                                                    <i class="ri-bank-card-2-line text-primary me-1"></i>
                                                    {{ __('National ID') }}
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text"
                                                       class="form-control"
                                                       minlength="5"
                                                       maxlength="50"
                                                       wire:model="usermetta_info.nationalID"
                                                       id="zipcodeInput1"
                                                       {{ $isValidateAccountRoute || $disabled ? 'disabled' : ''  }}
                                                       placeholder="{{ __('Enter your national ID') }}"
                                                       aria-label="{{ __('National ID') }}"
                                                       aria-required="true">
                                                <div class="form-text">
                                                    <i class="ri-information-line"></i>
                                                    {{__('Required for account validation')}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3 pb-2">
                                                <label for="exampleFormControlTextarea"
                                                       class="form-label fw-semibold">
                                                    <i class="ri-map-pin-user-line text-primary me-1"></i>
                                                    {{ __('Address') }}
                                                </label>
                                                <textarea wire:model="usermetta_info.adresse"
                                                          class="form-control"
                                                          id="exampleFormControlTextarea"
                                                          placeholder="{{__('Address')}}"
                                                          rows="3"
                                                          {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                          aria-label="{{ __('Address') }}"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3 pb-2">
                                                <div class="card bg-light border-0">
                                                    <div class="card-body p-3">
                                                        <div class="form-check form-switch d-flex align-items-center" dir="ltr">
                                                            <input wire:model.live="user.is_public"
                                                                   type="checkbox"
                                                                   class="form-check-input me-2"
                                                                   id="customSwitchIsPublic"
                                                                   @checked($user['is_public']??false)
                                                                   {{ $isValidateAccountRoute ? 'disabled' : '' }}
                                                                   role="switch"
                                                                   aria-checked="{{$user['is_public']??false}}">
                                                            <label class="form-check-label mb-0" for="customSwitchIsPublic">
                                                                <i class="ri-hand-heart-line me-1"></i>
                                                                {{ __('I agree to receive funding requests') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if($paramIdUser =="")
                                        <div class="col-lg-12">
                                            <div class="text-end pt-3 border-top">
                                                <button type="button" id="btnsaveUser"
                                                        class="btn btn-primary px-4">
                                                    <i class="ri-save-line me-1"></i>{{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="border-top pt-4">
                                                    <div class="form-inline" x-data="{ open: false }">
                                                        <div class="d-flex gap-2 mb-3">
                                                            <button x-show="!open" type="button"
                                                                    @click="open = true"
                                                                    class="btn btn-danger px-4" id="reject">
                                                                <i class="ri-close-circle-line me-1"></i>{{ __('Reject') }}
                                                            </button>
                                                            <button x-show="!open" class="btn btn-success px-4"
                                                                    wire:click="approuve({{$paramIdUser}})"
                                                                    id="validate">
                                                                <div wire:loading
                                                                     wire:target="approuve({{$paramIdUser}})">
                                                                        <span
                                                                            class="spinner-border spinner-border-sm me-1"
                                                                            role="status"
                                                                            aria-hidden="true"></span>
                                                                </div>
                                                                <i class="ri-checkbox-circle-line me-1"></i>{{ __('Approve') }}
                                                            </button>
                                                        </div>
                                                        <div class="row" x-show="open">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label fw-semibold">
                                                                        <i class="ri-file-text-line me-1"></i>{{ __('Libele_Note') }}
                                                                    </label>
                                                                    <textarea class="form-control"
                                                                              wire:model="noteReject"
                                                                              name="Text1"
                                                                              cols="80"
                                                                              rows="5"
                                                                              placeholder="{{ __('Enter rejection reason') }}"
                                                                              aria-label="{{ __('Rejection note') }}"></textarea>
                                                                </div>
                                                                <div class="d-flex gap-2">
                                                                    <button type="button"
                                                                            wire:click="reject({{$paramIdUser}})"
                                                                            class="btn btn-danger px-4">
                                                                            <span wire:loading
                                                                                  wire:target="reject({{$paramIdUser}})"
                                                                                  class="spinner-border spinner-border-sm me-1"
                                                                                  role="status"
                                                                                  aria-hidden="true"></span>
                                                                        <i class="ri-close-circle-line me-1"></i>{{ __('Reject') }}
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

    </div>
    <!-- Top modal for missing email when changing phone number or updating email -->
    <div id="topmodal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Update Email Address') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 mb-3" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="ri-information-line fs-5 me-2"></i>
                            <small>{{ __('Please enter your email address. You will need to verify it to update your contact number.') }}</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputEmailModal" class="form-label fw-semibold">
                            <i class="ri-mail-line me-1"></i>{{ __('Your Email') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control"
                               id="inputEmailModal"
                               placeholder="{{ __('your@email.com') }}"
                               aria-label="{{ __('Email address') }}"
                               value="{{ $user['email'] ?? '' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>{{ __('Close') }}
                    </button>
                    <button type="button"
                            class="btn btn-primary"
                            id="btnUpdateEmailModal"
                            onclick="updateEmailFromModal()">
                        <i class="ri-send-plane-line me-1"></i>{{ __('Update Email') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        function updateEmailFromModal() {
            const emailInput = document.getElementById('inputEmailModal');
            const email = emailInput.value.trim();

            if (!email) {
                alert('{{ __("Please enter an email address") }}');
                return;
            }

            // Email validation regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('{{ __("Please enter a valid email address") }}');
                return;
            }

            // Dispatch Livewire event to send verification mail
            window.Livewire.dispatch('sendVerificationMail', [email]);

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('topmodal'));
            if (modal) {
                modal.hide();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Handle save button click
            document.getElementById('btnsaveUser')?.addEventListener('click', function () {
                const childrenCount = document.getElementById('inputChild').value;
            @this.call('saveUser', childrenCount)
                ;
            });

            // Handle children count buttons
            document.getElementById('btnPlus')?.addEventListener('click', function () {
                let input = document.getElementById('inputChild');
                let currentValue = parseInt(input.value) || 0;
                if (currentValue < 100) {
                    input.value = currentValue + 1;
                @this.set('usermetta_info.childrenCount', input.value)
                    ;
                }
            });

            document.getElementById('btnMinus')?.addEventListener('click', function () {
                let input = document.getElementById('inputChild');
                let currentValue = parseInt(input.value) || 0;
                if (currentValue > 0) {
                    input.value = currentValue - 1;
                @this.set('usermetta_info.childrenCount', input.value)
                    ;
                }
            });

            // Allow Enter key to submit email in modal
            document.getElementById('inputEmailModal')?.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    updateEmailFromModal();
                }
            });
        });
    </script>
@endpush
