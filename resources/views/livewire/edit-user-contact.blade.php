<div class="container-fluid">
    <div>
        @component('components.breadcrumb')
            @slot('title')
                {{ __('Edit contact') }}
            @endslot
        @endcomponent
        <div class="card">
            <div class="card-header">
                <h5 class="card-title" id="ContactsModalLabel">{{ __('Edit a contact') }}</h5>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.flash-messages')
                    </div>
                </div>
                <div class="row">
                    <form id="basic-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('edit contact First name') }}</label>
                                    <input id="inputNameContact" type="text"
                                           class="form-control" name="name" wire:model="nameUserContact"
                                           placeholder="{{ __('edit contact First name placeholder') }} ">
                                </div>
                                @error('nameUserContact') <span
                                    class="error alert-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('edit contact Last name') }}</label>
                                    <input id="inputlLastNameContact" type="text"
                                           class="form-control" name="inputlLastNameContact"
                                           wire:model="lastNameUserContact"
                                           placeholder="{{ __('edit contact Last Name placeholder') }} "
                                    >
                                </div>
                                @error('lastNameUserContact') <span
                                    class="error alert-danger  ">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Mobile_Number') }}</label>
                                    <div id="ipAddContact" class="input-group signup mb-3">
                                    </div>
                                    <input type="tel" hidden id="pho" wire:model="phoneNumber"
                                           value="{{$phoneNumber}}">
                                    <input type="text" hidden id="intl-tel-input" name="intl-tel-input"
                                           value="{{$phoneNumber}}">
                                    <p hidden id="codecode">{{$phoneCode}}</p>
                                </div>
                            </div>
                            <div class="row">
                                <input type="text" name="idUser" hidden>
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-secondary float-end  mx-1"
                                            wire:click="close">{{ __('Close') }}
                                    </button>
                                    <button type="button" id="SubmitAd3dContact" onclick="editContactEvent()"
                                            class="btn btn-outline-info float-end mx-1">
                                        {{ __('Save') }}
                                        <div wire:loading wire:target="save">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                  aria-hidden="true"></span>
                                            <span class="sr-only">{{__('Loading')}}</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            var codePays = document.getElementById('codecode').textContent;
            var errorMap = ['{{trans('Invalid number')}}', '{{trans('Invalid country code')}}', '{{trans('Too shortsss')}}', '{{trans('Too long')}}', '{{trans('Invalid number')}}'];
            var ipAddContact = document.querySelector("#ipAddContact");

            function editContactEvent() {
                ccode = document.getElementById("ccodeAddContact");
                fullNumber = document.getElementById("outputAddContact");
                phone = document.getElementById("intl-tel-input");
                if (ccode.value.trim() && fullNumber.value.trim() && phone.value.trim()) {
                    window.Livewire.dispatch('save', [ccode.value.trim(), fullNumber.value.trim(), phone.value.trim()]);
                } else {
                    console.error("erreur number");
                }
            }


            document.addEventListener("DOMContentLoaded", function () {
                if (document.getElementById("ipAddContact")) {

                    ipAddContact.innerHTML = "<div class='input-group-prepend'> " +
                        "</div><input wire:model='phoneNumber' type='tel' name='intl-tel-input' id='intl-tel-input' class='form-control' onpaste='handlePaste(event)'" +
                        "placeholder='Mobile Number'><span id='valid-msgAddContact' class='invisible'>✓ Valid</span><span id='error-msgAddContact' class='hide'></span>" +
                        "<input type='hidden' name='fullnumber' id='outputAddContact' class='form-control'><input type='hidden' name='ccodeAddContact' id='ccodeAddContact'>";

                    var countryDataAddContact = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                        inputAddContact = document.querySelector("#intl-tel-input");

                    function initIntlTelInput() {
                        var phone = itiAddContact.getNumber();
                        if (phone == "") {
                            phone = $("#pho").val();
                        }
                         document.createTextNode(phone);
                        phone = phone.replace('+', '00');

                        var mobile = $("#intl-tel-input").val();
                        var countryData = itiAddContact.getSelectedCountryData();
                        if (!phone.startsWith('00' + countryData.dialCode)) {
                            phone = '00' + countryData.dialCode + phone;
                        }
                        $("#outputAddContact").val(phone);

                        $("#ccodeAddContact").val(countryData.dialCode);
                        if (inputAddContact.value.trim()) {
                            if (itiAddContact.isValidNumber()) {
                                errorMsg.classList.add("invisible");
                                $("#SubmitAddContact").prop("disabled", false);

                            } else {
                                $("#SubmitAddContact").prop("disabled", true);
                                inputAddContact.classList.add("error");
                                var errorCode = itiAddContact.getValidationError();
                                errorMsg.innerHTML = errorMap[errorCode];
                                errorMsg.classList.remove("invisible");
                            }
                        } else {
                            $("#SubmitAddContact").prop("disabled", true);
                            inputAddContact.classList.remove("error");
                            var errorCode = itiAddContact.getValidationError();
                            errorMsg.innerHTML = errorMap[errorCode];
                            errorMsg.classList.add("invisible");
                        }
                    };

                    var nameInput = document.querySelector('#inputNameContact');
                    var lastNameInput = document.querySelector('#inputlLastNameContact');
                    inputAddContact.addEventListener('keyup', initIntlTelInput);
                    inputAddContact.addEventListener('countrychange', initIntlTelInput);
                    nameInput.addEventListener('keyup', initIntlTelInput);
                    lastNameInput.addEventListener('keyup', initIntlTelInput);
                    var bbol = true;
                    var autoInit = "auto";
                    if (bbol) autoInit = codePays;
                    var itiAddContact = window.intlTelInput(inputAddContact, {
                        initialCountry: autoInit,
                        autoFormat: true,
                        separateDialCode: true,
                        useFullscreenPopup: false,
                        geoIpLookup: function (callback) {
                            $.get('https://ipinfo.io', function () {
                            }, "jsonp").always(function (resp) {
                                var countryCode = (resp && resp.country) ? resp.country : "TN";
                                callback(countryCode);
                            });
                        },
                        utilsScript: " {{Vite::asset('/resources/js/utils.js')}}"
                    });
                    for (var i = 0; i < countryDataAddContact.length; i++) {
                        var country = countryDataAddContact[i];
                        var optionNode = document.createElement("option");
                        optionNode.value = country.iso2;
                    }
                    ;
                    document.querySelector("#intl-tel-input").addEventListener("keypress", function (evt) {
                        if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                            evt.preventDefault();
                        }
                    });
                    var validMsg = document.querySelector("#valid-msgAddContact");
                    var errorMsg = document.querySelector("#error-msgAddContact");
                    inputAddContact.addEventListener('blur', function () {
                        if (inputAddContact.value.trim()) {
                            if (itiAddContact.isValidNumber()) {
                                errorMsg.classList.add("invisible");
                                $("#SubmitAddContact").prop("disabled", false);

                            } else {
                                $("#SubmitAddContact").prop("disabled", true);
                                inputAddContact.classList.add("error");
                                var errorCode = itiAddContact.getValidationError();
                                errorMsg.innerHTML = errorMap[errorCode];
                                errorMsg.classList.remove("invisible");
                            }
                        } else {
                            $("#SubmitAddContact").prop("disabled", true);
                            inputAddContact.classList.add("error");
                            var errorCode = itiAddContact.getValidationError();
                            errorMsg.innerHTML = errorMap[errorCode];
                            errorMsg.classList.remove("invisible");
                        }
                    });
                    initIntlTelInput();
                    $("#intl-tel-input").val($("#pho").val());
                }

            });
        </script>
    </div>
</div>
