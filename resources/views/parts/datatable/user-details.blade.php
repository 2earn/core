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

</ul>

