@php
    $idOrder=0;
          $balanceModel=\App\Models\CashBalances::find($balance->id);
              if(is_null($balanceModel)or is_null($balanceModel->order_id)){return;}
    $idOrder=$balanceModel->order_id;
@endphp
@if ($idOrder)
    <a title="{{$balance->balance_operation_id}}"
       href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$idOrder])}}"
       class=float-end">{{__('Order details')}}</a>
@endif
