<div>
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

                                @if(!is_null($numHelpPhone))
                                    <div class="alert alert-primary material-shadow" role="alert">
                                        <p class="text-center text-muted">{{ __('If you have not received the OTP code by SMS,') }}
                                            <br>{{__('please contact by WhatsApp the number')}}
                                            <strong>{{$numHelpPhone}}</strong>
                                        </p>
                                    </div>
                                @endif

                                <form action="javascript:void(0)">
                                    <input type="hidden" wire:model.defer="idUser">

                                    @csrf
                                    <div class="mb-3">
                                        @include('layouts.flash-messages')
                                    </div>
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

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
