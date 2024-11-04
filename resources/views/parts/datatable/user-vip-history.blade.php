<ul class="list-group list-group-flush">
    @if($user->periode)
        <li class="list-group-item">{{__('Periode')}}: <span class="float-end">{{$user->periode}}</span></li>
    @endif
    @if($user->minshares)
        <li class="list-group-item">{{__('Minshares')}}: <span class="float-end">{{$user->minshares}}</span></li>
    @endif
    @if($user->coeff)
        <li class="list-group-item">{{__('Coeff')}}: <span class="float-end">{{$user->coeff}}</span></li>
    @endif
    @if($user->date)
        <li class="list-group-item">{{__('Date')}}: <span class="float-end">{{$user->date}}</span></li>
    @endif
    @if($user->note)
        <li class="list-group-item">{{__('Note')}}: <span class="float-end">{{$user->note}}</span></li>
    @endif

</ul>
