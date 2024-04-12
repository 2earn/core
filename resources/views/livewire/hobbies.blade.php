<div>
    @component('components.breadcrumb')
        @slot('title') {{ __('Hobbies') }} @endslot
    @endcomponent
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
        </div>
    <script>
        $('#listeHobbies :checkbox').change(function () {
            window.livewire.emit('save');
        });
    </script>
</div>
