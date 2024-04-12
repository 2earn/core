<div data-turbolinks='false'>

    <style>
        .iti {
            width: 100% !important;
        }

        .degradee {
            background-image: linear-gradient(to right, #009fe3, #673bb7, #bc34b6) !important;
            border-color: #f6f8fe
        }
        .hide {
    display: none;
}
        .footer {


            height: auto !important;

        }

    </style>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <script>

                var existLogout = '{{Session::has('FromLogOut')}}';
                if (existLogout) {

                   // alert('er');
                    location.reload();
                }
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
            </script>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay opacity-75"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index" class="d-block">
                                                    <img src="{{ URL::asset('assets/images/2earn.png') }}"
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
                                                            <img src="{{asset('assets/images/icon-shop.png')}}"
                                                                 alt="Shop2earn" height="100"
                                                                 class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">Better Shopping
                                                                Experience {{--__('slide1')--}}</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img src="{{asset('assets/images/Move2earn Icon.png')}}"
                                                                 alt="Move2earn" height="100"
                                                                 class="responsive-image mb-3">
                                                            <p class="fs-15 fst-italic text-white">Exceptional
                                                                Transportation Services {{--__('slide2')--}}</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <img src="{{asset('assets/images/icon-learn.png')}}"
                                                                 alt="Learn2earn" height="100"
                                                                 class="responsive-image mb-3">


                                                            <p class="fs-15 fst-italic text-white">Empowering knowledge,
                                                                anywhere, anytime {{--__('slide3')--}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->

                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div>
                                            <h5 class="text-primary"> {{__('Welcome_Back')}}</h5>
                                            <p class="text-primary"> {{__('continueTo2earn')}} </p>
                                        </div>

                                        <div class="mt-4">
                                            {{--                                            <form action="index">--}}

                                            {{--                                                <div class="mb-3">--}}
                                            {{--                                                    <label for="username" class="form-label">Username</label>--}}
                                            {{--                                                    <input type="text" class="form-control" id="username"--}}
                                            {{--                                                           placeholder="Enter username">--}}
                                            {{--                                                </div>--}}

                                            {{--                                                <div class="mb-3">--}}
                                            {{--                                                    <div class="float-end">--}}
                                            {{--                                                        <a href="auth-pass-reset-cover" class="text-muted">Forgot--}}
                                            {{--                                                            password?</a>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                    <label class="form-label" for="password-input">Password</label>--}}
                                            {{--                                                    <div class="position-relative auth-pass-inputgroup mb-3">--}}
                                            {{--                                                        <input type="password" class="form-control pe-5 password-input"--}}
                                            {{--                                                               placeholder="Enter password" id="password-input">--}}
                                            {{--                                                        <button--}}
                                            {{--                                                            class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"--}}
                                            {{--                                                            type="button" id="password-addon"><i--}}
                                            {{--                                                                class="ri-eye-fill align-middle"></i></button>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}

                                            {{--                                                <div class="form-check">--}}
                                            {{--                                                    <input class="form-check-input" type="checkbox" value=""--}}
                                            {{--                                                           id="auth-remember-check">--}}
                                            {{--                                                    <label class="form-check-label" for="auth-remember-check">Remember--}}
                                            {{--                                                        me</label>--}}
                                            {{--                                                </div>--}}

                                            {{--                                                <div class="mt-4">--}}
                                            {{--                                                    <button class="btn btn-success w-100" type="submit">Sign In</button>--}}
                                            {{--                                                </div>--}}

                                            {{--                                                <div class="mt-4 text-center">--}}
                                            {{--                                                    <div class="signin-other-title">--}}
                                            {{--                                                        <h5 class="fs-13 mb-4 title">Sign In with</h5>--}}
                                            {{--                                                    </div>--}}

                                            {{--                                                    <div>--}}
                                            {{--                                                        <button type="button"--}}
                                            {{--                                                                class="btn btn-primary btn-icon waves-effect waves-light"><i--}}
                                            {{--                                                                class="ri-facebook-fill fs-16"></i></button>--}}
                                            {{--                                                        <button type="button"--}}
                                            {{--                                                                class="btn btn-danger btn-icon waves-effect waves-light"><i--}}
                                            {{--                                                                class="ri-google-fill fs-16"></i></button>--}}
                                            {{--                                                        <button type="button"--}}
                                            {{--                                                                class="btn btn-dark btn-icon waves-effect waves-light"><i--}}
                                            {{--                                                                class="ri-github-fill fs-16"></i></button>--}}
                                            {{--                                                        <button type="button"--}}
                                            {{--                                                                class="btn btn-info btn-icon waves-effect waves-light"><i--}}
                                            {{--                                                                class="ri-twitter-fill fs-16"></i></button>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                </div>--}}

                                            {{--                                            </form>--}}


                                            <form>
                                                @csrf
                                                {{--                                                <label for="username"--}}
                                                {{--                                                       class=" m-0 form-label">{{ __('Mobile Number') }}</label>--}}
                                                <div dir="ltr" class="mb-3">
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
                                                    <span id="valid-msg" class="hide">✓ Valid</span>
                                                    <span id="error-msg" class="hide"></span>
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
                                                            type="button" id="btn">{{ __('Sign in') }}
                                                    </button>
                                                </div>
                                                <div class="center" style=" display: flex;  justify-content: center;">
                                                    <div class="dropdown ms-1 topbar-head-dropdown header-item  ">
                                                        <button type="button"
                                                                class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                            <img
                                                                src="{{ URL::asset('/assets/images/flags/'.config('app.available_locales')[app()->getLocale()]['flag'].'.svg') }}"
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
                                                                        src="{{ URL::asset('assets/images/flags/'.$value['flag'].'.svg') }}"
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
                                            {{--                                            <div class="d-flex align-items-center justify-content-between justify-content-md-center">--}}
                                            {{--                                                @if(!empty($localLanguage) and count($localLanguage) > 1)--}}
                                            {{--                                                    <form action="/locale" method="post" class="mr-15 mx-md-20">--}}
                                            {{--                                                        {{ csrf_field() }}--}}

                                            {{--                                                        <input type="" name="locale">--}}

                                            {{--                                                        <div class="language-select">--}}
                                            {{--                                                            <div id="localItems"--}}
                                            {{--                                                                 data-selected-country="{{ localeToCountryCode(mb_strtoupper(app()->getLocale())) }}"--}}
                                            {{--                                                                 data-countries='{{ json_encode($localLanguage) }}'--}}
                                            {{--                                                            ></div>--}}
                                            {{--                                                        </div>--}}
                                            {{--                                                    </form>--}}
                                            {{--                                                @else--}}
                                            {{--                                                    <div class="mr-15 mx-md-20"></div>--}}
                                            {{--                                                @endif--}}


                                            {{--                                                <form action="/search" method="get" class="form-inline my-2 my-lg-0 navbar-search position-relative">--}}
                                            {{--                                                    <input class="form-control mr-5 rounded" type="text" name="search" placeholder="{{ trans('navbar.search_anything') }}" aria-label="Search">--}}

                                            {{--                                                    <button type="submit" class="btn-transparent d-flex align-items-center justify-content-center search-icon">--}}
                                            {{--                                                        <i data-feather="search" width="20" height="20" class="mr-10"></i>--}}
                                            {{--                                                    </button>--}}
                                            {{--                                                </form>--}}
                                            {{--                                            </div>--}}
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
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <script>

                            </script>
                            <div>
                                <strong>2Earn.cash</strong> has been accepted into
                                <a style="color: #ffffff;" href="https://www.fastercapital.com">FasterCapital</a>'s
                                <a style="color: #ffffff;" href="https://fastercapital.com/raise-capital.html">Raise Capital</a> program and is seeking a capital of $2.5 million to be raised.
                            </div>
                            <div>&#169 2023 Created by 2earn.cash</div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->


    {{--    @section('script')--}}
    {{--        <script src="{{ URL::asset('assets/js/pages/password-addon.init.js') }}"></script>--}}
    {{--    @endsection--}}


    <script>
        document.querySelector("#phone").addEventListener("keypress", function (evt) {
            if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
                evt.preventDefault();
            }
        });
        var togglePasswordLogin = document.querySelector("#togglePassword");
        var passwordLogin = document.querySelector("#password-input");
        togglePasswordLogin.addEventListener("click", function () {
            // toggle the type attribute
            var type = passwordLogin.getAttribute("type") === "password" ? "text" : "password";
            passwordLogin.setAttribute("type", type);
            // toggle the icon
            this.classList.toggle("bi-eye");
        });

        function changeLanguage() {
            // session('changeL'=>'true') ;
            const ss = '{{ Session::put('changeL', 'false' )}}';
            // window.livewire.emit('changeLanguage');
            ;
        }

        // $(".Langchange").change(function(){
        //     window.location.href = url + "?lang="+ $(this).val();
        // });
        function functionLogin(dd) {

            window.livewire.emit('login', $("#phone").val(), $("#ccodelog").val(), $("#password-input").val(), $("#isoCountryLog").val());
        }

        // $('.dropdown-menu a').click(function(){
        //    alert('dd');
        // });


    </script>
    {{--</div>--}}
</div>
