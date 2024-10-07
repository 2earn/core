<div>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
                <div class="px-4">
                    <div class="row">
                        <div class="col-xxl-12 align-self-center">
                            <div class="card-body p-5">
                                <a href="{{route('home',app()->getLocale())}}" class="d-block">
                                    <img src="{{ Vite::asset('resources/images/2earn.png') }}"
                                         alt="2earn.cash">
                                </a>
                            </div>
                            <div class="py-4 text-center">
                                <h4>{{__('Contact us')}}</h4>
                                <p class="text-success fs-15 mt-3">
                                    {{__('If you can not find answer to your question, you can always contact us or email us')}}
                                    {{__('We will answer you shortly!')}}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row justify-content-evenly mb-4">
                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-building-fill fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">{{__('Address')}}</h5>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-smartphone-fill fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">{{__('Phone')}}</h5>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="mt-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="flex-shrink-0 me-1">
                                <i class="ri-mail-fill fs-24 align-middle text-success me-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-16 mb-0 fw-semibold">{{__('Email')}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
</div>
