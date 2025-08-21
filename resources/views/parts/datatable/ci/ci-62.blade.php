@php
    $idOrder=0;
          $balanceModel=\App\Models\CashBalances::find($balance->id);
        $idOrder=$balanceModel->order_id;
@endphp
<a title="{{$balance->balance_operation_id}}"
   href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$idOrder])}}"
   class=float-end">{{__('Order details')}}</a>
