<div class="card border card-border-light">
    <div class="card-header">
            <span class="badge rounded-pill bg-primary">{{$order->id}}</span>
        <span class="m-2"> <strong>{{__('Created at')}}: </strong>  {{$order->created_at}}</span>
        <span class="m-2"><strong>{{__('Updated at')}}: </strong>  {{$order->updated_at}}</span>
        @if($order->status == \Core\Enum\OrderEnum::New)
            <button class="btn btn-success" wire:click="validateOrderCreation({{$order->id}})">
                {{__('Validate')}}
            </button>
        @endif
        <span class="badge border border-primary text-primary float-end m-1">
            {{__($order->status->name)}}
        </span>
        <span
            class="badge border border-info text-info float-end m-1">{{getUserDisplayedName($order->user()->first()->idUser)}}</span>
    </div>
    <div class="card-body">
        @if($order->note)
            <blockquote class="text-muted">
                <strong>{{__('Note')}}: </strong><br>{{$order->note}}
            </blockquote>
        @endif
    </div>
    <div class="card-body">
        @if($order->orderDetails()->count())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{__('Item')}}</th>
                    <th scope="col">{{__('Qty')}}</th>
                    <th scope="col">{{__('Unit price')}}</th>
                    <th scope="col">{{__('Shipping')}}</th>
                    <th scope="col">{{__('Total amount')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->orderDetails()->get() as $key => $orderDetail)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>
                            <ul class="list-group
  ">
                                <li class="list-group-item"><strong>{{__('Name')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->name}}</span></li>
                                <li class="list-group-item"><strong>{{__('Reference')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->ref}}</span></li>
                                <li class="list-group-item">
                                    <strong>{{__('Price')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->price}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                </li>
                                <li class="list-group-item"><strong>{{__('Discount')}}</strong><span
                                        class="float-end">{{$orderDetail->item()->first()->discount}} %</span>
                                </li>
                                @if($orderDetail->item()->first()->deal()->exists())
                                    <li class="list-group-item list-group-item-info ">
                                        <strong>{{__('Deal')}}</strong><span
                                            class="float-end">{{$orderDetail->item()->first()->deal()->first()->name}}</span>
                                    </li>
                                @endif
                            </ul>
                        </td>
                        <td>{{$orderDetail->qty}}</td>
                        <td>{{$orderDetail->unit_price}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                        <td>{{$orderDetail->shipping}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                        <td>{{$orderDetail->total_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="card-body row">
        <div class="col-md-6">
            <ul class="list-group">
            @if($order->out_of_deal_amount)
                <li class="list-group-item"><strong>{{__('Out of deal amount')}}</strong><span
                        class="float-end text-muted">{{$order->out_of_deal_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                </li>
            @endif
            @if($order->deal_amount_before_discount)
                <li class="list-group-item"><strong>{{__('Deal amount before discount')}}</strong>  <span
                        class="float-end text-muted">{{$order->deal_amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                </li>
            @endif
                @if($order->out_of_deal_amount || $order->deal_amount_before_discount)
                    <li class="list-group-item"><strong>{{__('Total Order')}}</strong>  <span
                            class="float-end text-muted">{{$order->out_of_deal_amount+$order->deal_amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-md-6">
            <ul class="list-group">
                @if($order->final_discount_percentage)
                    <li class="list-group-item"><strong>{{__('Final discount value percentage')}}</strong><span
                            class="float-end text-muted">{{$order->final_discount_percentage}} %</span>
                    </li>
                @endif                @if($order->final_discount_value)
                    <li class="list-group-item"><strong>{{__('Final discount value')}}</strong> <span
                            class="float-end text-muted">{{$order->final_discount_value}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
        </ul>
        </div>
    </div>
    <div class="card-footer">
        @if($order->status == \Core\Enum\OrderEnum::New)
            <button class="btn btn-success" wire:click="validateOrderCreation({{$order->id}})">
                {{__('Validate')}}
            </button>
        @endif
    </div>
</div>
