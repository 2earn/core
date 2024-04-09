<div>


    {{--    SessionUserUpdated--}}


    <div>


        <div class="card ">
            <div class="card-header">
                <h5 class="card-title" id="ContactsModalLabel">{{ __('Edit a contact') }}</h5>
            </div>

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


            </div>
        </div>
    </div>
    <script>
        var codePays = document.getElementById('codecode').textContent ;
        function saveContactEvent() {

            window.livewire.emit('save',$('#ccodeAddContact').val(), $('#outputAddContact').val());

        }
        {{--let code = {{$phoneCode}} ;--}}
        {{--alert(code);--}}
    </script>

    {{-- Stop trying to control. --}}

</div>
