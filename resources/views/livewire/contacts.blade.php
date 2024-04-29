<div>
    <style>

        .iti {
            width: 100% !important;
        }

        .hide {
            display: none;
        }

        #error-msg {
            color: red;
        }
    </style>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('You Contacts') }}
        @endslot
    @endcomponent
    <div class="container-fluid">
        <div class="row">
            @if(Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if(Session::has('danger'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('danger') }}
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card" id="leadsList">
                    <div class="card-header border-0">
                        <div class="row g-4 align-items-center">
                            <div class="col-sm-auto ms-auto">
                                <div class="hstack gap-2">
                                    <button type="button" class="btn btn-secondary add-btn btn2earn"
                                            data-bs-toggle="modal"
                                            id="create-btn" data-bs-target="#addModal"><i
                                            class="ri-add-line align-bottom me-1 "></i> {{ __('Add a contact') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                                <table class="table table-striped">
                                    <thead class="table-light">
                                    <tr class="tabHeader2earn">
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Last Name') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{__('Country')}}</th>
                                        <th>{{__('registred')}}</th>
                                        <th>{{__('Availablity')}}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="list form-check-all">
                                    @foreach ($contactUser as $value)
                                        <tr>
                                            <td title="{{$value->updated_at}}">{{$value->name}}</td>
                                            <td>{{$value->lastName}}</td>
                                            <td>{{$value->mobile}}</td>
                                            <td>
                                                <div class="d-flex align-items-center fw-medium">
                                                    <img
                                                        src="{{ URL::asset('assets/images/flags/'. Illuminate\Support\Str::lower($value->apha2) .'.svg') }}"
                                                        alt=""
                                                        class="avatar-xs me-2 rounded-circle">
                                                    <a href="javascript:void(0);"
                                                       class="currency_name"> {{getCountryByIso($value->apha2)}}</a>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button"
                                                        class="btn btn-outline-{{$value->color}}"> {{$value->status}}
                                                </button>
                                            </td>
                                            @php
                                                $disableUntil = getSwitchBlock($value->id);
                                                if($value->availablity == 1) $disableUntil = now();
                                                else $disableUntil = getSwitchBlock($value->id);// Désactiver le commutateur jusqu'à 24 heures à partir de maintenant
                                            @endphp
                                            <td>
                                                <button type="button"
                                                        class="btn btn-outline-{{$value->sponsoredStatus}}">{{$value->sponsoredMessage}}</button>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <a href="{{ route('editContact', ['locale' =>  app()->getLocale(), 'UserContact'=>  $value->id  ]) }}"
                                                       class="btn btn-outline-primary ">
                                                        {{__('Edit')}}
                                                    </a>
                                                    <a wire:click="deleteId('{{$value->id}}')"
                                                       class="btn btn-outline-danger">
                                                        <div wire:loading wire:target="deleteId('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                            <span class="sr-only">__('Loading')</span>
                                                        </div>
                                                        {{__('Delete')}}
                                                    </a>
                                                </div>
                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    @if($value->canBeSponsored)
                                                        <a wire:click="sponsorId({{$value->id}})"
                                                           class="btn btn-info">
                                                            <div wire:loading wire:target="sponsorId('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                                <span class="sr-only">__('Loading')</span>
                                                            </div>
                                                            {{__('Sponsor it')}}
                                                        </a>
                                                    @endif
                                                    @if($value->canBeDisSponsored)
                                                        <a wire:click="removeSponsoring({{$value->id}})"
                                                           class="btn btn-outline-dark">
                                                            <div wire:loading
                                                                 wire:target="removeSponsoring('{{$value->id}}')">
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                                <span class="sr-only">__('Loading')</span>
                                                            </div>
                                                            {{__('Remove sponsoring')}}
                                                        </a>
                                                    @endif
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
        <div class="row">
            {{ $contactUser->links() }}
        </div>
    </div>

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
                                    <span id="error-msg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="button" onclick="saveContactEvent()" class="btn btn-success"
                                    id="add-btn">{{__('Save')}}
                                <div wire:loading>
                                              <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                    <span class="sr-only">{{__('Loading...')}}</span>
                                </div>
                            </button>
                        </div>
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
        const errorMsg = document.querySelector("#error-msg");
        var out = "00" + inputname.value.trim() + parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
        var phoneNumber = parseInt(inputphone.value.trim().replace(/\D/g, ''), 10);
        var inputName = inputname.value.trim();
        console.log(inputName);
        console.log(phoneNumber);
        console.log(out);


        // Envoi des données au serveur via une requête AJAX
        $.ajax({
            url: '{{ route('validate_phone') }}',
            method: 'POST',
            data: {phoneNumber: phoneNumber, inputName: inputName, "_token": "{{ csrf_token() }}"},
            success: function (response) {
                if (response.message == "") {
                    window.livewire.emit('save', phoneNumber, inputname.value.trim(), out);
                    errorMsg.innerHTML = "";
                    errorMsg.classList.add("hide");
                } else {
                    errorMsg.innerHTML = response.message;
                    errorMsg.classList.remove("hide");
                }
            }
        });

    }

    function editContact(id) {
        console.log(id)
        window.livewire.emit('initUserContact', id);
    }

    function updateContact() {
    }

    function deleteContact(dd) {
        window.livewire.emit('deleteContact', dd);
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/1.0.2/list.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/list.pagination.js/0.1.1/list.pagination.min.js"></script>
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
                    var url = "{{ route('editContact', ['locale' =>  app()->getLocale(), 'UserContact'=> Session::get('sessionIdUserExiste')]) }}";
                    document.location.href = url;
                }
            });
        }
    });
</script>
</div>
