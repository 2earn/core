<div>
    <script>
        var existSucess = '{{Session::has('ErrorExpirationCode')}}';

        if (existSucess && "{{Session::get('ErrorExpirationCode')}}" != "") {
            var msgsuccess = "{{Session::get('ErrorExpirationCode')}}";
            // "Opt code expired !";
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
        var existSucess2 = '{{Session::has('ErrorOptCode')}}';
        if (existSucess2 && "{{Session::get('ErrorOptCode')}}" != "") {
            var msgsuccess2 = "Invalid Opt code !";
            var local = '{{app()->getLocale()}}';
            if (local == 'ar') {
                msgsuccess = "هذا  !";
            }
            Swal.fire({
                title: ' ',
                text: msgsuccess2,
                icon: 'error',
                confirmButtonText: '{{trans('ok')}}',
            })
        }

    </script>
    <div id="main-wrapper">
        <div class="authincation section-padding">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-xl-5 col-md-6">
                        <div class="mini-logo text-center my-5">
                            <a href="{{route('registre',app()->getLocale())}}"><img
                                    src="{{Vite::asset('resources/images/2earn.png')}}"
                                    alt=""></a>
                        </div>
                        <div class="auth-form card">
                            <div class="card-body">
                                <a class="page-back text-muted"
                                   href="{{route('registre',app()->getLocale())}}"><span><i
                                            class="fa fa-angle-left"></i></span>{{ __('Back') }} </a>
                                <h3 class="text-center">{{ __('OTP Verification') }}</h3>
                                <p class="text-center">{{ __('We will send one time code on this number') }} </br> {{$numPhone}}</p>
                                <form action="javascript:void(0)">
                                    <input type="hidden" wire:model.defer="idUser">
                                    @csrf
                                    <div class="mb-3">
                                        <label>{{ __('Your OTP Code') }}</label>
                                        <input type="number" min="1" max="9999"
                                               class="form-control text-center font-weight-bold"
                                               name="activationCodeValue" wire:model.defer="code">
                                    </div>
                                    <div class="text-center" style="margin-top:10px">
                                        @if ($message = Session::get('error'))
                                            <p class="text-danger">{{ $message }}</p>
                                        @endif
                                        <button type="button" wire:click="verifCodeOpt"
                                                class="btn btn-success w-100 btnlogin">{{ __('Verify') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div style="background-color: #FFFFFF " . class="card-body">
                                <div class="footerOpt">{{__('Dont get code?') }} <a>{{__('Resend')}} </a></div>
                            </div>
                            <div class="card-footer text-center " style="background-color: #FFFFFF">
                                <nav class="">
                                    <ul class="logoLogin ">
                                        <li class="active active-underline">
                                            <div>
                                                <a href="{{env('SHOP_LIEN')}}">
                                                    <img src="{{Vite::asset('resources/images/icon-shop.png')}}" width="70"
                                                         height="70">
                                                </a>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <a href="{{env('LEARN_LIEN')}}">
                                                    <img src="{{Vite::asset('resources/images/icon-learn.png')}}" width="70"
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

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
