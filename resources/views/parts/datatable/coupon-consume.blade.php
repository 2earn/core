@if(!$coupon->consumed)
    <a data-id="{{$coupon->id}}" data-name="{{$coupon->pin }}" title="{{$coupon->pin  }}"
       class="btn btn-xs btn-warning btn2earnTable consumecoupon m-1">{{__('Consume')}}</a>
@else
    <span class="text-muted">{{__('Consumed')}}</span>
@endif
@if(!$coupon->consumed)

<a data-id="{{$coupon->id}}" data-name="{{$coupon->sn }}" title="{{$coupon->sn}}"
   class="btn btn-xs btn-soft-warning btn2earnTable copycoupon m-1">{{__('Copy')}}</a>
@endif
