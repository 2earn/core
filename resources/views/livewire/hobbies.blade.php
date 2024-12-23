<div>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Hobbies') }}
        @endslot
    @endcomponent
        <div id="listeHobbies" class="row mt-2">
            <div class="col-xxl-4">
                <div class="card">
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
            <div class="col-xxl-4">
                <div class="card">
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
            <div class="col-xxl-4">
                <div class="card">
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
                window.Livewire.emit('save');
            });
        });
    </script>
</div>
