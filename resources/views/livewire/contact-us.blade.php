<div class=" mt-2">
    @section('title')
        {{ __('Contact us') }}
    @endsection
    <div class="row card rounded-0 bg-success-subtle mx-n4 mt-n4 border-top">
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
                    <p class="text-info fs-15 mt-3">
                        {{__('If you can not find answer to your question, you can always contact us or email us')}},
                        {{__('We will answer you shortly')}}!
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row card justify-content-evenly mb-4">
        <div class="card-body">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-shrink-0 me-1">
                        <i class="ri-building-fill fs-24 align-middle text-info me-1"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="fs-16 mb-0 fw-semibold">{{__('Address')}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="flex-grow-1">
                    <p class="text-muted align-middle">
                        @if(app()->getLocale()=='en')
                            King Abdulaziz University in Jeddah, Saudi Arabia
                        @endif
                        @if(app()->getLocale()=='fr')
                            l’Université King Abdulaziz à Jeddah, en Arabie Saoudite
                        @endif
                        @if(app()->getLocale()=='ar')
                            جامعة الملك عبد العزيز بجدة، المملكة العربية السعودية
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row card justify-content-evenly mb-4">
        <div class="card-body">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-shrink-0 me-1">
                        <i class="ri-smartphone-fill fs-24 align-middle text-info me-1"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="fs-16 mb-0 fw-semibold">{{__('Phone')}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="flex-grow-1">
                    <p class="text-muted align-middle ">+966597555211</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row card justify-content-evenly mb-4">
        <div class="card-body">
            <div class="col-lg-6">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-shrink-0 me-1">
                        <i class="ri-mail-fill fs-24 align-middle text-info me-1"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="fs-16 mb-0 fw-semibold">{{__('Email')}}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="flex-grow-1">
                    <p class="text-muted align-middle font-weight-bold">Support@2earn.cash</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

