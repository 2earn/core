<div>
    <style>
        .iti {
            width: 100% !important;
        }

        .hide {
            display: none;
        }
    </style>
    <div class="auth-page-wrapper pt-5">
        <div class="auth-one-bg-position auth-one-bg col-lg-12 d-none d-md-block" id="auth-particles">
            <div class="bg-overlay"></div>
            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                     viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>
        <div class="auth-page-content">
            <div class="container">

                <div class="row col-lg-12 d-none d-md-block">
                    <div class="col-lg-12 mb-2 ">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <a href="{{route('login',app()->getLocale())}}" class="d-inline-block auth-logo">
                                <img src="{{ Vite::asset('resources/images/2earn.png') }}" id="super-logo" height="60">
                            </a>
                            <p class="mt-3 fs-15 fw-medium"></p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">
                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">{{__('Create_New_Account')}}</h5>
                                    <p class="text-muted">{{__('Get_free_account')}}</p>
                                </div>
                                <div class="mt-4">
                                    @include('layouts.flash-messages')
                                    <div id="error-msg"
                                         class="alert alert-danger material-shadow material-shadow mx-1 hide"
                                         role="alert">
                                    </div>
                                    <div id="valid-msg" class="alert alert-danger material-shadow mx-1 hide"
                                         role="alert">
                                        {{__('Valid')}}
                                    </div>
                                </div>
                                <div class="p-2 mt-4">
                                    <form>
                                        @csrf
                                        <div class="mb-3">
                                            <label for="userPhone" class="form-label">{{ __('Mobile Number') }} <span
                                                    class="text-danger">*</span></label>
                                            <input wire:model="phoneNumber" type="tel" name="mobile" id="intl-tel-input"
                                                   class="form-control @error('mobile') is-invalid @enderror"
                                                   value=" "
                                                   placeholder="{{ __('Mobile number') }}" required>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="invalid-feedback">
                                                {{__('Please enter email')}}
                                            </div>
                                            <input type="hidden" name="fullnumber" id="output"
                                                   value=""
                                                   class="form-control">
                                            <input type="hidden" name="ccode" id="ccode">
                                            <input type="hidden" name="iso2Country" id="iso2Country">
                                            <input type="hidden" name="validation" id="validation">
                                            <input type="hidden" name="validationError" id="validationError">
                                        </div>
                                        <div class="mb-4">
                                            <p class="mb-0 fs-12 text-muted fst-italic">{{__('agree_terms')}}
                                                <a href="{{route('privacy',app()->getLocale())}}"
                                                   class="text-primary text-decoration-underline fst-normal fw-medium">
                                                    {{__('Terms_of_Use')}}
                                                </a>
                                            </p>
                                        </div>

                                        <div class="mt-4">
                                            <button class="g-recaptcha btn btn-success w-100 btn2earn"
                                                    data-sitekey="{{config('services.recaptcha.key')}}"
                                                    data-callback='signupEvent' id="btn1"
                                                    data-action='submit'>  {{__('Sign up')}}
                                            </button>
                                        </div>
                                        <div class=" text-center mt-4" style="background-color: #FFFFFF">
                                            <nav class="">
                                                <ul style="display: inline-block;" class="logoLogin ">
                                                    <li class="active active-underline">
                                                        <div>
                                                            <a href="{{env('SHOP_LIEN')}}">
                                                                <img
                                                                    src="{{Vite::asset('resources/images/icon-shop.png')}}"
                                                                    width="70" height="70">
                                                            </a>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div>
                                                            <a href="{{env('LEARN_LIEN')}}">
                                                                <img
                                                                    src="{{Vite::asset('resources/images/icon-learn.png')}}"
                                                                    width="70" height="70">
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
                                                            class="btn btn-topbar btn-ghost-secondary "
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                        <img
                                                            src="{{ Vite::asset('resources/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
                                                            class="rounded" alt="Header Language"
                                                            height="20">
                                                        <span
                                                            style="margin: 10px">{{ __('lang'.app()->getLocale())  }}</span>
                                                    </button>
                                                    @php
                                                        $var = \Illuminate\Support\Facades\Route::currentRouteName() ;
                                                    @endphp
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @foreach (config('app.available_locales') as  $locale => $value )
                                                            <a href="{{ route('registre', $locale ) }}"
                                                               class="dropdown-item notify-item language py-2"
                                                               data-lang="en"
                                                               title="{{ __('lang'.$locale)  }}"
                                                            >
                                                                <img
                                                                    src="{{ Vite::asset('resources/images/flags/'.$value['flag'].'.svg') }}"
                                                                    alt="user-image" class="me-2 rounded"
                                                                    height="20">
                                                                <span
                                                                    class="align-middle">{{ __('lang'.$locale)  }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="mb-0">
                                {{__('have_account')}}
                                <a href="{{route('login',['locale'=>app()->getLocale()])}}"
                                   class="fw-semibold text-primary text-decoration-underline ">
                                    {{__('Sign in')}}
                                </a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer', ['pageName' => 'register'])
    </div>
    <script>
        var iti = null;

        function signupEvent() {
            const input = document.querySelector("#intl-tel-input");
            const button = document.querySelector("#btn1");
            const errorMsg = document.querySelector("#error-msg");
            const validMsg = document.querySelector("#valid-msg");
            var errorMap = ['{{trans('Invalid number')}}', '{{trans('Invalid country code')}}', '{{trans('Too shortsss')}}', '{{trans('Too long')}}', '{{trans('Invalid number')}}'];


            const reset = () => {
                input.classList.remove("error");
                errorMsg.innerHTML = "";
                errorMsg.classList.add("hide");
                validMsg.classList.add("hide");
            };
            var out = "00" + $("#ccode").val() + parseInt($('#intl-tel-input').val().replace(/\D/g, ''), 10);

            if (input.value.trim()) {
                if ($("#validation").val() == "true") {

                @this.set('captcha', grecaptcha.getResponse());
                    window.Livewire.dispatch('changefullNumber', [out.replace(/\D/g, ''), $("#ccode").val(), $("#iso2Country").val()]);
                } else {
                    input.classList.add("error");
                    const errorCode = $("#validationError").val();
                    errorMsg.innerHTML = errorMap[errorCode] || "{{__('Invalid number')}}";
                    errorMsg.classList.remove("hide");
                }
            } else {
                input.classList.add("error");
                errorMsg.innerHTML = "{{__('Invalid number')}}";
                errorMsg.classList.remove("hide");
            }
            input.addEventListener('change', initIntlTelInput);
            input.addEventListener('keyup', initIntlTelInput);
            grecaptcha.reset();
        }
    </script>
    <script type="module">
        $(function () {
            var countryData = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                input = document.querySelector("#intl-tel-input");
            iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        callback((resp && resp.country) ? resp.country : "TN");
                    });
                },
                utilsScript: " {{Vite::asset('utils.js/utils.js')}}"
            });

            function initIntlTelInput() {
                var phone = iti.getNumber();
                 document.createTextNode(phone);
                phone = phone.replace('+', '00');
                var mobile = $("#intl-tel-input").val();
                var countryData = iti.getSelectedCountryData();
                if (!phone.startsWith('00' + countryData.dialCode)) {
                    phone = '00' + countryData.dialCode + mobile;
                }
                $("#output").val(phone);
                $("#ccode").val(countryData.dialCode);
                $("#country_code").val(countryData.dialCode);
                $("#iso2Country").val(countryData.iso2);
                $("#validation").val(iti.isValidNumberPrecise());
                $("#validationError").val(iti.getValidationError());
            }

            input.addEventListener('keyup', initIntlTelInput);
            input.addEventListener('countrychange', initIntlTelInput);
            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                var optionNode = document.createElement("option");
                optionNode.value = country.iso2;
            }
        });
    </script>
</div>
