<div class="row align-content-end">
    <div class="col-lg-12 text-center text-light">
        {{__('Copyright')}} © {{ date('Y') }} 2Earn.cash
    </div>
    <div class="col-lg-12 text-center text-light">
        <a href="{{route('who_we_are',app()->getLocale())}}" class="link-info link-footer">
            {{__('Who we are')}}
        </a>
        <i class="mdi mdi-circle-medium"></i>
        <a href="{{route('general_terms_of_use',app()->getLocale())}}" class="link-info link-footer">
            {{__('General terms of use')}}
        </a>
        <i class="mdi mdi-circle-medium"></i>
        <a href="{{route('contact_us',app()->getLocale())}}" class="link-info link-footer">
            {{__('Contact us')}}
        </a>
    </div>
</div>
