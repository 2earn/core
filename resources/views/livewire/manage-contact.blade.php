<div class="container">

    @component('components.breadcrumb')
        @slot('title')
            {{ $isEditMode ? __('Edit contact') : __('Add a contact') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
            <div class="col-12 card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ $isEditMode ? __('Edit a contact') : __('Add a contact') }}
                    </h5>
                </div>
                <div class="card-body">
                    @error('contactName')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('contactLastName')
                    <div class="alert alert-danger">{{ $message }}</div>
                    @enderror

                    <form>
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="contactName" class="form-label">
                                        {{ __('FirstName') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="contactName"
                                        id="contactName"
                                        class="form-control"
                                        name="contactName"
                                        placeholder="{{ __('Enter first name') }}"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="contactLastName" class="form-label">
                                        {{ __('LastName') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model="contactLastName"
                                        id="contactLastName"
                                        class="form-control"
                                        name="contactLastName"
                                        placeholder="{{ __('Enter last name') }}"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="intl-tel-input" class="form-label">
                                        {{ __('Mobile Number') }}
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        wire:model="mobile"
                                        type="tel"
                                        name="mobile"
                                        id="intl-tel-input"
                                        class="form-control mobile-input-with-flag"
                                        placeholder="{{ __('Mobile number') }}"
                                    >
                                    <input type='hidden' name='fullnumber' id='outputAddContact' class='form-control'>
                                    <input type='hidden' name='ccodeAddContact' id='ccodeAddContact'>
                                    <p hidden id="codecode">{{ $phoneCode ?? '' }}</p>
                                    <span class="text-danger" id="error-msg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="hstack gap-2">
                                <button type="button" onclick="saveContactEvent()" class="btn btn-success" id="saveContactBtn">
                                    <i class="ri-save-line align-bottom me-1"></i>
                                    <span id="btnText">{{ $isEditMode ? __('Update Contact') : __('Save Contact') }}</span>
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true" id="btnSpinner"></span>
                                </button>
                                <a href="{{ route('contacts_index', app()->getLocale()) }}" class="btn btn-light">
                                    <i class="ri-close-line align-bottom me-1"></i>
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <script>
        // Global instance
        window.itiLog = null;

        function updateHiddenFields() {
            if (!window.itiLog) return;

            const countryData = window.itiLog.getSelectedCountryData();
            let phone = window.itiLog.getNumber();

            if (phone) {
                phone = phone.replace('+', '00');
                if (!phone.startsWith('00' + countryData.dialCode)) {
                    phone = '00' + countryData.dialCode + phone.replace(/^00/, '');
                }

                const ccodeEl = document.getElementById('ccodeAddContact');
                const fullEl = document.getElementById('outputAddContact');
                if (ccodeEl) ccodeEl.value = countryData.dialCode;
                if (fullEl) fullEl.value = phone;
            }
        }

        function initializeIntlTelInput() {
            const input = document.getElementById('intl-tel-input');
            if (!input || !window.intlTelInput) {
                console.warn('Input or intlTelInput not available');
                return;
            }

            // Destroy existing instance
            if (window.itiLog && typeof window.itiLog.destroy === 'function') {
                try {
                    window.itiLog.destroy();
                } catch (e) {
                    console.warn('Failed to destroy existing instance:', e);
                }
                window.itiLog = null;
            }

            // Get phone code from hidden element
            const phoneCode = (document.getElementById('codecode')?.textContent || '').trim();
            const initialCountry = phoneCode !== '' ? phoneCode : 'auto';

            // Initialize intl-tel-input
            window.itiLog = window.intlTelInput(input, {
                initialCountry: initialCountry,
                autoFormat: true,
                separateDialCode: true,
                useFullscreenPopup: false,
                showSelectedDialCode: true,
                formatOnDisplay: true,
                geoIpLookup: function (callback) {
                    if (initialCountry !== 'auto') {
                        callback(initialCountry.toUpperCase());
                    } else {
                        fetch('https://ipinfo.io/json')
                            .then(response => response.json())
                            .then(data => callback(data.country || 'TN'))
                            .catch(() => callback('TN'));
                    }
                },
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js'
            });

            // Remove old event listeners if any
            input.removeEventListener('keyup', updateHiddenFields);
            input.removeEventListener('countrychange', updateHiddenFields);

            // Add event listeners
            input.addEventListener('keyup', updateHiddenFields);
            input.addEventListener('countrychange', updateHiddenFields);

            // Initial update
            setTimeout(updateHiddenFields, 200);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(initializeIntlTelInput, 150);
        });

        // Reinitialize on Livewire events
        document.addEventListener('livewire:initialized', () => {
            window.Livewire.on('contactPhoneNeedsInit', () => {
                setTimeout(initializeIntlTelInput, 200);
            });
        });

        function saveContactEvent() {
            const inputphone = document.getElementById("intl-tel-input");
            const inputname = document.getElementById("ccodeAddContact");
            const inputlast = document.getElementById("outputAddContact");
            const errorMsg = document.querySelector("#error-msg");
            const saveBtn = document.getElementById("saveContactBtn");
            const btnSpinner = document.getElementById("btnSpinner");
            const btnText = document.getElementById("btnText");

            // Update hidden fields before validation
            if (window.itiLog) {
                updateHiddenFields();
            }

            const phoneNumber = parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
            const inputName = inputname.value.trim();
            const out = inputlast.value.trim();

            if (!inputName || !out || !phoneNumber) {
                errorMsg.innerHTML = "{{ __('Please enter a valid phone number') }}";
                errorMsg.classList.remove("d-none");
                return;
            }

            if (validateContact()) {
                saveBtn.disabled = true;
                btnSpinner.classList.remove("d-none");

                $.ajax({
                    url: '{{ route('validate_phone', app()->getLocale()) }}',
                    method: 'POST',
                    data: {phoneNumber: phoneNumber, inputName: inputName, "_token": "{{ csrf_token() }}"},
                    success: function (response) {
                        if (response.message === "") {
                            window.Livewire.dispatch('save', [phoneNumber, inputname.value.trim(), out]);
                            errorMsg.innerHTML = "";
                            errorMsg.classList.add("d-none");
                        } else {
                            errorMsg.innerHTML = response.message;
                            errorMsg.classList.remove("d-none");
                            saveBtn.disabled = false;
                            btnSpinner.classList.add("d-none");
                        }
                    },
                    error: function(xhr, status, error) {
                        errorMsg.innerHTML = "{{ __('Phone validation failed') }}";
                        errorMsg.classList.remove("d-none");
                        saveBtn.disabled = false;
                        btnSpinner.classList.add("d-none");
                    }
                });
            }
        }

        function validateContact() {
            let valid = true;
            const inputcontactName = document.getElementById("contactName");
            const inputcontactLastName = document.getElementById("contactLastName");

            if (inputcontactName.value.trim() === "") {
                inputcontactName.style.borderColor = '#FF0000';
                valid = false;
            } else {
                inputcontactName.style.borderColor = '#008000';
            }

            if (inputcontactLastName.value.trim() === "") {
                inputcontactLastName.style.borderColor = '#FF0000';
                valid = false;
            } else {
                inputcontactLastName.style.borderColor = '#008000';
            }

            return valid;
        }
    </script>
</div>
