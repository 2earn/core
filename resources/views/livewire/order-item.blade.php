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

                @if(\App\Models\User::isSuperAdmin())
                    <button type="button" class="btn btn-sm btn-outline-info  float-end m-1">
                {{__($order->status->name)}}
            </button>
                @endif

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
                                    <th scope="col">{{__('Prices')}}</th>
                                    <th scope="col">{{__('Shipping')}}</th>
                                    @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && $currentRouteName=="orders_detail")
                                        <th scope="col">{{__('Partner Discount')}}</th>
                                        <th scope="col">{{__('2earn Discount')}}</th>
                                        <th scope="col">{{__('Deal Discount')}}</th>
                                        <th scope="col">{{__('With discount partner')}}</th>
                                        <th scope="col">{{__('Refund dispatching')}}</th>
                                        <th scope="col">{{__('Without discount partner')}}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($order->orderDetails()->get() as $key => $orderDetail)
                                    <tr @if($orderDetail->item()->first()->deal()->exists()) class="table-primary"
                                        @else class="table-warning" @endif
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
                                                            class="float-end">{{$orderDetail->item()->first()->price}}  {{config('app.currency')}}</span>
                                                    </li>
                                                    <li class="list-group-item"><strong>{{__('Discount')}}</strong><span
                                                            class="float-end">{{$orderDetail->item()->first()->discount}} %</span>
                                                    </li>
                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                        <li class="list-group-item list-group-item-success">
                                                            <strong>{{__('Deal')}}</strong>
                                                            @if(\App\Models\User::isSuperAdmin())
                                                            <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}"><span
                                                                    class="float-end"> {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}</span>
                                                            </a>
                                                            @else
                                                                <span
                                                                    class="float-end"> {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}</span>
                                                            @endif

                                                        </li>
                                                    @endif
                                                </ul>
                                            @else
                                                <strong>
                                                    <strong>{{__('Item')}}:</strong>
                                                    <span class="text-info float-end">
                                                        @if(\App\Models\User::isSuperAdmin())
                                                            <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->id])}}">
                                                                {{$orderDetail->item()->first()->ref}}
                                                                - {{$orderDetail->item()->first()->name}}
                                                            </a>
                                                        @else
                                                            {{$orderDetail->item()->first()->ref}}
                                                            - {{$orderDetail->item()->first()->name}}
                                                        @endif
                                            </span>
                                                </strong>
                                                @if($orderDetail->item()->first()->deal()->exists())
                                                    <hr>
                                                    @if(\App\Models\User::isSuperAdmin())
                                                        <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}">
                                                            <strong>{{__('Deal')}}:</strong>
                                                            <span class="text-info float-end">
                                                                {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                            </span>
                                                        </a>
                                                    @else
                                                        <span class="text-info float-end">
                                                            {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                        </span>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            {{$orderDetail->qty}}
                                            <br>
                                            *
                                            <br>
                                            {{$orderDetail->unit_price}}  {{config('app.currency')}}
                                            <hr>
                                            = {{$orderDetail->total_amount}}  {{config('app.currency')}}
                                        </td>
                                        <td>{{$orderDetail->shipping}}  {{config('app.currency')}}</td>
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
                                                            <span
                                                                class="float-end"> {{$orderDetail->deal_discount}}</span>
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
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <strong>{{__('Discount')}}</strong><span
                                                                class="float-end">{{$orderDetail->total_discount_with_discount_partner}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Ponderation')}}</strong><span
                                                                class="float-end">{{$orderDetail->ponderation_with_discount_partner}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Percentage')}}</strong><span
                                                                class="float-end">{{$orderDetail->total_discount_percentage_with_discount_partner}} {{config('app.percentage')}}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <strong>{{__('Final discount')}}</strong><span
                                                                class="float-end">{{$orderDetail->final_discount}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Refund dispatching')}}</strong><span
                                                                class="float-end">{{$orderDetail->refund_dispatching}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Final amount')}}</strong><span
                                                                class="float-end">{{$orderDetail->final_amount}} {{config('app.currency')}}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <ul class="list-group">
                                                        <li class="list-group-item">
                                                            <strong>{{__('Final')}}</strong><span
                                                                class="float-end">{{$orderDetail->final_discount_without_discount_partner}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Value')}}</strong><span
                                                                class="float-end">{{$orderDetail->discount_value_without_discount_partner}} {{config('app.currency')}}</span>
                                                        </li>
                                                        <li class="list-group-item">
                                                            <strong>{{__('Percentage')}}</strong><span
                                                                class="float-end">{{$orderDetail->discount_percentage_without_discount_partner}} {{config('app.percentage')}}</span>
                                                        </li>
                                                    </ul>
                                                </td>
                                            @else
                                                <td colspan="6" class="text-center">
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

                                            @if($order->deal_amount_before_discount)
                                                <li class="list-group-item">
                                                    <strong>{{__('Amount before discount')}}</strong>
                                                    <span
                                                        class="float-end text-muted">
                            {{$order->deal_amount_before_discount}}  {{config('app.currency')}}</span>
                                                </li>
                                            @endif
                                            @if($order->out_of_deal_amount)
                                                <li class="list-group-item">
                                                    <strong>{{__('Out of deal amount')}}</strong><span
                                                        class="float-end text-muted">{{$order->out_of_deal_amount}}  {{config('app.currency')}}</span>
                                                </li>
                                            @endif
                                            @if($order->out_of_deal_amount && $order->deal_amount_before_discount)
                                                <li class="list-group-item">
                                                    <strong>{{__('Total')}}</strong>
                                                    <span
                                                        class="badge border border-success text-success float-end text-muted">
                                                        {{$order->out_of_deal_amount +$order->deal_amount_before_discount}}  {{config('app.currency')}}</span>
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
                                                            class="badge border border-danger text-light float-end text-muted">{{$order->deal_amount_before_discount}}  {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_partner_discount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('After partner discount')}}</strong><span
                                                            class="float-end text-muted">{{$order->deal_amount_after_partner_discount}}   {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_2earn_discount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('After 2earn discount')}}</strong><span
                                                            class="float-end text-muted">{{$order->deal_amount_after_2earn_discount}}   {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_deal_discount)
                                                    <li class="list-group-item  text-bg-light">
                                                        <strong>{{__('After deal discount')}}</strong>
                                                        <span
                                                            class="float-end text-muted">{{$order->deal_amount_after_deal_discount}}   {{config('app.currency')}}</span>
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
                                                            class="badge float-end border  border-success text-success">{{$order->final_discount_value}}  {{config('app.currency')}}</span>
                                                    </li>
                                                    <li class="list-group-item">
                                                        <strong>{{__('Lost discount value')}}</strong>
                                                        <span
                                                            class="badge float-end border  border-danger text-danger">{{$order->lost_discount_amount}}  {{config('app.currency')}}</span>
                                                    </li>
                                                    @endif
                                                @if($order->deal_amount_after_discounts)
                                                    <li class="list-group-item">
                                                        <strong>{{__('Deal amount after discounts')}}</strong> <span
                                                            class="badge border border-success text-light float-end text-muted">{{$order->deal_amount_after_discounts}}   {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                                @if($order->deal_amount_after_discounts&&$order->out_of_deal_amount)
                                                    <li class="list-group-item">
                                                        <strong>{{__('Order amount after discounts')}}</strong> <span
                                                            class="badge border border-success text-light float-end text-muted">{{$order->deal_amount_after_discounts +$order->out_of_deal_amount}}   {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
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
                                            {{$order->amount_after_discount}}   {{config('app.currency')}}</span>
                                </li>
                                <li class="list-group-item list-group-item-action list-group-item-secondary">
                                                <span
                                                    class="text-dark text-xl-start">{{__('Gain from BFSs soldes')}}</span>
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->amount_after_discount-$order->paid_cash}}   {{config('app.currency')}}</span>
                                </li>
                                <li class="list-group-item list-group-item-success">
                                    <span class="text-dark text-xl-start">{{__('Paid cash')}}</span>
                                    <span
                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->paid_cash}}   {{config('app.currency')}}</span>
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
                                                    <span
                                                        class="badge bg-success text-end fs-14 float-end"> {{$discount->value}}  {{config('app.currency')}}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info text-end fs-14 float-end"> {{$discount->current_balance}} {{config('app.currency')}}</span>
                                                </td>
                                                <td>
                                                    <p>  {{$discount->description}}</p>
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
                                                        <span
                                                            class="badge bg-success text-end fs-14 float-end"> {{$bfs->value}}  {{config('app.currency')}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-info text-end fs-14 float-end"> {{$bfs->current_balance}}  {{config('app.currency')}}</span>
                                                    </td>
                                                    <td>
                                                        <p>{{$bfs->description}}</p>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-warning text-end fs-14 float-end">   {{$bfs->percentage}}</span>
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
                                                    <span
                                                        class="badge bg-success text-end fs-14 float-end"> {{$cash->value}}  {{config('app.currency')}}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-info text-end fs-14 float-end">     {{$cash->current_balance}}  {{config('app.currency')}}</span>
                                                </td>
                                                <td>
                                                    <p>
                                                        {{$cash->description}}
                                                    </p>
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
            @if(\App\Models\User::isSuperAdmin())
            @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                @include('livewire.commission-breackdowns', ['commissions' => $commissions])
            @endif
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
