@if(!is_null($balance))


    <span class="badge bg-secondary text-end  fs-14">   {{$balance->id}} <i class="las la-grip-lines-vertical"></i> {{$balance->operation}}</span>


@else
    <span class="text-muted">{{__('No parent')}}</span>
@endif
