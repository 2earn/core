<div class="{{getContainerType()}}">
    <style>
        .iti {
            width: 100% !important;
        }
    </style>

    @component('components.breadcrumb')
        @slot('title')
            {{ __('Add a contact') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Add a contact') }}</h5>
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
                                    <label for="contactName" class="form-label">{{ __('FirstName') }}
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
                                    <input type='hidden' name='fullnumber' id='outputAdd2Contact' class='form-control'>
                                    <input type='hidden' name='ccodeAdd2Contact' id='ccodeAdd2Contact'>
                                    <span class="text-danger" id="error-msg"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="hstack gap-2">
                                <button type="button" onclick="saveContactEvent()" class="btn btn-success float-end">
=                                    {{__('Save Contact')}}
                                </button>
                                <a href="{{ route('contacts', app()->getLocale()) }}" class="btn btn-light float-end">
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
            inputname = document.getElementById("ccodeAdd2Contact");
            inputlast = document.getElementById("outputAdd2Contact");
            const errorMsg = document.querySelector("#error-msg");
            var out = "00" + inputname.value.trim() + parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
            var phoneNumber = parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
            var inputName = inputname.value.trim();

            if (validateAdd()) {
                $.ajax({
                    url: '{{ route('validate_phone',app()->getLocale()) }}',
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
                    }
                });
            }
        }

        function validateAdd() {
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
        document.addEventListener("DOMContentLoaded", function () {
            var countryDataLog = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [];
            var inputlog = document.querySelector("#intl-tel-input");

            if (inputlog) {
                var itiLog = window.intlTelInput(inputlog, {
                    initialCountry: "auto",
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
                    var phone = itiLog.getNumber();
                    phone = phone.replace('+', '00');
                    var countryData = itiLog.getSelectedCountryData();
                    phone = '00' + countryData.dialCode + phone;
                    $("#ccodeAdd2Contact").val(countryData.dialCode);
                    $("#outputAdd2Contact").val(phone);
                }

                inputlog.addEventListener('keyup', initIntlTelInput);
                inputlog.addEventListener('countrychange', initIntlTelInput);

                // Initialize on load
                initIntlTelInput();
            }
        });
    </script>
</div>

