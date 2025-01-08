<div class="card border card-border-light">
    <div class="card-header">
            <span class="badge rounded-pill bg-primary">{{$order->id}}</span>
        <span class="badge border border-primary text-primary float-end m-1">{{__($order->status->name)}}</span>
        <span
            class="badge border border-info text-info float-end m-1">{{getUserDisplayedName($order->user()->first()->idUser)}}</span>
    </div>
    <div class="card-body">
        @if($order->note)
            <blockquote class="text-muted">
                <strong>{{__('Note')}}: </strong><br>{{$order->note}}
            </blockquote>
        @endif
        @if($order->orderDetails()->count())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('Item')}}</th>
                    <th scope="col">{{__('Qty')}}</th>
                    <th scope="col">{{__('Unit price')}}</th>
                    <th scope="col">{{__('Total amount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails()->get() as $key => $orderDetail)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>
                            <ul class="list-group">
                                <li class="list-group-item"><strong>{{__('Name')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->name}}</span></li>
                                <li class="list-group-item"><strong>{{__('Reference')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->ref}}</span></li>
                                <li class="list-group-item"><strong>{{__('Price')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->price}}</span></li>
                                <li class="list-group-item"><strong>{{__('Discount')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->discount}}</span></li>
                            </ul>
                        </td>
                        <td>{{$orderDetail->qty}}</td>
                        <td>{{$orderDetail->unit_price}}</td>
                        <td>{{$orderDetail->total_amount}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <span class="float-end m-2"> <strong>{{__('Created at')}}: </strong>  {{$order->created_at}}</span>
        <span class="float-end m-2"><strong>{{__('Updated at')}}: </strong>  {{$order->updated_at}}</span>
    </div>
    <div class="card-footer">
    </div>
</div>
