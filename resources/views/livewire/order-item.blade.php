<div class="card border card-border-light">
    <div class="card-header">

        <button type="button" class="btn btn-sm btn-outline-info m-1">
            {{__('Order id')}} <span
                class="badge bg-info ms-1">{{$order->id}}</span>
        </button>

        @if($order->total_order_quantity)
            <button type="button" class="btn btn-sm btn-outline-info  float-end m-1">
                {{__('Total order quantity')}} <span
                    class="badge bg-info ms-1">{{$order->total_order_quantity}}</span>
            </button>
        @endif

        <button type="button" class="btn btn-sm btn-outline-info  float-end m-1">
            {{__($order->status->name)}}
        </button>
        <button type="button" class="btn btn-sm btn-outline-info  float-end m-1">
            {{getUserDisplayedName($order->user()->first()->idUser)}}
        </button>
    </div>
    <div class="card-body">
        @if($order->note)
            <blockquote class="text-muted">
                <strong>{{__('Note')}}: </strong><br>{{$order->note}}
            </blockquote>
        @endif
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <h5 class="text-info">{{__('Order simulation summary')}}</h5>
        </div>
        <div class="col-md-12">
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
                        @if($order->status <= \Core\Enum\OrderEnum::Simulated)
                            <th scope="col">{{__('Partner Discount')}}</th>
                            <th scope="col">{{__('2earn Discount')}}</th>
                            <th scope="col">{{__('Deal Discount')}}</th>
                        @endif
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
                            @if($order->status <= \Core\Enum\OrderEnum::Simulated)
                                <td>
                                    <ul class="list-group">
                                        <li class="list-group-item">{{__('Percentage')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->partner_discount_percentage}}</span>
                                        </li>
                                        <li class="list-group-item">{{__('value')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->partner_discount}}</span>
                                        </li>
                                        <li class="list-group-item">{{__('Amount')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->amount_after_partner_discount}}</span>
                                        </li>
                                    </ul>
                                </td>

                                <td>
                                    <ul class="list-group">
                                        <li class="list-group-item">{{__('Percentage')}}
                                            :<span
                                                class="float-end text-muted"> {{$orderDetail->earn_discount_percentage}}</span>
                                        </li>
                                        <li class="list-group-item">{{__('value')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->earn_discount}}</span></li>
                                        <li class="list-group-item">{{__('Amount')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->amount_after_earn_discount}}</span>
                                        </li>
                                    </ul>
                                </td>
                                <td>
                                    <ul class="list-group">
                                        <li class="list-group-item">{{__('Percentage')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->deal_discount_percentage}}</span>
                                        </li>
                                        <li class="list-group-item">{{__('value')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->deal_discount}}</span></li>
                                        <li class="list-group-item">{{__('Amount')}}
                                            : <span
                                                class="float-end text-muted">{{$orderDetail->amount_after_deal_discount}}</span>
                                        </li>
                                    </ul>
                                </td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
    @if($order->status <= \Core\Enum\OrderEnum::Simulated)
    <div class="card-body row">
        <div class="col-md-12">
            <h4 class="text-info">{{__('Order simulation summary')}}</h4>
        </div>
        <div class="col-md-4">
            <h5 class="text-info">{{__('Order totals')}}</h5>
            <ul class="list-group">
                @if($order->out_of_deal_amount)
                    <li class="list-group-item"><strong>{{__('Out of deal amount')}}</strong><span
                            class="float-end text-muted">{{$order->out_of_deal_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                @if($order->deal_amount_before_discount)
                    <li class="list-group-item"><strong>{{__('Deal amount before discount')}}</strong> <span
                            class="float-end text-muted">{{$order->deal_amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                @if($order->amount_before_discount)
                    <li class="list-group-item"><strong>{{__('Amount before discount')}}</strong>
                        <span class="badge border border-success text-success float-end text-muted">
                            {{$order->amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}
                        </span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-md-4">
            <h5 class="text-info">{{__('Deal amounts')}}</h5>
            <ul class="list-group">
                @if($order->deal_amount_after_partner_discount)
                    <li class="list-group-item"><strong>{{__('After partner discount')}}</strong><span
                            class="float-end text-muted">{{$order->deal_amount_after_partner_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                @if($order->deal_amount_after_2earn_discount)
                    <li class="list-group-item"><strong>{{__('After 2earn discount')}}</strong><span
                            class="float-end text-muted">{{$order->deal_amount_after_2earn_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                @if($order->deal_amount_after_deal_discount)
                    <li class="list-group-item"><strong>{{__('After deal discount')}}</strong><span
                            class="float-end text-muted">{{$order->deal_amount_after_deal_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                @if($order->deal_amount_after_discounts)
                    <li class="list-group-item"><strong>{{__('After discounts')}}</strong><span
                            class="float-end text-muted">{{$order->deal_amount_after_discounts}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
            </ul>
        </div>
        <div class="col-md-4">
            <h5 class="text-info">{{__('Order discounts')}}</h5>
            <ul class="list-group">
                @if($order->final_discount_percentage)
                    <li class="list-group-item">
                        <strong>{{__('Final discount percentage')}}</strong>
                        <span
                            class="float-end text-muted">{{$order->final_discount_percentage}} %</span>
                    </li>
                @endif
                @if($order->final_discount_value)
                    <li class="list-group-item">
                        <strong>{{__('Final discount value')}}</strong>
                        <span
                            class="badge float-end border  border-success text-success">{{$order->final_discount_value}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                    </li>
                @endif
                <li class="list-group-item">
                    <strong>{{__('Lost discount value')}}</strong>
                    <span
                        class="badge float-end border  border-danger text-danger">{{$order->lost_discount_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                </li>
            </ul>
        </div>
    </div>
    @endif

    <div class="card-footer">
        @if($order->status == \Core\Enum\OrderEnum::New)
            <button class="btn btn-success btn-sm" wire:click="validateOrderCreation({{$order->id}})">
                {{__('Validate order')}}
            </button>
        @endif
        <span
            class="badge bg-info-subtle text-info badge-border float-end">{{__('Updated at')}} : {{$order->updated_at}}</span>
        <span
            class="badge bg-info-subtle text-info badge-border float-end">{{__('Created at')}} : {{$order->created_at}}</span>
    </div>
</div>
