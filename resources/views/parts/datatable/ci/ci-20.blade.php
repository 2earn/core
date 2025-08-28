@php
    $idOrder=0;
          $balanceModel=\App\Models\SharesBalances::find($balance->id);
          $cashModel=\App\Models\CashBalances::where('reference',$balanceModel->reference)->where('balance_operation_id',\Core\Enum\BalanceOperationsEnum::OLD_ID_48->value)->first();
@endphp
@if (App::environment(['local', 'dev']))
    <span class="text-muted">{{$balance->id}}:</span>/20/{{$balance->balance_operation_id}}<hr>
@endif
@if(!is_null($cashModel))
    <span class="text-muted">{{__('About the cash operation')}}:</span>
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
                        <span class="text-danger">{{formatSolde($cashModel->value,2)}} {{config('app.currency')}}</span>
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
                        <span class="text-success">{{$cashModel->created_at}}</span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
@else
    <span class="text-muted">{{__('No cash operation found')}}:</span>
@endif
