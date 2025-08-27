@php
    $idOrder=0;
          $balanceModel=\App\Models\BFSsBalances::find($balance->id);
        $idOrder=$balanceModel->order_id;
@endphp
<span class="text-muted">{{$balance->id}}:</span> / <span
    class="text-muted">{{$balance->operation_category_id}}:</span>/
<hr>17<hr>
<a title="{{$balance->balance_operation_id}}"
   href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$idOrder])}}"
   class=float-end">{{__('Order details')}}</a>
