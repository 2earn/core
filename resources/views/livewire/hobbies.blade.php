<div>
    @component('components.breadcrumb')
        @slot('title') {{ __('Hobbies') }} @endslot
    @endcomponent
{{--    <div class="row">--}}
{{--        <div class="card" id="Hobbies-form">--}}
{{--            <div class="card-body">--}}
{{--                <form action=" " method=" " enctype="multipart/form-data">--}}
{{--                    @csrf--}}
{{--                    <div id="listeHobbies" class="row">--}}

{{--                        @foreach($hobbies as $key => $hobbie)--}}
{{--                            <div  class="mb-4 col-12 col-lg-3">--}}

{{--                                <div class="form-check form-switch   ms-5 me-5 mb-3" dir="ltr">--}}
{{--                                    <input wire:model.defer="hobbies.{{$key}}.selected" type="checkbox"--}}
{{--                                           class="form-check-input" id="" checked="">--}}
{{--                                    <label class="form-check-label"--}}
{{--                                           for="customSwitchsizesm">{{ __('Hobbies_'. $hobbie->name ) }}  </label>--}}
{{--                                </div>--}}

{{--                                                        <div class="mb-4 col-6">--}}
{{--                                                            --}}
{{--                                                            <label>--}}
{{--                                                                <input class="toggle-checkbox" type="checkbox" role="switch"--}}
{{--                                                                       id="flexSwitchCheckDefault"--}}
{{--                                                                       wire:model.defer="hobbies.{{$key}}.selected">--}}
{{--                                                                <div class="toggle-switch"></div>--}}
{{--                                                                <span class="toggle-label"> {{ __('Hobbies_'. $hobbie->name ) }}  </span>--}}
{{--                                                            </label>--}}
{{--                                                        </div>--}}

{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                        --}}{{--                        <div class="form-check form-switch   ms-5 me-5 mb-3" dir="ltr">--}}
{{--                        --}}{{--                            <input  type="checkbox"--}}
{{--                        --}}{{--                                   class="form-check-input" id="listeHobbies" checked="">--}}
{{--                        --}}{{--                            <label class="form-check-label"--}}
{{--                        --}}{{--                                   for="customSwitchsizesm">123 </label>--}}
{{--                        --}}{{--                        </div>--}}

