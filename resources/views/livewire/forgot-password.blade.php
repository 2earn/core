<div>
    <div>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.1.0/css/flag-icon.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <div class="auth-page-wrapper pt-5">
            <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
                <div class="bg-overlay"></div>
                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 1440 120">
                        <path
                            d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                    </svg>
                </div>
            </div>
            <div class="auth-page-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 mb-2">
                            <div class="text-center mt-sm-5 mb-4 text-white-50">
                                <div>
                                    <a href="{{route('login',app()->getLocale())}}" class="d-inline-block auth-logo">
                                        <img src="{{ Vite::asset('resources/images/2earn.png') }}" alt="" height="60">
                                    </a>
                                </div>
                                <p class="mt-3 fs-15 fw-medium"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4">
                                <div class="card-body">
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">{{__('Forgot Password?')}}</h5>
                                        <p class="text-muted">{{__('Reset password with 2earn')}}</p>
                                        <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop"
                                                   colors="primary:#0ab39c" class="avatar-xl">
                                        </lord-icon>
                                    </div>
                                    <div
                                        class="alert alert-borderless alert-warning  material-shadow text-center mb-2 mx-2"
                                        role="alert">
                                        {{__('Enter your mobile  will be sent to you!')}}
                                    </div>
                                    @include('layouts.flash-messages')
                                    <form id="forget-password-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="useremail"
                                                   class="form-label">{{ __('Your phone number') }}</label>
                                            <input type="tel" name="mobile" id="intl-tel-input"
                                                   class="form-control @error('mobile') is-invalid @enderror"
                                                   placeholder="{{ __('Mobile number') }}"
                                                   value="">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <input type="text" hidden name="fullnumber" id="full_phone_number"
                                                   value=""
                                                   class="form-control">
                                            <input type="text" hidden name="country_code" id="country_code">
                                        </div>

                                        <div class="">
                                            <button style="width: 100%" onclick="sendSmsEvent()"
                                                    class="btn btn-primary w-md waves-effect waves-light btn2earn"
                                                    type="button">
                                                {{ __('Send') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                <div class=" text-center mt-4" style="background-color: #FFFFFF">
                                    <nav class="">
                                        <ul style="display: inline-block;" class="logoLogin ">
                                            <li class="active active-underline">
                                                <div>
                                                    <a href="{{env('SHOP_LIEN')}}">
                                                        <img src="{{Vite::asset('resources/images/icon-shop.png')}}"
                                                             width="70"
                                                             height="70">
                                                    </a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <a href="{{env('LEARN_LIEN')}}">
                                                        <img src="{{Vite::asset('resources/images/icon-learn.png')}}"
                                                             width="70"
                                                             height="70">
                                                    </a>
                                                </div>
                                            </li>
                                            <li>
                                                <div>
                                                    <a href="{{env('LEARN_LIEN')}}"><img
                                                            @if(isset($plateforme)) @if($plateforme==1) style="box-shadow: 0 0 30px #004dcede;
                                                border-radius: 39px;"
                                                            @endif @endif src="{{Vite::asset('resources/images/Move2earn Icon.png')}}"
                                                            width="70" height="70"></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="mt-4 text-center">
                                    <div class="center" style=" display: flex;  justify-content: center;">
                                        <div class="dropdown ms-1 topbar-head-dropdown header-item  ">
                                            <button type="button"
                                                    class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                <img
                                                    src="{{ Vite::asset('resources/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                                    class="rounded" alt="Header Language"
                                                    height="20">
                                                <span
                                                    style="margin: 10px">{{ __('lang'.app()->getLocale())  }}</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @foreach ($locales as  $locale => $value )
                                                    <a href="{{ route('forget_password', ['locale'=>$locale]) }} "
                                                       class="dropdown-item notify-item language py-2"
                                                       data-lang="en"
                                                       title="{{ __('lang'.$locale)  }}"
                                                    >
                                                        <img
                                                            src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                                            alt="user-image" class="me-2 rounded" height="20">
                                                        <span class="align-middle">
                                                        {{ __('lang'.$locale)  }}
                                                    </span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <p class="mb-0">{{__('remember my password')}}
                                    <a href="{{route('login',['locale'=>app()->getLocale()])}}"
                                       class="fw-semibold text-primary text-decoration-underline">
                                        {{__('Click_here')}}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer', ['pageName' => 'forget'])
        </div>
        <script>
            var timerInterval;
            document.querySelector("#intl-tel-input").addEventListener("keypress", function (evt) {
                if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                    evt.preventDefault();
                }
            });

            function sendSmsEvent() {
                window.Livewire.dispatch('Presend', [$("#country_code").val(), $("#full_phone_number").val()]);
            }

            window.addEventListener('OptForgetPass', event => {
                Swal.fire({
                    title: '{{ __('Your verification code') }}',
                    html: '{{ __('We_will_send') }}<br> ',
                    html: '{{ __('We_will_send') }}<br>' + event.detail[0].FullNumber + '<br>' + '{{ __('Your OTP Code') }}',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    timer: '{{ env('timeOPT',180000) }}',
                    cancelButtonText: '{{__('Cancel')}}',
                    confirmButtonText: '{{__('Confirm')}}',
                    footer: ' <i></i><div class="footerOpt"></div>',
                    didOpen: () => {
                        const b = Swal.getFooter().querySelector('i')
                        const p22 = Swal.getFooter().querySelector('div')
                        p22.innerHTML = '{{trans('Dont get code?') }}' + ' <a>' + '{{trans('Resend')}}' + '</a>';

                        timerInterval = setInterval(() => {
                            b.textContent = '{{trans('It will close in')}}' + (Swal.getTimerLeft() / 1000).toFixed(0) + '{{trans('secondes')}}'
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    },

                }).then((resultat) => {
                    if (resultat.value) {
                        window.Livewire.dispatch('sendSms', [resultat.value, $("#full_phone_number").val()]);
                    }
                    if (resultat.isDismissed) {
                        location.reload();
                    }
                })
            })
        </script>
        <script type="module">

            $(function () {
                var countryDataforget = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                    inputforget = document.querySelector("#intl-tel-input");

                    var itiforget = window.intlTelInput(inputforget, {
                        initialCountry: "auto",
                        separateDialCode: true,
                        autoFormat: true,
                        useFullscreenPopup: false,
                        geoIpLookup: function (callback) {
                            $.get('https://ipinfo.io', function () {
                            }, "jsonp").always(function (resp) {
                                var countryCode = (resp && resp.country) ? resp.country : "TN";
                                callback(countryCode);
                            });
                        },
                        utilsScript: " {{Vite::asset('/resources/js/utils.js')}}"
                    });

                function initIntlTelInput() {
                    $("#submit_form").prop("disabled", false);
                    var phone = itiforget.getNumber();
                    var textNode = document.createTextNode(phone);
                    phone = phone.replace('+', '00');
                    var mobile = $("#intl-tel-input").val();
                    var countryData = itiforget.getSelectedCountryData();
                    if (!phone.startsWith('00' + countryData.dialCode)) {
                        phone = '00' + countryData.dialCode + mobile;
                    }
                    $("#full_phone_number").val(phone);
                    $("#country_code").val(countryData.dialCode);
                }

                    inputforget.addEventListener('keyup', initIntlTelInput);
                    inputforget.addEventListener('countrychange', initIntlTelInput);
                    for (var i = 0; i < countryDataforget.length; i++) {
                        var country13 = countryDataforget[i];
                        var optionNode13 = document.createElement("option");
                        optionNode13.value = country13.iso2;
                }
            })


        </script>
    </div>
</div>
