<ol class="list-group list-group-flush">
    <li class="list-group-item list-group-item-action">
        <a data-bs-toggle="modal" data-bs-target="#AddCash" data-phone="{{$phone}}" data-country="{{$country}}"
           data-reciver="{{$reciver}}"
           class="btn btn-xs btn-soft-primary btn2earnTable addCash float-end m-1">{{__('Add cash')}}</a>
    </li>
    <li class="list-group-item list-group-item-action">
        <a href="{{route('platform_promotion',['locale'=>app()->getLocale(),'userId'=>$userId])}}"
           class="btn btn-xs btn-soft-secondary btn2earnTable float-end m-1">{{__('Promote')}}</a>
    </li>

    @if(!is_null($isVip))
        <li class="list-group-item list-group-item-action">
            @if($isVip)
                <a class="btn btn-success float-end  m-1" disabled="disabled">{{__('Acctually is vip')}}</a>
            @else
                <a class="btn btn-info float-end  m-1" disabled="disabled">{{__('It was a vip')}}</a>
            @endif
        </li>
    @endif
    <li class="list-group-item list-group-item-action">
        <a data-bs-toggle="modal" data-bs-target="#vip" data-phone="{{$phone}}" data-country="{{$country}}"
           data-reciver="{{$reciver}}"
           class="btn btn-xs btn-flash btn2earnTable float-end  vip m-1">
            <i class="glyphicon glyphicon-add"></i>{{__('VIP')}}</a>
    </li>
</ol>
