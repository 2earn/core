<div class="row page-title-box" @if (!is_null($bg)) style="background-color: {{$bg}} !important;" @endif >
    <div class="col-auto">
        <a href="{{route('home', app()->getLocale())}}"
           title="{{__('To Home')}}">
            <i class=" ri-home-gear-line btn btn-outline-light waves-effect waves-light material-shadow-none btn-sm"
            ></i>
        </a>
    </div>
    <div class="col-auto page-title-right mt-1">
        <h6 class="breadcrumb-item text-white font-weight-bolder font-size-20">{!! $pageTitle !!}</h6>
    </div>
</div>

