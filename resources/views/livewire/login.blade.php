<div data-turbolinks='false'>
    <div class="auth-page-wrapper auth-bg-cover mt-5 py-2 justify-content-center align-items-center min-vh-75">
        <div class="bg-overlay"></div>
        <div class="auth-page-content">
            <script>
                window.addEventListener('load', () => {
                    var existmessageLogin = '{{Session::has('message')}}';
                    if (existmessageLogin) {
                        var msgMsgLogin = '{{Session::get('message')}}';
                        var local = '{{Session::has('locale')}}';
                        if (local == 'ar') {
                            msg = "هاتفك أو كلمة المرور الخاصة بك غير صحيحة !";
                        }
                        Swal.fire({
                            title: ' ',
                            text: msgMsgLogin,
                            icon: 'error',
                            confirmButtonText: '{{trans('ok')}}'
                        }).then(okay => {
                            if (okay) {
                                window.location.reload();
                            }
                        });
                    }
                });
            </script>
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
                                                            <img src="{{Vite::asset('resources/images/Move2earn Icon.png')}}"
                                                                 alt="Move2earn" height="100"
                                                                 class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">{{__('Exceptional Transportation Services')}}</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img src="{{Vite::asset('resources/images/icon-learn.png')}}"
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
                                            <form id="login-form">
                                                @csrf
                                                <div dir="ltr w-100" class="mb-3">
                                                    <label for="username"
                                                           class="float-start form-label">{{ __('Mobile Number') }}</label>
                                                    <br>
                                                    <input type="tel" name="mobile" id="phone"
                                                           class="form-control @error('email') is-invalid @enderror"
                                                           value=""
                                                           placeholder="{{ __('PH_MobileNumber') }}">
                                                    @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                                                            <strong>{{ $message }}</strong>
                                                                                        </span>
                                                    @enderror
                                                    <span id="valid-msg" class="d-none">✓ Valid</span>
                                                    <span id="error-msg" class="d-none"></span>
                                                    <input type="hidden" name="ccodelog" id="ccodelog">
                                                    <input type="hidden" name="isoCountryLog" id="isoCountryLog">
                                                </div>
                                                <div class="mb-3">
                                                    <label
                                                        class="float-end">
                                                        <a href="{{route('forgetpassword',app()->getLocale())}}">
                                                            {{ __('Forgot Password?') }}
                                                        </a>
                                                    </label>
                                                    <label class="form-label"
                                                           for="password-input">{{ __('Password') }}</label>
                                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                                        <input type="password"
                                                               class="form-control pe-5 @error('password') is-invalid @enderror"
                                                               name="password" placeholder="{{ __('PH_Password') }}"
                                                               id="password-input">
                                                        <button
                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                            type="button" id="togglePassword"><i
                                                                class="ri-eye-fill align-middle"></i></button>
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
                                                        @php
                                                            $var = \Illuminate\Support\Facades\Route::currentRouteName() ;
                                                        @endphp
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            @foreach (config('app.available_locales') as  $locale => $value )
                                                                <a href="{{ route($var, ['locale'=> $locale ]) }} "
                                                                   class="dropdown-item notify-item language py-2"
                                                                   data-lang="en"
                                                                   title="{{ __('lang'.$locale)  }}"
                                                                   data-turbolinks="false">
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
        document.querySelector("#phone").addEventListener("keypress", function (evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });
        var togglePasswordLogin = document.querySelector("#togglePassword");
        var passwordLogin = document.querySelector("#password-input");
        togglePasswordLogin.addEventListener("click", function () {
            var type = passwordLogin.getAttribute("type") === "password" ? "text" : "password";
            passwordLogin.setAttribute("type", type);
            this.classList.toggle("bi-eye");
        });

        function changeLanguage() {
            const ss = '{{ Session::put('changeL', 'false' )}}';
        }

        function functionLogin(dd) {
            window.Livewire.emit('login', $("#phone").val(), $("#ccodelog").val(), $("#password-input").val(), $("#isoCountryLog").val());
        }
    </script>
</div>
