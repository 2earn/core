<footer class="footer {{$pageName}}">
    <div class="container">
        <div class="row align-content-end">
            <div class="col-lg-12 text-center text-muted">
                <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer">
                    2Earn.cash
                </a> {{ __('has been accepted into') }}
                <a href="https://www.fastercapital.com" class="link-info link-footer">
                    {{ __('FasterCapital')}}</a>{{__("'s") }}
                <a href="https://fastercapital.com/incubation-pending/2earncash.html" class="link-info link-footer">
                    {{ __('Raise Capital') }}
                </a>
                {{ __('program and is seeking a capital of $2.0 million to be raised.') }}
                - {{ __('Design & Develop by') }}
                <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer">
                    2Earn.cash
                </a>
            </div>
        </div>
        <div class="row align-content-end">
            <div class="col-lg-12 text-center text-muted">
                {{__('Copyright')}} © {{ date('Y') }} 2Earn.cash
            </div>
            <div class="col-lg-12 text-center text-muted">
                <a href="{{route('who_we_are',app()->getLocale())}}" class="link-info link-footer">
                    {{__('Who we are')}}
                </a>
                <i class="ri-subtract-line"></i>
                <a href="{{route('general_terms_of_use',app()->getLocale())}}" class="link-info link-footer">
                    {{__('General terms of use')}}
                </a>
                <i class="ri-subtract-line"></i>
                <a href="{{route('contact_us',app()->getLocale())}}" class="link-info link-footer">
                    {{__('Contact us')}}
                </a>
            </div>
        </div>
    </div>
</footer>
