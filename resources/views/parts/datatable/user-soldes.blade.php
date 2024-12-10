<!-- Center Alignment -->
<ul class="list-group list-group-horizontal-md justify-content-center">
    <li class="list-group-item" title="{{__('SoldeCB')}}">
        <a data-bs-toggle="modal" data-bs-target="#detail" data-amount="1"
           data-reciver="{{$idUser}}" class="btn btn-ghost-secondary waves-effect waves-light cb">
            <i class="glyphicon glyphicon-add"></i>$ {{number_format(getUserBalanceSoldes($idUser, 1), 2)}}
        </a>
    </li>
    <li class="list-group-item" title="{{__('SoldeBFS')}}">
        <a data-bs-toggle="modal" data-bs-target="#detail" data-amount="2" data-reciver="{{$idUser}}"
           class="btn btn-ghost-danger waves-effect waves-light bfs">
            <i class="glyphicon glyphicon-add"></i>${{number_format(getUserBalanceSoldes($idUser, 2), 2)}}
        </a>
    </li>
    <li class="list-group-item" title="{{__('SoldeDB')}}">
        <a data-bs-toggle="modal" data-bs-target="#detail" data-amount="3" data-reciver="{{$idUser}}"
           class="btn btn-ghost-info waves-effect waves-light db">
            <i class="glyphicon glyphicon-add"></i>${{number_format(getUserBalanceSoldes($idUser, 3), 2) }}</a>
    </li>
</ul>
<ul class="list-group list-group-horizontal-md justify-content-center">
    <li class="list-group-item" title="{{__('SoldeSMS')}}">
        <a data-bs-toggle="modal" data-bs-target="#detail" data-amount="5" data-reciver="' . $idUser . '"
           class="btn btn-ghost-warning waves-effect waves-light smsb">
            <i class="glyphicon glyphicon-add"></i>{{number_format(getUserBalanceSoldes($idUser, 5), 0)}}</a>
    </li>
    <li class="list-group-item" title="{{__('SoldeSHARES')}}">
        <a data-bs-toggle="modal" data-bs-target="#detailsh" data-amount="6" data-reciver="{{ $idUser}}"
           class="btn btn-ghost-success waves-effect waves-light sh">
            <i class="glyphicon glyphicon-add"></i> {{ number_format(getUserSelledActions($idUser), 0) }}</a>
    </li>
</ul>
