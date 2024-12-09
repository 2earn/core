@if($platform->show_profile)
    <a href="{{route('platform_show', ['locale' => app()->getLocale(), 'id' => $platform->id])}}"
       class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>
@endif


<a href="{{route('platform_create_update', ['locale' => app()->getLocale(), 'idPlatform' => $platform->id])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>

@if($platform->enabled)
    <a href="{{route('deals_create_update', ['locale' => app()->getLocale(),'id'=> null, 'idPlatform' => $platform->id])}}"
       class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Create deal')}}</a>
@endif

<a data-id="{{$platform->id}}" data-name="{{$platform->name }}" title="{{$platform->name  }}"
   class="btn btn-xs btn-danger btn2earnTable deletePlatform m-1">{{__('Delete')}}</a>
