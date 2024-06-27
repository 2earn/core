<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hobbies') }}
        @endslot
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
                        <div class="form-check form-switch ms-1 me-1 mb-3" dir="ltr">
                            <input wire:model.defer="hobbies.{{$key}}.selected" type="checkbox"
                                   class="form-check-input toggle-checkboxFree" id="" checked="">
                            <label class="form-check-label"
                                   for="customSwitchsizesm">{{ __('Hobbies_'. $hobbie->name ) }}  </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        $(document).on('turbolinks:load', function () {
            $('#listeHobbies :checkbox').change(function () {
                window.livewire.emit('save');
            });
        });
    </script>
</div>
