<a data-bs-toggle="modal" data-bs-target="#AddCash" data-phone="{{$phone}}" data-country="{{$country}}"
    data-reciver="{{$reciver}}"
    class="btn btn-xs btn-primary btn2earnTable addCash m-1">{{__('Add cash')}}</a>
<a  href=""
   class="btn btn-xs btn-secondary btn2earnTable addCash m-1">{{__('Promote')}}</a>
@include('parts.datatable.user-detail-link',['id' => $user->id])

