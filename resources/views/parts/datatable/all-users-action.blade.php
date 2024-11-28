<a  href="{{route('adminUserEdit', ['userId' => $userLine->idUser, 'locale' => app()->getLocale()])}}"
    onclick="EditUserByAdmin()" class="btn btn-xs btn-primary btn2earnTable" ><i class="glyphicon glyphicon-edit"></i>{{__('Edit')}}</a>
<a onclick="deleteUser({{$userLine->idUser}})" class="btn btn-xs btn-danger btn2earnTable"><i></i>{{__('Delete')}}</a>
