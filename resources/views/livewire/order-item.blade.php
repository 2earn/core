<div class="{{getContainerType()}}">
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
    <div class="card shadow-sm border">
        <div class="card-header bg-light border-bottom">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <i class="ri-file-list-line text-primary fs-18 me-2"></i>
                    <span class="badge bg-primary-subtle text-primary fs-14 fw-semibold">#{{$order->id}}</span>
                </div>

                <span class="badge bg-info-subtle text-info">
                    <i class="ri-user-line me-1"></i>{{getUserDisplayedName($order->user()->first()->idUser)}}
                </span>

                @if($order->total_order_quantity && $currentRouteName=="orders_detail")
                    <span class="badge bg-primary-subtle text-primary">
                        <i class="ri-shopping-bag-line me-1"></i>{{__('Total order quantity')}}: {{$order->total_order_quantity}}
                    </span>
                @endif

                <span class="badge bg-secondary-subtle text-secondary">
                    <i class="ri-checkbox-circle-line me-1"></i>{{__($order->status->name)}}
                </span>

                @if($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)
                    <span class="badge bg-warning-subtle text-warning" title="{{__('Platform')}}">
                        <i class="ri-computer-line me-1"></i>{{__($order->OrderDetails()->first()?->item()->first()?->platform()->first()?->name)}}
                    </span>
                @endif

                @if($order->status->value == \Core\Enum\OrderEnum::Dispatched->value && $currentRouteName=="orders_detail")
                    <button type="button" class="btn btn-success btn-sm ms-auto"
                            onclick="window.print()" title="{{__('Print')}}">
                        <i class="ri-printer-line me-1"></i>{{ __('Print') }}
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">
                        <i class="ri-file-text-line text-primary me-2"></i>{{__('Order details summary')}}
                    </h6>
                </div>
                @if($order->OrderDetails()->count()>0)
                    <div class="card-body row">
                        @if(\App\Models\User::isSuperAdmin())
                            @if($order->note && $currentRouteName=="orders_detail")
                                <div class="col-md-12 mb-3">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning-subtle">
                                            <h6 class="card-title mb-0">
                                                <i class="ri-alert-line text-warning me-2"></i>{{__('Order details')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-warning mb-0">
                                                <p class="mb-2">
                                                    <i class="ri-lock-line me-1"></i>
                                                    <small
                                                        class="text-muted">{{__('This is only for admin / not translatable note')}}</small>
                                                </p>
                                                <strong><i class="ri-sticky-note-line me-1"></i>{{__('Note')}}:
                                                </strong>
                                                <p class="mb-0 mt-2">{{$order->note}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        @if( str_contains($order->note, 'Coupons buy from'))
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="ri-coupon-line me-2"></i>
                                        {{__('This order contains coupons')}}
                                    </span>
                                    <a class="btn btn-info btn-sm"
                                       href="{{route('coupon_history',['locale'=>app()->getLocale()])}}">
                                        <i class="ri-history-line me-1"></i>{{ __('Go to Coupons History') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="col-md-12">
                            @if($order->orderDetails()->count())
                                <div class="card mt-2 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0">
                                                <thead class="table-light">
                                                <tr>
                                                    <th scope="col" class="text-center">#</th>
                                                    <th scope="col">{{__('Order details')}}</th>
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
                                                    <tr @if($orderDetail->item()->first()->deal()->exists())
                                                            class="table-success"
                                                        @else
                                                            class="table-light"
                                                        @endif
                                                    >
                                                        <th scope="row" class="text-center fw-bold">{{$key + 1}}</th>
                                                        <td>
                                                            @if($currentRouteName=="orders_detail")
                                                                <ul class="list-group list-group-flush">
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong><i
                                                                                class="ri-price-tag-line text-primary me-1"></i>{{__('REF')}}
                                                                            - {{__('Name')}}</strong>
                                                                        <span
                                                                            class="badge bg-primary-subtle text-primary">#{{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}</span>
                                                                    </li>
                                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <strong><i
                                                                                class="ri-money-dollar-circle-line text-success me-1"></i>{{__('Price')}}
                                                                        </strong>
                                                                        <span
                                                                            class="badge bg-success-subtle text-success">
                                                                        {{$orderDetail->unit_price}} {{config('app.currency')}}</span>
                                                                    </li>

                                                                    @if($orderDetail->item()->first()?->platform()->exists())
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <strong><i
                                                                                    class="ri-computer-line text-info me-1"></i>{{__('Platform')}}
                                                                            </strong>
                                                                            <span
                                                                                class="badge bg-info-subtle text-info">{{__($orderDetail->item()->first()?->platform()->first()?->name)}}</span>
                                                                        </li>
                                                                    @endif

                                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                                                                            <strong><i
                                                                                    class="ri-gift-line text-success me-1"></i>{{__('Deal')}}
                                                                            </strong>
                                                                            @if(\App\Models\User::isSuperAdmin())
                                                                                <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}"
                                                                                   class="badge bg-success text-white text-decoration-none">
                                                                                    {{$orderDetail->item()->first()->deal()->first()->id}}
                                                                                    - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                </a>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-success-subtle text-success">
                                                                                    {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                </span>
                                                                            @endif

                                                                        </li>
                                                                    @endif
                                                                </ul>
                                                            @else
                                                                <div class="mb-3">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-start mb-2">
                                                                        <strong class="text-muted"><i
                                                                                class="ri-shopping-bag-line me-1"></i>{{__('Item')}}
                                                                            :</strong>
                                                                        <span class="badge bg-info-subtle text-info">
                                                                            @if(\App\Models\User::isSuperAdmin())
                                                                                <a href="{{route('items_detail',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->id])}}"
                                                                                   class="text-info text-decoration-none">
                                                                                    {{$orderDetail->item()->first()->ref}} - {{$orderDetail->item()->first()->name}}
                                                                                </a>
                                                                            @else
                                                                                {{$orderDetail->item()->first()->ref}}
                                                                                - {{$orderDetail->item()->first()->name}}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @if($orderDetail->item()->first()->deal()->exists())
                                                                        <div
                                                                            class="d-flex justify-content-between align-items-start">
                                                                            <strong class="text-muted"><i
                                                                                    class="ri-gift-line me-1"></i>{{__('Deal')}}
                                                                                :</strong>
                                                                            <span
                                                                                class="badge bg-success-subtle text-success">
                                                                                @if(\App\Models\User::isSuperAdmin())
                                                                                    <a href="{{route('deals_show',['locale'=>app()->getLocale(),'id'=>$orderDetail->item()->first()->deal()->first()->id])}}"
                                                                                       class="text-success text-decoration-none">
                                                                                        {{$orderDetail->item()->first()->deal()->first()->id}} - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                    </a>
                                                                                @else
                                                                                    {{$orderDetail->item()->first()->deal()->first()->id}}
                                                                                    - {{$orderDetail->item()->first()->deal()->first()->name}}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="d-flex flex-column align-items-end gap-2">
                                                                <span class="badge bg-light text-dark border">
                                                                    {{$orderDetail->qty}} × {{$orderDetail->unit_price}} {{config('app.currency')}}
                                                                </span>
                                                                <span class="badge bg-primary fs-14">
                                                                    = {{$orderDetail->total_amount}} {{config('app.currency')}}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            @if($orderDetail->shipping)
                                                                <span
                                                                    class="badge bg-warning-subtle text-warning fs-13">
                                                                    <i class="ri-truck-line me-1"></i>{{$orderDetail->shipping}} {{config('app.currency')}}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">
                                                                    <i class="ri-close-circle-line me-1"></i>{{__('No shipping')}}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        @if($order->status->value >= \Core\Enum\OrderEnum::Simulated->value && in_array($currentRouteName, ['orders_simulation', 'orders_detail']))
                                                            @if($orderDetail->item->deal()->exists())
                                                                <td class="text-end">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-percent-line text-primary"></i>
                                                                            <span
                                                                                class="badge bg-primary-subtle text-primary">
                                                                                {{$orderDetail->partner_discount_percentage}}%
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                            <span class="text-danger fw-semibold">
                                                                                -{{$orderDetail->partner_discount}}
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                            <span
                                                                                class="badge bg-success-subtle text-success">
                                                                                {{$orderDetail->amount_after_partner_discount}}
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td class="text-end">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-percent-line text-primary"></i>
                                                                            <span
                                                                                class="badge bg-primary-subtle text-primary">
                                                                                {{$orderDetail->earn_discount_percentage}}%
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                            <span class="text-danger fw-semibold">
                                                                                -{{$orderDetail->earn_discount}}
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                            <span
                                                                                class="badge bg-success-subtle text-success">
                                                                                {{$orderDetail->amount_after_earn_discount}}
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td class="text-end">
                                                                    <ul class="list-group">
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-percent-line text-primary"></i>
                                                                            <span
                                                                                class="badge bg-primary-subtle text-primary">
                                                                                {{$orderDetail->deal_discount_percentage}}%
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-arrow-down-line text-danger"></i>
                                                                            <span class="text-danger fw-semibold">
                                                                                -{{$orderDetail->deal_discount}}
                                                                            </span>
                                                                        </li>
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                            <i class="ri-money-dollar-circle-line text-success"></i>
                                                                            <span
                                                                                class="badge bg-success-subtle text-success">
                                                                                {{$orderDetail->amount_after_deal_discount}}
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span class="badge bg-success fs-14">
                                                                        <i class="ri-discount-percent-line me-1"></i>{{$orderDetail->total_discount}} {{config('app.currency')}}
                                                                    </span>
                                                                </td>
                                                            @else
                                                                <td colspan="4" class="text-center">
                                                                    <div class="alert alert-light mb-0">
                                                                        <i class="ri-information-line me-1"></i>{{__('No deal in this order details')}}
                                                                    </div>
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
                    <div class="card-body">
                        <div class="alert alert-info d-flex align-items-center mb-0">
                            <i class="ri-inbox-line fs-20 me-2"></i>
                            <span>{{__('Empty order')}}</span>
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
                    <div class="card mt-2 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="card-title mb-0">
                                <i class="ri-file-chart-line text-primary me-2"></i>{{__('Order simulation summary')}}
                            </h6>
                        </div>
                        <div class="card-body row">
                            <div class="col-md-4">
                                <div class="card mt-2 border shadow-sm">
                                    <div class="card-header bg-primary-subtle">
                                        <h6 class="card-title mb-0 text-primary">
                                            <i class="ri-calculator-line me-2"></i>{{__('Order totals')}}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @if($order->deal_amount_before_discount)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-primary me-2"></i>
                                                        <strong>{{__('Amount before discount')}}</strong>
                                                    </span>
                                                    <span class="badge bg-light text-dark border">
                                                        {{$order->deal_amount_before_discount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                            @endif
                                            @if($order->out_of_deal_amount)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-warning me-2"></i>
                                                        <strong>{{__('Out of deal amount')}}</strong>
                                                    </span>
                                                    <span class="badge bg-light text-dark border">
                                                        {{$order->out_of_deal_amount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                            @endif
                                            @if($order->out_of_deal_amount && $order->deal_amount_before_discount)
                                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                    <span>
                                                        <i class="ri-calculator-line text-success me-2"></i>
                                                        <strong>{{__('Total')}}</strong>
                                                    </span>
                                                    <span class="badge bg-success fs-14">
                                                        {{$order->out_of_deal_amount +$order->deal_amount_before_discount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @if($order->total_final_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2 border shadow-sm">
                                        <div class="card-header bg-warning-subtle">
                                            <h6 class="card-title mb-0 text-warning">
                                                <i class="ri-discount-percent-line me-2"></i>{{__('Order discounts')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                @if($order->total_final_discount)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                                        title="{{$order->total_final_discount_percentage}} {{config('app.percentage')}}">
                                                        <span>
                                                            <i class="ri-checkbox-circle-line text-success me-2"></i>
                                                            <strong>{{__('Total final discount')}}</strong>
                                                        </span>
                                                        <span class="badge bg-success-subtle text-success">
                                                            {{$order->total_final_discount}} {{config('app.currency')}}
                                                        </span>
                                                    </li>
                                                @endif
                                                @if($order->total_lost_discount)
                                                    <li class="list-group-item"
                                                        title="{{$order->total_lost_discount_percentage}} {{config('app.percentage')}}">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2">
                                                            <span>
                                                                <i class="ri-error-warning-line text-danger me-2"></i>
                                                                <strong>{{__('Total lost discount')}}</strong>
                                                            </span>
                                                            <span class="badge bg-danger-subtle text-danger">
                                                                {{$order->total_lost_discount}} {{config('app.currency')}}
                                                            </span>
                                                        </div>
                                                        <div class="alert alert-warning mb-0 small">
                                                            <i class="ri-information-line me-1"></i>
                                                            {{__('You can top up your discount with')}} {{$order->total_lost_discount}} {{config('app.currency')}}
                                                        </div>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($order->amount_after_discount)
                                <div class="col-md-4">
                                    <div class="card mt-2 border shadow-sm">
                                        <div class="card-header bg-success-subtle">
                                            <h6 class="card-title mb-0 text-success">
                                                <i class="ri-wallet-3-line me-2"></i>{{__('Order mains amounts')}}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-price-tag-3-line text-success me-2"></i>
                                                        <strong>{{__('Amount after discount')}}</strong>
                                                    </span>
                                                    <span class="badge bg-success fs-14">
                                                        {{$order->amount_after_discount}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                                    <span>
                                                        <i class="ri-arrow-up-circle-line text-info me-2"></i>
                                                        <strong>{{__('Gain from BFSs soldes')}}</strong>
                                                    </span>
                                                    <span class="badge bg-info-subtle text-info fs-14">
                                                        {{$order->amount_after_discount-$order->paid_cash}} {{config('app.currency')}}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span>
                                                        <i class="ri-money-dollar-circle-line text-danger me-2"></i>
                                                        <strong>{{__('Paid cash')}}</strong>
                                                    </span>
                                                    <span class="badge bg-danger fs-14">
                                                        {{$order->paid_cash}} {{config('app.currency')}}
                                                    </span>
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
                            <div class="card mt-2 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-wallet-line text-primary me-2"></i>{{__('Balances Operations')}}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @if(isset($discount))
                                            <li class="list-group-item logoTopDBLabel">
                                                <h5 class="mb-3">
                                                    <i class="ri-discount-percent-line text-primary me-2"></i>{{__('Discount')}}
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered align-middle mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">{{__('Reference')}}</th>
                                                            <th scope="col" class="text-end">{{__('Value')}}</th>
                                                            <th scope="col"
                                                                class="text-end">{{__('Current balance')}}</th>
                                                            <th scope="col">{{__('Description')}}</th>
                                                            <th scope="col">{{__('Created at')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <span
                                                                    class="badge bg-primary-subtle text-primary">{{$discount->reference}}</span>
                                                            </td>
                                                            <td class="logoTopDBLabel text-end">
                                                                <h6 class="mb-0 text-danger">
                                                                    -{{$discount->value}} {{config('app.currency')}}</h6>
                                                            </td>
                                                            <td class="logoTopDBLabel text-end">
                                                                <h6 class="mb-0">{{$discount->current_balance}} {{config('app.currency')}}</h6>
                                                            </td>
                                                            <td>
                                                                <p class="mb-1 small">{{$discount->description}}</p>
                                                                <p class="mb-1 text-muted small">
                                                                    {{__('Discount')}} {{$discount->current_balance+$discount->value}}
                                                                    - {{$discount->value}}
                                                                    = {{$discount->current_balance}}
                                                                </p>
                                                                <div class="mt-2">
                                                                    {!! \App\Services\Balances\Balances::generateDescription($discount) !!}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <small
                                                                    class="text-muted">{{$discount->created_at}}</small>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($bfss) && $bfss->isNotEmpty())
                                            <li class="list-group-item logoTopBFSLabel">
                                                <h5 class="mb-3">
                                                    <i class="ri-shopping-bag-line text-info me-2"></i>{{__('BFS (Balances for Shopping)')}}
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered align-middle mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">{{__('Reference')}}</th>
                                                            <th scope="col" class="text-end">{{__('Value')}}</th>
                                                            <th scope="col"
                                                                class="text-end">{{__('Current balance')}}</th>
                                                            <th scope="col">{{__('Description')}}</th>
                                                            <th scope="col" class="text-end">{{__('Percentage')}}</th>
                                                            <th scope="col">{{__('Created at')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($bfss as $bfs)
                                                            <tr>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-info-subtle text-info">{{$bfs->reference}}</span>
                                                                </td>
                                                                <td class="logoTopBFSLabel text-end">
                                                                    <h6 class="mb-0 text-danger">
                                                                        -{{$bfs->value}} {{config('app.currency')}}</h6>
                                                                </td>
                                                                <td class="logoTopBFSLabel text-end">
                                                                    <h6 class="mb-0">{{$bfs->current_balance}} {{config('app.currency')}}</h6>
                                                                </td>
                                                                <td>
                                                                    <p class="mb-1 small">{{$bfs->description}}</p>
                                                                    <p class="mb-1 text-muted small">
                                                                        {{__('BFS')}} {{$bfs->current_balance+$bfs->value}}
                                                                        - {{$bfs->value}}
                                                                        = {{$bfs->current_balance}}
                                                                    </p>
                                                                    <div class="mt-2">
                                                                        {!! \App\Services\Balances\Balances::generateDescription($bfs) !!}
                                                                    </div>
                                                                </td>
                                                                <td class="text-end">
                                                                    <span class="badge bg-warning-subtle text-warning">
                                                                        {{$bfs->percentage}} {{config('app.percentage')}}
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <small
                                                                        class="text-muted">{{$bfs->created_at}}</small>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </li>
                                        @endif
                                        @if(isset($cash))
                                            <li class="list-group-item logoTopCashLabel">
                                                <h5 class="mb-3">
                                                    <i class="ri-wallet-3-line text-success me-2"></i>{{__('Cash')}}
                                                </h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered align-middle mb-0">
                                                        <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">{{__('Reference')}}</th>
                                                            <th scope="col" class="text-end">{{__('Value')}}</th>
                                                            <th scope="col"
                                                                class="text-end">{{__('Current balance')}}</th>
                                                            <th scope="col">{{__('Description')}}</th>
                                                            <th scope="col">{{__('Created at')}}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>
                                                                <span
                                                                    class="badge bg-success-subtle text-success">{{$cash->reference}}</span>
                                                            </td>
                                                            <td class="logoTopCashLabel text-end">
                                                                <h6 class="mb-0 text-danger">
                                                                    -{{$cash->value}} {{config('app.currency')}}</h6>
                                                            </td>
                                                            <td class="logoTopCashLabel text-end">
                                                                <h6 class="mb-0">{{$cash->current_balance}} {{config('app.currency')}}</h6>
                                                            </td>
                                                            <td>
                                                                <p class="mb-1 small">{{$cash->description}}</p>
                                                                <p class="mb-1 text-muted small">
                                                                    {{__('CASH')}} {{$cash->current_balance+$cash->value}}
                                                                    - {{$cash->value}}
                                                                    = {{$cash->current_balance}}
                                                                </p>
                                                                <div class="mt-2">
                                                                    {!! \App\Services\Balances\Balances::generateDescription($cash) !!}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">{{$cash->created_at}}</small>
                                                            </td>
                                                        </tr>
                                                        </tbody>
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

            <div class="card-footer bg-light">
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <span class="badge bg-info-subtle text-info fs-13">
                        <i class="ri-refresh-line me-1"></i>{{__('Updated at')}} : {{$order->updated_at}}
                    </span>
                    @if($currentRouteName=="orders_detail")
                        <span class="badge bg-info-subtle text-info fs-13">
                            <i class="ri-time-line me-1"></i>{{__('Created at')}} : {{$order->created_at}}
                        </span>
                    @endif
                    @if($currentRouteName=="orders_index" || $currentRouteName=="orders_previous"|| $currentRouteName=="orders_summary" )
                        <a href="{{route('orders_detail', ['locale'=>app()->getLocale(),'id'=>$order->id])}}"
                           class="btn btn-sm btn-primary ms-auto">
                            <i class="ri-eye-line me-1"></i>{{__('More details')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
