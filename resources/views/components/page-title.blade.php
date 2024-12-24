<div class="row page-title-box px-3 pt-2" @if (!is_null($bg)) style="background-color: {{$bg}} !important;" @endif >
    <div class="col-auto">
        <a href="{{route('home', app()->getLocale())}}"
           title="{{__('To Home')}}">
            <h6 class="breadcrumb-item text-white"><i class="ri-home-7-line"></i> </h6>
        </a>
    </div>
    @if($pageTitle!='Home')
        <div class="col-auto">
            <h6 class="breadcrumb-item text-white">{!! $pageTitle !!}</h6>
        </div>
    @endif
</div>

