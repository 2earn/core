<ul class="list-group list-group-horizontal-md justify-content-center">
    <li class="list-group-item list-group-item-action">
        <a data-bs-toggle="modal" data-bs-target="#AddCash" data-phone="{{$phone}}" data-country="{{$country}}"
           data-reciver="{{$reciver}}"
           class="btn btn-xs btn-soft-primary btn2earnTable addCash float-end m-1">{{__('Add cash')}}</a>
    </li>
    <li class="list-group-item list-group-item-action">
        <a href="{{route('platform_promotion',['locale'=>app()->getLocale(),'userId'=>$userId])}}"
           class="btn btn-xs btn-soft-secondary btn2earnTable float-end m-1">{{__('Promote')}}</a>
    </li>
</ul>
<ul class="list-group list-group-horizontal-md justify-content-center">
    <li class="list-group-item list-group-item-action">
        @include('parts.datatable.user-detail-link',['id' => $user->id])
    </li>
</ul>


