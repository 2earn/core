<ul class="list-group list-group-flush">
    @if($user->OptActivation)
        <li class="list-group-item"><strong>{{__('Opt activation code')}}:</strong> <span
                class="float-end">{{$user->OptActivation}}</span></li>
    @endif
    @if($user->activationCodeValue)
        <li class="list-group-item"><strong>{{__('Activation code value')}}:</strong> <span
                class="float-end">{{$user->activationCodeValue}}</span></li>
    @endif
    @if($user->register_upline)
        <li class="list-group-item"><strong>{{__('Register upline')}}:</strong> <span
                class="float-end">{{$user->register_upline}}</span>
        </li>
    @endif
    @if($user->pass)
        <li class="list-group-item"><strong>{{__('Password')}}:</strong> <span class="float-end">{{$user->pass}}</span>
        </li>
    @endif
    @if($user->idUser)
        <li class="list-group-item"><strong>{{__('idUser')}}:</strong> <span class="float-end">{{$user->idUser}}</span>
        </li>
    @endif
    @if($user->id)
        <li class="list-group-item"><strong>{{__('Id')}}:</strong> <span class="float-end">{{$user->id}}</span></li>
    @endif
    <br>
    <li class="list-group-item">  @include('parts.datatable.user-detail-link',['id' => $user->id])</li>
</ul>




