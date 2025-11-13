<div class="{{getContainerType()}}">
    <style>
        .iti {
            width: 100% !important;
        }
    </style>

    @component('components.breadcrumb')
        @slot('title')
            {{ $isEditMode ? __('Edit contact') : __('Add a contact') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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
                                        class="form-control"
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
                                <button type="button" onclick="saveContactEvent()" class="btn btn-success">
                                    <i class="ri-save-line align-bottom me-1"></i>
                                    {{ $isEditMode ? __('Update Contact') : __('Save Contact') }}
                                    <div wire:loading wire:target="save" class="d-inline">
                                        <span class="spinner-border spinner-border-sm ms-2" role="status"
                                              aria-hidden="true"></span>
                                    </div>
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
    </div>

    <script>
        function saveContactEvent() {
            inputphone = document.getElementById("intl-tel-input");
            inputname = document.getElementById("ccodeAddContact");
            inputlast = document.getElementById("outputAddContact");
            const errorMsg = document.querySelector("#error-msg");

            // Ensure intl-tel-input has processed the current value
            if (typeof itiLog !== 'undefined' && itiLog) {
                var phone = itiLog.getNumber();
                if (phone && phone.trim() !== '') {
                    phone = phone.replace('+', '00');
                    var countryData = itiLog.getSelectedCountryData();
                    if (!phone.startsWith('00' + countryData.dialCode)) {
                        phone = '00' + countryData.dialCode + phone.replace(/^00/, '');
                    }
                    $("#ccodeAddContact").val(countryData.dialCode);
                    $("#outputAddContact").val(phone);
                }
            }

            var phoneNumber = parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
            var inputName = inputname.value.trim();
            var out = inputlast.value.trim();

            // Validate that we have the necessary phone data
            if (!inputName || !out || !phoneNumber) {
                errorMsg.innerHTML = "{{ __('Please enter a valid phone number') }}";
                errorMsg.classList.remove("d-none");
                return;
            }

            if (validateContact()) {
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
                        }
                    },
                    error: function(xhr, status, error) {
                        errorMsg.innerHTML = "{{ __('Phone validation failed') }}";
                        errorMsg.classList.remove("d-none");
                    }
                });
            }
        }

        function validateContact() {
            var valid = true;
            inputcontactName = document.getElementById("contactName");
            inputcontactLastName = document.getElementById("contactLastName");

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

    <script type="module">
        var itiLog; // Global variable to store the intl-tel-input instance

        document.addEventListener("DOMContentLoaded", function () {
            // Wait a bit for Livewire to populate the field in edit mode
            setTimeout(function() {
                var codePays = document.getElementById('codecode').textContent.trim();
                var inputlog = document.querySelector("#intl-tel-input");

                if (inputlog) {
                    var autoInit = codePays ? codePays : "auto";

                    itiLog = window.intlTelInput(inputlog, {
                        initialCountry: autoInit,
                        autoFormat: true,
                        separateDialCode: true,
                        useFullscreenPopup: false,
                        geoIpLookup: function (callback) {
                            $.get('https://ipinfo.io', function () {
                            }, "jsonp").always(function (resp) {
                                var countryCodelog = (resp && resp.country) ? resp.country : "TN";
                                callback(countryCodelog);
                            });
                        },
                        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
                    });

                    function initIntlTelInput() {
                        // Wait for the plugin to be fully initialized
                        if (!itiLog) return;

                        var phone = itiLog.getNumber();

                        // If phone is empty, don't process
                        if (!phone || phone.trim() === '') {
                            return;
                        }

                        phone = phone.replace('+', '00');

                        var countryData = itiLog.getSelectedCountryData();

                        if (!phone.startsWith('00' + countryData.dialCode)) {
                            phone = '00' + countryData.dialCode + phone.replace(/^00/, '');
                        }

                        $("#ccodeAddContact").val(countryData.dialCode);
                        $("#outputAddContact").val(phone);
                    }

                    inputlog.addEventListener('keyup', initIntlTelInput);
                    inputlog.addEventListener('countrychange', initIntlTelInput);

                    // Initialize on load with a delay to ensure everything is loaded
                    setTimeout(function() {
                        initIntlTelInput();
                    }, 300);
                }
            }, 100);
        });
    </script>
</div>
