<div>


    {{--    SessionUserUpdated--}}


    <div>


        <div class="card ">
            <div class="card-header">
                <h5 class="card-title" id="ContactsModalLabel">{{ __('Edit a contact') }}</h5>
{{--                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>--}}
            </div>
            {{--        @error('name') <span class="error alert-danger">{{ $message }}</span> @enderror--}}
            {{--        @error('lastName') <span class="error alert-danger  ">{{ $message }}</span> @enderror--}}
            {{--        @if(Session::has('message'))--}}
            {{--            <div class="alert alert-danger" role="alert">--}}
            {{--                {{ Session::get('message')}}--}}
            {{--            </div>--}}
            {{--        @endif--}}
            <div class="card-body ">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input id="inputNameContact" type="text"
                                   class="form-control" name="name" wire:model.defer="nameUserContact"
                                   placeholder="name ">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">{{ __('Last Name') }}</label>
                            <input id="inputlLastNameContact" type="text"
                                   class="form-control" name=" " wire:model.defer="lastNameUserContact"
                                   placeholder="name ">
                            <input   id="ipPhoneCode" type=""
                                     class="form-control" name="" wire:model.defer="phoneCode"
                                     placeholder=" " hidden>
                        </div>
                    </div>
                </div>
                <input type="text" hidden id="pho" wire:model.defer = "phoneNumber"  >
                <div style="margin-top: 20px" class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Mobile_Number') }}</label>
                            <div id="ipAddContact" data-turbolinks-permanent class="input-group signup mb-3">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 20px" class="row justify-content-center">
                    <div class="col-md-6">
                        <button type="button" id="SubmitAddContact" onclick="saveContactEvent()"
                                class="btn btn-primary">{{__('Save')}}
                        </button>
                    </div>
                </div>
                <p hidden id="codecode">{{$phoneCode}}</p>
                {{--            <form id="basic-form" enctype="multipart/form-data">--}}
                {{--                @csrf--}}
                {{--                <div class="row">--}}
                {{--                    <div class="col-md-3">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <label class="form-label">{{ __('Name') }}</label>--}}
                {{--                            <input id="inputNameContact" type="text"--}}
                {{--                                   class="form-control" name="name" wire:model.defer="nameUserContact"--}}
                {{--                                   placeholder="name ">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <div class="col-md-3">--}}
                {{--                        <div class="form-group">--}}
                {{--                            <label class="form-label">{{ __('Last Name') }}</label>--}}
                {{--                            <input id="inputLastNameContact" type="text"--}}
                {{--                                   class="form-control" name="lastName" wire:model.defer="lastNameUserContact"--}}
                {{--                                   value="" placeholder="">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                <div class="row">--}}
                {{--                    <div class="col-md-6">--}}
                {{--                        <div class="label_phone"><label>{{ __('Mobile Number') }}</label></div>--}}
                {{--                        <div id="ipAddContact" data-turbolinks-permanent class="input-group signup mb-3">--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                    <input type="text" name="idUser" hidden>--}}
                {{--                    <div class="col-md-6">--}}
                {{--                        <div class="modal-footer">--}}
                {{--                            <button type=" " class="btn btn-secondary"--}}
                {{--                                    data-bs-dismiss="modal">{{ __('Close') }}</button>--}}
                {{--                            --}}{{--                                        <button type="submit"  class="btn btn-primary">{{ __('backand.Save') }}</button>--}}
                {{--                            <button type="button" id="SubmitAddContact" onclick="saveContactEvent()"--}}

                {{--                                    class="btn btn-primary">dddd--}}
                {{--                            </button>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </form>--}}
            </div>
        </div>
    </div>
    <script>
        var codePays = document.getElementById('codecode').textContent ;
        function saveContactEvent() {
            // window.livewire.emit('saveContact');
            // inputphone = document.getElementById("phoneAddContact");

            // if (inputphone.value.trim() && inputname.value.trim() && inputlast.value.trim())
            window.livewire.emit('save',$('#ccodeAddContact').val(), $('#outputAddContact').val());
            // else
            //     alert("erreur number");
        }
        // alert(codePays);
        {{--let code = {{$phoneCode}} ;--}}
        {{--alert(code);--}}
    </script>

    {{-- Stop trying to control. --}}

</div>
