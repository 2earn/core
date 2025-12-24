<div class="container">

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
                <div class="card-body">
                    @if(count($userContactNumber) > 0)
                        <div class="row g-3">
                            @foreach ($userContactNumber as $value)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="card border shadow-sm h-100 {{ $value->active == 1 ? 'border-primary' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($value->isID == 1)
                                                        <span class="badge badge-soft-success badge-border" title="{{__('ID_Number')}}">
                                                            <i class="ri-checkbox-circle-line align-middle"></i>
                                                        </span>
                                                    @else
                                                        <span class="badge badge-soft-danger badge-border" title="{{__('ID_Number')}}">
                                                            <i class="ri-close-circle-line align-middle"></i>
                                                        </span>
                                                    @endif
                                                    @if($value->active == 1)
                                                        <span class="badge bg-primary-subtle text-primary">
                                                            <i class="ri-star-fill"></i> {{__('Active')}}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="form-check form-switch" dir="ltr"
                                                     @if($value->active != 1) onclick="setActiveNumber({{$value->id}})" style="cursor: pointer;" @endif>
                                                    <input <?php if ($value->active == 1){ ?> checked disabled
                                                           style="background-color: #3595f6!important; opacity: 1"
                                                           <?php } ?> type="checkbox" class="form-check-input"
                                                           id="customSwitch{{$value->id}}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ri-phone-line text-primary me-2 fs-5"></i>
                                                    <h6 class="mb-0 text-muted small">{{__('Mobile Number')}}</h6>
                                                </div>
                                                <h5 class="fw-semibold mb-0 ms-4">{{$value->fullNumber}}</h5>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="ri-map-pin-line text-primary me-2 fs-5"></i>
                                                    <h6 class="mb-0 text-muted small">{{__('Country')}}</h6>
                                                </div>
                                                <div class="d-flex align-items-center ms-4">
                                                    <img
                                                        src="{{ Vite::asset('resources/images/flags/'.$value->isoP.'.svg') }}"
                                                        alt="{{getCountryByIso($value->isoP)}}"
                                                        class="avatar-xs me-2 rounded-circle shadow-sm">
                                                    <span class="fw-medium">{{getCountryByIso($value->isoP)}}</span>
                                                </div>
                                            </div>

                                            <div class="mt-3 pt-3 border-top">
                                                @if($value->active != 1)
                                                    <div class="d-flex gap-2">
                                                        <button onclick="setActiveNumber({{$value->id}})"
                                                                class="btn btn-sm btn-primary flex-fill">
                                                            <i class="ri-check-line me-1"></i>{{ __('Active') }}
                                                        </button>
                                                        <button onclick="deleteContactNUmber({{$value->id}})"
                                                                class="btn btn-sm btn-danger flex-fill">
                                                            <i class="ri-delete-bin-line me-1"></i>{{__('Delete')}}
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <span class="badge badge-soft-info fs-12 px-3 py-2">
                                                            <i class="ri-check-double-line me-1"></i>{{ __('Activated_number') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-phone-line fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">{{__('No contact numbers found')}}</h5>
                            <p class="text-muted">{{__('Add your first contact number to get started')}}</p>
                            <a href="{{route('add_contact_number',['locale'=>app()->getLocale()])}}"
                               class="btn btn-primary mt-2">
                                <i class="ri-add-line me-1"></i>{{__('Add contact number')}}
                            </a>
                        </div>
                    @endif
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
        </div>
</div>
