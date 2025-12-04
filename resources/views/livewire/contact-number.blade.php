<div class="{{getContainerType()}}">

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
            <div class="col-12 card shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="ri-phone-line fs-4 text-info me-2"></i>
                            <h5 class="card-title mb-0 text-info">{{ __('Contact Numbers') }}</h5>
                        </div>
                        <a href="{{route('add_contact_number',['locale'=>app()->getLocale()])}}">
                            {{__('Add contact number')}}
                        </a>
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
                                <td class="text-center align-middle"
                                    <?php if ($value->active != 1){ ?> onclick="setActiveNumber({{$value->id}})"
                                    style="cursor: pointer;" <?php } ?> >
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
                                            <button onclick="setActiveNumber({{$value->id}})"
                                                    class="btn btn-sm btn-primary">
                                                <i class="ri-check-line me-1"></i>{{ __('Active') }}
                                            </button>
                                            <button onclick="deleteContactNUmber({{$value->id}})"
                                                    class="btn btn-sm btn-danger">
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
