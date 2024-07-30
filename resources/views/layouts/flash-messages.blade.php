@if(Session::has('success'))
    <div class="alert alert-success mx-1" role="alert">
        {{ Session::get('success') }}
    </div>
@endif
@if(Session::has('danger'))
    <div class="alert alert-danger mx-1" role="alert">
        {{ Session::get('danger') }}
    </div>
@endif
@if(Session::has('warning'))
    <div class="alert alert-warning mx-1" role="alert">
        {{ Session::get('warning') }}
    </div>
@endif
