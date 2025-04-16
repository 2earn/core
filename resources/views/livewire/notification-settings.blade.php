<div class="container-fluid">
    <style>
        .custom-range::-webkit-slider-thumb {
            background: #f02602;
        }

        .custom-range::-webkit-slider-thumb:active {
            background-color: red;
        }

        .custom-range::-webkit-slider-thumb::-ms-fill {
            background-color: red;
        }

        .custom-range::-moz-range-thumb {
            background: #3595f6;
        }

        .custom-range::-ms-thumb {
            background: #89e8ba;
        }
    </style>
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Manage my notifications') }}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('email_notification')}}</h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3"
                             src="{{ Vite::asset('resources/images/eMailIcon.png') }}"
                             width="30" height="30" alt="">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <h4 class="mb-3 m-0 fs-15">{{ __( 'Je souhaite recevoir un E-mail :' ) }}</h4>
                        @foreach($setting_notif->where('type','b')  as $key => $setting)
                            @if($setting->typeNotification=='m')
                                <div
                                    class="form-check form-switch @if($setting->payer==0) toggle-checkboxFree @else toggle-checkboxPay @endif m-2 col-12   mb-3"
                                    dir="ltr">
                                    <input wire:model="setting_notif.{{$key}}.value" type="checkbox"
                                           class="form-check-input @if($setting->payer==0) toggle-checkboxFree @else toggle-checkboxPay @endif"
                                           id="flexSwitchCheckDefault" checked="">
                                    <label class="form-check-label"
                                           for="customSwitchsizesm">{{ __( $setting->libelle ) }}  </label>

                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('Discount_plan')}}</h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3"
                             src="{{ Vite::asset('resources/images/discount.png') }}"
                             width="30" height="30" alt="">
                    </div>

                </div>
                <div class="card-body">
                    <div style="margin: 0;padding: 0" class="row">

                        @php
                            $i = 1 ;
                        @endphp
                        @foreach($setting_notif->where('type','v')  as $key => $setting)
                            @if($setting->id != '19')
                                @php
                                    $idSlider = 'slider' . $i ;
                                    $classPct = 'pct'.$i;
                                @endphp
                                <div class=" col-md-4 col-12" style="text-align: center">
                                    <div style=" height: 90px" class="row">
                                        <span class="toggle-label">{{ __( $setting->libelle ) }}  </span>
                                        <p class="{{$classPct}}">{{$setting->value}}%</p>
                                    </div>
                                    <div class="" style="margin: 0;padding: 0">
                                        <input type="range" value="0" id='{{$idSlider}}'
                                               wire:model="setting_notif.{{$key}}.value"
                                               name="discount_email_p">
                                    </div>
                                </div>
                                @php
                                    $i= $i + 1 ;
                                @endphp
                            @endif
                        @endforeach
                        <div class="live-preview mt-5 ">

                            <div class="d-flex justify-content-center gap-2">
                                <div style="background-color: #009fe3"
                                     class="alert   alert-dismissible alert-solid alert-label-icon fade show"
                                     role="alert">
                                    <i class="ri-notification-off-line label-icon"></i><strong>{{__('Gratuit')}}</strong>
                                </div>
                                <div style="background-color: #bc34b6"
                                     class="alert alert-danger material-shadow alert-dismissible alert-solid alert-label-icon fade show"
                                     role="alert">
                                    <i class="ri-notification-off-line label-icon"></i><strong>{{__('Payant')}}</strong>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{__('sms notification')}}</h4>
                    <div class="flex-shrink-0">
                        <img class="me-3 rounded-circle me-0 me-sm-3"
                             src="{{ Vite::asset('resources/images/SMSIcon.png') }}"
                             width="30" height="30" alt="">
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <h4 class="mb-3 fs-15">{{ __( 'I would like to receive an SMS' ) }}</h4>
                        @foreach($setting_notif->where('type','b')  as $key => $setting)

                            @if($setting->typeNotification=='s')
                                <div
                                    class="form-check form-switch @if($setting->payer==0) toggle-checkboxFree @else toggle-checkboxPay @endif  m-2 mb-3"
                                    dir="ltr">
                                    <input wire:model="setting_notif.{{$key}}.value" type="checkbox"
                                           class="form-check-input @if($setting->payer==0) toggle-checkboxFree @else toggle-checkboxPay @endif"
                                           id="flexSwitchCheckDefault" checked="">
                                    <label class="form-check-label"
                                           for="customSwitchsizesm">{{ __( $setting->libelle ) }}  </label>

                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="d-flex flex-row" style="margin-top: 30px;gap: 10px;">
                        <div><label for="">{{__('accepts to receive')}}</label></div>
                        <div>
                            <select style="width: 60px" id="nbrSms" wire:model="nbrSms">
                                @for($i=0; $i<= $nbrSmsPossible ;$i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div><label for="">{{__('SMS by week')}}</label></div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary d-inline-block btn2earn" type="button"
                                wire:click="save">{{__('validate')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var slider1 = document.querySelector('#slider1');
        var pct1 = document.querySelector('.pct1');
        slider1.oninput = () => {
            pct1.textContent = `${slider1.value}%`
            const p = (1 - slider1.value / 100) * (2 * (22 / 7) * 40);
        }
        var slider2 = document.querySelector('#slider2');
        var pct2 = document.querySelector('.pct2');
        slider2.oninput = () => {
            pct2.textContent = `${slider2.value}%`
            const p = (1 - slider2.value / 100) * (2 * (22 / 7) * 40);
        }
        var slider3 = document.querySelector('#slider3');
        var pct3 = document.querySelector('.pct3');
        slider3.oninput = () => {
            pct3.textContent = `${slider3.value}%`
            const p = (1 - slider3.value / 100) * (2 * (22 / 7) * 40);
        }
    </script>
</div>
