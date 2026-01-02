<div class="row">
    <div class="col-12 card">
        @if(isset($currentRouteName))
            @if($currentRouteName!='deals_show')
                @if(\App\Models\User::isSuperAdmin())
                    <a href="{{route('deals_show', ['locale' => app()->getLocale(), 'id' => $deal->id])}}"
                       class="btn btn-xs btn-outline-info btn2earnTable  m-1">{{__('Show')}}</a>
                @endif

                @if(\Core\Models\Platform::havePartnerSpecialRole(auth()->user()->id))
                    <a class="link-warning" target="_blank"
                       href="{{route('sales_tracking',['locale'=>app()->getLocale(),'id'=>$deal->id])}}">
                        @if(\App\Models\User::isSuperAdmin())
                            {{ __('See details for Platform role') }}
                        @else
                            {{ __('See more deal details') }}
                        @endif
                    </a>
                @endif
            @endif
        @endif
        <hr>
        @if(!$deal->validated)
            <a href="{{route('deals_create_update', ['locale' => app()->getLocale(), 'id' => $deal->id, 'idPlatform' => $deal->platform_id])}}"
               class="btn btn-xs btn-primary btn2earnTable  m-1">{{__('Edit')}}</a>
            @if($deal->status< \App\Enums\DealStatus::Opened->value)
                <button class="btn btn-secondary updateDeal" data-status="0"
                        data-id="{{$deal->id}}" data-status-name="{{__('Validate')}}">
                    {{__('Validate')}}
                </button>
            @endif
        @endif
        @if($deal->validated)
            <a href="{{route('items_create_update',['locale'=>app()->getLocale(), 'dealId' => $deal->id])}}"
               class="btn btn-outline-success">{{__('Create Item')}}
            </a>
            @if($deal->status== \App\Enums\DealStatus::New->value)
                <button class="btn btn-secondary updateDeal" data-status="{{\App\Enums\DealStatus::Opened->value}}"
                        data-id="{{$deal->id}}" data-status-name="{{__(\App\Enums\DealStatus::Opened->name)}}">
                    {{__('Open')}}
                </button>
            @endif
            @if($deal->validated)
                @if($deal->status== \App\Enums\DealStatus::Opened->value)
                    <button class="btn btn-secondary updateDeal" data-status="{{\App\Enums\DealStatus::Closed->value}}"
                            data-id="{{$deal->id}}" data-status-name="{{__(\App\Enums\DealStatus::Closed->name)}}">
                        {{__('close')}}
                    </button>
                @endif
                @if($deal->status== \App\Enums\DealStatus::Closed->value)
                    <button class="btn btn-secondary updateDeal"
                            data-status="{{\App\Enums\DealStatus::Archived->value}}"
                            data-id="{{$deal->id}}" data-status-name="{{__(\App\Enums\DealStatus::Archived->name)}}">
                        {{__('Archive')}}
                    </button>
                @endif
            @endif
        @endif
        @if(\App\Models\User::isSuperAdmin())
            <a data-id="{{$deal->id}}" data-name="{{$deal->name }}" title="{{$deal->name }}"
               class="btn btn-xs btn-danger btn2earnTable deleteDeal m-1">{{__('Delete')}}</a>
        @endif
    </div>
</div>
