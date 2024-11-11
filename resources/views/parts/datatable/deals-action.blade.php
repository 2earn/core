<div>
    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
       class="btn btn-xs btn-info btn2earnTable  m-1">{{__('Show')}}</a>
    @if(!$deal->validated)
        <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
           class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>
        @if($deal->status< \Core\Enum\DealStatus::Opened->value)
            <button class="btn btn-secondary updateDeal" data-status="0"
                    data-id="{{$deal->id}}" data-status-name="{{__('Validate')}}">
                {{__('Validate')}}
            </button>
        @endif
    @endif
    @if($deal->validated)
        @if($deal->status== \Core\Enum\DealStatus::New->value)
            <button class="btn btn-secondary updateDeal" data-status="{{\Core\Enum\DealStatus::Opened->value}}"
                    data-id="{{$deal->id}}" data-status-name="{{__(\Core\Enum\DealStatus::Opened->name)}}">
                {{__('Open')}}
            </button>
        @endif
        @if($deal->validated)
            @if($deal->status== \Core\Enum\DealStatus::Opened->value)
                <button class="btn btn-secondary updateDeal" data-status="{{\Core\Enum\DealStatus::Closed->value}}"
                        data-id="{{$deal->id}}" data-status-name="{{__(\Core\Enum\DealStatus::Closed->name)}}">
                    {{__('close')}}
                </button>
            @endif
            @if($deal->status== \Core\Enum\DealStatus::Closed->value)
                <button class="btn btn-secondary updateDeal" data-status="{{\Core\Enum\DealStatus::Archived->value}}"
                        data-id="{{$deal->id}}" data-status-name="{{__(\Core\Enum\DealStatus::Archived->name)}}">
                    {{__('Archive')}}
                </button>
            @endif
        @endif
    @endif
    <a data-id="{{$deal->id}}" data-name="{{$deal->name }}" title="{{$deal->name }}"
       class="btn btn-xs btn-danger btn2earnTable deleteDeal m-1">{{__('Delete')}}</a>
</div>
