<ul class="list-group list-group-flush">
    @if($user->periode)
        <li class="list-group-item">
            <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
            <strong>{{__('Periode')}}:</strong> <span class="float-end">{{$user->periode}}</span></li>
    @endif
    @if($user->minshares)
        <li class="list-group-item">
            <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
            <strong>{{__('Minshares')}}:</strong> <span class="float-end">{{$user->minshares}}</span></li>
    @endif
    @if($user->coeff)
        <li class="list-group-item">
            <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
            <strong>{{__('Coeff')}}:</strong> <span class="float-end">{{$user->coeff}}</span></li>
    @endif
    @if($user->date)
        <li class="list-group-item">
            <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
            <strong>{{__('Date')}}:</strong> <span class="float-end">{{$user->date}}</span></li>
    @endif
    @if($user->note)
        <li class="list-group-item">
            <i class="mdi mdi-check-bold align-middle lh-1 me-2"></i>
            <strong>{{__('Note')}}:</strong> <span class="float-end">{{$user->note}}</span></li>
    @endif

</ul>
