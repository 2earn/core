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
                                <label class="form-label">{{ __('Name') }}</label>
                                <input id="inputNameContact" type="text"
                                       class="form-control" name="name" wire:model.defer="nameUserContact"
                                       placeholder="{{ __('Name') }} ">
                            </div>
                            @error('nameUserContact') <span class="error alert-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Last name') }}</label>
                                <input id="inputlLastNameContact" type="text"
                                       class="form-control" name="inputlLastNameContact"
                                       wire:model.defer="lastNameUserContact"
                                       placeholder="{{ __('Last Name') }} "
                                >
                            </div>
                            @error('lastNameUserContact') <span
                                class="error alert-danger  ">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Mobile_Number') }}</label>
                                <div id="ipAddContact" data-turbolinks-permanent class="input-group signup mb-3">
                                </div>
                                <input type="tel" hidden id="pho" wire:model.defer="phoneNumber"
                                       value="{{$phoneNumber}}">
                                <input type="text" hidden id="phoneAddContact" name="phoneAddContact"
                                       value="{{$phoneNumber}}">
                                <p hidden id="codecode">{{$phoneCode}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <input type="text" name="idUser" hidden>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary"
                                <button type="button" class="btn btn-outline-secondary float-end  mx-1"
                                        wire:click="close">{{ __('Close') }}
                                </button>
                                <button type="button" id="SubmitAd3dContact" onclick="editContactEvent()"
                                        class="btn btn-outline-info">
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

        function editContactEvent() {
            ccode = document.getElementById("ccodeAddContact");
            fullNumber = document.getElementById("outputAddContact");
            phone = document.getElementById("phoneAddContact");
            if (ccode.value.trim() && fullNumber.value.trim() && phone.value.trim())
                window.Livewire.emit('save', ccode.value.trim(), fullNumber.value.trim(), phone.value.trim());
            else
                console.log("erreur number");
        }
    </script>
</div>

