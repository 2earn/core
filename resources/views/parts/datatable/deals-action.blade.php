<a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>
<a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>

<a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-success  btn2earnTable  m-1">{{__('Open')}}</a>
<a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-warning  btn2earnTable  m-1">{{__('Close')}}</a>
<a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $dealId])}}"
   class="btn btn-xs btn-dark btn2earnTable  m-1">{{__('Archive')}}</a>


<a data-id="{{$dealId}}" data-name="{{$dealName }}" title="{{$dealName }}"
   class="btn btn-xs btn-danger btn2earnTable deleteDeal m-1">{{__('Delete')}}</a>
