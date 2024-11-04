@if(!is_null($isVip))
    @if($isVip)
        <a class="btn btn-success m-1" disabled="disabled">{{__('Acctually is vip')}}</a>
    @else
        <a class="btn btn-info m-1" disabled="disabled">{{__('It was a vip')}}</a>
    @endif
@endif
<a data-bs-toggle="modal" data-bs-target="#vip" data-phone="{{$mobile}}" data-country="{{$country}}"
   data-reciver="{{$reciver}}"
   class="btn btn-xs btn-flash btn2earnTable vip m-1">
    <i class="glyphicon glyphicon-add"></i>{{__('VIP')}}</a>
