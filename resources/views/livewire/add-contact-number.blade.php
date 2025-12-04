<div class="{{getContainerType()}}">
    <div>
        <div>
            @component('components.breadcrumb')
                @slot('title')
                    {{ __('Add contact number') }}
                @endslot
            @endcomponent
            <div wire:ignore.self class="modal fade" id="AddContactNumberModel" tabindex="-1"
                 aria-labelledby="AddContactNumberModelTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <i class="ri-phone-add-line fs-4 text-primary me-2"></i>
                                <h5 class="modal-title text-primary fw-semibold mb-0" id="AddContactNumberModelTitle">
                                    {{ __('Add new user phone number') }}
                                </h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="mb-3" dir="ltr">
                                <label class="form-label fw-semibold">
                                    <i class="ri-smartphone-line text-primary me-1"></i>
                                    {{ __('Your new phone number') }}
                                </label>
                                <div id="inputNumberContact" class="input-group w-100 signup mb-3">
                                </div>
                                <div class="form-text">
                                    <i class="ri-information-line"></i>
                                    {{ __('Enter a valid phone number with country code') }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-top">
                            <button type="button" class="btn btn-secondary btn-close-add"
                                    data-bs-dismiss="modal">
                                <i class="ri-close-line me-1"></i>{{ __('Close') }}
                            </button>
                            <button type="button" id="saveAddContactNumber"
                                    class="btn btn-primary">
                                <i class="ri-save-line me-1"></i>{{ __('Save new contact number') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="module">
                var timerInterval;

                document.addEventListener("DOMContentLoaded", function () {

                    $("#saveAddContactNumber").click(function (event) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        window.Livewire.dispatch('preSaveContact', [$("#outputinitIntlTelInput").val(), $("#isoContactNumber").val(), $("#initIntlTelInput").val()]);
                    });

                    // Handle showAlert event for displaying error/success messages
                    window.addEventListener('showAlert', event => {
                        const alertData = event.detail[0];
                        Swal.fire({
                            title: alertData.title || '{{ __("Notification") }}',
                            text: alertData.text || '',
                            icon: alertData.type || 'info',
                            confirmButtonText: '{{ __("ok") }}',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            }
                        }).then(() => {
                            // Reinitialize the input field after error
                            const inputField = document.querySelector("#initIntlTelInput");
                            if (inputField) {
                                inputField.value = '';
                                $("#outputinitIntlTelInput").val('');
                                $("#ccodeinitIntlTelInput").val('');
                                $("#isoContactNumber").val('');
                                $('#saveAddContactNumber').prop("disabled", true);

                                // Trigger the intl-tel-input to update
                                if (typeof itiAddContactNumber !== 'undefined' && itiAddContactNumber) {
                                    itiAddContactNumber.setNumber('');
                                }
                            }
                        });
                    });

                    window.addEventListener('PreAddNumber', event => {
                        $('.btn-close-add').trigger('click');
                        $('#AddContactNumberModel').modal('hide');

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
                                window.location.reload();
                            }
                        })
                    })
                });

            </script>

            <script type="module">
                var ipNumberContact = document.querySelector("#inputNumberContact");
                var itiAddContactNumber; // Declare in outer scope for reuse

                document.addEventListener("DOMContentLoaded", function () {
                    ipNumberContact.innerHTML = "<div class='input-group-prepend'> " +
                        "</div><input wire:model='' type='tel' name='initIntlTelInput' id='initIntlTelInput' class='form-control' onpaste='handlePaste(event)'" +
                        "placeholder='{{ __("Mobile Number") }}'><span id='valid-msginitIntlTelInput' class='invisible'>✓ Valid</span><span id='error-msginitIntlTelInput' class='hide'></span>" +
                        " <input type='hidden' name='fullnumber' id='outputinitIntlTelInput' class='form-control'><input type='hidden' name='ccodeinitIntlTelInput' id='ccodeinitIntlTelInput'>" +
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
</div>
