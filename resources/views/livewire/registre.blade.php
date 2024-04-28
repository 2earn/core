<div>


        <style>

            .iti {
                width: 100% !important;
            }
            .hide {
    display: none;
}
#error-msg {
    color: red;
}
        </style>
        <script>
            var existSucess = '{{Session::has('successRegistre')}}';
            var msgsuccess = "success !";
            if (existSucess && "{{Session::get('successRegistre')}}" != ""  ) {
                var local = '{{app()->getLocale()}}';
                if (local == 'ar') {
                    msgsuccess = "هذا  !";
                }
                Swal.fire({
                    title: ' ',
                    text: msgsuccess,
                    icon: 'error',
                    confirmButtonText: '{{trans('ok')}}',
                })
            }
            var exist = '{{Session::has('errorPhoneExiste')}}';
            var msg = "this number is already registered!";
            if (exist && "{{Session::get('errorPhoneExiste')}}" != ""  ) {
                msg="{{Session::get('errorPhoneExiste')}}" ;
                //var local = '{{app()->getLocale()}}';
                // if (local == 'ar') {
                //     msg = "هذا الرقم مسجل!";
                // }
                Swal.fire({
                    title: ' ',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: '{{trans('ok')}}',
                }).then(okay => {
                    if (okay) {
                        window.location.reload();
                    }
                });
            }
            var existn = '{{Session::has('errorPhoneValidation')}}';
            var msg = "This number is not valid!";
            if (existn && "{{Session::get('errorPhoneValidation')}}" != ""  ) {
                var local = '{{app()->getLocale()}}';
                if (local == 'ar') {
                    msg = "هذا الرقم غير صالح!";
                }
                Swal.fire({
                    title: ' ',
                    text: msg,
                    icon: 'error',
                    confirmButtonText: '{{trans('ok')}}',
                }).then(okay => {
                    if (okay) {
                        window.location.reload();
                    }
                });
            }
        </script>

        <div class="auth-page-wrapper pt-5">
            <!-- auth page bg -->
            <div class="auth-one-bg-position auth-one-bg col-lg-6 d-none d-md-block" id="auth-particles">
                <div class="bg-overlay"></div>

                <div class="shape">
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 1440 120">
                        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                    </svg>
                </div>
            </div>

            <!-- auth page content -->
            <div class="auth-page-content">
                <div class="container">
                    <div class="row col-lg-6 d-none d-md-block">
                        <div class="col-lg-12 mb-2 ">
                            <div class="text-center mt-sm-5 mb-4 text-white-50">
                                    <a href="{{route('login',app()->getLocale())}}" class="d-inline-block auth-logo">
                                        <img src="{{ URL::asset('assets/images/2Earn.png') }}" id="super-logo" height="60">
                                    </a>
                                <p class="mt-3 fs-15 fw-medium"> </p>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card mt-4">

                                <div class="card-body p-4">
                                    <div class="text-center mt-2">
                                        <h5 class="text-primary">{{__('Create_New_Account')}}</h5>
                                        <p class="text-muted">{{__('Get_free_account')}}</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form action="javascript:void(0)"
                                             >
                                            @csrf
                                            <div class="mb-3">
                                                <label for="userPhone" class="form-label">{{ __('Mobile Number') }} <span
                                                        class="text-danger">*</span></label>
                                                <input wire:model.defer="phoneNumber" type="tel" name="mobile" id="phonereg"   class="form-control @error('mobile') is-invalid @enderror"
                                                         value=" "
                                                       placeholder="{{ __('PH_MobileNumber') }}" required>
                                                @error('email')


                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <div class="invalid-feedback">
                                                    Please enter email
                                                </div>
                                                <span id="valid-msg" class="hide" >Valid</span>
                                                <span id="error-msg" class="hide"></span>
                                                <input type="hidden" name="fullnumber" id="output"
                                                       value=""
                                                       class="form-control">
                                                <input type="hidden"    name="ccode" id="ccode" >
                                                <input type="hidden"    name="iso2Country" id="iso2Country" >
                                            </div>
                                            <div class="mb-4">
                                                <p class="mb-0 fs-12 text-muted fst-italic">{{__('agree_terms')}}  <a href=""
                                                              class="text-primary text-decoration-underline fst-normal fw-medium">{{__('Terms_of_Use')}}</a></p>
                                            </div>

                                            <div class="mt-4">
                                                <button onclick="signupEvent()" class="btn btn-success w-100 btn2earn" type="button" id="btn1">
                                                    {{__('Sign up')}}</button>
                                            </div>


                                            <div class=" text-center mt-4" style="background-color: #FFFFFF">
                                                <nav class="">
                                                    <ul style="display: inline-block;" class="logoLogin ">
                                                        <li class="active active-underline">
                                                            <div>
                                                                <a href="{{env('SHOP_LIEN')}}">
                                                                    <img src="{{asset('assets/images/icon-shop.png')}}" width="70" height="70">
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div>
                                                                <a href="{{env('LEARN_LIEN')}}">
                                                                    <img src="{{asset('assets/images/icon-learn.png')}}" width="70" height="70">
                                                                </a>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div>
                                                                <a href="{{env('LEARN_LIEN')}}"><img
                                                                        @if(isset($plateforme)) @if($plateforme==1) style="box-shadow: 0 0 30px #004dcede;
                                                border-radius: 39px;"
                                                                        @endif @endif src="{{asset('assets/images/Move2earn Icon.png')}}"
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
                                                                        alt="user-image" class="me-2 rounded" height="20">
                                                                    <span
                                                                        class="align-middle">{{ __('lang'.$locale)  }}</span>
                                                                </a>
                                                            @endforeach

                                                        </div>
                                                    </div>
                                                </div>

