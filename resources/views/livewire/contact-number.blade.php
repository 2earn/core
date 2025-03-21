<div class="container-fluid">
    <div>
        <div class="row">
            @include('layouts.flash-messages')
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <button data-bs-toggle="modal"
                                    data-bs-target="#AddContactNumberModel" type="button"
                                    class="btn btn-secondary add-btn btn2earn float-end">{{ __('Add_contact-number') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="example" class="table table-striped table-bordered  display nowrap">
                            <thead class="table-light">
                            <tr class="tabHeader2earn">
                                <th>{{__('ID_Number')}}</th>
                                <th>{{__('Mobile Number')}}</th>
                                <th>{{__('Active')}}</th>
                                <th>{{__('Country')}}</th>
                                <th>{{__('Actions')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($userContactNumber as $value)
                                <tr>
                                    <td style="text-align: center">
                                        @if($value->isID ==1)
                                            <span>
                                        <i style="color: #51A351;font-size: 20px" class="ri-checkbox-circle-line"></i>
                                    </span>
                                        @else
                                            <span>
                                        <i style="color: #f02602;font-size: 20px" class="ri-close-circle-line"></i>
                                    </span>
                                        @endif
                                    </td>
                                    <td>{{$value->fullNumber}}</td>
                                    <td <?php if ($value->active != 1){ ?> onclick="setActiveNumber({{$value->id}})" <?php } ?> >
                                        <div class="form-check form-switch" dir="ltr">
                                            <input <?php if ($value->active == 1){ ?> checked disabled
                                                   style="background-color: #3595f6!important; opacity: 6"
                                                   <?php } ?>     type="checkbox" class="form-check-input"
                                                   id="customSwitchsizesm">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center fw-medium">
                                            <img src="{{ Vite::asset('resources/images/flags/'.$value->isoP.'.svg') }}"
                                                 alt=""
                                                 class="avatar-xxs me-2">
                                            <a href="javascript:void(0);"
                                               class="currency_name"> {{getCountryByIso($value->isoP)}}</a>
                                        </div>
                                    </td>
                                    <td>
                                        @if($value->active!=1)
                                            <a onclick="deleteContactNUmber({{$value->id}})"><span
                                                    class="btn btn-danger">{{__('Delete')}}</span></a>
                                            <a onclick="setActiveNumber({{$value->id}})"><span
                                                    class="btn btn-primary">{{ __('Active') }}</span></a>
                                        @else
                                            <a><span
                                                    class="btn btn-info">{{ __('Activated_number') }}</span></a>
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
             aria-labelledby="AddContactNumberModel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Add new user phone number') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3" dir="ltr">
                            <label>{{ __('Your new phone number') }}</label>
                            <div id="inputNumberContact" class="input-group signup mb-3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-close-add"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="button" id="saveAddContactNumber"
                                class="btn btn-primary">{{ __('Save new contact number') }}
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
                    title: '{{ __('activate_number') }}',
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
                        window.Livewire.dispatch('setActiveNumber', 1, [$id]);
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
                    window.Livewire.dispatch('preSaveContact', [$("#outputphoneContactNumber").val(), $("#isoContactNumber").val(), $("#phoneContactNumber").val()]);
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
    </div>
</div>
