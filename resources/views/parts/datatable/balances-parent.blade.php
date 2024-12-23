@if(!is_null($balance))
    {{$balance->id}} <i class="las la-grip-lines-vertical"></i> {{$balance->operation}}
@else
    <span class="text-muted">{{__('No parent')}}</span>
@endif
