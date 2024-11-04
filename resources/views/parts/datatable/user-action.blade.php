<a data-bs-toggle="modal" data-bs-target="#AddCash" data-phone="{{$phone}}" data-country="{{$country}}"
   data-reciver="{{$reciver}}"
   class="btn btn-xs btn-soft-primary btn2earnTable addCash m-1">{{__('Add cash')}}</a>
<a href="{{route('platform_promotion',['locale'=>app()->getLocale(),'userId'=>$userId])}}"
   class="btn btn-xs btn-soft-secondary btn2earnTable addCash m-1">{{__('Promote')}}</a>
@include('parts.datatable.user-detail-link',['id' => $user->id])

