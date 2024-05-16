@if(Session::has('success'))
    <div class="card alert alert-success mx-1" role="alert">
        {{ Session::get('success') }}
    </div>
@endif
@if(Session::has('danger'))
    <div class="card alert alert-danger mx-1" role="alert">
        {{ Session::get('danger') }}
    </div>
@endif
