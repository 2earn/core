@if(Session::has('success'))
    <div class="alert alert-success alert-top-border alert-dismissible fade show material-shadow" role="alert">
        <i class="ri-notification-on-line me-3 align-middle fs-16 text-success"></i>
        {{ Session::get('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(Session::has('danger'))
    <div class="alert alert-danger alert-top-border alert-dismissible fade show material-shadow" role="alert">
        <i class="ri-error-warning-line me-3 align-middle fs-16 text-danger"></i>
        {{ Session::get('danger') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(Session::has('warning'))
    <div class="alert alert-warning alert-top-border alert-dismissible fade show material-shadow" role="alert">
        <i class="ri-alert-line me-3 align-middle fs-16 text-warning"></i>
        {{ Session::get('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif
@if(Session::has('info'))
    <div class="alert alert-info alert-top-border alert-dismissible fade show material-shadow" role="alert">
        <i class="ri-airplay-line me-3 align-middle fs-16 text-info"></i>
        {{ Session::get('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

@endif
@if(Session::has('from'))
    <div class="alert alert-warning mx-1" role="alert">
        {{__(' You can now login to')}}: <a href="https://{{ Session::get('from') }}">{{ Session::get('from') }}</a>
    </div>
@endif

