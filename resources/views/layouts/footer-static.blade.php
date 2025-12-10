<div class="row g-3">
    <div class="col-12">
        <nav class="footer-nav" aria-label="Footer Navigation">
            <div class="footer-nav-inner">
                <a href="{{route('who_we_are',app()->getLocale())}}" class="footer-nav-link">
                    <i class="mdi mdi-information-outline"></i>
                    <span>{{__('Who we are')}}</span>
                </a>

                <span class="footer-nav-separator"></span>

                <a href="{{route('general_terms_of_use',app()->getLocale())}}" class="footer-nav-link">
                    <i class="mdi mdi-file-document-outline"></i>
                    <span>{{__('General terms of use')}}</span>
                </a>

                <span class="footer-nav-separator"></span>

                <a href="{{route('contact_us',app()->getLocale())}}" class="footer-nav-link">
                    <i class="mdi mdi-email-outline"></i>
                    <span>{{__('Contact us')}}</span>
                </a>

                @auth
                    @if(Auth::user()->isSuperAdmin())
                        <span class="footer-nav-separator"></span>

                        <a href="/api/documentation" class="footer-nav-link" target="_blank" rel="noopener">
                            <i class="mdi mdi-api"></i>
                            <span>{{__('API Documentation')}}</span>
                        </a>
                    @endif
                @endauth
            </div>
        </nav>
    </div>
</div>
