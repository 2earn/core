<a href="{{route('platform_show', ['locale' => app()->getLocale(), 'id' => $platformId])}}"
   class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>
<a href="{{route('platform_create_update', ['locale' => app()->getLocale(), 'id' => $platformId])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>
<a data-id="{{$platformId}}" data-name="{{$platformName }}" title="{{$platformName }}"
   class="btn btn-xs btn-danger btn2earnTable deletePlatform m-1">{{__('Delete')}}</a>
