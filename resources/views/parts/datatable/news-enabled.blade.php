@if ($news->amountsactive == 1)
    <span class="badge bg-success">{{__('Yes')}}</span>
@else
    <span class="badge bg-danger">{{__('No')}}</span>
@endif
