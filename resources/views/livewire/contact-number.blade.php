<div class="{{getContainerType()}}">
    <div>

        <div>
            @component('components.breadcrumb')
                @slot('title')
                    {{ __('Contact number') }}
                @endslot
            @endcomponent
            <div class="row">
                @include('layouts.flash-messages')
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-transparent border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line fs-4 text-info me-2"></i>
                                    <h5 class="card-title mb-0 text-info">{{ __('Contact Numbers') }}</h5>
                                </div>
                                <button data-bs-toggle="modal"
                                        data-bs-target="#AddContactNumberModel" type="button"
                                        class="btn btn-soft-info add-btn">
                                    <i class="ri-add-line me-1"></i>{{ __('Add_contact-number') }}
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="example" class="table table-striped table-bordered  display nowrap">
                                <thead class="table-light">
                                <tr class="tabHeader2earn">
                                    <th class="text-center">{{__('ID_Number')}}</th>
                                    <th>{{__('Mobile Number')}}</th>
                                    <th class="text-center">{{__('Active')}}</th>
                                    <th>{{__('Country')}}</th>
                                    <th class="text-center">{{__('Actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($userContactNumber as $value)
                                    <tr>
                                        <td class="text-center align-middle">
                                            @if($value->isID ==1)
                                                <span class="badge badge-soft-success badge-border">
                                                    <i class="ri-checkbox-circle-line align-middle"></i>
                                                </span>
                                            @else
                                                <span class="badge badge-soft-danger badge-border">
                                                    <i class="ri-close-circle-line align-middle"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            <span class="fw-medium">{{$value->fullNumber}}</span>
                                        </td>
                                        <td class="text-center align-middle" <?php if ($value->active != 1){ ?> onclick="setActiveNumber({{$value->id}})" style="cursor: pointer;" <?php } ?> >
                                            <div class="form-check form-switch d-flex justify-content-center" dir="ltr">
                                                <input <?php if ($value->active == 1){ ?> checked disabled
                                                       style="background-color: #3595f6!important; opacity: 1"
                                                       <?php } ?>     type="checkbox" class="form-check-input"
                                                       id="customSwitchsizesm">
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center fw-medium">
                                                <img
                                                    src="{{ Vite::asset('resources/images/flags/'.$value->isoP.'.svg') }}"
                                                    alt="{{getCountryByIso($value->isoP)}}"
                                                    class="avatar-xxs me-2 rounded-circle shadow-sm">
                                                <span class="currency_name">{{getCountryByIso($value->isoP)}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center align-middle">
                                            @if($value->active!=1)
                                                <div class="d-flex gap-2 justify-content-center">
                                                    <button onclick="setActiveNumber({{$value->id}})" class="btn btn-sm btn-primary">
                                                        <i class="ri-check-line me-1"></i>{{ __('Active') }}
                                                    </button>
                                                    <button onclick="deleteContactNUmber({{$value->id}})" class="btn btn-sm btn-danger">
                                                        <i class="ri-delete-bin-line me-1"></i>{{__('Delete')}}
                                                    </button>
                                                </div>
                                            @else
                                                <span class="badge badge-soft-info fs-12 px-3 py-2">
                                                    <i class="ri-check-double-line me-1"></i>{{ __('Activated_number') }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="AddContactNumberModel" tabindex="-1" style="z-index: 9000000"
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
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
            <script>
                function setActiveNumber($id) {
                    try {
                        $('#modalCeckContactNumber').modal('hide');
                    } catch (e) {
                    }

                    Swal.fire({
                        title: '{{ __('Are you sure to activate this number') }}',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: '{{trans('ok')}}',
                        cancelButtonText: '{{trans('canceled !')}}',
                        denyButtonText: '{{trans('No')}}',
                        customClass: {
                            actions: 'my-actions',
                            cancelButton: 'order-1 right-gap',
                            confirmButton: 'order-2',
                            denyButton: 'order-3',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.Livewire.dispatch('setActiveNumber', [1, $id]);
                        }
                    });
                }

                function deleteContactNUmber($id) {
                    Swal.fire({
                        title: '{{ __('delete_contact') }}',
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: '{{trans('ok')}}',
                        cancelButtonText: '{{trans('canceled !')}}',
                        denyButtonText: '{{trans('No')}}',
                        customClass: {
                            actions: 'my-actions',
                            cancelButton: 'order-1 right-gap',
                            confirmButton: 'order-2',
                            denyButton: 'order-3',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.Livewire.dispatch('deleteContact', [$id]);
                        }
                    });
                }
            </script>
            <script type="module">
                var timerInterval;

                document.addEventListener("DOMContentLoaded", function () {

                    $("#saveAddContactNumber").click(function (event) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        $('.btn-close-add').trigger('click')
                        window.Livewire.dispatch('preSaveContact', [$("#outputinitIntlTelInput").val(), $("#isoContactNumber").val(), $("#initIntlTelInput").val()]);
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
                                window.location.reload();
                            }
                        })
                    })
                });

            </script>
            <script type="module">
                document.addEventListener("DOMContentLoaded", function () {
                    $('#example').DataTable({
                        retrieve: true,
                        "colReorder": true,
                        "orderCellsTop": true,
                        "fixedHeader": true,
                        "language": {"url": urlLang},
                    });
                });
            </script>
            <script type="module">
                var ipNumberContact = document.querySelector("#inputNumberContact");
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
                    var itiAddContactNumber = window.intlTelInput(inputAddContactNumber, {
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
