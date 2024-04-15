<div>

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
                                <button type="button" class="btn btn-secondary add-btn btn2earn d-none" data-bs-toggle="modal"
                                        id="create-btn" data-bs-target="#showModal" ><i
                                        class="ri-add-line align-bottom me-1 d-none" ></i> {{ __('Add a contact') }}</button>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table nowrap dt-responsive align-middle table-hover table-bordered" id="contacts_table" style="width: 100%">
                        <thead class="table-light">
                        <tr class="head2earn  tabHeader2earn" >
                            <th class="sort" data-sort="name">{{ __('Name') }}</th>
                            <th class="sort" data-sort="lastName">{{ __('Last Name') }}</th>
                            <th class="sort" data-sort="mobile">{{ __('Phone') }}</th>
                            <th class="sort" data-sort="mobile">{{__('Country')}}</th>
                            <th class="sort" data-sort="mobile">{{__('registred')}}</th>
                            <th class="sort" data-sort="mobile">{{__('reserve')}}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="body2earn">

                        </tbody>
                    </table>
                </div>
                <div class="card-body table-responsive d-none">



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
                                    <div class="dropdown d-inline-block d-none">
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