{{--                                                <div class="signin-other-title">--}}
{{--                                                    <h5 class="fs-13 mb-4 title text-muted">Create account with</h5>--}}
{{--                                                </div>--}}

{{--                                                <div>--}}
{{--                                                    <button type="button"--}}
{{--                                                            class="btn btn-primary btn-icon waves-effect waves-light"><i--}}
{{--                                                            class="ri-facebook-fill fs-16"></i></button>--}}
{{--                                                    <button type="button"--}}
{{--                                                            class="btn btn-danger btn-icon waves-effect waves-light"><i--}}
{{--                                                            class="ri-google-fill fs-16"></i></button>--}}
{{--                                                    <button type="button"--}}
{{--                                                            class="btn btn-dark btn-icon waves-effect waves-light"><i--}}
{{--                                                            class="ri-github-fill fs-16"></i></button>--}}
{{--                                                    <button type="button"--}}
{{--                                                            class="btn btn-info btn-icon waves-effect waves-light"><i--}}
{{--                                                            class="ri-twitter-fill fs-16"></i></button>--}}
{{--                                                </div>--}}
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->

                            <div class="mt-4 text-center">
                                <p class="mb-0">{{__('have_account')}} <a  href="{{route('login',['locale'=>app()->getLocale()])}}"
                                                                             class="fw-semibold text-primary text-decoration-underline ">
                                    {{__('Sign in')}} </a> </p>
                            </div>

                        </div>
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
                            <div>
                                <strong>2Earn.cash</strong> has been accepted into
                                <a href="https://www.fastercapital.com">FasterCapital</a>'s
                                <a href="https://fastercapital.com/raise-capital.html">Raise Capital</a> program and is seeking a capital of $2.5 million to be raised.
                            </div>
                            <div class="text-center">
                                @Created by 2Earn.cash
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>
        <!-- end auth-page-wrapper -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/19.2.16/js/intlTelInput.min.js"></script>
    <script src="path/to/lib/libphonenumber/build/utils.js"></script>
       <script>
            // document.querySelector("#phonereg").addEventListener("keypress", function (evt) {
            //     if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
            //         evt.preventDefault();
            //     }
            // });
            function signupEvent()
            {
               // console.log($("#output").val());
      //console.log($("#ccode").val());
      //console.log($("#iso2Country").val());
      //console.log($('#phonereg').intlTelInput("getDialCode"));


      const input = document.querySelector("#phonereg");
const button = document.querySelector("#btn1");
const errorMsg = document.querySelector("#error-msg");
const validMsg = document.querySelector("#valid-msg");


// here, the index maps to the error code returned from getValidationError - see readme
const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

// initialise plugin
const iti = window.intlTelInput(input, {

    initialCountry: $("#iso2Country").val(),
    //showSelectedDialCode: true,
    useFullscreenPopup: false,
    nationalMode: false,

});
const reset = () => {
  input.classList.remove("error");
  errorMsg.innerHTML = "";
  errorMsg.classList.add("hide");
  validMsg.classList.add("hide");
};



var out = "00"+$("#ccode").val()+parseInt($('#phonereg').val().replace(/\D/g, ''), 10);

// on click button: validate

  reset();
  if (input.value.trim()) {
    if (iti.isValidNumberPrecise()) {
      //validMsg.classList.remove("hide");
        //console.log()
      window.livewire.emit('changefullNumber',out.replace(/\D/g, ''),$("#ccode").val(),$("#iso2Country").val());
      //console.log($("#output").val());
      //console.log($("#ccode").val());
      //console.log($("#iso2Country").val());
    } else {
      input.classList.add("error");
      const errorCode = iti.getValidationError();
      errorMsg.innerHTML = errorMap[errorCode] || "Invalid number";
      errorMsg.classList.remove("hide");
    }
  }


// on keyup / change flag: reset
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);
            }




        </script>
</div>
