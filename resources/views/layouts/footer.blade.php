<footer class="footer {{$pageName}}">
    <div class="container">
        <div class="row align-content-end">
            <div class="col-lg-12 text-end">
                    <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer">
                        2Earn.cash
                    </a> {{ __('has been accepted into') }}
                    <a href="https://www.fastercapital.com" class="link-info link-footer">
                        {{ __('FasterCapital')}}</a>{{__("'s") }}
                    <a href="https://fastercapital.com/raise-capital.html" class="link-info link-footer">
                        {{ __('Raise Capital') }}
                    </a>
                    {{ __('program and is seeking a capital of $2.5 million to be raised.') }}
                    - {{ __('Design & Develop by') }}
                    <a href="{{route('home',app()->getLocale())}}" class="link-info link-footer">
                        2Earn.cash
                    </a>
            </div>
        </div>
    </div>
</footer>
