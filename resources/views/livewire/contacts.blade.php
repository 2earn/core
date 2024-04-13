<div>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script data-turbolinks-eval="false">
        // $(document).on('ready turbolinks:load', function () {
        var existeUserContact = '{{Session::has('existeUserContact')}}';

        if (existeUserContact) {
            Swal.fire({
                title: '{{trans('user_existe_déja')}}',
                text: '{{trans('changer_contact')}}',
                icon: "warning",
                showCancelButton: true,
                cancelButtonText: '{{trans('canceled !')}}',
                confirmButtonText: '{{trans('Yes')}}',
            }).then((result) => {
                if (result.isConfirmed) {
                    const iddd = '{{Session::get('sessionIdUserExiste')}}';
                    var url = "{{ route('editContact2', ['locale' =>  app()->getLocale(), 'UserContact'=> Session::get('sessionIdUserExiste')]) }}";
                    document.location.href = url;
                }
            })
            ;
            // window.location.reload();
        }

        var toEditForm = '{{Session::has('toEditForm')}}';
        if (toEditForm) {

            var someTabTriggerEl = document.querySelector('#pills-AddContact-tab');
            var tab = new bootstrap.Tab(someTabTriggerEl);
            tab.show();
        }

        var existUpdate = '{{Session::has('SessionUserUpdated')}}';
        if (existUpdate) {

            toastr.success('Succées');
        }

    </script>
    @component('components.breadcrumb')
        @slot('title') {{ __('You Contacts') }} @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="leadsList">
                <div class="card-header border-0">
                    <div class="row g-4 align-items-center">

                        <div class="col-sm-auto ms-auto">
                            <div class="hstack gap-2">
                                <button type="button" class="btn btn-secondary add-btn btn2earn" data-bs-toggle="modal"
                                        id="create-btn" data-bs-target="#showModal" hidden><i
                                        class="ri-add-line align-bottom me-1" hidden></i> {{ __('Add a contact') }}</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive ">

                    <table class="table align-middle dt-responsive nowrap" id="customerTable2">
                        <thead class="table-light">
                        <tr class="tabHeader2earn">

                            <th class="sort" data-sort="name">{{ __('Name') }}</th>
                            <th class="sort" data-sort="lastName">{{ __('Last Name') }}</th>
                            <th class="sort" data-sort="mobile">{{ __('Phone') }}</th>
                            <th class="sort" data-sort="mobile">{{__('Country')}}</th>
                            <th class="sort" data-sort="mobile">{{__('registred')}}</th>
                            <th class="sort" data-sort="mobile">{{__('reserve')}}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="list form-check-all">
                        @foreach ($contactUser as $value)
                            <tr>

                                <td>
                                    {{ $value->name}}
                                </td>
                                <td>{{$value->lastName}}</td>
                                <td>{{$value->mobile}}</td>

                                <td>
                                    <div class="d-flex align-items-center fw-medium">
                                        <img
                                            src="{{ URL::asset('assets/images/flags/'.   Illuminate\Support\Str::lower($value->apha2)  .'.svg') }}"
                                            alt="" class="avatar-xxs me-2">
                                        <a href="javascript:void(0);"
                                           class="currency_name"> {{getCountryByIso($value->apha2)}}</a>
                                    </div>
                                </td>
                                <td><span class="badge rounded-pill {{$value->color}}"><i class="mdi mdi-circle-medium">{{$value->status}}</i> </span></td>



                                @php

                                    $disableUntil = getSwitchBlock($value->id);
                                    if($value->availablity == 1) $disableUntil = now();
                                    else $disableUntil = getSwitchBlock($value->id);// Désactiver le commutateur jusqu'à 24 heures à partir de maintenant
                                @endphp
                                <td>
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <input type="checkbox" class="balance-switch-c form-check-input" role="switch" data-id="{{$value->id}}"
                                        {{$value->availablity == 1 ? 'checked' : ''}}  {{$disableUntil > now()   ? 'disabled' : ''}}></div></td>
                                <td>
                                    <script>
                                        $(document).on('change', '.balance-switch-c', function () {
                                            var id = $(this).data('id');
                                            var status = $(this).prop('checked');
                                            // Make an AJAX request to update the status
                                            $.ajax({
                                                url: '{{ route('update-reserve-date') }}', // Adjust the endpoint URL
                                                method: 'POST',
                                                data: { id: id, status: status,"_token": "{{ csrf_token() }}" },
                                                success: function (data) {
                                                },
                                                error: function (xhr, status, error) {
                                                    // Handle error
                                                }
                                            });
                                        });
                                    </script>
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-soft-secondary btn-sm dropdown" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-fill align-middle"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
{{--                                            <li><a href="" class="dropdown-item"><i--}}
{{--                                                        class="ri-eye-fill align-bottom me-2 text-muted"></i> {{__('View')}}--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
                                            <li><a
                                                    href="{{ route('editContact2', ['locale' =>  app()->getLocale(), 'UserContact'=>  $value->id  ]) }}"
                                                    class="dropdown-item edit-item-btn"><i
                                                        class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                                    {{__('Edit')}}</a></li>
                                            <li>
                                                <a onclick="deleteId('{{$value->id}}')"
                                                   class="dropdown-item remove-item-btn">
                                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                    {{__('Delete')}}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <!--end col-->
    </div>
{{--    <div class="row">--}}
{{--        <h4 style="padding-top: 5px" class="card-title">{{ __('Import Your Contact') }} </h4>--}}
{{--        <div class="input-group">--}}
{{--            <label for="inputGroupFileAddon03"  >Select Image</label>--}}
{{--            <button class="btn btn-outline-primary" type="button" id="inputGroupFileAddon03">{{ __('Save') }}</button>--}}
{{--            <input value="55" type="file" class="form-control" id="inputGroupFile03" aria-describedby="inputGroupFileAddon03"--}}
{{--                   aria-label="Upload">--}}
{{--        </div>--}}

{{--    </div>--}}
<!-- Modal -->
    <div wire:ignore.self class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
         aria-labelledby="deleteRecordLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="btn-close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                               colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">{{__('Delete_Confirm')}} </h4>
                        <p class="text-muted fs-14 mb-4 pt-1">{{__('Are_you_sure_want_to_delete?')}}</p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                    id="deleteRecord-close" data-bs-dismiss="modal"><i
                                    class="ri-close-line me-1 align-middle"></i>
                                {{__('Close')}}</button>
                            <button class="btn btn-danger" onclick="deleteId()"
                                    id="delete-record">{{__('Yes, Delete')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end modal -->
    <div wire:ignore.self class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal"></button>
                </div>
                @error('name') <span
                    class="error alert-danger">{{ $message }}</span> @enderror
                @error('lastName') <span
                    class="error alert-danger  ">{{ $message }}</span> @enderror
                @if(Session::has('message'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('message')}}
                    </div>
                @endif
                <form action="">
                    @csrf
                    <div class="modal-body">
                        <input id="id-field" style="display: none"
                               type="text"
                               class="form-control" name="name"
                               placeholder="name ">
                        <div class="row g-3">
                            <div class="col-lg-12">

                                <div>
                                    <label for="nameField" class="form-label">{{ __('Name') }}</label>
                                    <input id="nameField"
                                           type="text"
                                           class="form-control" wire:model.defer="name" name="nameField"
                                           required>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div>
                                    <label for="lastNameField" class="form-label">{{ __('Last Name') }}</label>
                                    <input id="lastNameField"
                                           type="text"
                                           class="form-control" wire:model.defer="lastName" name="lastNameField"
                                           required>
                                </div>
                            </div>
                            <div class=" col-lg-12">
                                <div class="mb-3">
                                    <label for="username"
                                           class="form-label">{{ __('Mobile Number') }}</label><br>
                                    <input type="tel" name="mobile" id="ipAdd2Contact"
                                           class="form-control"
                                           value=""
                                           placeholder="{{ __('PH_MobileNumber') }}">

                                    <input type='hidden' name='fullnumber' id='outputAdd2Contact' class='form-control'>
                                    <input type='hidden' name='ccodeAdd2Contact' id='ccodeAdd2Contact'>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="button" onclick="saveContactEvent()" class="btn btn-success"
                                    id="add-btn">{{__('Save')}}</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="deleteModal" tabindex="-1" role="dialog"
         aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="deleteModalLabel">{{__('Delete_Confirm')}} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true close-btn">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{__('Are_you_sure_want_to_delete?')}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close-btn"
                            data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" wire:click.prevent="delete()"
                            class="btn btn-danger close-modal"
                            data-bs-dismiss="modal">{{__('Yes, Delete')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modalimport" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" data-bs-backdrop="static"
         data-bs-keyboard="false">
        <div wire:ignore class=" modal-dialog modal-dialog-centered " role="document">
            {{--                modal-dialog-centered--}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="ImportModalLabel">{{ __('Import Your Contact') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="basic-form" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="alert alert-info" role="alert">
                                {{ __('Only Csv files') }}
                            </div>
                            <form style="display: flex" action=""
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="idUser" value="197604161"
                                           hidden>
                                    <input type="file" class="form-control"
                                           id="inputGroupFile03"
                                           aria-describedby="inputGroupFileAddon03"
                                           aria-label="Upload" accept=".csv"
                                           onchange="uploadFile()">
                                    <button class="btn btn-secondary" type="button"
                                            id="inputGroupFileAddon03">{{ __('Import') }}</button>
                                </div>
                            </form>


                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        function initNewUserContact() {
            window.livewire.emit('initNewUserContact');
        }

        function saveContactEvent() {

            inputphone = document.getElementById("ipAdd2Contact");
            inputname = document.getElementById("ccodeAdd2Contact");
            inputlast = document.getElementById("outputAdd2Contact");

            if (inputphone.value.trim() && inputname.value.trim() && inputlast.value.trim())
                window.livewire.emit('save', $('#ipAdd2Contact').val(), $('#ccodeAdd2Contact').val(), $('#outputAdd2Contact').val());
            else
                alert("erreur number");
        }


        function editContactFunction() {

            // window.livewire.emit('inituserContact', dd);
            inputphone = document.getElementById("mobileField").value;
            inputid = document.getElementById("id-field").value;
            inputname = document.getElementById("nameField").value;
            inputlast = document.getElementById("lastNameField").value;
            window.livewire.emit('edit', inputid, inputname, inputlast, inputphone);

        }

        function deleteId(dd) {

            // console.log(itemId);
             window.livewire.emit('deleteId', dd);


        }

        function deleteContact(dd) {
            window.livewire.emit('deleteContact', dd);
            // $('#contacts_table').DataTable().ajax.reload( );
        }


    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.0.2/list.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js"></script>

    <!-- Sweet Alerts js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script data-turbolinks-eval="false">
        var lan = "{{config('app.available_locales')[app()->getLocale()]['tabLang']}}";
        var urlLang = "//cdn.datatables.net/plug-ins/1.12.1/i18n/" + lan + ".json";
        $('#customerTable2').DataTable(
            {
                retrieve: true,
                "colReorder": true,
                "orderCellsTop": true,
                "fixedHeader": true,
                "language": {
                    "url": urlLang
                }
            }
        );
    </script>

    <script>

    </script>
</div>
