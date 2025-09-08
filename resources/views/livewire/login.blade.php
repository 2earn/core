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
                                                <a href="{{route('home',app()->getLocale(),false)}}" class="d-block">
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
                                            <p class="text-muted mx-3 mt-2"> {{__('continueTo2earn')}} </p>
                                        </div>
                                        <div class="mt-4">
                                            @include('layouts.flash-messages')
                                        </div>
                                        <div class="mt-4" wire:ignore>
                                            @php
                                                session(['oauth_state' => $state, 'oauth_nonce' => $nonce]);

                                                $params = http_build_query([
                                                    'response_type' => 'code',
                                                    'client_id' => config('app.auth_2earn_client_id'),
                                                    'redirect_uri' => config('app.auth_2earn_redirect_url'),
                                                    'scope' => 'openid',
                                                    'state' => $state,
                                                    'nonce' => $nonce,
                                                ]);
                                            @endphp

                                            <button class="btn btn-primary w-100"
                                                    onclick="window.location.href='https://auth.2earn.test/oauth/authorize?{{ $params }}'">
                                                {{__('Log in with auth.2earn')}}
                                            </button>

                                        </div>
                                        <div class="mt-5 text-center">
                                            <p class="mb-0">{{ __('Dont have an account') }} <a
                                                        href="{{$loginUrl}}"
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
</div>
