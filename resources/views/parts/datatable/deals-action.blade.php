<a href="{{route('deal_show', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>
<a href="{{route('deal_create_update', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>
<a data-id="{{$dealId}}" data-name="{{$dealName }}" title="{{$dealName }}"
   class="btn btn-xs btn-danger btn2earnTable deletedeal m-1">{{__('Delete')}}</a>
