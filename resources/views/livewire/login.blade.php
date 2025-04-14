<div>
    <div class="auth-page-wrapper auth-bg-cover mt-5 py-2 justify-content-center align-items-center min-vh-75">
        <img src="{{ Vite::asset('resources/images/2earn.png') }}" class="mx-auto d-block d-lg-none">
        <div class="bg-overlay"></div>
        <div class="auth-page-content">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6 d-none d-md-block ">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay opacity-75"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index" class="d-block">
                                                    <img src="{{ Vite::asset('resources/images/2earn.png') }}"
                                                         alt="2earn.cash">
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div class="mb-3">
                                                    <i class="ri-double-quotes-l display-4 text-white"></i>
                                                </div>
                                                <div id="qoutescarouselIndicators" class="carousel slide"
                                                     data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="0" class="active" aria-current="true"
                                                                aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#qoutescarouselIndicators"
                                                                data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <img src="{{Vite::asset('resources/images/icon-shop.png')}}"
                                                                 alt="Shop2earn" height="100"
                                                                 class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">
                                                                {{__('Better Shopping Experience')}}
                                                            </p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img
                                                                src="{{Vite::asset('resources/images/Move2earn Icon.png')}}"
                                                                alt="Move2earn" height="100"
                                                                class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">{{__('Exceptional Transportation Services')}}</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img
                                                                src="{{Vite::asset('resources/images/icon-learn.png')}}"
                                                                alt="Learn2earn" height="100"
                                                                class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">{{__('Empowering knowledge, anywhere, anytime')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary"> {{__('Welcome_Back')}}</h5>
                                            <p class="text-primary"> {{__('continueTo2earn')}} </p>
                                        </div>
                                        <div class="mt-4">
                                            @include('layouts.flash-messages')
                                        </div>
                                        <div class="mt-4" wire:ignore>
                                            <form id="login-form">
                                                @csrf
                                                <div dir="ltr w-100" class="mb-3">
                                                    <label for="username"
                                                           class="float-start form-label">{{ __('Mobile Number') }}</label>
                                                    <br>
                                                    <input type="tel" name="mobile" id="intl-tel-input"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           value=""
                                                           placeholder="{{ __('Mobile number') }}">
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                    @enderror
                                                    <span id="valid-msg" class="d-none">✓ Valid</span>
                                                    <span id="error-msg" class="d-none"></span>
                                                    <input type="hidden" name="country_code" id="country_code">
                                                    <input type="hidden" name="iso_country_code" id="iso_country_code">
                                                </div>
                                                <div class="mb-3">
                                                    <label
                                                        class="float-end">
                                                        <a href="{{route('forget_password',app()->getLocale())}}">
                                                            {{ __('Forgot Password?') }}
                                                        </a>
                                                    </label>
                                                    <label class="form-label"
                                                           for="password-input">{{ __('Password') }}</label>
                                                                                <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password" class="form-control pe-5 password-input" placeholder="{{ __('Password') }}" id="password-input">
                                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon material-shadow-none" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                        @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                                                                <strong>{{ $message }}</strong>
                                                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                           id="auth-remember-check">
                                                    <label class="form-check-label"
                                                           for="auth-remember-check">{{ __('Remember me') }}</label>
                                                </div>
                                                <div class="mt-4">
                                                    <button onclick="functionLogin()"
                                                            class="btn btn-success w-100 btn2earn"
                                                            type="button" id="btn">
                                                        <div wire:loading>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                      aria-hidden="true"></span>
                                                            <span class="sr-only">{{__('Loading')}}...</span>
                                                        </div>
                                                        {{ __('Sign in') }}
                                                    </button>
                                                </div>
                                                <div class="center" style=" display: flex;  justify-content: center;">
                                                    <div class="dropdown ms-1 topbar-head-dropdown header-item  ">
                                                        <button type="button"
                                                                class="btn btn-topbar btn-ghost-secondary"
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
                                                                <a href="{{ route($var, ['locale'=> $locale ]) }} "
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
                                            </form>
                                        </div>
                                        <div class="mt-5 text-center">
                                            <p class="mb-0">{{ __('Dont have an account?') }} <a
                                                    href="{{route('registre', app()->getLocale())}}"
                                                    class="fw-semibold text-primary text-decoration-underline">
                                                    {{ __('Sign up') }}</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footer', ['pageName' => 'login'])
    </div>
    <script>
        function functionLogin() {
            window.Livewire.dispatch('login', [$("#intl-tel-input").val(), $("#country_code").val(), $("#password-input").val(), $("#iso_country_code").val()]);
        }
    </script>
    <script type="module">
        document.querySelector("#intl-tel-input").addEventListener("keypress", function (evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });
        $(function () {
            var countryDataLog = (typeof window.intlTelInputGlobals !== "undefined") ? window.intlTelInputGlobals.getCountryData() : [],
                inputlog = document.querySelector("#intl-tel-input");
            var itiLog = window.intlTelInput(inputlog, {
                initialCountry: "auto",
                autoFormat: true,
                separateDialCode: true,
                useFullscreenPopup: false,
                geoIpLookup: function (callback) {
                    $.get('https://ipinfo.io', function () {
                    }, "jsonp").always(function (resp) {
                        callback((resp && resp.country) ? resp.country : "TN");
                    });
                },
                utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.10/build/js/utils.js"
            });

            function initIntlTelInput() {
                $("#signin").prop("disabled", false);
                var phone = itiLog.getNumber();
                 document.createTextNode(phone);
                phone = phone.replace('+', '00');
                var countryData = itiLog.getSelectedCountryData();
                phone = '00' + countryData.dialCode + phone;
                $("#country_code").val(countryData.dialCode);
                $("#iso_country_code").val(countryData.iso2);
                if (inputlog.value.trim()) {
                    if (itiLog.isValidNumber()) {
                        $("#signin").prop("disabled", false);
                    } else {
                        $("#signin").prop("disabled", true);
                        inputlog.classList.add("error");
                    }
                } else {
                    $("#signin").prop("disabled", true);
                    inputlog.classList.remove("error");
                }
            }

            inputlog.addEventListener('keyup', initIntlTelInput);
            inputlog.addEventListener('countrychange', initIntlTelInput);
            for (var i = 0; i < countryDataLog.length; i++) {
                var country12 = countryDataLog[i];
                var optionNode12 = document.createElement("option");
                optionNode12.value = country12.iso2;
            }
            inputlog.focus();
            $("#password").focus();

            inputlog.addEventListener('blur', function () {
                if (inputlog.value.trim()) {
                    if (itiLog.isValidNumber()) {
                        $("#signin").prop("disabled", false);
                    } else {
                        $("#signin").prop("disabled", true);
                        inputlog.classList.add("error");
                    }
                } else {
                    $("#signin").prop("disabled", true);
                    inputlog.classList.add("error");
                    var errorCode = itiLog.getValidationError();
                }
            });
            initIntlTelInput();
        });
    </script>
</div>
