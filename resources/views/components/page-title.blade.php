<div class="row page-title-box px-3 pt-2" @if (!is_null($bg)) style="background-color: {{$bg}} !important;" @endif >
    <div class="col-auto">
        <a href="{{route('home', app()->getLocale())}}"
           title="{{__('To Home')}}">
            <h5 class="breadcrumb-item text-white"><i class="ri-home-7-line"></i></h5>
        </a>
    </div>
    @if($pageTitle!='Home')
        <span class="col align-self-center text-truncate">
            <h6 class="breadcrumb-item text-white">{!! $pageTitle !!}
            @if(!empty($helpUrl) && $helpUrl !== '#')
                <a href="{{ $helpUrl }}" title="{{__('Check the help page')}}"
                   class="m2 badge badge-outline-light float-end">
                    <i class="ri-question-line text-white"></i>
                </a>
            @endif
            </h6>
        </span>
    @endif
</div>
