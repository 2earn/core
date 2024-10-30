<ul class="list-group list-group-flush">
    @if($user->OptActivation)
        <li class="list-group-item">{{__('Opt activation code')}}: <span
                class="float-end">{{$user->OptActivation}}</span></li>
    @endif
    @if($user->register_upline)
        <li class="list-group-item">{{__('Register upline')}}: <span class="float-end">{{$user->register_upline}}</span>
        </li>
    @endif
    @if($user->pass)
        <li class="list-group-item">{{__('Password')}}: <span class="float-end">{{$user->pass}}</span></li>
    @endif
    @if($user->idUser)
        <li class="list-group-item">{{__('idUser')}}: <span class="float-end">{{$user->idUser}}</span></li>
    @endif
    @if($user->id)
        <li class="list-group-item">{{__('Id')}}: <span class="float-end">{{$user->id}}</span></li>
    @endif
    <li class="list-group-item">  @include('parts.datatable.user-detail-link',['id' => $user->id])</li>
</ul>




