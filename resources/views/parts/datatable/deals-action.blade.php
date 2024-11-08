<a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
   class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>

<a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
   class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>

@if($deal->status== \Core\Enum\DealStatus::New->value)
    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
       class="btn btn-xs btn-success  btn2earnTable  m-1">{{__('Open')}}</a>
@endif
@if($deal->status== \Core\Enum\DealStatus::Open->value)
    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
       class="btn btn-xs btn-success  btn2earnTable  m-1">{{__('Close')}}</a>
@endif
@if($deal->status== \Core\Enum\DealStatus::Closed->value)
    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
       class="btn btn-xs btn-success btn2earnTable  m-1">{{__('Archive')}}</a>
@endif

<a data-id="{{$deal->id}}" data-name="{{$deal->name }}" title="{{$deal->name }}"
   class="btn btn-xs btn-danger btn2earnTable deleteDeal m-1">{{__('Delete')}}</a>
