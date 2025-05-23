@if ($share->reponce == 1)
    <span class="badge bg-success">{{__('create reponse')}}</span>
@else
    <span class="badge bg-info">{{__('sans reponse')}}</span>
@endif
