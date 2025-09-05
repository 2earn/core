@if(!is_null($category))
    <span class="badge bg-info text-end  fs-14">     {{$category->id}} <i class="las la-grip-lines-vertical"></i>
 {{strtoupper($category->name)}}</span>
@else
    <span class="text-muted">{{__('No category')}}</span>
@endif
