@php
    $idOrder=0;
          $balanceModel=\App\Models\CashBalances::find($balance->id);
        $idOrder=$balanceModel->order_id;
@endphp
@if (App::environment(['local', 'dev']))
    <span class="text-muted">{{$balance->id}}:</span>/62/{{$balance->balance_operation_id}}
    <hr>
@endif
@if ($idOrder)
    <a title="{{$balance->balance_operation_id}}"
       href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$idOrder])}}"
       class=float-end">{{__('Order details')}}</a>
@endif
