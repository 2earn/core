<div>
    <div class="{{getContainerType()}}">
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Add contact number') }}
            @endslot
        @endcomponent

        <div class="row">
            @include('layouts.flash-messages')
        </div>

        <div class="row">
            <div class="col-12 card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="ri-phone-add-line fs-4 text-primary me-2"></i>
                        <h5 class="card-title mb-0 text-primary fw-semibold">
                            {{ __('Add new user phone number') }}
                        </h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="addContactForm">
                        <div class="mb-3" dir="ltr">
                            <label class="form-label fw-semibold">
                                <i class="ri-smartphone-line text-primary me-1"></i>
                                {{ __('Your new phone number') }}
                            </label>
                            <div id="inputNumberContact" class="w-100 mb-3">
                            </div>
                            <div class="form-text">
                                <i class="ri-information-line me-1"></i>
                                {{ __('Enter a valid phone number with country code') }}
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('contact_number', app()->getLocale()) }}"
                               class="btn btn-secondary">
                                <i class="ri-close-line me-1"></i>{{ __('Cancel') }}
                            </a>
                            <button type="button" id="saveAddContactNumber"
                                    class="btn btn-primary" disabled>
                                <i class="ri-save-line me-1"></i>{{ __('Save new contact number') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type="module">
            // Define default translations (global scope)
            const defaultNotificationTitle = @json(__("Notification"));
            const defaultOkButton = @json(__("ok"));
            const defaultValidText = @json(__("Valid"));
            const mobileNumberPlaceholder = @json(__("Mobile Number"));

            var timerInterval;
            document.addEventListener("DOMContentLoaded", function () {

                $("#saveAddContactNumber").click(function (event) {
                    event.preventDefault();
                    event.stopImmediatePropagation();
                    window.Livewire.dispatch('preSaveContact', [$("#outputinitIntlTelInput").val(), $("#isoContactNumber").val(), $("#initIntlTelInput").val()]);
                });


                window.addEventListener('showAlert', event => {
                    let alertData = event.detail;

                    // Handle different event.detail formats
                    if (Array.isArray(alertData)) {
                        alertData = alertData[0] || {};
                    }

                    // Extract properties with safe fallbacks
                    let title = defaultNotificationTitle;
                    let text = '';
                    let icon = 'info';

                    if (typeof alertData === 'string') {
                        title = alertData;
                    } else if (typeof alertData === 'object' && alertData !== null) {
                        title = alertData.title || defaultNotificationTitle;
                        text = alertData.text || '';
                        icon = alertData.type || 'info';
                    }

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        confirmButtonText: defaultOkButton,
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        }
                    }).then(() => {
                        // Reinitialize the entire inputNumberContact div
                        if (typeof itiAddContactNumber !== 'undefined' && itiAddContactNumber) {
                            try {
                                itiAddContactNumber.destroy();
                            } catch (e) {
                                console.log('Error destroying intl-tel-input:', e);
                            }
                        }

                        // Recreate the input HTML
                        const ipNumberContact = document.querySelector("#inputNumberContact");
                        if (ipNumberContact) {
                            ipNumberContact.innerHTML =
                                "<input wire:model='' type='tel' name='initIntlTelInput' id='initIntlTelInput' class='form-control' onpaste='handlePaste(event)' placeholder='" + mobileNumberPlaceholder + "'>" +
                                "<span id='valid-msginitIntlTelInput' class='invisible'>✓ " + defaultValidText + "</span>" +
                                "<span id='error-msginitIntlTelInput' class='hide'></span>" +
                                "<input type='hidden' name='fullnumber' id='outputinitIntlTelInput' class='form-control'>" +
                                "<input type='hidden' name='ccodeinitIntlTelInput' id='ccodeinitIntlTelInput'>" +
                                "<input type='hidden' name='isoContactNumber' id='isoContactNumber'>";

                            // Reinitialize intl-tel-input
                            const inputAddContactNumber = document.querySelector("#initIntlTelInput");
                            if (inputAddContactNumber) {
                                itiAddContactNumber = window.intlTelInput(inputAddContactNumber, {
                                    initialCountry: "auto",
                                    autoFormat: true,
                                    separateDialCode: true,
                                    useFullscreenPopup: false,
                                    geoIpLookup: function (callback) {
                                        $.get('https://ipinfo.io', function () {
                                        }, "jsonp").always(function (resp) {
                                            var countryCode13 = (resp && resp.country) ? resp.country : "TN";
                                            callback(countryCode13);
                                        });
                                    },
                                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
                                });

                                // Re-attach event listeners
                                function initIntlTelInputValidation() {
                                    var phoneCN = itiAddContactNumber.getNumber();
                                    phoneCN = phoneCN.replace('+', '00');
                                    var countryDataCN = itiAddContactNumber.getSelectedCountryData();
                                    if (!phoneCN.startsWith('00' + countryDataCN.dialCode)) {
                                        phoneCN = '00' + countryDataCN.dialCode + phoneCN;
                                    }
                                    $("#outputinitIntlTelInput").val(phoneCN);
                                    $("#ccodeinitIntlTelInput").val(countryDataCN.dialCode);
                                    $("#isoContactNumber").val(countryDataCN.iso2);
                                    if (itiAddContactNumber.isValidNumber()) {
                                        $('#saveAddContactNumber').prop("disabled", false)
                                    } else {
                                        $('#saveAddContactNumber').prop("disabled", true)
                                    }
                                }

                                inputAddContactNumber.addEventListener('keyup', initIntlTelInputValidation);
                                inputAddContactNumber.addEventListener('countrychange', initIntlTelInputValidation);
                                initIntlTelInputValidation();
                            }
                        }

                        // Disable save button
                        $('#saveAddContactNumber').prop("disabled", true);
                    });
                });

                window.addEventListener('PreAddNumber', event => {
                    Swal.fire({
                        title: '{{ __('Your verification code') }}',
                        html: event.detail[0].msgSend + ' ' + '<br>' + event.detail[0].FullNumber + '<br>' + event.detail[0].userMail + '<br>' + '{{__('Your OTP Code')}}',
                        allowOutsideClick: false,
                        timer: '{{ env('timeOPT',180000) }}',
                        timerProgressBar: true,
                        showCancelButton: true,
                        cancelButtonText: '{{trans('canceled !')}}',
                        confirmButtonText: '{{trans('ok')}}',
                        footer: ' <i></i><div class="footerOpt"></div>',
                        didOpen: () => {
                            const b = Swal.getFooter().querySelector('i')
                            const p22 = Swal.getFooter().querySelector('div')
                            p22.innerHTML = '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';
                            timerInterval = setInterval(() => {
                                b.textContent = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}'
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        },
                        input: 'text',
                        inputAttributes: {autocapitalize: 'off'},
                    }).then((resultat) => {
                        if (resultat.isConfirmed) {
                            window.Livewire.dispatch('saveContactNumber', [resultat.value, event.detail[0].isoP, event.detail[0].mobile, event.detail[0].FullNumberNew]);
                        }
                        if (resultat.isDismissed && resultat.dismiss == 'cancel') {
                            window.location.href = '{{ route("contact_number", app()->getLocale()) }}';
                        }
                    })
                })
            });

            var ipNumberContact = document.querySelector("#inputNumberContact");
            var itiAddContactNumber; // Declare in outer scope for reuse

            document.addEventListener("DOMContentLoaded", function () {
                ipNumberContact.innerHTML =
                    "<input wire:model='' type='tel' name='initIntlTelInput' id='initIntlTelInput' class='form-control' onpaste='handlePaste(event)' placeholder='" + mobileNumberPlaceholder + "'>" +
                    "<span id='valid-msginitIntlTelInput' class='invisible'>✓ " + defaultValidText + "</span>" +
                    "<span id='error-msginitIntlTelInput' class='hide'></span>" +
                    "<input type='hidden' name='fullnumber' id='outputinitIntlTelInput' class='form-control'>" +
                    "<input type='hidden' name='ccodeinitIntlTelInput' id='ccodeinitIntlTelInput'>" +
                    "<input type='hidden' name='isoContactNumber' id='isoContactNumber'>";
                var countryDataNumberContact = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                    inputAddContactNumber = document.querySelector("#initIntlTelInput");
                try {
                    itiAddContactNumber.destroy();
                } catch (e) {

                }
                itiAddContactNumber = window.intlTelInput(inputAddContactNumber, {
                    initialCountry: "auto",
                    autoFormat: true,
                    separateDialCode: true,
                    useFullscreenPopup: false,
                    geoIpLookup: function (callback) {
                        $.get('https://ipinfo.io', function () {
                        }, "jsonp").always(function (resp) {
                            var countryCode13 = (resp && resp.country) ? resp.country : "TN";
                            callback(countryCode13);
                        });
                    },
                    utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
                });

                function initIntlTelInput() {
                    var phoneCN = itiAddContactNumber.getNumber();
                    phoneCN = phoneCN.replace('+', '00');
                    var mobileCN = $("#initIntlTelInput").val();
                    var countryDataCN = itiAddContactNumber.getSelectedCountryData();
                    if (!phoneCN.startsWith('00' + countryDataCN.dialCode)) {
                        phoneCN = '00' + countryDataCN.dialCode + phoneCN;
                    }
                    $("#outputinitIntlTelInput").val(phoneCN);
                    $("#ccodeinitIntlTelInput").val(countryDataCN.dialCode);
                    $("#isoContactNumber").val(countryDataCN.iso2);
                    if (itiAddContactNumber.isValidNumber()) {
                        $('#saveAddContactNumber').prop("disabled", false)
                    } else {
                        $('#saveAddContactNumber').prop("disabled", true)
                    }
                }

                inputAddContactNumber.addEventListener('keyup', initIntlTelInput);
                inputAddContactNumber.addEventListener('countrychange', initIntlTelInput);

                for (var i = 0; i < countryDataNumberContact.length; i++) {
                    var country = countryDataNumberContact[i];
                    var optionNode = document.createElement("option");
                    optionNode.value = country.iso2;
                }
                initIntlTelInput();
            });
        </script>
    </div>
</div>
