<div>
    <div>
        <div class="card ">
            <div class="card-header">
                <h5 class="card-title" id="ContactsModalLabel">{{ __('Edit a contact') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @error('name') <span class="error alert-danger">{{ $message }}</span> @enderror
            @error('lastName') <span class="error alert-danger  ">{{ $message }}</span> @enderror
            @if(Session::has('message'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('message')}}
                </div>
            @endif
            <div class="card-body ">
                <form id="basic-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input id="inputNameContact" type="text"
                                       class="form-control" name="name" wire:model.defer="nameUserContact"
                                       placeholder="name ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Last Name') }}</label>
                                <input id="inputlLastNameContact" type="text"
                                       class="form-control" name=" " wire:model.defer="lastNameUserContact"
                                       placeholder="name ">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Mobile_Number') }}</label>
                                <div id="ipAddContact" data-turbolinks-permanent class="input-group signup mb-3">
                                </div>
                                <input type="tel" hidden id="pho" wire:model.defer="phoneNumber">
                                <p hidden id="codecode">{{$phoneCode}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <input type="text" name="idUser" hidden>
                            <div class="col-md-12">
                                <div class="modal-footer">
                                    <button type=" " class="btn btn-secondary"
                                            wire:click="close">{{ __('Close') }}</button>
                                    <button type="button" id="SubmitAd3dContact" onclick="editContactEvent()"
                                            class="btn btn-primary">{{ __('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        var codePays = document.getElementById('codecode').textContent;



        function editContactEvent() {
            window.livewire.emit('saveContact');
            ccode = document.getElementById("ccodeAddContact");
            fullNumber = document.getElementById("outputAddContact");
            phone = document.getElementById("phoneAddContact");
            if (ccode.value.trim() && fullNumber.value.trim() && phone.value.trim())
                window.livewire.emit('save', ccode.value.trim(), fullNumber.value.trim(), phone.value.trim());
            else
                alert("erreur number");
        }

        let code = '{{$phoneCode}}';

    </script>
</div>
