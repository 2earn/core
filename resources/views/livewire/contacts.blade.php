<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('You Contacts') }}
        @endslot
    @endcomponent
    @if(Session::has('message'))
        <div class="alert alert-primary" role="alert">
            {{ Session::get('message') }}
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="leadsList">
                <div class="card-header border-0">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm-auto ms-auto">
                            <div class="hstack gap-2">
                                <button type="button" class="btn btn-secondary add-btn btn2earn" data-bs-toggle="modal"
                                        id="create-btn" data-bs-target="#addModal"><i
                                        class="ri-add-line align-bottom me-1 "></i> {{ __('Add a contact') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table align-middle dt-responsive nowrap " id="customerTable2">
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
                                <td>{{ $value->name}}</td>
                                <td>{{$value->lastName}}</td>
                                <td>{{$value->mobile}}</td>
                                <td>
                                    <div class="d-flex align-items-center fw-medium">
                                        <img
                                            src="{{ URL::asset('assets/images/flags/'. Illuminate\Support\Str::lower($value->apha2) .'.svg') }}"
                                            alt=""
                                            class="avatar-xxs me-2">
                                        <a href="javascript:void(0);"
                                           class="currency_name"> {{getCountryByIso($value->apha2)}}</a>
                                    </div>
                                </td>
                                <td><span class="badge rounded-pill {{$value->color}}">
                                        <i class="mdi mdi-circle-medium">{{$value->status}}</i>
                                    </span>
                                </td>
                                @php
                                    $disableUntil = getSwitchBlock($value->id);
                                    if($value->availablity == 1) $disableUntil = now();
                                    else $disableUntil = getSwitchBlock($value->id);// Désactiver le commutateur jusqu'à 24 heures à partir de maintenant
                                @endphp
                                <td>
                                    <div class="form-check form-switch form-switch-custom form-switch-success mb-3">
                                        <input type="checkbox" class="balance-switch-c form-check-input" role="switch"
                                               data-id="{{$value->id}}"
                                            {{$value->availablity == 1 ? 'checked' : ''}}  {{$disableUntil > now()   ? 'disabled' : ''}}>
                                    </div>
                                </td>
                                <td>
                                    <script>
                                        $(document).on('change', '.balance-switch-c', function () {
                                            var id = $(this).data('id');
                                            var status = $(this).prop('checked');
                                            // Make an AJAX request to update the status
                                            $.ajax({
                                                url: '{{ route('update-reserve-date') }}', // Adjust the endpoint URL
                                                method: 'POST',
                                                data: {id: id, status: status, "_token": "{{ csrf_token() }}"},
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
    </div>

    <!-- Modals -->
    <div wire:ignore.self class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id=addModalLabel"> {{ __('Add a contact') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal"></button>
                </div>
                @error('name') <span
                    class="error alert-danger">{{ $message }}</span>
                @enderror
                @error('lastName') <span
                    class="error alert-danger">{{ $message }}</span>
                @enderror
                <form action="">
                    @csrf
                    <div class="modal-body">
                        <input
                            id="id-field"
                            type="hidden"
                            class="form-control" name="name"
                            placeholder="name"
                            wire:model.defer="selectedContect"
                        >
                        <div class="row g-3">
                            <div class="col-lg-12">

                                <div>
                                    <label for="nameField" class="form-label">{{ __('Name') }}</label>
                                    <input
                                        id="nameField"
                                        type="text"
                                        class="form-control"
                                        wire:model.defer="name"
                                        name="nameField"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="lastNameField" class="form-label">{{ __('Last Name') }}</label>
                                    <input
                                        id="lastNameField"
                                        type="text"
                                        class="form-control"
                                        wire:model.defer="lastName"
                                        name="lastNameField"
                                        required>
                                </div>
                            </div>
                            <div class=" col-lg-12">
                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Mobile Number') }}</label><br>
                                    <input
                                        type="tel"
                                        name="mobile"
                                        id="ipAdd2Contact"
                                        class="form-control"
                                        wire:model.defer="mobile"
                                        value=""
                                        placeholder="{{ __('PH_MobileNumber') }}"
                                    >
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
    <div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="editModalLabel"> {{ __('Edit a contact') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal"></button>
                </div>
                @error('name') <span
                    class="error alert-danger">{{ $message }}</span>
                @enderror
                @error('lastName') <span
                    class="error alert-danger">{{ $message }}</span>
                @enderror
                <form action="">
                    @csrf
                    <div class="modal-body">
                        <input
                            id="id-field"
                            type="hidden"
                            class="form-control" name="name"
                            placeholder="name"
                            wire:model.defer="selectedContect"
                        >
                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div>
                                    <label for="nameField" class="form-label">{{ __('Name') }}</label>
                                    <input
                                        id="nameField"
                                        type="text"
                                        class="form-control"
                                        wire:model.defer="name"
                                        name="nameField"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div>
                                    <label for="lastNameField" class="form-label">{{ __('Last Name') }}</label>
                                    <input
                                        id="lastNameField"
                                        type="text"
                                        class="form-control"
                                        wire:model.defer="lastName"
                                        name="lastNameField"
                                        required>
                                </div>
                            </div>
                            <div class=" col-lg-12">
                                <div class="mb-3">
                                    <label for="username" class="form-label">{{ __('Mobile Number') }}</label><br>
                                    <input
                                        type="tel"
                                        name="mobile"
                                        id="ipAdd2Contact"
                                        class="form-control"
                                        wire:model.defer="mobile"
                                        value=""
                                        placeholder="{{ __('PH_MobileNumber') }}"
                                    >
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
                            <button type="button" onclick="updateContact()" class="btn btn-success"
                                    id="edit-btn">{{__('Update')}}</button>
                        </div>
                    </div>

                </form>
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
            if (inputphone.value.trim() && inputname.value.trim() && inputlast.value.trim()) {
                window.livewire.emit('save', inputphone.value.trim(), inputname.value.trim(), $('#outputAdd2Contact').val());
            } else {
                alert("erreur number");
            }
        }

        function editContact(id) {
            console.log(id)
            window.livewire.emit('initUserContact', id);
        }

        function updateContact() {
            console.log('aaaaaaaaaaaaaaa')

        }

        function deleteId(dd) {
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
        $(document).on('ready turbolinks:load', function () {
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
                });
                // window.location.reload();
            }
        });
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
