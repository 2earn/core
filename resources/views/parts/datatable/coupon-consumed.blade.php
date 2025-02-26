@if($coupon->consumed)
    <span class="badge bg-success">{{__('yes')}}</span>
@else
    <span class="badge bg-danger">{{__('no')}}</span>
@endif
