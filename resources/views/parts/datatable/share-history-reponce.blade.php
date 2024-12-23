@if ($share->reponce == 1)
    <span class="badge bg-success-subtle text-success">{{__('create reponce')}}</span>
@else
    <span class="badge bg-info-subtle text-info ">{{__('sans reponce')}}</span>
@endif