{{--                        --}}{{--                    <div class="text-center" style="margin-top: 20px;">--}}
{{--                        --}}{{--                        <button type="button" wire:click="save"--}}
{{--                        --}}{{--                                class="btn btn-success ps-5 pe-5 btn2earnNew">{{ __('Save') }}</button>--}}
{{--                        --}}{{--                    </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
        <div id="listeHobbies" class="row">
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-danger">
                    <div class="card-header">

                        <h5 class="card-title">Learn</h5>
                        <h6 class="card-subtitle text-muted">{{__('learnHobbiesDescription')}}</h6>
                    </div>
                    <div class="card-body">

                        @foreach($hobbies->where('platform','learn') as $key => $hobbie)
                            <div class="form-check form-switch   ms-1 me-1 mb-3" dir="ltr">
                                <input wire:model.defer="hobbies.{{$key}}.selected" type="checkbox"
                                       class="form-check-input" id="" checked="">
                                <label class="form-check-label"
                                       for="customSwitchsizesm">{{ __('Hobbies_'. $hobbie->name ) }}  </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-warning">
                    <div class="card-header">

                        <h5 class="card-title">Shop</h5>
                        <h6 class="card-subtitle text-muted">{{__('shopHobbiesDescription')}}</h6>
                    </div>
                    <div class="card-body">


                        @foreach($hobbies->where('platform','shop') as $key => $hobbie)
                            <div class="form-check form-switch   ms-1 me-1 mb-3" dir="ltr">
                                <input wire:model.defer="hobbies.{{$key}}.selected" type="checkbox"
                                       class="form-check-input" id="" checked="">
                                <label class="form-check-label"
                                       for="customSwitchsizesm">{{ __('Hobbies_'. $hobbie->name ) }}  </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card card-border-primary">
                    <div class="card-header">
                        <h5 class="card-title">Move</h5>
                        <h6 class="card-subtitle text-muted">{{__('2earnHobbiesDescription')}}</h6>
                    </div>
                    <div class="card-body p-3">
                        @foreach($hobbies->where('platform','move') as $key => $hobbie)
                                <div class="form-check form-switch ms-1 me-1 mb-3" dir="ltr" >
                                    <input wire:model.defer="hobbies.{{$key}}.selected" type="checkbox"
                                           class="form-check-input toggle-checkboxFree" id="" checked="" >
                                    <label class="form-check-label"
                                           for="customSwitchsizesm">{{ __('Hobbies_'. $hobbie->name ) }}  </label>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>


{{--            <div class="col-12 col-lg-6 col-xl-3">--}}
{{--                <div class="card card-border-success">--}}
{{--                    <div class="card-header">--}}
{{--                        <div class="card-actions float-right">--}}
{{--                            <div class="dropdown show">--}}
{{--                                <a href="#" data-toggle="dropdown" data-display="static">--}}
{{--                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal align-middle">--}}
{{--                                        <circle cx="12" cy="12" r="1"></circle>--}}
{{--                                        <circle cx="19" cy="12" r="1"></circle>--}}
{{--                                        <circle cx="5" cy="12" r="1"></circle>--}}
{{--                                    </svg>--}}
{{--                                </a>--}}

{{--                                <div class="dropdown-menu dropdown-menu-right">--}}
{{--                                    <a class="dropdown-item" href="#">Action</a>--}}
{{--                                    <a class="dropdown-item" href="#">Another action</a>--}}
{{--                                    <a class="dropdown-item" href="#">Something else here</a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <h5 class="card-title">Completed</h5>--}}
{{--                        <h6 class="card-subtitle text-muted">Nam pretium turpis et arcu. Duis arcu tortor.</h6>--}}
{{--                    </div>--}}
{{--                    <div class="card-body">--}}

{{--                        <div class="card mb-3 bg-light">--}}
{{--                            <div class="card-body p-3">--}}
{{--                                <div class="float-right mr-n2">--}}
{{--                                    <label class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input">--}}
{{--                                        <span class="custom-control-label"></span>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <p>Nam pretium turpis et arcu. Duis arcu tortor, suscipit eget, imperdiet nec, imperdiet iaculis, ipsum.</p>--}}
{{--                                <div class="float-right mt-n1">--}}
{{--                                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" width="32" height="32" class="rounded-circle" alt="Avatar">--}}
{{--                                </div>--}}
{{--                                <a class="btn btn-outline-primary btn-sm" href="#">View</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card mb-3 bg-light">--}}
{{--                            <div class="card-body p-3">--}}
{{--                                <div class="float-right mr-n2">--}}
{{--                                    <label class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input">--}}
{{--                                        <span class="custom-control-label"></span>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <p>In hac habitasse platea dictumst. Curabitur at lacus ac velit ornare lobortis. Curabitur a felis tristique.</p>--}}
{{--                                <div class="float-right mt-n1">--}}
{{--                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" width="32" height="32" class="rounded-circle" alt="Avatar">--}}
{{--                                </div>--}}
{{--                                <a class="btn btn-outline-primary btn-sm" href="#">View</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card mb-3 bg-light">--}}
{{--                            <div class="card-body p-3">--}}
{{--                                <div class="float-right mr-n2">--}}
{{--                                    <label class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input">--}}
{{--                                        <span class="custom-control-label"></span>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <p>Aenean posuere, tortor sed cursus feugiat, nunc augue blandit nunc, eu sollicitudin urna dolor sagittis.</p>--}}
{{--                                <div class="float-right mt-n1">--}}
{{--                                    <img src="https://bootdey.com/img/Content/avatar/avatar8.png" width="32" height="32" class="rounded-circle" alt="Avatar">--}}
{{--                                </div>--}}
{{--                                <a class="btn btn-outline-primary btn-sm" href="#">View</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card mb-3 bg-light">--}}
{{--                            <div class="card-body p-3">--}}
{{--                                <div class="float-right mr-n2">--}}
{{--                                    <label class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input">--}}
{{--                                        <span class="custom-control-label"></span>--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                                <p>Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Maecenas malesuada.</p>--}}
{{--                                <div class="float-right mt-n1">--}}
{{--                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" width="32" height="32" class="rounded-circle" alt="Avatar">--}}
{{--                                </div>--}}
{{--                                <a class="btn btn-outline-primary btn-sm" href="#">View</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <a href="#" class="btn btn-primary btn-block">Add new</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    <script>
        $('#listeHobbies :checkbox').change(function () {
            // alert(this.checked);
            window.livewire.emit('save');
        });
    </script>
</div>
