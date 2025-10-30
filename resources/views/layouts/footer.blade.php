<footer class="footer {{$pageName}} mt-auto border-top">
    <div class="container">
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 bg-transparent">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-10 offset-lg-1">
                                <p class="text-center text-muted mb-0 lh-lg fs-6">
                                    <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer text-decoration-none fw-semibold">
                                        2Earn.cash
                                    </a>
                                    <span class="d-inline-block mx-1">{{ __('has been accepted into') }}</span>
                                    <a href="https://www.fastercapital.com" class="link-info link-footer text-decoration-none fw-semibold" target="_blank" rel="noopener">
                                        {{ __('FasterCapital')}}
                                    </a>{{__("'s") }}
                                    <a href="https://fastercapital.com/incubation-pending/2earncash.html" class="link-info link-footer text-decoration-none fw-semibold" target="_blank" rel="noopener">
                                        {{ __('Raise Capital') }}
                                    </a>
                                    <span class="d-inline-block mx-1">{{ __('program and is seeking a capital of $2.0 million to be raised.') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                @include('layouts.footer-static', ['pageName' => $pageName])
            </div>

            <div class="col-12">
                <div class="text-center">
                    <small class="text-muted d-inline-flex align-items-center gap-2">
                        <i class="mdi mdi-code-tags"></i>
                        <span>{{ __('Design & Develop by') }}</span>
                        <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer text-decoration-none fw-bold">
                            2Earn.cash
                        </a>
                    </small>
                    <p class="text-muted mb-0 small">
                        <i class="mdi mdi-copyright"></i>
                        {{__('Copyright')}} {{ date('Y') }}
                        <span class="fw-semibold">2Earn.cash</span>
                    </p>
                    <div class="mt-3">
                        <button type="button" title="{{ __('Back to Top') }}" class="btn btn-soft-primary btn-sm" id="back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                            <i class="mdi mdi-arrow-up"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
