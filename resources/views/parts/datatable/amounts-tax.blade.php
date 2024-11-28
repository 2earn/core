@if ($amounts->amountswithholding_tax == 1)
    <span class="badge bg-success-info text-success">{{__('Yes')}}</span>
@else
    <span class="badge bg-danger-info text-info">{{__('No')}}</span>
@endif
