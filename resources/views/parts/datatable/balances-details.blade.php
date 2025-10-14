<h5 class="text-muted">{{$balance->operation}}
    <span class="badge bg-primary float-end fs-14">{{$balance->direction}}</span>
</h5>

<span class="text-info">{{__('Source')}}:</span><span class="text-muted float-end ">{{$balance->source}}</span>
<br>
@if(isset($balance->ref))
   <span class="text-info  my-2">{{__('Ref')}}:</span><span
        class="text-info float-end ">{{$balance->ref}}</span><br>
@endif
<span class="text-info">{{__('Modify amount')}}:</span>
@if($balance->modify_amount==1)
    <span class="badge bg-soft-dark text-success fs-14  float-end ">{{__('Yes')}}</span>
@else
    <span class="badge bg-soft-dark  text-danger fs-14  float-end ">{{__('No')}}</span>
@endif
