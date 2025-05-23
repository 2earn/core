@if(!$coupon->consumed)
    {{substr_replace($coupon->pin, str_repeat('*', strlen($coupon->pin)), 0 )}}
@else
    {{$coupon->pin}}
@endif
