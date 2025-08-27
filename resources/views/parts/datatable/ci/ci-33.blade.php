@php
    $balanceModel=\App\Models\CashBalances::find($balance->id);
    $treeModel=\App\Models\TreeBalances::where('reference',$balanceModel->reference)
    ->where('balance_operation_id',\Core\Enum\BalanceOperationsEnum::OLD_ID_13->value)->first();
@endphp
<span class="text-muted">{{$balance->id}}:</span>
<hr>33<hr>


@if(!is_null($treeModel))
    <span class="text-muted" title="{{$balanceModel->reference}}">{{__('About the tree operation')}}:</span>
    <div data-simplebar style="max-height: 215px;">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Value')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-danger">{{formatSolde($treeModel->value,0)}}</span>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <div class="d-flex">
                            <div class="flex-shrink-0 ms-2">
                                <h6 class="fs-14 mb-0">{{__('Created at')}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="text-success">{{$treeModel->created_at}}</span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
@else
    <span class="text-muted">{{__('No tree operation found')}}</span>
@endif
