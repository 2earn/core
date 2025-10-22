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
        <div class="card-header border-0 align-items-center d-flex">
            <span class="text-secondary mx-2 text-dark fs-16">#{{$order->id}}</span>
            <button type="button" class="btn btn-soft-primary material-shadow-none btn-sm mx-2">
                {{getUserDisplayedName($order->user()->first()->idUser)}}
            </button>
            <div>
                @if($order->total_order_quantity && $currentRouteName=="orders_detail")
                    <button type="button" class="btn btn-soft-primary material-shadow-none btn-sm">
                        {{__('Total order quantity')}} <span
                            class="badge bg-info ms-1">{{$order->total_order_quantity}}</span>
                    </button>
                @endif
                <button type="button" class="btn btn-soft-secondary material-shadow-none btn-sm">
                    {{__($order->status->name)}}
                </button>
                @if($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)
                    <button type="button" class="btn btn-soft-warning material-shadow-none btn-sm"
                            title="{{__('Platform')}}">
                        {{__($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)}}
                    </button>
                @endif
                @if($order->status->value == \Core\Enum\OrderEnum::Dispatched->value && $currentRouteName=="orders_detail")
                    <button type="button" class="btn btn-soft-success material-shadow-none btn-sm mx-2 float-end"
                            onclick="window.print()" title="{{__('Print')}}">
                        <i class="ri-printer-line"></i> {{ __('Print') }}
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">{{__('Order details summary')}}</h6>
                </div>
                @if($order->OrderDetails()->count()>0)
                    <div class="card-body row">
                        @if(\App\Models\User::isSuperAdmin())
                            @if($order->note && $currentRouteName=="orders_detail")
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{__('Order details')}}</h6>
                                        </div>
                                        <div class="card-body">
                                            <blockquote class="text-muted mt-2">
                                                <span
                                                    class="text-muted">{{__('This is only for admin / not translatable note')}}</span>
                                                <strong>{{__('Note')}}: </strong><br>{{$order->note}}
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if( str_contains($order->note, 'Coupons buy from'))
                            <div class="col-md-12">
                                <a class="link-info float-end"
                                   href="{{route('coupon_history',['locale'=>app()->getLocale()])}}">
                                    <span class=""> {{ __('Go to Coupons History') }}</span>
                                </a>
                            </div>
                        @endif
                        <div class="col-md-12">
                            @if($order->orderDetails()->count())
                                <div class="card mt-2">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-nowrap">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="text-end">#</th>
                                                    <th scope="col" class="text-end">{{__('Order details')}}</th>
                                                    <th scope="col" class="text-end">{{__('Prices')}}</th>
                                                    <th scope="col" class="text-end">{{__('Shipping')}}</th>
                                                    @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
                                                        <th scope="col" class="text-end">{{__('Partner Discount')}}</th>
                                                        <th scope="col" class="text-end">{{__('2earn Discount')}}</th>
                                                        <th scope="col" class="text-end">{{__('Deal Discount')}}</th>
                                                        <th scope="col" class="text-end">{{__('Total discount')}}</th>
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
                                                                    <li class="list-group-item"><strong>{{__('REF')}}
                                                                            - {{__('Name')}} </strong>
                                                                        <span class="float-end">#{{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}</span>
                                                                    </li>
                                                                    <li class="list-group-item">
                                                                        <strong>{{__('Price')}}</strong><span
                                                                            class="float-end">
                                                                        {{$orderDetail->unit_price}}  {{config('app.currency')}}</span>
                                                                    </li>

                                                                    @if($orderDetail->item()->first()?->platform()->exists())
                                                                    <li class="list-group-item">
                                                                        <strong>{{__('Platform')}}</strong><span
                                                                            class="float-end">  {{__($orderDetail->item()->first()?->platform()->first()?->name)}}</span>
                                                                    </li>
                                                                    @endif

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
                                                            <span class="float-end"> {{$orderDetail->qty}}</span>
                                                            <br>
                                                            *
                                                            <br>
                                                            <span
                                                                class="float-end">   {{$orderDetail->unit_price}}  {{config('app.currency')}}</span>
                                                            <br>
                                                            <hr>
                                                            = <span
                                                                class="badge bg-soft-primary text-dark text-end fs-14 float-end"> {{$orderDetail->total_amount}}  {{config('app.currency')}}</span>
                                                        </td>
                                                        <td>

                                                            @if($orderDetail->shipping)
                                                                <span
                                                                    class="badge bg-soft-warning text-dark text-end fs-13 float-end">
                                                            {{$orderDetail->shipping}}  {{config('app.currency')}}
                                                        </span>
                                                            @else
                                                                <span
                                                                    class="text-muted float-end"> {{__('No shipping')}} </span>
                                                            @endif
                                                        </td>
                                                        @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
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
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('value')}}">
                                                                            <i class="ri-increase-decrease-fill"></i>
                                                                            <span class="float-end">
                                                                        {{$orderDetail->partner_discount}}
                                                                    </span>
                                                                        </li>
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('Amount')}}">
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
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('value')}}">
                                                                            <i class="ri-increase-decrease-fill"></i>
                                                                            <span class="float-end">
                                                                        {{$orderDetail->earn_discount}}
                                                                    </span>
                                                                        </li>
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('Amount')}}">
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
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('value')}}">
                                                                            <i class="ri-increase-decrease-fill"></i>
                                                                            <span
                                                                                class="float-end"> {{$orderDetail->deal_discount}}</span>
                                                                        </li>
                                                                        <li class="list-group-item text-muted"
                                                                            title="{{__('Amount')}}">
                                                                            <i class="ri-money-dollar-box-fill"></i>
                                                                            <span class="float-end">
                                                                        {{$orderDetail->amount_after_deal_discount}}
                                                                    </span>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                    <span
                                                        class="badge bg-success text-end fs-14 float-end">{{$orderDetail->total_discount}} {{config('app.currency')}}</span>
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
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="card-body row">
                        <div class="col-12 alert alert-info material-shadow" role="alert">
                            {{__('Empty order')}}
                        </div>
                    </div>
                @endif
            </div>

            @if(\App\Models\User::isSuperAdmin())
                @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value && $commissions->isNotEmpty() &&(isset($discount) ||isset($bfss)||isset($cash)))
                    @include('livewire.order-deals', ['orderDeals' => $orderDeals])
                @endif
            @endif
            @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value  && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
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
                            @if($order->total_final_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{__('Order discounts')}}</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group">
                                                @if($order->total_final_discount)
                                                    <li class="list-group-item"
                                                        title="{{$order->total_final_discount_percentage}}   {{config('app.percentage')}}">
                                                        <strong>{{__('Total final discount')}}</strong>
                                                        <span
                                                            class="float-end text-info">
                                                 {{$order->total_final_discount}}  {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                                @if($order->total_lost_discount)
                                                    <li class="list-group-item"
                                                        title="{{$order->total_lost_discount_percentage}}   {{config('app.percentage')}}">
                                                        <strong>{{__('Total lost discount')}}</strong><span
                                                            class="float-end text-muted">{{$order->total_lost_discount}}  {{config('app.currency')}}</span>
                                                        <hr>
                                                        <span
                                                            class="text-warning float-end">{{__('You can top up your discount with')}} {{$order->total_lost_discount}}   {{config('app.currency')}}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($order->amount_after_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{__('Order mains amounts')}}</h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group mt-2">
                                                <li class="list-group-item">
                                                <span
                                                    class="text-dark text-xl-start">{{__('Amount after discount')}}</span>
                                                    <span
                                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->amount_after_discount}}   {{config('app.currency')}}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action">
                                                <span
                                                    class="text-dark text-xl-start">{{__('Gain from BFSs soldes')}}</span>
                                                    <span
                                                        class="badge bg-success text-end fs-14 float-end">
                                            {{$order->amount_after_discount-$order->paid_cash}}   {{config('app.currency')}}</span>
                                                </li>
                                                <li class="list-group-item">
                                                    <span class="text-dark text-xl-start">{{__('Paid cash')}}</span>
                                                    <span
                                                        class="badge bg-danger text-end fs-14 float-end">
                                            {{$order->paid_cash}}   {{config('app.currency')}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($currentRouteName=="orders_detail" && $order->status->value >= \Core\Enum\OrderEnum::Paid->value &&(!is_null($discount) ||!is_null($bfss)||!is_null($cash)))
                        <div class="col-md-12">
                            <div class="card mt-2">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">{{__('Balances Operations')}}</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        @if(isset($discount))
                                            <li class="list-group-item logoTopDBLabel">
                                                <h5>{{__('Discount')}}</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-striped border">
                                                        <thead>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col" class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                        </thead>
                                                        <tr>
                                                            <td>
                                                                {{$discount->reference}}
                                                            </td>
                                                            <td class="logoTopDBLabel text-end fs-14">
                                                                <h5> {{$discount->value}}  {{config('app.currency')}}</h5>
                                                            </td>
                                                            <td class="logoTopDBLabel text-end fs-14">
                                                                <h5>  {{$discount->current_balance}} {{config('app.currency')}}</h5>
                                                            </td>

                                                            <td>
                                                                <p>  {{$discount->description}}
                                                                    / {{__('Discount')}}  {{$discount->current_balance+$discount->value}}
                                                                    - {{$discount->value}}
                                                                    = {{$discount->current_balance}}
                                                                    {{-- BO NEW DESC--}}
                                                                    <br>
                                                                    {!! \App\Services\Balances\Balances::generateDescription($discount) !!}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                {{$discount->created_at}}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($bfss) && $bfss->isNotEmpty())
                                            <li class="list-group-item logoTopBFSLabel">
                                                <h5>{{__('BFS (Balances for Shopping)')}}</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-striped border">
                                                        <thead>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col" class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col" class="text-end">{{__('Percentage')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                        </thead>
                                                        @foreach($bfss as $bfs)
                                                            <tr>
                                                                <td>
                                                                    {{$bfs->reference}}
                                                                </td>

                                                                <td class="logoTopBFSLabel text-end fs-14">
                                                                    <h5> {{$bfs->value}}  {{config('app.currency')}}</h5>
                                                                </td>
                                                                <td class="logoTopBFSLabel text-end fs-14">
                                                                    <h5>  {{$bfs->current_balance}}  {{config('app.currency')}}</h5>
                                                                </td>
                                                                <td>
                                                                    <p>{{$bfs->description}}
                                                                        / {{__('BFS')}}  {{$bfs->current_balance+$bfs->value}}
                                                                        - {{$bfs->value}}
                                                                        = {{$bfs->current_balance}}
                                                                        {{-- BO NEW DESC--}}
                                                                        <br>
                                                                        {!! \App\Services\Balances\Balances::generateDescription($bfs) !!}
                                                                    </p>
                                                                </td>
                                                                <td>
                                                        <span
                                                            class="badge bg-warning text-end fs-14 float-end">   {{$bfs->percentage}} {{config('app.percentage')}}</span>
                                                                </td>
                                                                <td>
                                                                    {{$bfs->created_at}}
                                                                </td>
                                                            </tr>
                                                        @endforeach

                                                    </table>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($cash))
                                            <li class="list-group-item logoTopCashLabel">
                                                <h5>{{__('Cash')}}</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-striped border">
                                                        <thead>
                                                        <th scope="col">{{__('Reference')}}</th>
                                                        <th scope="col" class="text-end">{{__('Value')}}</th>
                                                        <th scope="col" class="text-end">{{__('Current balance')}}</th>
                                                        <th scope="col">{{__('Description')}}</th>
                                                        <th scope="col">{{__('Created at')}}</th>
                                                        </thead>
                                                        <tr>
                                                            <td>
                                                                {{$cash->reference}}
                                                            </td>
                                                            <td class="logoTopCashLabel text-end fs-14">
                                                                <h5> {{$cash->value}}  {{config('app.currency')}}</h5>
                                                            </td>
                                                            <td class="logoTopCashLabel text-end fs-14">
                                                                <h5>   {{$cash->current_balance}}  {{config('app.currency')}}</h5>
                                                            </td>
                                                            <td>
                                                                <p>
                                                                    {{$cash->description}}
                                                                    / {{__('CASH')}}  {{$cash->current_balance+$cash->value}}
                                                                    - {{$cash->value}}
                                                                    = {{$cash->current_balance}}
                                                                    {{-- BO NEW DESC--}}
                                                                    <br>
                                                                    {!! \App\Services\Balances\Balances::generateDescription($cash) !!}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                {{$cash->created_at}}
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
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
            @endif

            <div class="card-footer">
            <span class="badge bg-info-subtle text-info badge-border">
            {{__('Updated at')}} : {{$order->updated_at}}</span>
                @if($currentRouteName=="orders_detail")
                    <span class="badge bg-info-subtle text-info badge-border">
                {{__('Created at')}} : {{$order->created_at}}</span>
                @endif
                @if($currentRouteName=="orders_index" || $currentRouteName=="orders_previous"|| $currentRouteName=="orders_summary" )
                    <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                       class="float-end">{{__('More details')}}</a>
                @endif
            </div>
        </div>
    </div>
</div>
