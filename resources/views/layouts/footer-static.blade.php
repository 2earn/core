<div class="row g-3">

    <div class="col-12">
        <nav class="d-flex justify-content-center flex-wrap gap-3 py-2" aria-label="Footer Navigation">
            <a href="{{route('who_we_are',app()->getLocale())}}" class="link-info link-footer text-decoration-none d-inline-flex align-items-center small">
                <i class="mdi mdi-information-outline me-1"></i>
                {{__('Who we are')}}
            </a>

            <span class="text-muted d-none d-sm-inline">|</span>

            <a href="{{route('general_terms_of_use',app()->getLocale())}}" class="link-info link-footer text-decoration-none d-inline-flex align-items-center small">
                <i class="mdi mdi-file-document-outline me-1"></i>
                {{__('General terms of use')}}
            </a>

            <span class="text-muted d-none d-sm-inline">|</span>

            <a href="{{route('contact_us',app()->getLocale())}}" class="link-info link-footer text-decoration-none d-inline-flex align-items-center small">
                <i class="mdi mdi-email-outline me-1"></i>
                {{__('Contact us')}}
            </a>

            @auth
                @if(Auth::user()->isSuperAdmin())
                    <span class="text-muted d-none d-sm-inline">|</span>

                    <a href="/api/documentation" class="link-info link-footer text-decoration-none d-inline-flex align-items-center small" target="_blank" rel="noopener">
                        <i class="mdi mdi-api me-1"></i>
                        {{__('API Documentation')}}
                    </a>
                @endif
            @endauth
        </nav>
    </div>
</div>
