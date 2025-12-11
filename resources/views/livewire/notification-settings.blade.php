<div class="{{ getContainerType() }}">
    @component('components.breadcrumb')
        @slot('title')
            {{ __('Manage my notifications') }}
        @endslot
    @endcomponent

    <div class="row">
        @include('layouts.flash-messages')
        <div class="card">
            <div class="card-body row">
                <div class="card col-md-6">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('email_notification') }}</h4>
                        <div class="flex-shrink-0">
                            <img src="{{ Vite::asset('resources/images/eMailIcon.png') }}"
                                 width="30"
                                 height="30"
                                 alt="{{ __('email_notification') }}"
                                 class="rounded-circle">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3 fs-15">{{ __('Je souhaite recevoir un E-mail :') }}</h5>
                        <div class="row">
                            @foreach($setting_notif->where('type', 'b') as $key => $setting)
                                @if($setting->typeNotification == 'm')
                                    <div
                                        class="form-check form-switch {{ $setting->payer == 0 ? 'toggle-checkboxFree' : 'toggle-checkboxPay' }} m-2 col-12 mb-3"
                                        dir="ltr">
                                        <input wire:model="setting_notif.{{ $key }}.value"
                                               type="checkbox"
                                               class="form-check-input {{ $setting->payer == 0 ? 'toggle-checkboxFree' : 'toggle-checkboxPay' }}"
                                               id="email-notification-{{ $key }}"
                                            @checked($setting->value)>
                                        <label class="form-check-label" for="email-notification-{{ $key }}">
                                            {{ __($setting->libelle) }}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card col-md-6">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('Discount_plan') }}</h4>
                        <div class="flex-shrink-0">
                            <img src="{{ Vite::asset('resources/images/discount.png') }}"
                                 width="30"
                                 height="30"
                                 alt="{{ __('Discount_plan') }}"
                                 class="rounded-circle">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @php $sliderIndex = 1; @endphp
                            @foreach($setting_notif->where('type', 'v') as $key => $setting)
                                @if($setting->id != '19')
                                    <div class="col-md-4 col-12 text-center">
                                        <div class="mb-3" style="min-height: 90px;">
                                            <label for="slider-{{ $sliderIndex }}" class="form-label d-block">
                                                {{ __($setting->libelle) }}
                                            </label>
                                            <p class="pct{{ $sliderIndex }} fw-bold">{{ $setting->value }}%</p>
                                        </div>
                                        <input type="range"
                                               value="{{ $setting->value }}"
                                               id="slider-{{ $sliderIndex }}"
                                               class="form-range"
                                               wire:model="setting_notif.{{ $key }}.value"
                                               name="discount_email_p"
                                               min="0"
                                               max="100"
                                               aria-label="{{ __($setting->libelle) }}">
                                    </div>
                                    @php $sliderIndex++; @endphp
                                @endif
                            @endforeach

                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <div class="alert alert-dismissible alert-solid alert-label-icon fade show mb-0"
                                         style="background-color: #009fe3"
                                         role="alert">
                                        <i class="ri-notification-off-line label-icon"></i>
                                        <strong>{{ __('Gratuit') }}</strong>
                                    </div>
                                    <div
                                        class="alert alert-danger material-shadow alert-dismissible alert-solid alert-label-icon fade show mb-0"
                                        style="background-color: #bc34b6"
                                        role="alert">
                                        <i class="ri-notification-off-line label-icon"></i>
                                        <strong>{{ __('Payant') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card col-md-12">
                    <div class="card-header d-flex align-items-center">
                        <h4 class="card-title mb-0 flex-grow-1">{{ __('sms notification') }}</h4>
                        <div class="flex-shrink-0">
                            <img src="{{ Vite::asset('resources/images/SMSIcon.png') }}"
                                 width="30"
                                 height="30"
                                 alt="{{ __('sms notification') }}"
                                 class="rounded-circle">
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="mb-3 fs-15">{{ __('I would like to receive an SMS') }}</h5>
                        <div class="row">
                            @foreach($setting_notif->where('type', 'b') as $key => $setting)
                                @if($setting->typeNotification == 's')
                                    <div
                                        class="form-check form-switch {{ $setting->payer == 0 ? 'toggle-checkboxFree' : 'toggle-checkboxPay' }} m-2 col-12 mb-3"
                                        dir="ltr">
                                        <input wire:model="setting_notif.{{ $key }}.value"
                                               type="checkbox"
                                               class="form-check-input {{ $setting->payer == 0 ? 'toggle-checkboxFree' : 'toggle-checkboxPay' }}"
                                               id="sms-notification-{{ $key }}"
                                            @checked($setting->value)>
                                        <label class="form-check-label" for="sms-notification-{{ $key }}">
                                            {{ __($setting->libelle) }}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mt-4">
                            <label for="nbrSms" class="mb-0">{{ __('accepts to receive') }}</label>
                            <select id="nbrSms"
                                    wire:model="nbrSms"
                                    class="form-select form-select-sm per-page-width">
                                @for($i = 0; $i <= $nbrSmsPossible; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <label for="nbrSms" class="mb-0">{{ __('SMS by week') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-primary btn2earn"
                            type="button"
                            wire:click="save">
                        {{ __('validate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const slider1 = document.querySelector('#slider-1');
            const pct1 = document.querySelector('.pct1');
            if (slider1 && pct1) {
                slider1.oninput = () => {
                    pct1.textContent = `${slider1.value}%`;
                }
            }

            const slider2 = document.querySelector('#slider-2');
            const pct2 = document.querySelector('.pct2');
            if (slider2 && pct2) {
                slider2.oninput = () => {
                    pct2.textContent = `${slider2.value}%`;
                }
            }

            const slider3 = document.querySelector('#slider-3');
            const pct3 = document.querySelector('.pct3');
            if (slider3 && pct3) {
                slider3.oninput = () => {
                    pct3.textContent = `${slider3.value}%`;
                }
            }
        });
    </script>
    @endpush
    </div>
