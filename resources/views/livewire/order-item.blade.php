<div>
    @if($currentRouteName=="orders_detail")
        @section('title')
            {{ __('Order details') }} : {{__('Order id')}} : {{$order->id}}
        @endsection
        @component('components.breadcrumb')
            @slot('title')
                <a href="{{route('orders_previous',['locale'=> app()->getLocale()])}}" class="text-white mx-1"><i
                        class="ri-shopping-cart-fill"></i> </a>   {{ __('Order details') }} : {{__('Order id')}}
                : {{$order->id}}
            @endslot
        @endcomponent
        <div class="row">
            <div class="col-12">
                @include('layouts.flash-messages')
            </div>
        </div>
    @endif
    <div class="card border card-border-light">
        <div class="card-header" title="{{__('Order id')}} : {{$order->id}}">
            @if($currentRouteName=="orders_detail")
                <button type="button" class="btn btn-sm btn-outline-info m-1">
                    {{__('Order id')}} <span
                        class="badge bg-info ms-1">{{$order->id}}</span>
                </button>
            @endif

            @if($order->total_order_quantity && $currentRouteName=="orders_detail")
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
            <div class="card mt-2">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{__('Order details summary')}}</h6>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        @if($order->orderDetails()->count())
                            <table class="table table-striped table-bordered border-dark table-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{__('Order details')}}</th>
                                    <th scope="col">{{__('Qty')}}</th>
                                    <th scope="col">{{__('Unit price')}}</th>
                                    <th scope="col">{{__('Shipping')}}</th>
                                    <th scope="col">{{__('Total amount')}}</th>
                                    @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && $currentRouteName=="orders_detail")
                                        <th scope="col">{{__('Partner Discount')}}</th>
                                        <th scope="col">{{__('2earn Discount')}}</th>
                                        <th scope="col">{{__('Deal Discount')}}</th>
                                        <th scope="col">{{__('Final amount')}}</th>
                                        <th scope="col">{{__('Final discount')}}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderDetails()->get() as $key => $orderDetail)
                                    <tr @if($orderDetail->item()->first()->deal()->exists()) class="table-info" @endif
                                    >
                                        <th scope="row">{{$key + 1}}</th>
                                        <td>
                                            @if($currentRouteName=="orders_detail")
                                                <ul class="list-group">
                                                    <li class="list-group-item"><strong>{{__('Name')}}</strong><span
                                                            class="float-end">{{$orderDetail->item()->first()->name}}</span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>{{__('Reference')}}</strong><span
                                                            class="float-end">{{$orderDetail->item()->first()->ref}}</span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>{{__('Price')}}</strong><span
                                                            class="float-end">{{$orderDetail->item()->first()->price}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                    <li class="list-group-item"><strong>{{__('Discount')}}</strong><span
                                                            class="float-end">{{$orderDetail->item()->first()->discount}} %</span>
                                                    </li>
                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                        <li class="list-group-item list-group-item-success">
                                                            <strong>{{__('Deal')}}</strong>
                                                            <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}"><span
                                                                    class="float-end"> {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @else
                                                <strong>
                                                <span class="text-muted">
                                                    {{$orderDetail->item()->first()->ref}} -
                                                </span>
                                                </strong>
                                                <span class="text-muted">
                                                {{$orderDetail->item()->first()->name}}
                                            </span>
                                                @if($orderDetail->item()->first()->deal()->exists())
                                                    <span class="text-info"> : {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{$orderDetail->qty}}</td>
                                        <td>{{$orderDetail->unit_price}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                                        <td>{{$orderDetail->shipping}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                                        <td>{{$orderDetail->total_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</td>
                                        @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && $currentRouteName=="orders_detail")
                                            @if($orderDetail->item->deal()->exists())
                                                <td>
                                                    <ul class="list-group">
                                                        <li class="list-group-item text-muted"
                                                            title="{{__('Percentage')}}">
                                                            <i class="ri-percent-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->partner_discount_percentage}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('value')}}">
                                                            <i class="ri-increase-decrease-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->partner_discount}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('Amount')}}">
                                                            <i class="ri-money-dollar-box-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->amount_after_partner_discount}}
                                            </span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-group">
                                                        <li class="list-group-item text-muted"
                                                            title="{{__('Percentage')}}">
                                                            <i class="ri-percent-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->earn_discount_percentage}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('value')}}">
                                                            <i class="ri-increase-decrease-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->earn_discount}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('Amount')}}">
                                                            <i class="ri-money-dollar-box-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->amount_after_earn_discount}}
                                            </span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-group">
                                                        <li class="list-group-item text-muted"
                                                            title="{{__('Percentage')}}">
                                                            <i class="ri-percent-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->deal_discount_percentage}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('value')}}">
                                                            <i class="ri-increase-decrease-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->deal_discount}}
                                            </span>
                                                        </li>
                                                        <li class="list-group-item text-muted" title="{{__('Amount')}}">
                                                            <i class="ri-money-dollar-box-fill"></i>
                                                            <span class="float-end">
                                                {{$orderDetail->amount_after_deal_discount}}
                                            </span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                      <span
                                                          class="badge bg-danger text-end fs-14">
                                                    {{$orderDetail->final_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}                                                            </span>
                                                    </span>
                                                </td>
                                                <td>
                                                            <span
                                                                class="badge bg-success text-end fs-14">
                                                    {{$orderDetail->final_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}
                                                            </span>
                                                </td>
                                            @else
                                                <td colspan="3" class="text-center">
                                             <br>
                                                    <span
                                                    class="alert alert-light mt-2">{{__('No deal in this order details')}}</span>
                                                </td>
                                            @endif
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            @if($order->note && $currentRouteName=="orders_detail")
                <div class="card mt-2">
                    <div class="card-header">
                        <h6 class="card-title mb-0">{{__('Order details summary')}}</h6>
                    </div>
                    <div class="card-body">
                        <blockquote class="text-muted mt-2">
                            <strong>{{__('Note')}}: </strong><br>{{$order->note}}
                        </blockquote>
                    </div>
                </div>
            @endif
            @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value  && $currentRouteName=="orders_detail")
                <div class="col-md-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h6 class="card-title mb-0">{{__('Order simulation summary')}}</h6>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-4">
                                <div class="card mt-2">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">{{__('Order totals')}}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @if($order->out_of_deal_amount)
                                                <li class="list-group-item">
                                                    <strong>{{__('Out of deal amount')}}</strong><span
                                                        class="float-end text-muted">{{$order->out_of_deal_amount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                </li>
                                            @endif
                                            @if($order->amount_before_discount)
                                                <li class="list-group-item">
                                                    <strong>{{__('Amount before discount')}}</strong>
                                                    <span
                                                        class="badge border border-success text-success float-end text-muted">
                            {{$order->amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if($order->deal_amount_after_partner_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{__('Deal amounts')}}</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group  text-bg-light">
                                                @if($order->deal_amount_before_discount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('Deal amount before discount')}}</strong>
                                                        <span
                                                            class="badge border border-danger text-light float-end text-muted">{{$order->deal_amount_before_discount}} {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_partner_discount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('After partner discount')}}</strong><span
                                                            class="float-end text-muted">{{$order->deal_amount_after_partner_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_2earn_discount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('After 2earn discount')}}</strong><span
                                                            class="float-end text-muted">{{$order->deal_amount_after_2earn_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_deal_discount)
                                                    <li class="list-group-item  text-bg-light">
                                                        <strong>{{__('After deal discount')}}</strong>
                                                        <span
                                                            class="badge border border-success text-light float-end text-muted">{{$order->deal_amount_after_deal_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card mt-2">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{__('Order discounts')}}</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @if($order->deal_amount_after_discounts)
                                                    <li class="list-group-item">
                                                        <strong>{{__('After discounts')}}</strong><span
                                                            class="float-end text-muted">{{$order->deal_amount_after_discounts}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                                    </li>
                                                @endif
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
                                </div>
                        </div>
                        @endif
                    </div>
                </div>
            @endif
            @if($order->amount_after_discount)
                <div class="col-md-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h6 class="card-title mb-0">{{__('Order mains amounts')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group mt-2">
                                <li class="list-group-item  text-bg-light">
                                                <span
                                                    class="text-dark text-xl-start">{{__('Amount after discount')}}</span>
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->amount_after_discount}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                </li>
                                <li class="list-group-item list-group-item-action list-group-item-secondary">
                                                <span
                                                    class="text-dark text-xl-start">{{__('Gain from BFSs soldes')}}</span>
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->amount_after_discount-$order->paid_cash}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                </li>
                                <li class="list-group-item list-group-item-success">
                                    <span class="text-dark text-xl-start">{{__('Paid cash')}}</span>
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->paid_cash}}  {{\App\Http\Livewire\OrderItem::CURRENCY}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value &&(!is_null($discount) ||!is_null($bfss)||!is_null($cash)))
                <div class="col-md-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h6 class="card-title mb-0">{{__('Balances Operations')}}</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @if(isset($discount))
                                    <li class="list-group-item">
                                        <strong>{{__('Discount')}}</strong>
                                        <table class="table table-striped border">
                                            <thead>
                                            <td>{{__('Reference')}}</td>
                                            <td>{{__('Value')}}</td>
                                            <td>{{__('Current balance')}}</td>
                                            <td>{{__('Description')}}</td>
                                            <td>{{__('Created at')}}</td>
                                            </thead>
                                            <tr>
                                                <td>
                                                    {{$discount->reference}}
                                                </td>
                                                <td>
                                                    {{$discount->value}}
                                                </td>
                                                <td>
                                                    {{$discount->current_balance}}
                                                </td>
                                                <td>
                                                    {{$discount->description}}
                                                </td>
                                                <td>
                                                    {{$discount->created_at}}
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                @endif
                                @if(isset($bfss) && $bfss->isNotEmpty())
                                    <li class="list-group-item">
                                        <strong>{{__('BFSS')}}</strong>
                                        <table class="table table-striped border">
                                            <thead>
                                            <td>{{__('Reference')}}</td>
                                            <td>{{__('Value')}}</td>
                                            <td>{{__('Current balance')}}</td>
                                            <td>{{__('Description')}}</td>
                                            <td>{{__('Percentage')}}</td>
                                            <td>{{__('Created at')}}</td>
                                            </thead>
                                            @foreach($bfss as $bfs)
                                                <tr>
                                                    <td>
                                                        {{$bfs->reference}}
                                                    </td>

                                                    <td>
                                                        {{$bfs->value}}
                                                    </td>
                                                    <td>
                                                        {{$bfs->current_balance}}
                                                    </td>
                                                    <td>
                                                        {{$bfs->description}}
                                                    </td>
                                                    <td>
                                                        {{$bfs->percentage}}
                                                    </td>
                                                    <td>
                                                        {{$bfs->created_at}}
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </li>
                                @endif
                                @if(isset($cash))
                                    <li class="list-group-item">
                                        <strong>{{__('Cash')}}</strong>
                                        <table class="table table-striped border">
                                            <thead>
                                            <td>{{__('Reference')}}</td>
                                            <td>{{__('Value')}}</td>
                                            <td>{{__('Current balance')}}</td>
                                            <td>{{__('Description')}}</td>
                                            <td>{{__('Created at')}}</td>
                                            </thead>
                                            <tr>
                                                <td>
                                                    {{$cash->reference}}
                                                </td>
                                                <td>
                                                    {{$cash->value}}
                                                </td>
                                                <td>
                                                    {{$cash->current_balance}}
                                                </td>
                                                <td>
                                                    {{$cash->description}}
                                                </td>
                                                <td>
                                                    {{$cash->created_at}}
                                                </td>
                                            </tr>
                                        </table>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                @include('livewire.commission-breackdowns', ['commissions' => $commissions])
            @endif
        </div>
        <div class="card-footer">
            <span class="badge bg-info-subtle text-info badge-border">
            {{__('Updated at')}} : {{$order->updated_at}}</span>
            @if($currentRouteName=="orders_detail")
                <span class="badge bg-info-subtle text-info badge-border">
                {{__('Created at')}} : {{$order->created_at}}</span>
            @endif
            @if($currentRouteName=="orders_index" || $currentRouteName=="orders_previous" )
                <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                   class=float-end">{{__('More details')}}</a>
            @endif
            @if($order->status->value == \Core\Enum\OrderEnum::New->value)
                <button class="btn btn-success btn-sm mx-2" wire:click="validateOrderCreation({{$order->id}})">
                    {{__('Validate order')}}
                </button>
            @endif
        </div>
    </div>
</div>
